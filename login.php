<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
</head>

<?php
session_start();
require('database.php');

if(!hash_equals($_SESSION['token'], $_POST['token'])){
    die("Request forgery detected");
}
    
    $safe_username = $mysqli->real_escape_string($_POST['username']);
    $safe_password = $mysqli->real_escape_string($_POST['password']);
    
    $username = $safe_username;
    $password = $safe_password;

    $stmt = $mysqli->prepare("SELECT username, pass_h FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($username, $pass_hash);
    $stmt->fetch();
    $stmt->close();

    if (isset($username)){
        if (password_verify($password, $pass_hash)) {
            $_SESSION['validUsername'] = $username;
            $_SESSION['status'] = true;
            header("Location:homepage.php");
        }
    }
    else {
        print("Please check your username and password");
    }

    ?>

</html>




    
