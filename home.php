<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>
</head>

<?php
session_start();
require('database.php');

$_SESSION['actionResult'] = null; //erase last of user's action response
$_SESSION['status'] = false; //make sure user cannot access files from last login session
$_SESSION['token'] = bin2hex(random_bytes(32));
?>

<body>
<h2>Login</h2>
    <form action="login.php" method="POST">
        <label for="username">Username:</label>
        <input id ="username" type="text" name="username" required> <br> <br>
        <label for="password">Password:</label>
        <input id="password" type="text" name="password" required>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
        <br><br>
        <input type="submit" value="Log In">
    </form>
<br>

<h2>Continue Without Logging In</h2>
    <form action="notRegHomepage.php" method="POST">
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
        <input type="submit" value="Go">
        <br> <br>
    </form>


<h2>Create New User</h2>
    <form action="newUser.php" method="POST">
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
        <input type="submit" value="Create">
</form>


</body>
</html>