<?php

namespace AlexRoden\LibraryApiPhp\Mail;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

final class Mail implements Mailer
{
    /**
     * @throws Exception
     */
    public function send(
        string $to,
        string $subject,
        string $body,
    ): void {

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = env('MAIL_HOST');
        $mail->Port = env('MAIL_PORT');
        $mail->SMTPAuth = false;
        $mail->SMTPSecure = false;
        $mail->SMTPAutoTLS = false;
        $mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
        $mail->Debugoutput = static function (string $str, int $level): void {
            error_log($str);
        };

        $mail->setFrom('noreply@example.com', 'Library API');
        $mail->addAddress($to);

        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
    }
}
