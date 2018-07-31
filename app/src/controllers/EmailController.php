<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

final class EmailController extends BaseController {
  const LINK = 'teama-iot.calit2.net';
  
  static public function sendEmail($email, $firstName, $lastName, $html, $notHtml) {
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
      //Server settings
      $mail->SMTPDebug = 2;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'QIoTteamA@gmail.com';                 // SMTP username
      $mail->Password = 'vagrant!';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;                                    // TCP port to connect to

      //Recipients
      $mail->setFrom('QIoTteamA@gmail.com', 'TeamA');
      $mail->addAddress($email, $firstName . " " . $lastName);     // Add a recipient
      $mail->addReplyTo('QIoTteamA@gmail.com', 'TeamA');

      //Content
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = '[TEAMA] Account Activation';
      $mail->Body    = $html;
      $mail->AltBody = $notHtml;

      $mail->send();
      echo 'Message has been sent';
    } catch (Exception $e) {
      echo 'Message could not be sent.';
      echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
  }

  static public function activationEmailContent($nonce) {
    $html = 'Hi! This is account activation request email from teama-iot.calit2.net <br> <b>Please Click the activation link!</b> <br> The link is : <br>'.
    '<a href=\"http://'.self::LINK.'/accountActivation/'.$nonce.'\"> http://'.self::LINK.'/accountActivation/'.$nonce.' </a> <br>';
    $notHtml = 'Hi! This is account activation request email from teama-iot.calit2.net Please Click the activation link! The link is : http://'.self::LINK.'/'.$nonce;

    return array ($html, $notHtml);
  }


}
