<?php
require "conn.php";
if($_POST["EmailAddress"]!=""){
$EmailAddress=$_POST["EmailAddress"];
$HPassword=$_POST["HPassword"];
$FirstName=$_POST["FirstName"];
$LastName=$_POST["LastName"];
$PhoneNum=$_POST["PhoneNum"];

$qry = "INSERT INTO User (EmailAddress, HPassword, FirstName, LastName, PhoneNum) 
   VALUES ('$EmailAddress','$HPassword','$FirstName','$LastName','$PhoneNum')";
$result = mysql_query($qry);

if($result)
{
   echo 1;
}
else 
{
   echo 0;
}
}

mysql_close();
?>
<?php

$android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");

if (!$android){
?>

<html>
   <body>
   
      <form action="<?php $_PHP_SELF ?>" method="POST">
         EmailAddress: <input type = "text" name = "EmailAddress" />
         HPassword: <input type = "text" name = "HPassword" />
         FirstName: <input type = "text" name = "FirstName" />
         LastName: <input type = "text" name = "LastName" />
         PhoneNum: <input type = "text" name = "PhoneNum" />
         <input type = "submit" />
      </form>
   
   </body>
</html>
<?php
}
?>
