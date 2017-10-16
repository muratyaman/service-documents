<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/', function (Request $request, Response $response, array $args) {
    $this->logger->info('serving "/" ... ');
    return $this->renderer->render($response, 'index.phtml', $args);
});


$this->get('/api', function (Request $request, Response $response, $args)
{
    $data = [
        'service' => 'service-documents',
        'version' => '1.0.0',
    ];
    return $response->withStatus(200)
        ->write(json_encode($data));
});

$app->group('/api/documents', function() {

    $this->get ('/',       '\App\Http\Api\DocumentController:index');
    $this->post('/',       '\App\Http\Api\DocumentController:create');
    $this->get('/{id}',    '\App\Http\Api\DocumentController:retrieve');
    $this->put('/{id}',    '\App\Http\Api\DocumentController:update');// this may not work
    $this->post('/{id}',   '\App\Http\Api\DocumentController:update');
    $this->delete('/{id}', '\App\Http\Api\DocumentController:delete');

});

