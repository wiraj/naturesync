
<?Php

    session_start();
    
    include("setup_mysql.php");
    
    // Get user IP Address
    function getUserIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
    
    // Get current page name
    $pageName = basename($_SERVER['PHP_SELF']);
    
    // Get username from session if logged in
    $username = isset($_SESSION['user_id']) ? $_SESSION['first_name'] : null;
    
    // Record log in database
    $ipAddress = getUserIP();
    $stmt = $conn->prepare("INSERT INTO user_log (ip_address, page_name, username) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $ipAddress, $pageName, $username);
    $stmt->execute();
    $stmt->close();



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
	
	<!-- PWA Version -->
	<link rel="manifest" href="manifest.json">
    
    <!-- Stylesheets -->
	<link rel="stylesheet" href="assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css">
	<link rel="stylesheet" href="assets/vendor/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css?v=1.5">

    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200;300;400;600;700;800;900&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>   
<body class="bg-gradient-2">
<div class="page-wraper header-fixed">
    
	<!-- Preloader -->
	<div id="preloader">
		<div class="spinner"></div>
	</div>
    <!-- Preloader end-->
    
	
    <!-- Header -->
	<header class="header">
		<div class="container">
			<div class="main-bar">
			     <a href="index.php">
                    <img src="assets/images/logo.png" alt="NatureSync Logo" style="height: 40px; width: auto; margin-right: 10px;">
                </a>
				<div class="left-content">
					<a href="index.php"><h4 class="title mb-0">NatureSync</h4></a>
				</div>
				<div class="mid-content">
				</div>
				<div class="right-content d-flex align-items-center">
				
				</div>
			</div>
		</div>
	</header>
	<div class="dark-overlay"></div>
    <!-- Header End -->
    
