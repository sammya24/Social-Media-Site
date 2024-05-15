<!DOCTYPE html>
<html lang="en">
<head>
    <title>Delete Comment</title>
</head>


<?php
session_start();
require('database.php');

//check user
if (!isset($_SESSION['validUsername'])) {
    header('Location: home.php');
    exit();
}

//make sure post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //check toke
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

    //check input
    $comment_id = filter_input(INPUT_POST, 'comment_id', FILTER_VALIDATE_INT);

    if ($comment_id !== false) {
        $user = $_SESSION['validUsername'];

        //get comment and author
        $stmtRetrieve = $mysqli->prepare("SELECT content, user FROM comments WHERE id = ?");
        $stmtRetrieve->bind_param('i', $comment_id);
        $stmtRetrieve->execute();
        $stmtRetrieve->store_result();

        if ($stmtRetrieve->num_rows === 1) {
            $stmtRetrieve->bind_result($commentContent, $commentUser);
            $stmtRetrieve->fetch();

            //make sure user wrote the comment
            if ($commentUser == $user) {
                //edit comment form
                echo "<form action='updateComment.php' method='POST'>";
                echo "<textarea name='edited_comment' required>$commentContent</textarea>";
                echo "<input type='hidden' name='comment_id' value='$comment_id'>";
                echo "<input type='hidden' name='token' value='" . $_SESSION['token'] . "'>";
                echo "<input type='submit' value='Save Changes'>";
                echo "</form>";
            }
        }
    }
}
?>

</html>