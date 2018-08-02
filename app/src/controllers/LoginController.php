<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class LoginController extends BaseController {
  //abstract class loginMessage {
      const SUCCESS = 1;
      const NO_SUCH_EMAIL = 2;
      const WRONG_PASSWORD = 3;
      const NONCE_EXIST = 4;
      const NONCE_NOT_EXIST = 5;
  //}


  // for WEB
  function loginHandler(Request $request, Response $response, $args) {
    list ($loginResult, $userINFO) = $this->login($_POST['email'], $_POST['password']);

    if ($loginResult == self::NONCE_NOT_EXIST)
      $this->view->render($response, 'main_after.phtml',  ['USN' => $userINFO['USN'],
      'email' => $userINFO['EmailAddress'],
      'firstName' => $userINFO['FirstName'], 'lastName' => $userINFO['LastName']]);
    else
      $this->view->render($response, 'login.phtml', ['emailResult'=>$loginResult]);
  }

  // outermost common function
  function login($email, $password) {
    list ($pwCheckResult, $userINFO) = $this->checkPassword( $email, $password);

    if ($pwCheckResult == self::SUCCESS) {
      $nonceCheck = $this->checkNonceExist($USN);

      return array ($nonceCheck, $userINFO);
    }
    else
      return array ($pwCheckResult, -1);
  }

  function checkPassword($email, $password) {
    $sql = "SELECT * FROM User WHERE EmailAddress = '" . $email . "'" ;
    try {
    	$stmt = $this->db->query($sql);
    	$result = $stmt->fetch();

      if ($result == null) {
        return array (self::NO_SUCH_EMAIL, -1);
      } else if (!password_verify($password, $result['HPassword'])) {
        return array (self::WRONG_PASSWORD, -1);
      } else {
        return array (self::SUCCESS, $result);
      }
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }

  function checkNonceExist($USN) {
    $sql = "SELECT * FROM Nonce WHERE USN = '" . $USN . "'";

    $stmt = $this->db->query($sql);
    $result = $stmt->fetch();

    if ($result == null)
      return self::NONCE_NOT_EXIST;
    else
      return self::NONCE_EXIST;
  }
}

?>