<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create Post</title>
</head>

<?php
session_start();
require('database.php');

//see if user logged in
if (!isset($_SESSION['validUsername'])) {
    header('Location: home.php');
    exit();
}

//check if post request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //check token
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

    //handle user inputs
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $body = filter_input(INPUT_POST, 'body', FILTER_SANITIZE_STRING);
    $link = filter_input(INPUT_POST, 'link', FILTER_SANITIZE_STRING);

    //get user from session
    $user = $_SESSION['validUsername'];

    //insert new post
    $stmt = $mysqli->prepare("INSERT INTO stories (title, body, link, user) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $body, $link, $user);
    $stmt->execute();
    $stmt->close();

    //go home
    header("Location: homepage.php");
    exit();
}
?>
</html>