<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update Post</title>
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
    $safe_title = $mysqli->real_escape_string($_POST['title']);
    $safe_body = $mysqli->real_escape_string($_POST['body']);
    $safe_link = $mysqli->real_escape_string($_POST['link']);
    $postId = $_POST['postId'];
    $title = $safe_title;
    $body = $safe_body;
    $link = $safe_link;

    $stmt = $mysqli->prepare("UPDATE stories SET title = ?, body = ?, link = ? WHERE id = ?");
    $stmt->bind_param("sssi", $title, $body, $link, $postId);

    if ($stmt->execute()) {
        //update successful
        header('Location: homepage.php'); //go home
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

</html>