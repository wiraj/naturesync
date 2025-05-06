<?php
    session_start();
    
    include("functions.php");
    

    include_once 'includes/setup_mysql.php';
    
    $errors = [];
    $success = [];
    
    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
        

        $firstName = $_POST['first-name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm-password'];
    
       
        if (empty($firstName)) {
            $errors[] = 'First Name is required.';
        }
    
        
        if (empty($email)) {
        $errors[] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format.';
        } elseif (!preg_match('/^[a-zA-Z0-9._%+-]+@northumbria\.ac\.uk$/', $email)) {
            $errors[] = 'Only University emails are allowed.';
        } else {
       
            $sql = "SELECT * FROM user WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $errors[] = 'Email already exists.';
            }
        }
    
      
        if (empty($password)) {
            $errors[] = 'Password is required.';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/', $password)) {
            $errors[] = 'Password must contain at least 8 characters, including at least one uppercase letter, one lowercase letter, one number, and one special character.';
        }
        if (empty($confirmPassword)) {
            $errors[] = 'Confirm Password is required.';
        } elseif ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match.';
        }
    
        
        if (count($errors) === 0) {
            // Insert user data into the database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO user (user_type, first_name, email, `password`, `account_status`) VALUES ('user', '$firstName', '$email', '$hashedPassword', '1')";
            $result = mysqli_query($conn, $sql) or  die("error 21". $sql);
    
            if ($result) {
                
                $success[] = 'Your account successfully created and activate within two hours!';
               
    
    
            } else {
                $errors[] = 'Error occurred while registering. Please try again.';
            }
        }
    }



?>


<!DOCTYPE html>
<html lang="en">
<head>
    
    <!-- Meta -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, minimal-ui, viewport-fit=cover">
	<meta name="theme-color" content="#2196f3">

    
    <!-- Favicons Icon -->
	<link rel="shortcut icon" type="image/x-icon" href="assets/images/logo.png" />
    
    <!-- Title -->
	<title>NatureSync</title>
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="assets/vendor/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css?v=1.5">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200;300;400;600;700;800;900&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>   
<body>
<div class="page-wraper">
    
    <!-- Preloader -->
	<div id="preloader">
		<div class="spinner"></div>
	</div>
    <!-- Preloader end-->

    <!-- Welcome Start -->
	<div class="content-body">
		<div class="container vh-100">
			<div class="welcome-area">
			
				<div class="join-area">
					<div class="started">
						<h1 class="title">Create an Account</h1>
						    <?php if (count($errors) > 0): ?>
                                <div class="alert alert-danger">
                                    <ul>
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo $error; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                             <?php if (count($success) > 0): ?>
                                <div class="alert alert-success">
                                    <ul>
                                        <?php foreach ($success as $msg): ?>
                                            <li><?php echo $msg; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
					</div>
					<form id="signup-form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
						<div class="mb-3 input-group input-group-icon">
							
							<input type="text" class="form-control" id="first-name" name="first-name" placeholder="Account Name" required>
						</div>
						
						<div class="mb-3 input-group input-group-icon">
							
							<input type="email" class="form-control" id="email" name="email" placeholder="University Email" required>
						</div>
					
						<div class="mb-3 input-group input-group-icon">
							
							<input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
						</div>
						<div class="mb-3 input-group input-group-icon">
							
							<input type="password" class="form-control" id="confirm-password" name="confirm-password" placeholder="Confirm Password" required>
						</div>
						<div class="mb-3 input-group input-group-icon">
							<!--<input type="checkbox" class="form-check-input" id="agree-terms" name="agree-terms" required>  -->
							<!--<label class="form-check-label" for="agree-terms">I agree to Terms and Conditions</label>-->
								
						</div>
						<button type="submit" class="btn btn-primary btn-block mb-3">REGISTER</button>
					</form>
					
					<div class="d-flex align-items-center justify-content-center">
						<a href="javascript:void(0);" class="text-light text-center d-block">If you have an account?</a>
						<a href="login.php" class="btn-link d-block ms-3 text-underline">Signin here</a>
					</div>
				</div>
			</div>
		</div>
	</div>
    <!-- Welcome End -->
	
    
</div>
<!--**********************************
    Scripts
***********************************-->
<script src="assets/js/jquery.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script><!-- Swiper -->
<script src="assets/js/dz.carousel.js"></script><!-- Swiper -->
<script src="assets/js/settings.js"></script>
<script src="assets/js/custom.js"></script>
</body>
</html>