<?php


 

  function checkExistingEmail($email) {

      // Include the database connection file
      include("includes/setup_mysql.php");
      
      // Prepare the SQL statement
      $sql = "SELECT * FROM user WHERE email = '$email'";
      $result = mysqli_query($conn, $sql) or die("Error: " . $sql);
      $count = mysqli_num_rows($result);
  
      if($count > 0){
            return true;
      }else{
            return false;
      }
  }


 
?>