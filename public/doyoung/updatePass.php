<?php
require "conn.php";
$a_dong=isset($_POST['a_dong']) ? $_POST['a_dong'] : '';  
$a_ho=isset($_POST['a_ho']) ? $_POST['a_ho'] : '';
$a_password=isset($_POST['a_password']) ? $_POST['a_password'] : '';
$mysql_qry ="update post set a_password='$a_password' where a_dong LIKE '%$a_dong%' and a_ho LIKE '%$a_ho%'";
$result = mysqli_query($conn, $mysql_qry);
if($result){  
       echo "password change success";  
    }  
    else{  
       echo "password change failed"; 
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
         a_dong: <input type = "text" name = "a_dong" />
         a_ho: <input type = "text" name = "a_ho" />
       a_password: <input type = "text" name="a_password" />
         <input type = "submit" />
      </form>
   
   </body>
</html>
<?php
}
?>