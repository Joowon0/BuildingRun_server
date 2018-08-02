<?php
namespace App\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class SetNewPassword extends BaseController {
  //abstract class newPWMessage {
      const CORRECT_PASSWORD = 1;
      const WRONG_PASSWORD = 2;
      const NO_SUCH_ACCOUNT = 3;
  //}

  public function pw_check(Request $request, Response $response, $args)
  {
    $this->view->render($response, 'pw_check.phtml');
    return $response;
  }

  public function pw_checkHandler(Request $request, Response $response, $args) {
    $pwCheckResult = $this->checkPassword($_SESSION["USN"], $_POST['password']);

    if ($pwCheckResult == self::CORRECT_PASSWORD) {
      echo "<script> document.location.href='/pw_new'; </script>";
    } else if ($pwCheckResult == self::WRONG_PASSWORD) {
      $this->view->render($response, 'pw_check.phtml', ['pwCheckResult'=>self::WRONG_PASSWORD]);
      return $response;
    } else if ($pwCheckResult == self::NO_SUCH_ACCOUNT) {
      $this->view->render($response, 'login.phtml', ['pwCheckResult'=>self::NO_SUCH_ACCOUNT]);
      return $response;
    } else {

    }
  }

  public function checkPassword($USN, $password) {
    $sql = "SELECT * FROM User WHERE USN = '" . $USN . "'" ;
    try {
    	$stmt = $this->db->query($sql);
    	$result = $stmt->fetch();

      if ($result == null) {
        return self::NO_SUCH_ACCOUNT;
      } else if (!password_verify($password, $result['HPassword'])) {
        return self::WRONG_PASSWORD;
      } else {
        return self::CORRECT_PASSWORD;
      }
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }

  public function pw_new(Request $request, Response $response, $args)
  {
    $this->view->render($response, 'pw_new.phtml');
    return $response;
  }
}
