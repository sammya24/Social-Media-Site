<!DOCTYPE html>
<html lang="en">
<head>
    <title>Homepage</title>
</head>

<?php
session_start();
require('database.php');

?>

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


    }

    echo "<hr>"; //divider line
}
?>
</html>