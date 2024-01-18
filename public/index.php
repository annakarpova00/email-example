
<?php

require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Slim\Factory\AppFactory;
use Slim\Http\ServerRequest;
use Slim\Psr7\Request;
$app = AppFactory::create();

//подключение шаблонизации, шаблоны хранятся в дир. templates:
use DI\Container;
use Slim\Views\PhpRenderer;

$container = new Container();
$container->set('renderer', function () {
    return new PhpRenderer(__DIR__ . '/../templates');
});
$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);



$app->post('/', function (ServerRequest $request, $response)  {
    $body = $request->getParsedBody();
// если есть шаблон, надо сделать params
    $name = $body['name'];
    $lastName = $body['last-name'] ?? '';
    $email = $body['email'] ?? '';
    $phone = $body['phone'] ?? '';
    $guests = $body['guests'] ?? '';
    $golfOption = $body['golfOption'] ?? 'withoutGolfing';
    $foodSensitivities = $body['food_sensitivities'] ?? '';
    $readyToShare = isset($body['readyToShare']) ? "Yes" : 'No';

    //собираем сообщение
    $message = "
<p>You have a new form submission.</p>
<p> First name: $name <br />
    Last name: $lastName <br />
    E-mail: $email <br />
    Phone: $phone <br />
    Additional guests: $guests <br />
    Golf outing: $golfOption <br />
    Food sensitivities: $foodSensitivities <br />
    Agreements to share e-mail with vendors: $readyToShare <br />
</p>";


$message = "You have a new form submission.
First name: $name
Last name: $lastName
E-mail: $email
Phone: $phone
Additional guests: $guests
Golf outing: $golfOption
Food sensitivities: $foodSensitivities
Agreements to share e-mail with vendors: $readyToShare";




//отправка почты
//Create an instance; passing `true` enables exceptions

    return $response->write($message);
    //return $response->write('{status: ok}');// или $response->write('Check your email');
});// раз есть шаблон, то: return $this->get('renderer')->render($response, '/templates/show.phtml', $params)
//})

$app->get('/', function ($request, $response) {
    return $this->get('renderer')->render($response, 'show.phtml', []);
});

$app->run();
