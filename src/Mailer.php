<?php

declare(strict_types=1);

namespace App;

use PHPMailer\PHPMailer\PHPMailer;

final class Mailer
{
    public function send(string $to, string $subject, string $body): void
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = (string) getenv('SMTP_HOST');
        $mail->Port = (int) getenv('SMTP_PORT');
        $mail->SMTPAuth = false;
        $mail->CharSet = 'UTF-8';
        $mail->setFrom((string) getenv('SMTP_FROM'), 'Vite & Gourmand');
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
    }
}