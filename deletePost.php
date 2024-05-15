<!DOCTYPE html>
<html lang="en">
<head>
    <title>Delete Post</title>
</head>

<?php
session_start();
require('database.php');

//make sure user logged in
if (!isset($_SESSION['validUsername'])) {
    header('Location: home.php');
    exit();
}

//make sure post request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //check token
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }
    
    //handle input
    $story_id = filter_input(INPUT_POST, 'info', FILTER_VALIDATE_INT);

    if ($story_id !== false) {
        $author_username = $_SESSION['validUsername'];

        //delete comments
        $stmt = $mysqli->prepare("DELETE FROM comments WHERE story_id = ?");
        $stmt->bind_param('i', $story_id);
        $stmt->execute();
        $stmt->close();

        //and likes
        $stmt = $mysqli->prepare("DELETE FROM likes WHERE story_id = ?");
        $stmt->bind_param('i', $story_id);
        $stmt->execute();
        $stmt->close();

        //and dislikes
        $stmt = $mysqli->prepare("DELETE FROM dislikes WHERE story_id = ?");
        $stmt->bind_param('i', $story_id);
        $stmt->execute();
        $stmt->close();

        //and the post
        $stmt = $mysqli->prepare("DELETE FROM stories WHERE id = ? AND user = ?");
        $stmt->bind_param('is', $story_id, $author_username);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['actionResult'] = 'Story deleted successfully!';
        } else {
            $_SESSION['actionResult'] = 'Error: Unable to delete story.';
        }

        $stmt->close();
    }
    
    //go home
    header("Location: homepage.php");
    exit();
}
?>

</html>
