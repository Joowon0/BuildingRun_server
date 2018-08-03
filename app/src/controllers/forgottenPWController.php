<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class forgottenPWController extends BaseController {
  //abstract class forgottenPWMessage {
    const EMAIL_EXIST = 1;
    const EMAIL_NOT_EXIST = 2;
  //}


  public function forgot_pwHandler(Request $request, Response $response, $args)
  {
      list ($email_existence, $userINFO, $new_password) = $this->forgot_pw($_POST['email']);

      $this->view->render($response, 'forgot_pw.phtml', ['restPWResult'=>$email_existence]);
      EmailController::sendNewPwEmail($userINFO['EmailAddress'], $userINFO['FirstName'], $userINFO['LastName'], $new_password);

      return $response;
  }

  // outermost common function
  public function forgot_pw($email)
  {
    list ($email_existence, $userINFO) = $this->getUSN($email);

    if ($email_existence == self::EMAIL_EXIST) {
      $new_password = HomeController::randomString(10);
      $hashedPW = password_hash($new_password, PASSWORD_DEFAULT);

      $this->changePW($email, $hashedPW);
    }
    return array ($email_existence, $userINFO, $new_password);
  }

  public function getUSN($email) {
    //echo $email. "<br>"; exit;
    $sql = "SELECT * FROM User WHERE EmailAddress = '" . $email . "'" ;
    //echo $sql. "<br>"; exit;
    try {
      $stmt = $this->db->query($sql);
      $result = $stmt->fetch();

      if ($result != null) {
        return array (self::EMAIL_EXIST, $result);
      }
      else {
        return array (self::EMAIL_NOT_EXIST, array ());
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
