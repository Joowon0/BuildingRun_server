<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

final class forgottenPWController extends BaseController {
  public function forgot_pw(Request $request, Response $response, $args)
  {
      $this->view->render($response, 'forgot_pw.phtml');
      return $response;
  }

  public function forgot_pwHandler(Request $request, Response $response, $args)
  {
      $this->view->render($response, 'forgot_pw.phtml');
      return $response;
  }
}
