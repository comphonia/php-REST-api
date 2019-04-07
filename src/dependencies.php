<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

//API
$container['api'] = function($c){
    $api = $c->get('settings')['api'];
    $api['api_url'] = $api['base_url'].'/api/'.$api['version'];
    return $api;
};

$container['db'] = function($c) {
    $db = $c->get('settings')['db'];
    $pdo = new PDO($db['dsn'].':'.$db['database']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$container['course']=function($c){
    return new App\Model\Course($c->get('db'));
};

$container['review']=function($c){
    return new App\Model\Review($c->get('db'));
};

$container['errorHandler']=function($c){
    return function($request,$response,$exception){
        $data=[
            'status' => 'error',
            'code' => $exception->getCode(),
            'message' => $exception->getMessage()
        ];
        return $response->withJson($data, $exception->getCode());
    };
};
