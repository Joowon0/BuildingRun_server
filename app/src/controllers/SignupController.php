<?php
abstract class signupMessage {
  const SUCCESS = 0;
  const DUPILCATED = 1;
}

public function registerHandler(Request $request, Response $response, $args) {
  $duplicateCheckResult = $this->checkDuplicationEmail($_POST);
  switch (duplicateCheckResult) {
    case signupMessage.SUCCESS :
      storeUserInfo($_POST);
      $message = "Sign-up is completed. Please check you email to activate you account";
      echo "<script type='text/javascript'>alert('$message');</script>";
      break;
    case signupMessage.DUPLICATED:
      $message = "The Email is already registered. Please enter other email or sign-in";
      echo "<script type='text/javascript'>alert('$message');</script>";
      break;
    default:
      $message = "ERROR : smth wrong in checkDuplicationEmail().";
      echo "<script type='text/javascript'>alert('$message');</script>";
  }
  $this->sendEmail($_POST['email'], $_POST['firstName'], $_POST['lastName']);

  $this->view->render($response, 'register.phtml');
  return $response;
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

public function checkDuplicationEmail($userINFO) {
  $sql = "SELECT * FROM User WHERE EmailAddress = '" .
  $userINFO['email'] . "'" ;
  try {
    $stmt = $this->db->query($sql);
    $result = $stmt->fetch();

    if ($result != null) {
      return signupMessage.DUPLICATED;
    }
    else {
      return signupMessage.SUCCESS;
    }
  } catch (PDOException $e) {
    echo "ERROR : " . $e->getMessage();
  }
}

public function sendEmail($email, $firstName, $lastName) {
  $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
  try {
    //Server settings
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'QIoTteamA@gmail.com';                 // SMTP username
    $mail->Password = 'vagrant!';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('QIoTteamA@gmail.com', 'TeamA');
    $mail->addAddress($email, $firstName . " " . $lastName);     // Add a recipient
    $mail->addReplyTo('QIoTteamA@gmail.com', 'TeamA');

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = '[TEAMA] Account Activation';
    $mail->Body    = 'Hi! This is account activation request email from teama-iot.calit2.net <br> <b>Please Click the activation link!</b> <br>';
    $mail->AltBody = 'Hi! This is account activation request email from teama-iot.calit2.net Please Click the activation link!';

    $mail->send();
    echo 'Message has been sent';
  } catch (Exception $e) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
  }
}
?>
