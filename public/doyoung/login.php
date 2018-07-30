<?php
include("conn.php");
if($_POST["EmailAddress"]!=""){
$user_id=$_POST["EmailAddress"];
$user_pass=$_POST["HPassword"];

$qry = "select * from User where EmailAddress = '".$user_id."'";

$result = mysql_query($qry);

if(mysql_num_rows($result) > 0 )
{
    while($data = mysql_fetch_array($result)){
         echo $data['HPassword'];
         if(!password_verify($_POST['HPassword'], $data['HPassword'])){
            echo 2;
         }
         else{
            echo 1;
         }
    }
}
else 
{
   echo "PASSWORD:" $data['HPassword'];
   echo 9;
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
