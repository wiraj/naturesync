<?php
    session_start();
    
    // DB connection
    include_once 'includes/setup_mysql.php';
    
    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
       
        $username = $_POST['username'];
        $password = $_POST['password'];
    
        // Validate form data
        if (empty($username) || empty($password)) {
            $error = 'Username and password are required.';
        } else {
            
            $query = "SELECT * FROM user WHERE email = '$username'";
            $result = mysqli_query($conn, $query) or die("error 21" . $query);
    
            if ($result && mysqli_num_rows($result) > 0) {
    
               
                $user = mysqli_fetch_assoc($result);
    
               
                if (password_verify($password, $user['password'])) {
                    
                    
                    if($user['account_status'] == '0'){
                        $error = 'Your account is not activated!.';
                        header("Location: login.php?error=$error");
                        exit;
                    }
                    
                  
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_type'] = $user['user_type'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['last_name'] = $user['last_name'];
    
                    if(  $_SESSION['user_type'] == "user"){
                      
    
                        if (isset($_SESSION['route'])) {
                            header('Location: ' . $_SESSION['route']);
                            exit();
                        }
                        else{
                            // Redirect to the dashboard or home page
                            header('Location:index.php');
                            exit();
                        }
            
                    }
                   
                   
                } else {
                   
                    $error = 'Invalid password.';
                }
            } else {
                
                $error = 'Invalid username.';
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
    <link rel="stylesheet" type="text/css" href="assets/css/style.css?v=1.1">
    
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
				    <div style="text-align:center;"><img src="assets/images/logo.png" style="max-width:100px;"/></div><br/>
					<div class="started">
						<h1 class="title">Sign in</h1>
						
					</div>
					<?php if (isset($error)){?>
						<div class="alert alert-danger error-message" role="alert">
							<?php echo $error; ?>
						</div>
					<?php } ?>
					<?php
						
						if(isset($_GET['error'])){?>
						<div class="alert alert-danger error-message" role="alert">
							<?php echo $_GET['error']; ?>
						</div>
						        
					<?php }
					?>
					<form action="" method="POST">
						<div class="mb-3 input-group input-group-icon">
							
							<input type="text" name="username" class="form-control" placeholder="Uni Email" required>
						</div>
						<div class="mb-3 input-group input-group-icon">
							
							<input type="password" name="password" class="form-control" placeholder="Password" required>
							
						</div>
						<button type="submit" class="btn btn-primary btn-block mb-3">SIGN IN</button>
					</form>
				
					
					<div class="d-flex align-items-center justify-content-center">
						<a href="javascript:void(0);" class="text-light text-center d-block">Don’t have an account?</a>
						<a href="register.php" class="btn-link d-block ms-3 text-underline">Signup here</a>
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