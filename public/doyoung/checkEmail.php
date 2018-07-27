<?php
require "conn.php";
if($_POST["EmailAddress"]!=""){
$EmailAddress=$_POST["EmailAddress"];
$qry = "select * from User where EmailAddress = '$EmailAddress'";
$result = mysql_query($qry);
if(mysql_num_rows($result) > 0 )
   {
      echo 0;
   }
   else 
   {
      echo 1;
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
         <input type = "submit" />
      </form>
   
   </body>
</html>
<?php
}
?>