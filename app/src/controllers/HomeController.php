<?php
namespace App\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

final class HomeController extends BaseController {
  public function dispatch(Request $request, Response $response, $args)
  {
      $this->logger->info("Home page action dispatched");
      $this->flash->addMessage('info', 'Sample flash message');
      $this->view->render($response, 'home.twig');
      return $response;
  }
  public function viewPost(Request $request, Response $response, $args)
  {
      $this->logger->info("View post using Doctrine with Slim 3");
      $messages = $this->flash->getMessage('info');
      try {
        echo ($args['id']);
          //$post = $this->em->find('App\Model\Post', intval($args['id']));
      } catch (\Exception $e) {
          echo $e->getMessage();
          die;
      }
      //$this->view->render($response, 'post.twig', ['post' => $post, 'flash' => $messages]);
      //return $response;
  }

  public function main(Request $request, Response $response, $args)
  {
      $this->view->render($response, 'main_before.phtml');
      return $response;
  }
  public function main_after(Request $request, Response $response, $args, $firstName, $lastName)
  {
      $this->view->render($response, 'main_after.phtml', ['email' => $_POST['email'], 'firstName' => $firstName, 'lastName' => $lastName]);
      return $response;
  }
  public function login(Request $request, Response $response, $args)
  {
      $this->view->render($response, 'login.phtml');
      return $response;
  }
  public function register(Request $request, Response $response, $args)
  {
      $this->view->render($response, 'register.phtml');
      return $response;
  }

  public function signoutHandler(Request $request, Response $response, $args)
  {
      // remove all session variables
      session_unset();

      // destroy the session
      session_destroy();

      echo "<script> document.location.href='/login'; </script>";
      //$this->view->render($response, 'register.phtml');
      //return $response;
  }

  public function app_signout(Request $request, Response $response, $args)
  {
      if (isset($_POST['USN'])) {
        $sendData = array("ACK"=>true);

        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($sendData));
      }
  }

   public function user_change(Request $request, Response $response, $args)
  {
      $this->view->render($response, 'user_change.phtml');
      return $response;
  }

     public function pw_check(Request $request, Response $response, $args)
  {
      $this->view->render($response, 'pw_check.phtml');
      return $response;
  }

   public function pw_new(Request $request, Response $response, $args)
  {
      $this->view->render($response, 'pw_new.phtml');
      return $response;
  }

  public function delete_id_check(Request $request, Response $response, $args)
  {
      $this->view->render($response, 'delete_id_check.phtml');
      return $response;
  }
  public function forgot_pw(Request $request, Response $response, $args)
  {
      $this->view->render($response, 'forgot_pw.phtml');
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
