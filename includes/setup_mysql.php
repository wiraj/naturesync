<?php
    $server = "localhost";
    $server_Port = '3306';
    $admin_login = "u717526864_naturesync";
    $admin_password = "Urihal@123";
    $dbname = "u717526864_naturesync";
    $conn = mysqli_connect($server, $admin_login, $admin_password, $dbname, $server_Port);
    
    // Check if the connection was successful
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

?>