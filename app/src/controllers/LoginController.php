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



  function loginHandler(Request $request, Response $response, $args) {
    list ($pwCheckResult, $USN) = $this->checkPassword($_POST['email'], $_POST['password']);
    // echo $pwCheckResult . '<br>';
    // echo $USN .'<br>';
    if ($pwCheckResult == self::SUCCESS) {
      $nonceCheck = $this->checkNonceExist($USN);

      if ($nonceCheck == self::NONCE_NOT_EXIST)
        $this->view->render($response, 'main_after.phtml',  ['email' => $_POST['email'], 'firstName' => $firstName, 'lastName' => $lastName]);
      else
        $this->view->render($response, 'login.phtml', ['emailResult'=>$nonceCheck]);
    }
    else
      $this->view->render($response, 'login.phtml', ['emailResult'=>$pwCheckResult]);
  	// TODO : need to check if activated
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
