<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class SignupController extends BaseController {
  //abstract class signupMessage {
    const SUCCESS = 1;
    const DUPLICATED = 2;
    const NONCE_EXIST = 3;
    const NONCE_NOT_EXIST = 4;
  //}

  // for WEB
  public function registerHandler(Request $request, Response $response, $args) {
    // Start the session
    session_start();

    $_SESSION["email"] = $_POST['email'];
    $_SESSION["firstName"] = $_POST['firstName'];
    $_SESSION["lastName"] = $_POST['lastName'];
    $_SESSION["phoneNum"] = $_POST['phoneNum'];

    list ($acitvationLinkNonce, $duplicateCheckResult) = $this->signup($_POST);

    if ($duplicateCheckResult == self::SUCCESS) {
      session_unset();
      session_destroy();
    }

    $this->view->render($response, 'register.phtml', ['registerResult' => $duplicateCheckResult]);
    return $response;
  }

  // for app
  public function app_signup(Request $request, Response $response, $args) {
    $json = file_get_contents('php://input');
    $jsonArray = json_decode($json, true);

    if (isset($jsonArray['email']) && isset($jsonArray['firstName']) && isset($jsonArray['lastName']) &&
        isset($jsonArray['password']) && isset($jsonArray['phoneNum'])){

      list ($acitvationLinkNonce, $duplicateCheckResult) = $this->signup($jsonArray);

      $sendData = array("Result"=>$duplicateCheckResult);

      return $response->withStatus(200)
          ->withHeader('Content-Type', 'application/json')
          ->write(json_encode($sendData));
    }
    else
      return $respons->withStatus(204);
  }

  // outermost common function
  public function signup($userINFO) {
    $duplicateCheckResult = $this->checkDuplicationEmail($userINFO);

    if ($duplicateCheckResult == self::SUCCESS) {
      $hashedPW = password_hash($userINFO['password'], PASSWORD_DEFAULT);
      $this->storeUserInfo($userINFO, $hashedPW);

      $USN = $this->getUSN($userINFO['email']);
      $acitvationLinkNonce = HomeController::randomString(50);
      $this->storeNonceInfo($USN, $acitvationLinkNonce);

      EmailController::sendActivationEmail($userINFO['email'], $userINFO['firstName'], $userINFO['lastName'], $acitvationLinkNonce);
    } else
      $acitvationLinkNonce = "NONCE NOT CREATED";

    return array ($acitvationLinkNonce, $duplicateCheckResult);
  }

  public function checkDuplicationEmail($userINFO) {
    $sql = "SELECT * FROM User WHERE EmailAddress = '" . $userINFO['email'] . "'" ;
    try {
      $stmt = $this->db->query($sql);
      $result = $stmt->fetch();

      if ($result != null) {
        return self::DUPLICATED;
      }
      else {
        return self::SUCCESS;
      }
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }

  public function storeUserInfo($userINFO, $hashedPW) {

    $sql = "INSERT INTO User (EmailAddress, HPassword, FirstName, LastName, PhoneNum) VALUES (".
      "'". $userINFO['email'] ."', ".     // Email
      "'". $hashedPW          ."', ".     // Hashed password
      "'". $userINFO['firstName'] ."', ". // First Name
      "'". $userINFO['lastName'] ."', ".  // Last Name
      "'". $userINFO['phoneNum'] ."'".    // PhoneNumber
      ")";
    $stmt = $this->db->query($sql);

    return true;
  }

  public function getUSN($email) {
    $sql = "SELECT USN FROM User WHERE EmailAddress ='". $email ."'" ;
    try {
      $stmt = $this->db->query($sql);
      $result = $stmt->fetch();

      return $result['USN'];
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }

  public function storeNonceInfo($USN, $nonce) {
    $sql = "INSERT INTO Nonce( Nonce, USN) VALUES ( '".$nonce."' , '$USN' )";
    $stmt = $this->db->query($sql);
    return true;
  }



  public function accountActivation(Request $request, Response $response, $args) {
    $nonce = $args['id'];
    list ($nonce_existence, $nonce_ID) = $this->checkNonceExist($nonce);

    if ($nonce_existence==self::NONCE_EXIST) {
      $this->deleteNonce($nonce_ID);
    }

    $this->view->render($response, 'accountActivation.phtml', ['actvationResult' => $nonce_existence]);
    return $response;
  }

  public function checkNonceExist($nonce) {
    $sql = "SELECT * FROM Nonce WHERE Nonce = '" . $nonce . "'";
    try {
      $stmt = $this->db->query($sql);
      $result = $stmt->fetch();
      //print_r($result);

      if ($result != null) {
        return array (self::NONCE_EXIST, $result['Nonce_ID']);
      }
      else {
        return array (self::NONCE_NOT_EXIST, -1);
      }
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }

  public function deleteNonce($nonce_ID) {
    $sql = "DELETE FROM Nonce WHERE Nonce_ID = ". $nonce_ID;
    $stmt = $this->db->query($sql);
    return true;
  }
}
?>
