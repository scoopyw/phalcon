<?php namespace Events;

use Classes\Auth;
use Models\User;
use Phalcon\Di;
use Phalcon\Events\Event;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Micro;

class EventManager extends Manager {

    const UNAUTHENTICATED_ROUTES = [
        '/login' => false
    ];

    private $loginRequired = true;

    function __construct($loginRequired = true)
    {
        $this->loginRequired = $loginRequired;
        /**
         * @param $event \Phalcon\Events\Event
         * @param $app \Phalcon\Mvc\Micro
         */
        $this->attach('micro:beforeExecuteRoute', $this->getAuthFunction());
    }

    public function getToken() {
        return (new Micro())->request->getHeader('Authorization');
    }

    private function getAuthFunction() {
        return function (Event $event, Micro $app) {
            $loginRequired = !array_key_exists($_GET['_url'], self::UNAUTHENTICATED_ROUTES) && $this->loginRequired === true;
            $authHeader = $app->request->getHeader('Authorization');
            if(strlen($authHeader) === 0 && $loginRequired === true) {
                $app->response->setStatusCode(401, 'Unauthorized')->send();
                die;
            }
            $jwt = null;
            if ($loginRequired) {
                $authHeader = Di::getDefault()->get('request')->getHeader('Authorization');
                if (
                    !$authHeader ||
                    strlen(substr($authHeader, 7)) === 0 ||
                    !Auth::verifyToken(substr($authHeader, 7))
                ) {
                    throw new \HttpException('Unauthorized', 401);
                }
            }
        };
    }

}
