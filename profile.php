<?php
    session_start();
    include("functions.php");
    include("includes/setup_mysql.php");
    
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    
    $user_id = $_SESSION['user_id']; 
    
    // Fetch user details
    $stmt = $conn->prepare("SELECT first_name, last_name, loyalty_balance FROM user WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    // Fetch user's blog posts
    $stmt = $conn->prepare("SELECT photo FROM blog_posts WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
?>

<?php include("includes/app_header.php"); ?>

<link href="assets/vendor/lightgallery/dist/css/lightgallery.css" rel="stylesheet">
<link href="assets/vendor/lightgallery/dist/css/lg-thumbnail.css" rel="stylesheet">
<link href="assets/vendor/lightgallery/dist/css/lg-zoom.css" rel="stylesheet">

<style>
    .social-bar ul li {
        width: 50%;
    }
</style>

<!-- Page Content -->
<div class="page-content">
    <div class="container profile-area">
        
        <?php if(isset($_GET['task_completed'])){ ?>
        <div class="alert alert-success solid alert-dismissible fade show">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>	
            <strong>🎉 Congratulations!
            You have successfully completed the task and earned 5 Loyalty Points!</strong> 
            <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <?php } ?>
                            
        <div class="profile">
            <div class="main-profile">
                <div class="left-content">
                    <h5 class="mt-1"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h5>
                    <a href="logout.php"><h6 class="text-primary font-w400">Logout</h6></a>
                </div>
                <div class="right-content">
                    <div class="upload-box">
                        <img src="images/avatar.jpg" alt="/">
                        <a href="edit-profile.php" class="upload-btn"><i class="fa-solid fa-pencil"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="contant-section">
            <div class="social-bar">
                <ul>
                    <li style="background-color:#06e606;">
                        <a href="javascript:void(0);">
                            <h4><?php echo count($posts); ?></h4>
                            <span>Post</span>
                        </a>
                    </li>
                    <li class='active'>
                        <a href="javascript:void(0);">
                            <h4><?php echo $user['loyalty_balance'];?></h4>
                            <span>Loyalty Points</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="title-bar my-2">
                <h6 class="mb-0">My Posts</h6>
            </div>

            <div class="tab-content">
                <div class="tab-pane fade show active">
                    <div class="dz-lightgallery style-2" id="lightgallery">
                        <?php if (count($posts) > 0): ?>
                            <?php foreach ($posts as $post): ?>
                               
                                <a class="gallery-box" href="images/<?php echo htmlspecialchars($post['photo']); ?>">
                                    <img src="images/<?php echo htmlspecialchars($post['photo']); ?>" alt="Post Image">
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-center">No posts available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>	
        </div>
    </div>
</div>
<!-- Page Content End-->

<?php include("includes/menu_bar.php"); ?>

<script src="assets/vendor/lightgallery/dist/lightgallery.umd.js"></script>
<script src="assets/vendor/lightgallery/dist/plugins/thumbnail/lg-thumbnail.umd.js"></script>
<script src="assets/vendor/lightgallery/dist/plugins/zoom/lg-zoom.umd.js"></script>

<?php include("includes/app_footer.php"); ?>
