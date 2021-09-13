<?php

use Core\Classes\Queue\AMQPAdapter;
use Phalcon\Db\Adapter\Pdo\Mysql;

class Microservice extends \Phalcon\Mvc\Micro
{
    public function __construct(\Phalcon\Di\DiInterface $dependencyInjector = null, bool $loginRequired = true)
    {

//        require_once('./vendor/autoload.php');

        parent::__construct($dependencyInjector);


        $config = new \Phalcon\Config(include($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'config.php'));

        $di = new \Phalcon\Di\FactoryDefault();

        $di->setShared('config', $config);
        $di->setShared('router', function() {
            return new \Phalcon\Mvc\Router();
        });
        $di->setShared('request', function() {
            return new \Phalcon\Http\Request();
        });
        $di->setShared('response', function() {
            return new \Phalcon\Http\Response();
        });
        $di->setShared('db', function () use ($config) {
            $db = $config->db;
            try {
                $mysqlConfig = [
                    'username' => $db->username,
                    'password' => $db->password,
                    'options' => [
                        \PDO::ATTR_EMULATE_PREPARES => false //Tell PDO Not to convert all datatypes to strings
                    ]
                ];
                if ($db->has('socket')) {
                    $mysqlConfig['dsn'] = "mysql:unix_socket={$db->socket};dbname={$db->name};charset=utf8mb4";
                } else {
                    $mysqlConfig['host'] = $db->write;
                    $mysqlConfig['port'] = $db->port;
                    $mysqlConfig['dbname'] = $db->name;
                    $mysqlConfig['charset'] = 'utf8mb4';
                }
                return new Mysql($mysqlConfig);
            } catch (\Exception $e) {
                throw new \Core\Classes\Exceptions\HttpException('Failed to connect to Database: ' . $e->getMessage(), 500);
            }
        });

        $di->setShared('modelsManager', function() {
            return new \Phalcon\Mvc\Model\Manager();
        });

        $di->setShared('modelsMetadata', function() use ($di) {
            $meta = new \Phalcon\Mvc\Model\MetaData\Memory();
            $meta->setStrategy(new \Phalcon\Mvc\Model\MetaData\Strategy\Introspection());
            return $meta;
        });

        $di->setShared('user', function() {
            $auth = $this->get('request')->getHeader('Authorization');
            if(strlen($auth) === 0) {
                return false;
            }
            $jwt = json_decode(base64_decode(explode('.', $auth)[1]));
            $user = \Models\User::findFirstByEmail($jwt->data->email);
            return $user;
        });

        $this->setDI($di);
    }

    public function handle($uri = null)
    {
        if ($uri === null) {
            $uri = $_SERVER['REQUEST_URI'];
        }
        $config = $this->di->get('config');
        $db = $this->di->get('db');
        try {
            $db->begin();
            parent::handle($uri);
            $db->commit();
        } catch (\Exception $e) {
            $fp = fopen('php://stderr', 'w');
            fputs($fp, "Error: {$e->getMessage()} in file '{$e->getFile()}' on line {$e->getLine()}");
            fputs($fp, $e->getTraceAsString());
            fclose($fp);
            $content = (getenv('ENVIRONMENT') === 'dev')
                    ? [
                        'Error' => $e->getMessage(),
                        'File' => $e->getFile(),
                        'Line' => $e->getLine(),
                        'Trace' => $e->getTrace()
                    ] : [];
            ob_flush();
            (new \Phalcon\Http\Response())
                ->setStatusCode($e->getCode(), $e->getMessage())
                ->setJsonContent($content)->send();
        }
    }
}
