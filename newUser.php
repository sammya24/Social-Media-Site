<!DOCTYPE html>
<html lang="en">
<head>
    <title>New User</title>
</head>

<?php


session_start();
print ($_SESSION['actionResult']);

if(!hash_equals($_SESSION['token'], $_POST['token'])){
    die("Request forgery detected");
}


?>


        <h1>Register</h1>
        <form action="createNewUser.php" method="post">

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />

            <input type="submit" value="Register">
            <br></br>
            
        </form>



</html>