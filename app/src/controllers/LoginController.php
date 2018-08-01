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
    $loginResult = $this->login($_POST['email'], $_POST['password']);

    if ($loginResult == self::NONCE_NOT_EXIST) // TODO : need to get name from DB
      $this->view->render($response, 'main_after.phtml',  ['email' => $_POST['email'], 'firstName' => $firstName, 'lastName' => $lastName]);
    else
      $this->view->render($response, 'login.phtml', ['emailResult'=>$nonceCheck]);
  }

  // outermost common function
  function login($email, $password) {
    list ($pwCheckResult, $USN) = $this->checkPassword( $email, $password);

    if ($pwCheckResult == self::SUCCESS) {
      $nonceCheck = $this->checkNonceExist($USN);

      if ($nonceCheck == self::NONCE_NOT_EXIST)
        return self::NONCE_NOT_EXIST;
      else
        return self::NONCE_EXIST;
    }
    else
      return $pwCheckResult;
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
        return array (self::SUCCESS, $result['USN']);
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
