<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class forgottenPWController extends BaseController {
  //abstract class forgottenPWMessage {
    const EMAIL_EXIST = 1;
    const EMAIL_NOT_EXIST = 2;
  //}
  public function forgot_pw(Request $request, Response $response, $args)
  {
      $this->view->render($response, 'forgot_pw.phtml');
      return $response;
  }

  public function forgot_pwHandler(Request $request, Response $response, $args)
  {
      list ($email_existence, $userINFO) = $this->getUSN($_POST['email']);
      //print_r($userINFO); exit;

      if ($email_existence == self::EMAIL_EXIST) {
        $new_password = HomeController::randomString(10);
        $hashedPW = password_hash($new_password, PASSWORD_DEFAULT);

        $this->changePW($_POST['email'], $hashedPW);
      } else {

      }

      $this->view->render($response, 'forgot_pw.phtml', ['restPWResult'=>$email_existence]);
      EmailController::sendNewPwEmail($userINFO['EmailAddress'], $userINFO['FirstName'], $userINFO['LastName'], $new_password);

      return $response;
  }

  public function getUSN($email) {
    //echo $email. "<br>";
    $sql = "SELECT * FROM User WHERE EmailAddress = '" . $email . "'" ;
    //echo $sql. "<br>"; exit;
    try {
      $stmt = $this->db->query($sql);
      $result = $stmt->fetch();

      if ($result != null) {
        return array (self::EMAIL_EXIST, $result);
      }
      else {
        return array (self::EMAIL_NOT_EXIST, array());
      }
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }

  public function changePW($email, $new_password) {
    $sql = "UPDATE User SET HPassword='".$new_password."' WHERE EmailAddress='".$email."' ";
    try {
      $stmt = $this->db->query($sql);
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }
}
