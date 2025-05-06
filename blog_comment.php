<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    
    include("includes/app_header.php");
    include("includes/setup_mysql.php");
    
    // Get post ID from URL
    $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
    
    // Fetch post details
    $post_query = "SELECT * FROM blog_posts WHERE id = ?";
    $stmt = $conn->prepare($post_query);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $post_result = $stmt->get_result();
    $post = $post_result->fetch_assoc();
    
    // Fetch comments for this post
    $comments_query = "SELECT c.id, c.comment, c.created_at, u.first_name, u.last_name, u.profile_img
                       FROM comments c
                       JOIN user u ON c.user_id = u.id
                       WHERE c.post_id = ?
                       ORDER BY c.created_at DESC";
    $stmt = $conn->prepare($comments_query);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $comments_result = $stmt->get_result();
?>

<!-- Page Content -->
<div class="page-content">
    <div class="container profile-area bottom-content">
        
       

        <!-- Comments List -->
        <ul class="dz-comments-list" id="comment-list">
            <?php if ($comments_result->num_rows > 0): ?>
            <?php while ($row = $comments_result->fetch_assoc()): ?>
                <li>
                    <div class="list-content">
                        <img src="images/avatar.jpg" alt="/">
                        <div>
                            <h6 class="font-14 mb-1"><?php echo $row['first_name'] . " " . $row['last_name']; ?></h6>
                            <p class="mb-2"><?php echo htmlspecialchars($row['comment']); ?></p>
                            <span class="comment-time"><?php echo date('M d, Y H:i', strtotime($row['created_at'])); ?></span>
                        </div>
                    </div>
                </li>
            <?php endwhile; ?>
            
            <?php else: ?>
                <p class="text-center">No comment available.</p>
            <?php endif; ?>
        
            
            
        </ul>
    </div>

    <!-- Comment Input Box -->
    <footer class="footer border-top">
        <div class="container py-2">
            <div class="commnet-footer">
                <div class="d-flex align-items-center flex-1">
                    <div class="media media-40 rounded-circle">
                        <img src="images/avatar.jpg" alt="/">
                    </div>
                    <form id="comment-form" class="flex-1">
                        <input type="hidden" id="post_id" value="<?php echo $post_id; ?>">
                        <input type="text" id="comment-text" class="form-control" placeholder="Add a comment..." required/>
                    </form>
                </div>
                <a href="javascript:void(0);" class="send-btn" id="submit-comment">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M21.4499 11.11L3.44989 2.11C3.27295 2.0187 3.07279 1.9823 2.87503 2.00546C2.67728 2.02862 2.49094 2.11029 2.33989 2.24C2.18946 2.37064 2.08149 2.54325 2.02982 2.73567C1.97815 2.9281 1.98514 3.13157 2.04989 3.32L4.99989 12L2.09989 20.68C2.05015 20.8267 2.03517 20.983 2.05613 21.1364C2.0771 21.2899 2.13344 21.4364 2.2207 21.5644C2.30797 21.6924 2.42378 21.7984 2.559 21.874C2.69422 21.9496 2.84515 21.9927 2.99989 22C3.15643 21.9991 3.31057 21.9614 3.44989 21.89L21.4499 12.89C21.6137 12.8061 21.7512 12.6786 21.8471 12.5216C21.9431 12.3645 21.9939 12.184 21.9939 12C21.9939 11.8159 21.9431 11.6355 21.8471 11.4784C21.7512 11.3214 21.6137 11.1939 21.4499 11.11Z" fill="#40189D"></path>
                    </svg>
                </a>
            </div>
        </div>
    </footer>
</div>

<?php include("includes/menu_bar.php"); ?>
<?php include("includes/app_footer.php"); ?>

<!-- AJAX Script for Submitting Comments -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $("#submit-comment").click(function(){
        var commentText = $("#comment-text").val();
        var postID = $("#post_id").val();

        if(commentText.trim() === '') return;

        $.ajax({
            url: "submit_comment.php",
            type: "POST",
            data: { post_id: postID, comment: commentText },
            success: function(response){
                
                const res = JSON.parse(response);
                
                if(res.status == "success") {
                    $("#comment-list").prepend(`
                        <li>
                            <div class="list-content">
                                <img src="images/avatar.jpg" alt="/">
                                <div>
                                    <h6 class="font-14 mb-1">You</h6>
                                    <p class="mb-2">${commentText}</p>
                                    <span class="comment-time">Just now</span>
                                </div>
                            </div>
                        </li>
                    `);
                    $("#comment-text").val("");
                } else {
                    alert("Failed to submit comment. Please try again.");
                }
            }
        });
    });
});
</script>
