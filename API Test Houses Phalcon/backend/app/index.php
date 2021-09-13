<?php

header('Access-Control-Allow-Origin: *');

include_once('Microservice.php');
$app = new Microservice();

(new Phalcon\Loader())
    ->registerNamespaces([
        'Classes' => '/var/www/Classes',
        'Models' => '/var/www/Models',
        'Events' => '/var/www/Events',
    ])
    ->register();
$app->setEventsManager(new \Events\EventManager(true));

$app->get('/v1/houses', function() use($app) {
    if ($app->request->getQuery('hasRooms') === null) {
        return (new Phalcon\Http\Response())
            ->setJsonContent(\Models\House::getAll()->toArray());
    }
    $rooms = explode(',', $app->request->getQuery('hasRooms'));
    return (new Phalcon\Http\Response())
        ->setJsonContent(\Models\House::getHavingRooms($rooms)->toArray());
});

$app->get('/v1/houses/{id:\d+}', function($id) use($app) {
    return (new Phalcon\Http\Response())
        ->setJsonContent(\Models\House::getByID($id)->toArray());
});

$app->post('/login', function() use ($app) {
    $body = $app->request->getJsonRawBody();
    $user = \Models\User::findFirstByUsername($body->username);
    if (!$user || !(new Phalcon\Security())->checkHash($body->password, $user->password)) {
        return (new Phalcon\Http\Response())->setStatusCode(401);
    }
    $token = \Classes\Auth::getToken($user);
    return (new \Phalcon\Http\Response())->setHeader('Authorization', $token);
});

$app->post('/v1/houses', function() use ($app) {
    $data = $app->request->getJsonRawBody();
    $house = new \Models\House();
    $house->name = $data->name;
    $house->rooms = $data->rooms;
    $house->create();
});

$app->patch('/v1/houses/{id:\d+}', function($id) use ($app) {
    $data = $app->request->getJsonRawBody();
    $user = $app->di->get('user');
    $house = \Models\House::findFirstById($id);
    if (!$house) {
        return (new Phalcon\Http\Response())
            ->setStatusCode(404);
    }
    if ($user->id !== $house->user) {
        return (new Phalcon\Http\Response())
            ->setStatusCode(403);
    }
    $house->name = $data->name;
    $house->rooms = $data->rooms;
    $house->update();
});

$app->delete('/v1/houses/{id:\d+}', function ($id) use ($app) {
    $user = $app->di->get('user');
    $house = \Models\House::findFirstById($id);
    if (!$house) {
        return (new Phalcon\Http\Response())
            ->setStatusCode(404);
    }
    if ($user->class !== 'admin') {
        return (new Phalcon\Http\Response())
            ->setStatusCode(403);
    }
});

$app->handle();