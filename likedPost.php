<!DOCTYPE html>
<html lang="en">
<head>
    <title>Liked Post</title>
</head>

<?php
session_start();
require('database.php');

if (!isset($_SESSION['validUsername'])) {
    header('Location: home.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
    //get post data
    $postId = (int)$_POST['post_id'];
    $user = $_SESSION['validUsername'];

    $stmtCheckLike = $mysqli->prepare("SELECT * FROM likes WHERE supportive_friend_username = ? AND story_id = ?");
$stmtCheckLike->bind_param("si", $user, $postId);
$stmtCheckLike->execute();
$resultCheckLike = $stmtCheckLike->get_result();

if ($resultCheckLike->num_rows === 0) {
    //user hasn't liked post
    $stmtInsertLike = $mysqli->prepare("INSERT INTO likes (supportive_friend_username, story_id) VALUES (?, ?)");
    $stmtInsertLike->bind_param("si", $user, $postId);
    $stmtInsertLike->execute();
    $stmtInsertLike->close();

    //go home
    header("Location: homepage.php");
    exit();
} else {
    //user already liked
    $_SESSION['actionResult'] = "You have already liked this post.";
    header("Location: homepage.php");
    exit();
}
}

?>

</html>
