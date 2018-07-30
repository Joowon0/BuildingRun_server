<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class LoginController extends BaseController {
  //abstract class loginMessage {
      const SUCCESS = 1;
      const NO_SUCH_EMAIL = 2;
      const WRONG_PASSWORD = 3;
  //}

  function checkPassword($email, $password) {
    $sql = "SELECT * FROM User WHERE EmailAddress = '" . $email . "'" ;
    try {
    	$stmt = $this->db->query($sql);
    	$result = $stmt->fetch();

      if ($result == null) {
        return self::NO_SUCH_EMAIL;
      } else if (!password_verify($password, $result['HPassword'])) {
        return self::WRONG_PASSWORD;
      } else {
        return self::SUCCESS;
      }
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }

  // TODO : need to check if email and password entry is not null
  function loginHandler(Request $request, Response $response, $args) {
    $pwCheckResult = $this->checkPassword($_POST['email'], $_POST['password']);
    if ($pwCheckResult == self::SUCCESS)
      $this->view->render($response, 'main_after.phtml',  ['email' => $_POST['email'], 'firstName' => $firstName, 'lastName' => $lastName]);
    else
      $this->view->render($response, 'login.phtml', ['emailResult'=>$pwCheckResult]);
  	// TODO : need to check if activated
  }
}

?>
