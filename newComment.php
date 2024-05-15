<!DOCTYPE html>
<html lang="en">
<head>
    <title>Homepage</title>
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
    
    $post_id = (int)$_POST['post_id'];
    //display form to add comment
    echo "<h2>Add Comment</h2>";
    echo "<form action='submitNewComment.php' method='POST'>";
    echo "<label for='comment'>Comment:</label>";
    echo "<input id='comment' type='text' name='comment' required>";
    echo "<input type='hidden' name='post_id' value='$post_id'>";
    echo "<br><br>";
    echo "<input type='hidden' name='token' value='" . $_SESSION['token'] . "'>";
    echo "<input type='submit' value='Submit Comment'>";
    echo "</form>";
}


?>

</html>
