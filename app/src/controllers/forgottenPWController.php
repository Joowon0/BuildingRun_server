<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class forgottenPWController extends BaseController {
  //abstract class forgottenPWMessage {
    const EMAIL_EXIST = 1;
    const EMAIL_NOT_EXIST = 2;
  //}

  // for WEB
  public function forgot_pwHandler(Request $request, Response $response, $args)
  {
      list ($email_existence, $userINFO, $new_password) = $this->forgot_pw($_POST['email']);

      if ($email_existence == self::EMAIL_EXIST)
        EmailController::sendNewPwEmail($userINFO['EmailAddress'], $userINFO['FirstName'], $userINFO['LastName'], $new_password);

      $this->view->render($response, 'forgot_pw.phtml', ['restPWResult'=>$email_existence]);
      return $response;
  }

  // for APP
  public function app_forgotPW(Request $request, Response $response, $args)
  {
      $json = file_get_contents('php://input');
      $jsonArray = json_decode($json, true);

      list ($email_existence, $userINFO, $new_password) = $this->forgot_pw($jsonArray['email']);

      if ($email_existence == self::EMAIL_EXIST)
        EmailController::sendNewPwEmail($userINFO['EmailAddress'], $userINFO['FirstName'], $userINFO['LastName'], $new_password);

      $sendData = array("Result"=>$email_existence);
      return $response->withStatus(200)
          ->withHeader('Content-Type', 'application/json')
          ->write(json_encode($sendData));
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
    $sql = "SELECT * FROM User WHERE EmailAddress = '" . $email . "'" ;

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
