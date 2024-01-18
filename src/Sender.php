<?php

namespace Src;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Sender
{
    protected array $configs = [];

    public function __construct()
    {
        $this->configs = require_once __DIR__ . '/../config/mail.php';
    }

    public function send(array $body): bool
    {
        [$html, $plain] = $this->getMessage($body);

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host       = $this->configs['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->configs['username'];
            $mail->Password   = $this->configs['password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = $this->configs['port'];

            //Recipients
            $mail->setFrom($this->configs['fromAddress'], $this->configs['fromName']);
            $to = $this->configs['toAddress'];

            foreach ($to as $email) {
                $mail->addAddress($email);
            }

            //Content
            $mail->isHTML();
            $mail->Subject = 'Submission form';
            $mail->Body    = $html;
            $mail->AltBody = $plain;

            $mail->send();
            return true;

        } catch (Exception $exception) {
            return false;
        }
    }

    protected function getMessage($body): array
    {
        $name = $body['name'];
        $lastName = $body['last-name'] ?? '';
        $email = $body['email'] ?? '';
        $phone = $body['phone'] ?? '';
        $guests = $body['guests'] ?? '';
        $golfOption = $body['golfOption'] ?? 'withoutGolfing';
        $foodSensitivities = $body['food_sensitivities'] ?? '';
        $readyToShare = isset($body['readyToShare']) ? "Yes" : 'No';

        //собираем сообщение
        $html = "
<p>You have a new form submission.</p>
<p> First name: $name <br />
    Last name: $lastName <br />
    E-src: $email <br />
    Phone: $phone <br />
    Additional guests: $guests <br />
    Golf outing: $golfOption <br />
    Food sensitivities: $foodSensitivities <br />
    Agreements to share e-src with vendors: $readyToShare <br />
</p>";

        $plain = "You have a new form submission.
First name: $name
Last name: $lastName
E-src: $email
Phone: $phone
Additional guests: $guests
Golf outing: $golfOption
Food sensitivities: $foodSensitivities
Agreements to share e-src with vendors: $readyToShare";

        return [$html, $plain];
    }
}