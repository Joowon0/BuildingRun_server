<?php
require "conn.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PhPMailer\Exception;

if($_POST["EmailAddress"]!=""){
$EmailAddress=$_POST["EmailAddress"];
$FirstName=$_POST["FirstName"];
$LastName=$_POST["LastName"];
$mysql_qry ="select * from user where EmailAddress = '".$EmailAddress."'";
$result = mysqli_query($conn, $mysql_qry);

if(mysql_num_rows($result) > 0 )
{
    while($data = mysql_fetch_array($result)){
         	   $FirstName=$data["FirstName"];
		   $LastName=$data["LastName"];
                   sendEmail($EmailAddress,$FirstName,$LastName);
	}
}
else 
{
   echo 0;
}
}
mysqli_close($conn);
?>
<?php

$android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");

if (!$android){
?>

<html>
   <body>
   
      <form action="<?php $_PHP_SELF ?>" method="POST">
         Email: <input type = "text" name = "EmailAddress" />
              firstName: <input type = "text" name="FirstName" />
         	  lastName: <input type = "text" name="LastName" />
         
	<input type = "submit" />
      </form>
   
   </body>
</html>
<?php
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
