<?php
require_once 'config.php';
require_once 'vendor/autoload.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET"
    && isset($_GET['username'])
    && isset($_GET['password'])
    && isset($_GET['$sign_in_option'])
) {
    // Get username and password from the form
    $username = $_GET['username'];
    $password = $_GET['password'];
    $sign_in_option = $_GET['$sign_in_option'];
    
    // Connect to the database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Prepare SQL statement to retrieve user from database
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username='$username'");

    // Execute the statement
    $stmt->execute();
    // Store the result
    $stmt->store_result();
    // Check if username exists
    if ($stmt->num_rows > 0) {
        // Bind the result variables
        $stmt->bind_result($db_id, $db_username, $db_password);
        // Fetch the result
        $stmt->fetch();
        // Verify the password
        if ($password == $db_password) {
            // Password is correct, store username in session
            setcookie("username", $username, -1, "/"); // 86400 = 1 day
            setcookie("userid", $db_id, -1, "/"); // 86400 = 1 day
            // Redirect to index.php
            header("Location: index.php");
            exit();
        } else {
            // Password is incorrect
            echo "Incorrect password";
        }
    } else {
        // Username does not exist
        echo "Username does not exist";
    }

    // TODO: Remove test
    echo $sign_in_option;

    // Close statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form action="<?php $_SERVER["PHP_SELF"]; ?>" method="get">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="hidden" id="sign_in_option_hidden_input" name="$sign_in_option" value="">

        <button type="submit">Submit</button>
        <button type="button" id="sign_in_option_toggle_button" onclick="toggleSignIn()"></button>
    </form>
</body>
</html>

<script>
    // variables
    let isSignInGoogle = false;

    // elements
    const signInOptionToggleButtonElement = document.querySelector("#sign_in_option_toggle_button");
    const signInOptionHiddenInputElement = document.querySelector("#sign_in_option_hidden_input");

    // functions
    function toggleSignIn() {
        isSignInGoogle = !isSignInGoogle;
        renderElements();
    }

    function renderElements() {
        if (isSignInGoogle) {
            signInOptionToggleButtonElement.innerText = "Sign Up With Google";
            signInOptionHiddenInputElement.value = "google"
        }
        else {
            signInOptionToggleButtonElement.innerText = "Sign Up With Email";
            signInOptionHiddenInputElement.value = "email"
        }
    }

    // setup
    renderElements();
</script>