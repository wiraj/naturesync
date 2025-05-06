<?php

    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    
    include("includes/app_header.php");
    
    include("includes/setup_mysql.php");

    //Retrive all blog details
    $query = "SELECT bp.id, bp.user_id, bp.photo, bp.description, bp.mood, bp.created_at, 
         u.first_name, u.last_name, u.profile_img,
         (SELECT COUNT(*) FROM blog_likes WHERE post_id = bp.id) AS likes_count,
         (SELECT COUNT(*) FROM blog_likes WHERE post_id = bp.id AND user_id = {$_SESSION['user_id']}) AS user_liked,
         (SELECT COUNT(*) FROM comments WHERE post_id = bp.id) AS comments_count
      FROM blog_posts bp 
      JOIN user u ON bp.user_id = u.id 
      ORDER BY bp.created_at DESC";        
         
    $result = $conn->query($query);

    // Define mood-based emojis
    $moodEmojis = [
        "Sad" => "😢",
        "Neutral" => "😐",
        "Happy" => "😊",
        "Excited" => "🤩"
    ];


?>

<!-- Page Content -->
<div class="page-content">
    <div class="content-inner pt-0">
        <div class="container p-b50">

            <div class="post-area">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="post-card">
                            <div class="top-meta">
                                <div class="d-flex justify-content-between align-items-start">
                                    <a href="user-profile.php?user_id=<?php echo $row['user_id']; ?>" class="media media-40">
                                        <img class="rounded" src="<?php echo !empty($row['profile_img']) ? 'uploads/' . $row['profile_img'] : 'images/avatar.jpg'; ?>" alt="/">
                                    </a>
                                    <div class="meta-content ms-3">
                                        <h6 class="title mb-0">
                                            <a href="user-profile.php?user_id=<?php echo $row['user_id']; ?>">
                                                <?php echo $row['first_name'] . " " . $row['last_name']; ?>
                                            </a>
                                        </h6>
                                        <ul class="meta-list">
                                            <li>📅 <?php echo date('M d, Y', strtotime($row['created_at'])); ?></li>
                                            <li>🕒 <?php echo date('H:i', strtotime($row['created_at'])); ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <p class="text-black">
                                <?php echo htmlspecialchars($row['description']); ?>
                                <?php if (!empty($row['mood'])): ?>
                                    <span> <?php echo $moodEmojis[$row['mood']] . " " . htmlspecialchars($row['mood']); ?></span>
                                <?php endif; ?>
                            </p>

                            <div class="dz-media">
                                <img src="images/<?php echo htmlspecialchars($row['photo']); ?>" alt="/" style="width:100%; border-radius:10px;">
                                <div class="post-meta-btn">
                                    <ul>
                                        <li>
                                            <a href="javascript:void(0);" class="action-btn bg-primary like-btn" data-post-id="<?php echo $row['id']; ?>">
                                                <i class="fa<?php echo ($row['user_liked'] > 0) ? '-solid' : '-regular'; ?> fa-heart like-icon"></i>
                                                &nbsp;&nbsp;<span class="likes-count"><?php echo $row['likes_count'].' Like'; ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="blog_comment.php?post_id=<?php echo $row['id']; ?>" class="action-btn bg-secondary">
                                                <i class="fa-solid fa-comment fill-icon"></i>
                                                &nbsp;&nbsp; <?php echo $row['comments_count'].' Comment'; ?>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center">No blog posts available. Be the first to share!</p>
                <?php endif; ?>
            </div>

        </div>    
    </div>
</div>    
<!-- Page Content End -->

<?php include("includes/menu_bar.php"); ?>
<?php include("includes/app_footer.php"); ?>

<script>
$(document).ready(function(){
    $(".like-btn").click(function(){
        var post_id = $(this).data("post-id");
        var likeBtn = $(this);
        var likeCount = likeBtn.find(".likes-count");

        $.ajax({
            url: "like_post.php",
            type: "POST",
            data: { post_id: post_id },
            success: function(response){
                
                const res = JSON.parse(response);
          
                 
                if(res.sts == "liked") {
                   
                    likeBtn.find("i").removeClass("fa-regular").addClass("fa-solid");
                    
                    
                } else {
                    likeBtn.find("i").removeClass("fa-solid").addClass("fa-regular");
                }
                likeCount.text(res.likes + " Like");
            }
        });
    });
});
</script>
