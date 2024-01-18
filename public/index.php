
<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Psr7\Request;
use Src\Sender;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

$app->post('/', function (Request $request, $response)  {
    $data = $request->getParsedBody();
    $email = new Sender();
    $response->getBody()->write($email->send($data) ? 'ok' : 'error');
    return $response;
});

$app->run();
