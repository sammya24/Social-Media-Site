<!DOCTYPE html>
<html lang="en">
<head>
    <title>New User</title>
</head>

<?php
session_start();
require 'database.php';

//see if user logged in
if (!isset($_SESSION['validUsername'])) {
    header('Location: home.php');
    exit();
}

//see if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //check token
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }

    //validate input
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST['password']; //will be hashed- don't need to sanitize

    //see if username already exists
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        //if username already exists
        $error_message = "Username already exists";
        $_SESSION['actionResult'] = $error_message;
        header("Location: newUser.php");
        exit();
    }

    //hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    //insert new user into table
    $stmt = $mysqli->prepare("INSERT INTO users (username, pass_h) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();
    $stmt->close();

    $success_message = "New user successfully registered!";
    $_SESSION['actionResult'] = $success_message;

    //go home
    header("Location: home.php");
    exit();
}

?>

</html>