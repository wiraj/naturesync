

<?php
    session_start();
    include("functions.php");
    include("includes/setup_mysql.php");
    
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
    	// Redirect to the login page or handle accordingly
    	header("Location:login.php");
    	exit();
    }
    
    
    // Password reset form submission
    if (isset($_POST['reset_password'])) {
      // Retrieve form data
      $currentPassword = $_POST['current_password'];
      $newPassword = $_POST['new_password'];
      $confirmNewPassword = $_POST['confirm_new_password'];
    
      // Validate and sanitize form inputs
      $errors1 = array();
    
      // Validate current password field
      if (empty($currentPassword)) {
        $errors1[] = "Current Password field is required.";
      } else {
        // Check if the current password matches the one in the database
        $userId = $_SESSION['user_id'];
        $query = "SELECT password FROM user WHERE id = '$userId'";
        $result = mysqli_query($conn, $query) or die("Error: " . mysqli_error($conn));
        $row = mysqli_fetch_assoc($result);
        $currentPasswordHash = $row['password'];
        if (!password_verify($currentPassword, $currentPasswordHash)) {
          $errors1[] = "Invalid current password.";
        }
      }
    
      // Validate new password field
      if (empty($newPassword)) {
        $errors1[] = "New Password field is required.";
      } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/', $newPassword)) {
        $errors1[] = "New password must contain at least 8 characters, including uppercase and lowercase letters, numbers, and special characters.";
      }
    
      // Validate confirm new password field
      if (empty($confirmNewPassword)) {
        $errors1[] = "Confirm New Password field is required.";
      } elseif ($newPassword !== $confirmNewPassword) {
        $errors1[] = "New Password and Confirm New Password do not match.";
      }
    
      // If there are no errors, update the user password
      if (empty($errors1)) {
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE user SET password = '$newPasswordHash' WHERE id = '$userId'";
        mysqli_query($conn, $query) or die("Error: " . mysqli_error($conn));
    
        // Redirect to the profile page or display a success message
        $msg= "Password reset successfully.";
         $_SESSION["msg"] = $msg;
         echo'<script>window.location="profile.php"</script>';
      }
    }

?>


<?php include("includes/app_header.php"); ?>

    
    <!-- Page Content -->
    <div class="page-content">
        <div class="container">
      
		<div class="edit-profile">
				<?php if (isset($errors1) && count($errors1) > 0): ?>
					<div class="alert alert-danger">
						<ul>
						<?php foreach ($errors1 as $error): ?>
						<li><?php echo $error; ?></li>
						<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
				<form id="password-reset-form" method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
					<div class="mb-3 input-group input-mini">
						<input type="password" id="current-password" name="current_password" placeholder="Current Password" class="form-control" required>
					</div>
					<div class="mb-3 input-group input-mini">
						<input type="password" id="new-password" name="new_password" class="form-control" placeholder="New Password" required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$">
					</div>
					<div class="mb-3 input-group input-mini">
						<input type="password" id="confirm-new-password" name="confirm_new_password" placeholder="Confirm New Password" class="form-control" required>
					</div>
					
					<button type="submit" name="reset_password"  class="btn btn-primary btn-block mb-3">Reset Password</button>
				</form>
            </div>

			
        </div>
    </div>
    <!-- Page Content End-->
</div> 

<?php include("includes/menu_bar.php"); ?>
	
<?php include("includes/app_footer.php"); ?>
