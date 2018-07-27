<?php
require "conn.php";
$user_email=isset($_POST['EmailAddress']) ? $_POST['EmailAddress'] : '';
$user_pass=isset($_POST['HPassword']) ? $_POST['HPassword'] : '';
$mysql_qry ="update User set HPassword='$user_pass' where EmailAddress = '$user_email'";
$result = mysqli_query($conn, $mysql_qry);
if($result){  
       echo 1;  
    }  
    else{  
       echo 0; 
       echo mysqli_error($conn);
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
         Email: <input type = "text" name = "user_email" />
              a_password: <input type = "text" name="user_pass" />
         <input type = "submit" />
      </form>
   
   </body>
</html>
<?php
}
?>
