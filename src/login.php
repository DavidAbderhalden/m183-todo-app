<?php
require_once 'vendor/autoload.php';

include 'session/session.php';
include 'fw/headers.php';
include 'fw/db.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST"
    && isset($_POST['username'])
    && isset($_POST['password'])
) {
    // Get username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // login attempt limit (not really secure at all, one can just delete the session and continue)
    $login_attempts = $_SESSION['login_attempts'] ?? 0;
    $_SESSION['login_attempts'] = $login_attempts + 1;

    // initialize bind variables
    $db_id = null;
    $db_username = null;
    $db_password = null;

    // Prepare SQL statement to retrieve user from database
    list($stmt, $_) = executeStatement("SELECT id, username, password FROM users WHERE username='$username'");

    // Check if username exists
    if ($stmt -> num_rows > 0 && $login_attempts < 6) {
        // Bind the result variables
        $stmt -> bind_result($db_id, $db_username, $db_password);
        // Fetch the result
        $stmt -> fetch();
        // Verify the password
        if (password_verify($password, $db_password)) {
            // Password is correct, store username in session
            $_SESSION['username'] = $username;
            $_SESSION['userid'] = $db_id;
            // Redirect to index.php
            header("Location: index.php");
            exit();
        } else {
            // Password is incorrect
            echo "Username or password is invalid";
        }
    } else {
        // Username does not exist
        echo "Username or password is invalid";
    }

    // Close statement
    $stmt -> close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="fw/style.css"/>
    <title>Login</title>
</head>
<body>
<h2>Login</h2>
<form action="<?php $_SERVER["PHP_SELF"]; ?>" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>
    <button type="submit">Submit</button>
</form>
<a type="button" href="google-auth.php">Sign In With Google</a>
</body>
</html>
