<?php
namespace App\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class IDCancellation extends BaseController {
  //abstract class newPWMessage {
      const CORRECT_PASSWORD = 1;
      const WRONG_PASSWORD = 2;
      const NO_SUCH_ACCOUNT = 3;
  //}

  // for WEB
  public function delete_id_checkHandler(Request $request, Response $response, $args)
  {
      $pwCheckResult = $this->checkPassword($_SESSION["USN"], $_POST['password']);

      if ($pwCheckResult == self::CORRECT_PASSWORD) {
        $this->deleteUserInfo($_SESSION["USN"]);
      }

      $this->view->render($response, 'delete_id_check.phtml', ['pwCheckResult'=>$pwCheckResult]);
      return $response;
  }

  // for APP
  public function app_accountCancel(Request $request, Response $response, $args)
  {
      $pwCheckResult = $this->checkPassword($_POST["USN"], $_POST['password']);

      if ($pwCheckResult == self::CORRECT_PASSWORD) {
        $this->deleteUserInfo($_POST["USN"]);
      }

      $sendData = array("Result"=>$pwCheckResult);

      return $response->withStatus(200)
          ->withHeader('Content-Type', 'application/json')
          ->write(json_encode($sendData));
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

  public function deleteUserInfo($USN) {
    $sql = "DELETE FROM User WHERE USN = " . $USN;

    try {
      $stmt = $this->db->query($sql);

    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }
}
