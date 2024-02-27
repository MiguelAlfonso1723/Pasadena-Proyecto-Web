<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Mailer
{
    function enviarEmail($email, $asunto, $cuerpo)
    {
        require_once './config/config.php';
        require './phpmailer/src/PHPMailer.php';
        require './phpmailer/src/SMTP.php';
        require './phpmailer/src/Exception.php';

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host = MAIL_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = MAIL_USER;
            $mail->Password = MAIL_PASS; //'tfmqzpfilmzvfewt';                              
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = MAIL_PORT;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom(MAIL_USER, 'Supermercado Pasadena');
            $mail->addAddress($email);
            

            $mail->isHTML(true);
            $mail->Subject = $asunto;


            $mail->Body = utf8_decode($cuerpo);
            $mail->setLanguage('es', '../phpmailer/language/phpmailer.lang-es.php');

            if($mail->send()){
                return true;
            }else{
                return false;
            }


        } catch (Exception $e) {
            echo "Error al enviar el correo electronico de la compra: {$mail->ErrorInfo}";
            return false;
        }
    }


}