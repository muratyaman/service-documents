<?php
// DIC configuration

$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// error handler
$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        $msg   = $exception->getMessage();
        $trace = $exception->getTraceAsString();

        if ($exception instanceof \App\DocumentError) {
            $userMsg = $msg;
            $status  = $exception->getCode();
            $status  = $status ?: 500;
        } else {
            $userMsg = 'Something went wrong!';
            $status  = 500;
            error_log($msg); error_log($trace);
            $c['logger']->error($msg); $c['logger']->error($trace);
        }

        return $c['response']->withStatus($status)
            ->withJson(['error' => $userMsg]);
    };
};

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

