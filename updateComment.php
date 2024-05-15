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
    $comment_id = (int)$_POST['comment_id'];
    $safe_edited_comment = $mysqli->real_escape_string((string)$_POST['edited_comment']);
    $edited_comment = $safe_edited_comment;
    $user = $_SESSION['validUsername'];

 
    $stmtCheck = $mysqli->prepare("SELECT user FROM comments WHERE id = ?");
    $stmtCheck->bind_param('i', $comment_id);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows === 1) {
        $stmtCheck->bind_result($commentUser);
        $stmtCheck->fetch();

        
        if ($commentUser == $user) {
            //update comment
            $stmtUpdate = $mysqli->prepare("UPDATE comments SET content = ? WHERE id = ?");
            $stmtUpdate->bind_param('si', $edited_comment, $comment_id);
            $stmtUpdate->execute();
            $stmtUpdate->close();
        }
    }

    header("Location: homepage.php"); //go home
    exit();
}
?>

</html>