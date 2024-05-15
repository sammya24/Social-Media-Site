<!DOCTYPE html>
<html lang="en">
<head>
    <title>Database</title>
</head>

<?php
// Content of database.php

$mysqli = new mysqli('localhost', 'test1', 'Karatechop2!', 'news_site');

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>

</html>