<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class SignupController extends BaseController {
  //abstract class signupMessage {
    
    const SUCCESS = 1;
    const DUPLICATED = 2;
    const NONCE_EXIST = 3;
    const NONCE_NOT_EXIST = -1;
  //}

  public function registerHandler(Request $request, Response $response, $args) {
    $duplicateCheckResult = $this->checkDuplicationEmail($_POST);
    switch ($duplicateCheckResult) {
      case self::SUCCESS :
        $this->storeUserInfo($_POST);
        $USN = $this->getUSN($_POST['email']);
        $message = "Sign-up is completed. Please check you email to activate you account";
        echo "<script type='text/javascript'>alert('$message');</script>";

        $acitvationLinkNonce = HomeController::randomString(50);
        $this->storeNonceInfo($USN, $acitvationLinkNonce);
        list ($html, $notHtml) = EmailController::activationEmailContent($acitvationLinkNonce);
        EmailController::sendEmail($_POST['email'], $_POST['firstName'], $_POST['lastName'], $html, $notHtml);

        break;
      case self::DUPLICATED:
        $message = "The Email is already registered. Please enter other email or sign-in";
        echo "<script type='text/javascript'>alert('$message');</script>";
        break;
      default:
        $message = "ERROR : smth wrong in checkDuplicationEmail().";
        echo "<script type='text/javascript'>alert('$message');</script>";
    }

    $this->view->render($response, 'register.phtml', ['former' => $_POST]);
    return $response;

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

  public function storeUserInfo($userINFO) {
    $hashedPW = password_hash($userINFO['password'], PASSWORD_DEFAULT);
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
    $nonce_existence = $this->checkNonceExist($nonce);

    if ($nonce_existence!=self::NONCE_NOT_EXIST) {
      $nonceID = $nonce_existence;
      $this->deleteNonce($nonceID);

    }
    else {
      // $message = "INVALID PAGE REQUEST : NO SUCH NONCE EXIST";
      // echo "<script type='text/javascript'>alert('$message');</script>";
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
        return $result['Nonce_ID'];
      }
      else {
        return self::NONCE_NOT_EXIST;
      }
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }

  public function deleteNonce($nonceID) {
    $sql = "DELETE FROM Nonce WHERE Nonce_ID = ". $nonceID;
    $stmt = $this->db->query($sql);
  }
}
?>
