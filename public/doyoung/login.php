<?php
include("conn.php");
if($_POST["EmailAddress"]!=""){
$user_id=$_POST["EmailAddress"];
$user_pass=$_POST["HPassword"];

$qry = "select * from User where EmailAddress = '$user_id' and HPassword = '$user_pass'";

$result = mysql_query($qry);

if(mysql_num_rows($result) > 0 )
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
         id: <input type = "text" name = "EmailAddress" />
         password: <input type = "text" name = "HPassword" />
         <input type = "submit" />
      </form>
   </body>
</html>
<?php
}
?>
