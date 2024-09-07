<?php
session_start();
include "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Connect to the database
    $DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);
    if (!$DBC) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prevent SQL injection
    $username = mysqli_real_escape_string($DBC, $username);
    $password = mysqli_real_escape_string($DBC, $password);

    // Check credentials
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($DBC, $query);

    if (mysqli_num_rows($result) == 1) {
        // User found, start session
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit;
    } else {
        echo "Invalid username or password.";
    }

    mysqli_close($DBC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="POST" action="login.php">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>
