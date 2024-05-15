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

print($_SESSION['actionResult']);

if ($_SESSION['status'] == false) {
    header('Location: login.php');
}
?>
<h2>New Post</h2>
<form action="createPost.php" method="POST">
   
    <label for="title">Title:</label>
    <input id="title" type="text" name="title" required> <br> <br>
    <label for="body">Content:</label>
    <input id="body" type="text" name="body" required>
    <label for="link">Link:</label>
    <input id="link" type="text" name="link" required>
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    <br><br>
    <input type="submit" value="Post">
</form>
<br>

<h2>Old Post(s)</h2>
<?php

//order posts by likes and dislikes
$stmt = $mysqli->prepare("SELECT stories.id, stories.title, stories.body, stories.link, stories.user, users.username,
                          COUNT(likes.id) AS like_count, COUNT(dislikes.id) AS dislike_count
                          FROM stories
                          JOIN users ON stories.user = users.username
                          LEFT JOIN likes ON stories.id = likes.story_id
                          LEFT JOIN dislikes ON stories.id = dislikes.story_id
                          GROUP BY stories.id, stories.title, stories.body, stories.link, stories.user, users.username
                          ORDER BY (COUNT(likes.id) - COUNT(dislikes.id)) DESC");

if (!$stmt) {
    die("Error in SQL query: " . $mysqli->error);
}

                          
$stmt->execute();
$result = $stmt->get_result();

//go through all posts
while ($row = $result->fetch_assoc()) {
    $storyId = $row['id'];
    $storyTitle = $row['title'];
    $storyContent = $row['body'];
    $storyLink = $row['link'];
    $postUser = $row['user'];
    $username = $row['username'];
    $likeCount = $row['like_count'];
    $dislikeCount = $row['dislike_count'];

    //get likes
    $stmtLikes = $mysqli->prepare("SELECT COUNT(*) AS like_count FROM likes WHERE story_id = ?");
    $stmtLikes->bind_param("i", $storyId);
    $stmtLikes->execute();
    $resultLikes = $stmtLikes->get_result();

    $likeRow = $resultLikes->fetch_assoc();
    $likeCount = $likeRow['like_count'];

    //display info with labels
    echo "<h2>Post Details</h2>";
    echo "<p><strong>Title:</strong> $storyTitle</p>";
    echo "<p><strong>Body:</strong> $storyContent</p>";

    $externalLink = $storyLink;
    if (!preg_match("~^(?:f|ht)tps?://~i", $externalLink)) {
        $externalLink = "http://" . $externalLink;
    }

    echo "<p><strong>Link:</strong> <a href='$externalLink' target='_blank'>$externalLink</a></p>";
    echo "<p><strong>Like Count:</strong> $likeCount</p>";
    echo "<p><strong>Dislike Count:</strong> $dislikeCount</p>";
    echo "<p><strong>Posted By:</strong> $username</p>";
    $passval = (int) $storyId;

    //like button
    echo "<form action='likedPost.php' method='POST' style='display: inline;'>";
    echo "<input type='hidden' name='post_id' value='$storyId'>";
    echo "<input type='hidden' name='user' value='{$_SESSION['validUsername']}'>";
    echo "<input type='hidden' name='token' value='" . $_SESSION['token'] . "'>";
    echo "<input type='submit' name='like' value='Like'>";
    echo "</form>";
    
    //dislike button
    echo "<form action='dislikedPost.php' method='POST' style='display: inline;'>";
    echo "<input type='hidden' name='post_id' value='$storyId'>";
    echo "<input type='hidden' name='user' value='{$_SESSION['validUsername']}'>";
    echo "<input type='hidden' name='token' value='" . $_SESSION['token'] . "'>";
    echo "<input type='submit' name='like' value='Dislike'>";
    echo "</form>";

    //add comment
    echo "<form action='newComment.php' method='POST' style='display: inline;'>";
    echo "<input type='hidden' name='post_id' value='$storyId'>";
    echo "<input type='hidden' name='token' value='" . $_SESSION['token'] . "'>";
    echo "<input type='submit' name='comment' value='Add Comment'>";
    echo "</form>";


    //make sure post made by user
    if ($_SESSION['validUsername'] == $postUser) {
        ?>
        <form action='deletePost.php' method='POST' style="display: inline;">
            <input type='submit' name='delete' value='Delete'>
            <input type='hidden' name='info' value='<?php echo $passval;?>'>
            <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
        </form>
        <form action='editPost.php' method='POST' style="display: inline;">
            <input type='submit' name='edit' value='Edit'>
            <input type='hidden' name='info' value='<?php echo $passval;?>'>
            <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
        </form>
        <?php
    }

    //get comments
    $stmtComments = $mysqli->prepare("SELECT id, content, user FROM comments WHERE story_id = ?");
    $stmtComments->bind_param("i", $storyId);
    $stmtComments->execute();
    $resultComments = $stmtComments->get_result();

    echo "<h3>Comments:</h3>";
    while ($commentRow = $resultComments->fetch_assoc()) {
        $commentId = $commentRow['id'];
        $commentContent = $commentRow['content'];
        $commentUser = $commentRow['user'];

        echo "<p><strong>Comment by $commentUser:</strong> $commentContent</p>";
    
    
if ($commentUser == $_SESSION['validUsername']) {
    echo "<form action='editComment.php' method='POST' style='display: inline;'>";
    echo "<input type='submit' name='edit' value='Edit Comment'>";
    echo "<input type='hidden' name='comment_id' value='$commentId'>";
    echo "<input type='hidden' name='token' value='" . $_SESSION['token'] . "'>";

    echo "</form>";
}

if ($commentUser == $_SESSION['validUsername']) {
    echo "<form action='deleteComment.php' method='POST' style='display: inline;'>";
    echo "<input type='submit' name='delete' value='Delete Comment'>";
    echo "<input type='hidden' name='comment_id' value='$commentId'>";
    echo "<input type='hidden' name='token' value='" . $_SESSION['token'] . "'>";

    echo "</form>";
}

    }

    echo "<hr>"; //divider line
}

?>
</html>