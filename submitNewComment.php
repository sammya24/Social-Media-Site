<!DOCTYPE html>
<html lang="en">
<head>
    <title>New Comment</title>
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

    $safe_comment = $mysqli->real_escape_string((string)$_POST['comment']);
    $comment = $safe_comment;
    $post_id = (int)$_POST['post_id'];
    $user = $_SESSION['validUsername'];

    //insert comment to database
    $stmt = $mysqli->prepare("INSERT INTO comments (story_id, content, user) VALUES (?, ?, ?)");
    $stmt->bind_param('iss', $post_id, $comment, $user);
    $stmt->execute();
    $stmt->close();

    //go home
    header("Location: homepage.php");
    exit();
}

?>
</html>