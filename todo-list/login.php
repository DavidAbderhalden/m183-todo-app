<?php
require_once 'config.php';
require_once 'vendor/autoload.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET"
    && isset($_GET['username'])
    && isset($_GET['password'])
) {
    // Get username and password from the form
    $username = $_GET['username'];
    $password = $_GET['password'];
    
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

    // Close statement
    $stmt->close();
}


/*
$redirectUrl = 'https://redirectmeto.com/http://localhost/login.php';

$client = new Client();
$client->setAuthConfig('google.json');

$client->setRedirectUri($redirectUrl);
$client->addScope([
    'https://www.googleapis.com/auth/userinfo.profile',
    'https://www.googleapis.com/auth/userinfo.email'
]);

# === SCENARIO 1: PREPARE FOR AUTHORIZATION ===
if(!isset($_GET['code']) && empty($_SESSION['google_oauth_token'])) {
    $_SESSION['code_verifier'] = $client->getOAuth2Service()->generateCodeVerifier();

    # Get the URL to Google’s OAuth server to initiate the authentication and authorization process
    $authUrl = $client->createAuthUrl();

    $connected = false;
}


# === SCENARIO 2: COMPLETE AUTHORIZATION ===
# If we have an authorization code, handle callback from Google to get and store access token
if (isset($_GET['code'])) {
    # Exchange the authorization code for an access token
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code'], $_SESSION['code_verifier']);
    $client->setAccessToken($token);
    $_SESSION['google_oauth_token'] = $token;
    header('Location: ' . $redirectUrl);
}


# === SCENARIO 3: ALREADY AUTHORIZED ===
# If we’ve previously been authorized, we’ll have an access token in the session
if (!empty($_SESSION['google_oauth_token'])) {
    $client->setAccessToken($_SESSION['google_oauth_token']);
    if ($client->isAccessTokenExpired()) {
        $_SESSION['google_oauth_token'] = null;
        $connected = false;
    }
    $connected = true;

    // TODO: Refactor (example of user data retrieval)
    $oAuth = new Google_Service_Oauth2($client);
    try {
        $userData = $oAuth->userinfo_v2_me->get();
    } catch (\Google\Service\Exception $e) {
    }

    print($userData->email);
}

# === SCENARIO 4: TERMINATE AUTHORIZATION ===
if(isset($_GET['disconnect'])) {
    $_SESSION['google_oauth_token'] = null;
    $_SESSION['code_verifier'] = null;
    header('Location: ' . $redirectUrl);
}
*/
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
        <button type="submit">Submit</button>
    </form>
    <a type="button" href="google-auth.php">Sign In With Google</a>
</body>
</html>
