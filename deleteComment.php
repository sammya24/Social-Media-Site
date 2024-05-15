<!DOCTYPE html>
<html lang="en">
<head>
    <title>Delete Comment</title>
</head>

<?php
session_start();
require('database.php');

//make sure user logged in
if (!isset($_SESSION['validUsername'])) {
    header('Location: home.php');
    exit();
}

//check if post request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //handle inputs
    $comment_id = filter_input(INPUT_POST, 'comment_id', FILTER_VALIDATE_INT);
    $user = $_SESSION['validUsername'];

    //check token
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

    //make sure user made comment
    $stmtCheck = $mysqli->prepare("SELECT user FROM comments WHERE id = ?");
    $stmtCheck->bind_param('i', $comment_id);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows === 1) {
        $stmtCheck->bind_result($commentUser);
        $stmtCheck->fetch();

        if ($commentUser == $user) {
            //delete comment
            $stmtDelete = $mysqli->prepare("DELETE FROM comments WHERE id = ?");
            $stmtDelete->bind_param('i', $comment_id);
            $stmtDelete->execute();
            $stmtDelete->close();
        }
    }

    //go home
    header("Location: homepage.php");
    exit();
}
?>
</html>