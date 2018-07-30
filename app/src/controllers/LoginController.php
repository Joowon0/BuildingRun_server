<?php
abstract class loginMessage {
    const SUCCESS = 0;
    const NO_SUCH_EMAIL = 1;
    const WORNG_PASSWORD = 2;
}

function checkPassword($email) {
  $sql = "SELECT * FROM User WHERE EmailAddress = '" . $_POST['email'] . "'" ;
  try {
  	$stmt = $this->db->query($sql);
  	$result = $stmt->fetch();

    if ($result == null) {
      return loginMessage.NO_SUCH_EMAIL;
    } else if (!password_verify($_POST['password'], $result['HPassword'])) {
      return loginMessage.WORNG_PASSWORD;
    } else {
      return loginMessage.SUCCESS;
    }
  } catch (PDOException $e) {
    echo "ERROR : " . $e->getMessage();
  }
}

// TODO : need to check if email and password entry is not null
function loginHandler(Request $request, Response $response, $args) {
  $pwCheckResult = checkPassword($_POST['email']);

  switch($pwCheckResult) {
    case loginMessage.NO_SUCH_EMAIL:
      $message = "There is no account corresponding to input Email";
      echo "<script type='text/javascript'>alert('$message');</script>";
      break;
    case loginMessage.WORNG_PASSWORD:
      $message = "Wrong Password. Please enter again.";
      echo "<script type='text/javascript'>alert('$message');</script>";
      break;
    case loginMessage.SUCCESS:
      $message = "Wrong Password. Please enter again.";
      echo "<script type='text/javascript'>alert('$message');</script>";
      break;
    default :
      $message = "ERROR : smth wrong in checkPassword().";
      echo "<script type='text/javascript'>alert('$message');</script>";
  }

	// TODO : need to check if activated
}
?>
