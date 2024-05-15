<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Post</title>
</head>

<?php
session_start();
require('database.php');

//check user
if (!isset($_SESSION['validUsername'])) {
    header('Location: home.php');
    exit();
}

//check token
if (!hash_equals($_SESSION['token'], $_POST['token'])) {
    die("Request forgery detected");
}

//check input
$story_id = filter_input(INPUT_POST, 'info', FILTER_VALIDATE_INT);

if ($story_id !== false) {
    $stmt = $mysqli->prepare("SELECT title, body, link FROM stories WHERE id = ?");
    $stmt->bind_param('i', $story_id);
    $stmt->execute();
    $stmt->bind_result($title, $body, $link);
    $stmt->fetch();
    $stmt->close();
} else {
    // invalid input for story_id
    header("Location: homepage.php");
    exit();
}

?>


    <h1>Edit Post</h1>
    <form action="updatePost.php" method="POST">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>"><br>

        <label for="body">Body:</label>
        <textarea id="body" name="body"><?php echo htmlspecialchars($body); ?></textarea><br>

        <label for="link">Link:</label>
        <input type="text" id="link" name="link" value="<?php echo htmlspecialchars($link); ?>"><br>
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token']); ?>" />
        <input type="hidden" name="postId" value="<?php echo htmlspecialchars($story_id); ?>">

        <input type="submit" value="Update Post">
       
    </form>

</html>
