<?php
    session_start();
    include("includes/setup_mysql.php");
    
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["status" => "error", "message" => "User not logged in"]);
        exit();
    }
    
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];
    
    // Check if user already liked the post
    $checkLike = $conn->prepare("SELECT * FROM blog_likes WHERE user_id = ? AND post_id = ?");
    $checkLike->bind_param("ii", $user_id, $post_id);
    $checkLike->execute();
    $likeResult = $checkLike->get_result();
    
    if ($likeResult->num_rows > 0) {
        // Unlike: Remove the like record
        $removeLike = $conn->prepare("DELETE FROM blog_likes WHERE user_id = ? AND post_id = ?");
        $removeLike->bind_param("ii", $user_id, $post_id);
        $removeLike->execute();
        $removeLike->close();
        $status = "unliked";
    } else {
        // Like: Add a new record
        $addLike = $conn->prepare("INSERT INTO blog_likes (user_id, post_id) VALUES (?, ?)");
        $addLike->bind_param("ii", $user_id, $post_id);
        $addLike->execute();
        $addLike->close();
        $status = "liked";
    }
    
    // Get the new like count
    $getLikes = $conn->prepare("SELECT COUNT(*) AS like_count FROM blog_likes WHERE post_id = ?");
    $getLikes->bind_param("i", $post_id);
    $getLikes->execute();
    $getLikesResult = $getLikes->get_result();
    $likeCount = $getLikesResult->fetch_assoc()['like_count'];
    $getLikes->close();
    
    echo json_encode(["sts" => $status, "likes" => $likeCount]);
?>
