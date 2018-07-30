<?php
require "conn.php";


if($_POST["EmailAddress"]!=""){
$EmailAddress=$_POST['EmailAddress']';
$FirstName=$_POST['FirstName']';
$LastName=$_POST['LastName'];
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
?>
