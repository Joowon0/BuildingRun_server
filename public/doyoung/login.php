<?php
include("conn.php");
if($_POST["user_id"]!=""){
$user_id=$_POST["user_id"];
$user_pass=$_POST["user_pass"];

$qry = "select * from User where user_id = '$user_id' and user_pass = '$user_pass'";

$result = mysql_query($qry);

if(mysql_num_rows($result) > 0 )
{
   echo "login success";
}
else 
{
   echo "login failed";
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
         id: <input type = "text" name = "user_id" />
         password: <input type = "text" name = "user_pass" />
         <input type = "submit" />
      </form>
   </body>
</html>
<?php
}
?>