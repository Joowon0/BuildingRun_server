<?php
require "conn.php";
include "SignupController.php"
$EmailAddress=$_POST["EmailAddress"];
$FirstName=$_POST["FirstName"];
$LastName=$_POST["LastName"];

sendEmail($EmailAddress,$FirstName,$LastName);

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
?>
