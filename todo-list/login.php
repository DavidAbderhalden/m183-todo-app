<?php
require_once 'vendor/autoload.php';

include 'fw/db.php';
include 'session/session.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET"
    && isset($_GET['username'])
    && isset($_GET['password'])
) {
    // Get username and password from the form
    $username = $_GET['username'];
    $password = $_GET['password'];

    // Prepare SQL statement to retrieve user from database
    $stmt = executeStatement("SELECT id, username, password FROM users WHERE username='$username'");

    // Check if username exists
    if ($stmt->num_rows > 0) {
        // Bind the result variables
        $stmt->bind_result($db_id, $db_username, $db_password);
        // Fetch the result
        $stmt->fetch();
        if ($password == null) {
            echo "Username or password is invalid";
        }
        // Verify the password
        elseif ($password == $db_password) {
            // Password is correct, store username in session
            // FIXME: When does the session expire?
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
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="fw/style.css" />
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form action="<?php $_SERVER["PHP_SELF"]; ?>" method="get">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Submit</button>
    </form>
    <a type="button" href="google-auth.php">Sign In With Google</a>
</body>
</html>
