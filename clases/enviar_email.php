<?php 


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
require '../phpmailer/src/Exception.php';

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF;    
    $mail->isSMTP();                                            
    $mail->Host       = MAIL_HOST;                    
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = MAIL_USER;                     
    $mail->Password   = MAIL_PASS; //'tfmqzpfilmzvfewt';                              
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
    $mail->Port       = MAIL_PORT;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom(MAIL_USER, 'Supermercado Pasadena');
    $mail->addAddress('mono170203@gmail.com', 'Miguel Alfonso');   
    $mail->addReplyTo('mono170203@gmail.com');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    

    //Content
    $mail->isHTML(true);                                 
    $mail->Subject = 'Detalles de su compra';

    $cuerpo = '<h4>Gracias por su compra</h4>';
    $cuerpo .= '<p>El ID de su compra es <b>'. $id_transaccion .'</b></p>';

    $mail->Body    = utf8_decode($cuerpo);
    $mail->AltBody = 'Le enviamos los detalles de su compra.';

    $mail->setLanguage('es', '../phpmailer/language/phpmailer.lang-es.php');

    $mail->send();
  

} catch (Exception $e) {
    echo "Error al enviar el correo electronico de la compra: {$mail->ErrorInfo}";
}