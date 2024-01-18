
<?php

require __DIR__ . '/../vendor/autoload.php';

use Src\Sender;
use Slim\Factory\AppFactory;
use Slim\Http\ServerRequest;
use DI\Container;
use Slim\Views\PhpRenderer;

$app = AppFactory::create();

$container = new Container();
$container->set('renderer', function () {
    return new PhpRenderer(__DIR__ . '/../templates');
});
$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);

$app->post('/', function (ServerRequest $request, $response)  {
    $data = $request->getParsedBody();
    $email = new Sender();
    return $response->write($email->send($data) ? 'ok' : 'error');
});

$app->get('/', function ($request, $response) {
    return $this->get('renderer')->render($response, 'show.phtml', []);
});

$app->run();
