<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

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

    // TODO : need to check if email and password entry is not null
    // TODO : need to check if email has right format
    public function main(Request $request, Response $response, $args)
    {
	$sql = "SELECT * FROM User WHERE EmailAddress = '" . $_POST['email'] . "'" ;
	try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch();

	    //print_r($result);
            // TODO : need to check if activated
            if ($result == null)
                echo "Invalid Email";
            else if (strcmp($result['HPassword'], $_POST['password']) != 0)
		echo "Wrong Password";
            else
                echo "Sign In Success";


        } catch (PDOException $e) {
            echo "ERROR : " . $e->getMessage();
        }

	
//          print_r($_POST);
//          print_r($_POST['email']);
//          print_r($_POST['password']);
//        $this->view->render($response, 'index.phtml', ['email' => $_POST['email'], 'password' => $_POST['password']]);
//        return $response;
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

    public function registerHandler(Request $request, Response $response, $args)
    {
        $sql = "SELECT * FROM User WHERE EmailAddress = '" .
		$_POST['email'] . "'" ;
	try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch();
            
            // duplicated email
            if ($result != null)
                echo "email already exists";
            else {
                echo "usable email";
                $sql = "INSERT INTO User (EmailAddress, HPassword, FirstName, LastName, PhoneNum) VALUES (".
		"'". $_POST['email'] ."', ". // Email
		"'". $_POST['password'] ."', ". // Hashed password
		"'". $_POST['firstName'] ."', ". // First Name
		"'". $_POST['lastName'] ."', ". // Last Name
		"'". $_POST['phoneNum'] ."'". // PhoneNumber
		")";
		$stmt = $this->db->query($sql);
            }

        } catch (PDOException $e) {
            echo "ERROR : " . $e->getMessage();
        }

//        $this->view->render($response, 'register.phtml');
//        return $response;
    }
}
