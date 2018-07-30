<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;



final class LoginController extends BaseController {
  //abstract class loginMessage {
      const SUCCESS = 0;
      const NO_SUCH_EMAIL = 1;
      const WRONG_PASSWORD = 2;
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

    switch($pwCheckResult) {
      case self::NO_SUCH_EMAIL:
        $message = "There is no account corresponding to input Email";
        echo "<script type='text/javascript'>alert('$message');</script>";
        break;
      case self::WRONG_PASSWORD:
        $message = "Wrong Password. Please enter again.";
        echo "<script type='text/javascript'>alert('$message');</script>";
        break;
      case self::SUCCESS:
        $message = "Wrong Password. Please enter again.";
        echo "<script type='text/javascript'>alert('$message');</script>";
        break;
      default :
        $message = "ERROR : smth wrong in checkPassword().";
        echo "<script type='text/javascript'>alert('$message');</script>";
    }

  	// TODO : need to check if activated
  }
}

?>
