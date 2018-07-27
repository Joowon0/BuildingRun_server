<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

final class HomeController extends BaseController
{
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
            $post = $this->em->find('App\Model\Post', intval($args['id']));
        } catch (\Exception $e) {
            echo $e->getMessage();
            die;
        }

        $this->view->render($response, 'post.twig', ['post' => $post, 'flash' => $messages]);
        return $response;
    }

    public function forgot_pw(Request $request, Response $response, $args)
    {
        $this->view->render($response, 'forgot_pw.phtml');
        return $response;
    }
    public function main(Request $request, Response $response, $args)
    {
        $this->view->render($response, 'main_before.phtml');
        return $response;
    }
    public function main_after(Request $request, Response $response, $args)
    {
        $this->view->render($response, 'main_after.phtml', ['email' => $_POST['email'], 'password' => $_POST['password']]);
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






    // TODO : need to check if email and password entry is not null
    // TODO : need to check if email has right format
    public function loginHandler(Request $request, Response $response, $args)
    {
	$sql = "SELECT * FROM User WHERE EmailAddress = '" . $_POST['email'] . "'" ;
	try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch();

	    //print_r($result);
            // TODO : need to check if activated
            if ($result == null) {
                $message = "There is no account corresponding to input Email";
		echo "<script type='text/javascript'>alert('$message');</script>";
                $this->login($request, $response, $args);
            }
            else if (strcmp($result['HPassword'], $_POST['password']) != 0) {
		$message = "Wrong Password. Please enter again.";
		echo "<script type='text/javascript'>alert('$message');</script>";
                $this->login($request, $response, $args);
            }
            else {
                $this->main_after($request, $response, $args);
            }
        } catch (PDOException $e) {
            echo "ERROR : " . $e->getMessage();
        }

	
//          print_r($_POST);
//          print_r($_POST['email']);
//          print_r($_POST['password']);
//        $this->view->render($response, 'index.phtml', ['email' => $_POST['email'], 'password' => $_POST['password']]);
//        return $response;
    }


    // sign-out
    public function registerHandler(Request $request, Response $response, $args)
    {
	$this->checkDuplicationEmail($_POST);
	$this->sendEmail($_POST['email'], $_POST['firstName'], $_POST['lastName']);
	
        $this->view->render($response, 'register.phtml');
        return $response;
    }

    public function checkDuplicationEmail($userINFO) {
	$sql = "SELECT * FROM User WHERE EmailAddress = '" .
		$userINFO['email'] . "'" ;
	try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch();
            
            // duplicated email
            if ($result != null) {
                $message = "The Email is already registered. Please enter other email or sign-in";
		echo "<script type='text/javascript'>alert('$message');</script>";
		return;
            }
            else {
                $sql = "INSERT INTO User (EmailAddress, HPassword, FirstName, LastName, PhoneNum) VALUES (".
		"'". $userINFO['email'] ."', ".     // Email
		"'". $userINFO['password'] ."', ".  // Hashed password
		"'". $userINFO['firstName'] ."', ". // First Name
		"'". $userINFO['lastName'] ."', ".  // Last Name
		"'". $userINFO['phoneNum'] ."'".    // PhoneNumber
		")";
		$stmt = $this->db->query($sql);
			$message = "Sign-up is completed. Please check you email to activate you account";
			echo "<script type='text/javascript'>alert('$message');</script>";
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
//            $mail->addAddress('ellen@example.com');               // Name is optional
            $mail->addReplyTo('info@example.com', 'Information');
//            $mail->addCC('cc@example.com');
//            $mail->addBCC('bcc@example.com');

            //Attachments
//            $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

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
}
