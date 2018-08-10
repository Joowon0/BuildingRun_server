<?php
namespace App\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class HomeController extends BaseController {

  static public function isSignedIn() {
    if (isset($_SESSION["USN"]) && isset($_SESSION["email"]) && $_SESSION["email"] != "")
      return true;
    else
      return false;
  }

  public function need2BSignIn(Response $response, $message, $link) {
    if ($this->isSignedIn()) {
      $this->view->render($response, $link);
      return $response;
    }
    else {
      echo "<script type='text/javascript'>alert('$message');</script>";

      echo "<script> document.location.href='/login'; </script>";
      return $response;
    }
  }

  public function need2BSignOut(Response $response, $message, $link) {
    if ($this->isSignedIn()) {
      echo "<script type='text/javascript'>alert('$message');</script>";

      echo "<script> document.location.href='/'; </script>";
      return $reponse;
    }
    else {
      $this->view->render($response, $link);
      return $response;
    }
  }

  public function main(Request $request, Response $response, $args)
  {
      if ($this->isSignedIn()) {
        $this->view->render($response, 'main_after.phtml');
      }
      else {
        $this->view->render($response, 'main_before.phtml');
      }
      return $response;
  }

  public function login(Request $request, Response $response, $args)
  {
    $message = "You are already signed in.";
    $link = 'login.phtml';
    return $this->need2BSignOut($response, $message, $link);
  }
  public function register(Request $request, Response $response, $args)
  {
    $message = "You are already signed in. Please sign out before you register.";
    $link = 'register.phtml';
    return $this->need2BSignOut($response, $message, $link);
  }

  public function signoutHandler(Request $request, Response $response, $args)
  {
      // remove all session variables
      session_unset();

      // destroy the session
      session_destroy();

      echo "<script> document.location.href='/'; </script>";
      return true;
  }

  public function app_signout(Request $request, Response $response, $args)
  {
      $json = file_get_contents('php://input');
      $jsonArray = json_decode($json, true);

      if (isset($jsonArray['USN'])) {
        $sendData = array("ACK"=>true);

        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($sendData));
      }
  }

   public function user_change(Request $request, Response $response, $args)
  {
    $message = "Please sign-in to view your profile.";
    $link = 'user_change.phtml';
    return $this->need2BSignIn($response, $message, $link);
  }

     public function pw_check(Request $request, Response $response, $args)
  {
    $message = "Please sign-in before changing password.";
    $link = 'pw_check.phtml';
    return $this->need2BSignIn($response, $message, $link);
  }

   public function pw_new(Request $request, Response $response, $args)
  {
    $message = "Please sign-in before changing password.";
    $link = 'pw_new.phtml';
    return $this->need2BSignIn($response, $message, $link);
  }

  public function delete_id_check(Request $request, Response $response, $args)
  {
    $message = "Please sign-in to delete an account.";
    $link = 'delete_id_check.phtml';
    return $this->need2BSignIn($response, $message, $link);
  }

  public function forgot_pw(Request $request, Response $response, $args)
  {
    $message = "You are already signed in..";
    $link = 'forgot_pw.phtml';
    return $this->need2BSignOut($response, $message, $link);
  }


  // public function intro_teama(Request $request, Response $response, $args)
  // {
  //     $this->view->render($response, 'intro_teama.phtml');
  //     return $response;
  // }


  public function air_chart(Request $request, Response $response, $args)
  {
      if (!isset($_SESSION['MAC']) || $_SESSION['MAC'] == '') {
        $sql = "SELECT * FROM Sensor WHERE USN = ".$_SESSION['USN']." LIMIT 1";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        $_SESSION['MAC'] = $result['MAC'];
      }

      $sql = "SELECT * FROM Sensor WHERE MAC = '".$_SESSION['MAC']."' LIMIT 1";

      try {
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();

      } catch (PDOException $e) {
        echo "ERROR : " . $e->getMessage();
      }

      $this->view->render($response, 'air_chart.phtml', ['MAC'=>$result['MAC'], 'lati'=>$result['latitude'], 'logn'=>$result['longitude']]);
      return $response;
  }

  public function air_chart10min(Request $request, Response $response, $args)
  {
      if (!isset($_SESSION['MAC']) || $_SESSION['MAC'] == '') {
        $sql = "SELECT * FROM Sensor WHERE USN = ".$_SESSION['USN']." LIMIT 1";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        $_SESSION['MAC'] = $result['MAC'];
      }

      $sql = "SELECT * FROM Sensor WHERE MAC = '".$_SESSION['MAC']."' LIMIT 1";

      try {
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();

      } catch (PDOException $e) {
        echo "ERROR : " . $e->getMessage();
      }

      $this->view->render($response, 'air_chart10min.phtml', ['MAC'=>$result['MAC'], 'lati'=>$result['latitude'], 'logn'=>$result['longitude']]);
      return $response;
  }
  public function air_chartHour(Request $request, Response $response, $args)
  {
      if (!isset($_SESSION['MAC']) || $_SESSION['MAC'] == '') {
        $sql = "SELECT * FROM Sensor WHERE USN = ".$_SESSION['USN']." LIMIT 1";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        $_SESSION['MAC'] = $result['MAC'];
      }

      $sql = "SELECT * FROM Sensor WHERE MAC = '".$_SESSION['MAC']."' LIMIT 1";

      try {
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();

      } catch (PDOException $e) {
        echo "ERROR : " . $e->getMessage();
      }

      $this->view->render($response, 'air_chartHour.phtml', ['MAC'=>$result['MAC'], 'lati'=>$result['latitude'], 'logn'=>$result['longitude']]);
      return $response;
  }

  public function heart(Request $request, Response $response, $args)
  {
      $this->view->render($response, 'heart.phtml');
      return $response;
  }



  static function randomString($length = 6) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
  	     $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
  }












}
