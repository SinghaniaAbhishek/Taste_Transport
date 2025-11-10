<?php
include("connection/connect.php");
session_start();

if(empty($_SESSION["user_id"])) {
    header('location:login.php');
    exit();
}

if(isset($_POST['submit_review'])) {
    $user_id = $_SESSION["user_id"];
    $order_id = $_POST['order_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    
    // Validate rating
    if($rating < 1 || $rating > 5) {
        echo "<script>alert('Invalid rating!'); window.location.href='your_orders.php';</script>";
        exit();
    }
    
    // Insert review into database
    $sql = "INSERT INTO reviews (user_id, order_id, rating, comment) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "iiis", $user_id, $order_id, $rating, $comment);
    
    if(mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Thank you for your review!'); window.location.href='your_orders.php';</script>";
    } else {
        echo "<script>alert('Error submitting review. Please try again.'); window.location.href='your_orders.php';</script>";
    }
    
    mysqli_stmt_close($stmt);
} else {
    header('location:your_orders.php');
}
?> 