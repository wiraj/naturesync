<?php
    session_start();
    include("includes/setup_mysql.php");
    
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["status" => "error", "message" => "User not logged in"]);
        exit();
    }
    
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];
    $comment = trim($_POST['comment']);
    
    if ($comment == "") {
        echo json_encode(["status" => "error", "message" => "Comment cannot be empty"]);
        exit();
    }
    
    // Insert comment into the database
    $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $post_id, $user_id, $comment);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error"]);
    }
    $stmt->close();
?>
