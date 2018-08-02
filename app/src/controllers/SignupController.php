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
    list ($acitvationLinkNonce, $duplicateCheckResult) = $this->signup($_POST);

    $this->view->render($response, 'register.phtml', ['registerResult' => $duplicateCheckResult]);
    if ($duplicateCheckResult == self::SUCCESS)
      EmailController::sendActivationEmail($_POST['email'], $_POST['firstName'], $_POST['lastName'], $acitvationLinkNonce);

    return $response;
  }

  // outermost common function
  public function signup($userINFO) {
    $duplicateCheckResult = $this->checkDuplicationEmail($userINFO);

    if ($duplicateCheckResult == self::SUCCESS) {
      // generate hashed password and nonce
      $hashedPW = password_hash($userINFO['password'], PASSWORD_DEFAULT);
      $acitvationLinkNonce = HomeController::randomString(50);

      // store user information
      $this->storeUserInfo($userINFO, $hashedPW);
      $USN = $this->getUSN($userINFO['email']); // TODO : can I extract this to one query?
      $this->storeNonceInfo($USN, $acitvationLinkNonce);
    } else
      $acitvationLinkNonce = "NONCE NOT CREATED";

    return array ($acitvationLinkNonce, $duplicateCheckResult);
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

  public function storeUserInfo($userINFO, $hashedPW) {

    $sql = "INSERT INTO User (EmailAddress, HPassword, FirstName, LastName, PhoneNum) VALUES (".
      "'". $userINFO['email'] ."', ".     // Email
      "'". $hashedPW          ."', ".     // Hashed password
      "'". $userINFO['firstName'] ."', ". // First Name
      "'". $userINFO['lastName'] ."', ".  // Last Name
      "'". $userINFO['phoneNum'] ."'".    // PhoneNumber
      ")";
    $stmt = $this->db->query($sql);
  }

  public function storeNonceInfo($USN, $nonce) {
    $sql = "INSERT INTO Nonce( Nonce, USN) VALUES ( '".$nonce."' , '$USN' )";
    $stmt = $this->db->query($sql);
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
  }
}
?>