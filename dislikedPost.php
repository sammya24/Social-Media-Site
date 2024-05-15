<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dislike Post</title>
</head>

<?php
session_start();
require('database.php');

//make sure user logged in
if (!isset($_SESSION['validUsername'])) {
    header('Location: home.php');
    exit();
}

//see if post request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //check token
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }
    
    //check input
    $postId = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);

    if ($postId !== false) {
        $user = $_SESSION['validUsername'];

        //see if user already disliked
        $stmtCheckDislike = $mysqli->prepare("SELECT * FROM dislikes WHERE unsupportive_friend_username = ? AND story_id = ?");
        $stmtCheckDislike->bind_param("si", $user, $postId);
        $stmtCheckDislike->execute();
        $resultCheckDislike = $stmtCheckDislike->get_result();

        if ($resultCheckDislike->num_rows === 0) {
            //insert dislike
            $stmtInsertDislike = $mysqli->prepare("INSERT INTO dislikes (unsupportive_friend_username, story_id) VALUES (?, ?)");
            $stmtInsertDislike->bind_param("si", $user, $postId);
            $stmtInsertDislike->execute();
            $stmtInsertDislike->close();

            //go home
            header("Location: homepage.php");
            exit();
        } else {
            //user disliked already
            $_SESSION['actionResult'] = "You have already disliked this post.";
            header("Location: homepage.php");
            exit();
        }
    }
}
?>

</html>