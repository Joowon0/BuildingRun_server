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

  // for WEB
  public function pw_checkHandler(Request $request, Response $response, $args) {
    $pwCheckResult = $this->checkPassword($_SESSION["USN"], $_POST['password']);

    if ($pwCheckResult == self::CORRECT_PASSWORD) {
      echo "<script> document.location.href='/pw_new'; </script>";
    } else if ($pwCheckResult == self::WRONG_PASSWORD) {
      $this->view->render($response, 'pw_check.phtml', ['pwCheckResult'=>self::WRONG_PASSWORD]);
      return $response;
    } else if ($pwCheckResult == self::NO_SUCH_ACCOUNT) {
      echo "<script> document.location.href='/login'; </script>";
    } else {
      echo "ERROR : smth wrong with pw_checkHandler()";
    }
  }

  // for APP
  public function app_checkPw(Request $request, Response $response, $args) {
    if (isset($_POST['USN']) && isset($_POST['password'])) {
      $pwCheckResult = $this->checkPassword($_POST["USN"], $_POST['password']);

      $sendData = array("Result"=>$pwCheckResult);

      return $response->withStatus(200)
          ->withHeader('Content-Type', 'application/json')
          ->write(json_encode($sendData));
    }
  }

  // outermost common function
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

  // for app
  public function app_setNewPW(Request $request, Response $response, $args) {
    if (isset($_POST['USN']) && isset($_POST['password'])) {
      $hashedPW = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $this->setNewPassword($_POST["USN"], $hashedPW);

      $sendData = array("ACK"=>true);

      return $response->withStatus(200)
          ->withHeader('Content-Type', 'application/json')
          ->write(json_encode($sendData));
    }
  }

  public function pw_newHandler(Request $request, Response $response, $args) {
    $hashedPW = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $this->setNewPassword($_SESSION["USN"], $hashedPW);

    echo "<script> document.location.href='/'; </script>";
  }

  public function setNewPassword($USN, $newPassword) {
    $sql = "UPDATE User SET HPassword='". $newPassword. "'  WHERE USN = " . $USN;

    try {
    	$stmt = $this->db->query($sql);
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }
}
