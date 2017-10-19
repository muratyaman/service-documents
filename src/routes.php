<?php

use Slim\Http\Request;
use Slim\Http\Response;


//$app->group('', function() {
//    $app = $this;//inside group
    $id = '{id:[a-z0-9]+}';// 64 chars
    $app->post('/api/documents/search',        '\App\Http\Api\DocumentController:index');
    $app->post('/api/documents/upload',        '\App\Http\Api\DocumentController:create');
    $app->post('/api/documents/retrieve/'.$id, '\App\Http\Api\DocumentController:retrieve');
    $app->post('/api/documents/update/'.$id,   '\App\Http\Api\DocumentController:update');
    $app->post('/api/documents/delete/'.$id,   '\App\Http\Api\DocumentController:delete');

    $app->get ('/api/documents',              '\App\Http\Api\DocumentController:index');
    $app->post('/api/documents',              '\App\Http\Api\DocumentController:create');
    $app->get('/api/documents/'.$id,          '\App\Http\Api\DocumentController:retrieve');
    $app->get('/api/documents/download/'.$id, '\App\Http\Api\DocumentController:download');
    $app->put('/api/documents/'.$id,          '\App\Http\Api\DocumentController:update');// this may not work
    $app->post('/api/documents/'.$id,         '\App\Http\Api\DocumentController:update');
    $app->delete('/api/documents/'.$id,       '\App\Http\Api\DocumentController:delete');

//});

$app->get('/api', function (Request $request, Response $response, $args) {
    $container = $this;// inside route
    $data = [
        'service' => 'service-documents',
        'version' => '1.0.0',
    ];
    return $response->withStatus(200)
        ->write(json_encode($data));
});

$app->get('/', function (Request $request, Response $response, array $args) {
    $container = $this;
    $container->logger->info('serving "/" ... ');
    return $container->renderer->render($response, 'index.phtml', $args);
});
