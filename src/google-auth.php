<?php
require_once 'vendor/autoload.php';

include 'session/session.php';
include 'fw/headers.php';
include 'fw/db.php';

use Google\Client;
use JetBrains\PhpStorm\NoReturn;

const OAUTH_CONFIG_FILE = 'google.json';

$redirectUrl = 'https://redirectmeto.com/http://' . $_SERVER['HTTP_HOST'] . '/google-auth.php';
$identityProviderUrl = null;

$client = new Client();
// FIXME: Add try catch?
$client -> setAuthConfig(OAUTH_CONFIG_FILE);
$client -> setRedirectUri($redirectUrl);
$client -> addScope([
    'https://www.googleapis.com/auth/userinfo.profile',
    'https://www.googleapis.com/auth/userinfo.email'
]);

$is_unauthenticated = empty($_SESSION['username']) && empty($_SESSION['userid']);
$is_google_callback = isset($_GET['code']);

#[NoReturn] function redirect_to_identity_provider($client): void {
    $_SESSION['code_verifier'] = $client -> getOAuth2Service() -> generateCodeVerifier();
    $identityProviderUrl = $client -> createAuthUrl();
    header('location:' . $identityProviderUrl);
    exit();
}

function handle_google_callback($client): void {
    $code = $_GET['code'];
    $codeVerifier = $_SESSION['code_verifier'];
    $accessToken = $client -> fetchAccessTokenWithAuthCode($code, $codeVerifier);
    $client -> setAccessToken($accessToken);
    $_SESSION['google_oauth2_access_token'] = $accessToken;
}

#[NoReturn] function authenticate_client($client): void {
    $accessToken = $_SESSION['google_oauth2_access_token'];
    $client -> setAccessToken($accessToken);
    if ($client -> isAccessTokenExpired()) {
        terminateSession();
    }
    // load user profile information
    $oAuth2 = new Google_Service_Oauth2($client);
    // FIXME: Add try catch
    $userData = $oAuth2 -> userinfo_v2_me -> get();
    $username = $userData -> name;
    $userEmail = $userData -> email;
    $dbUserId = null;

    // TODO: Email? Double usernames?
    list($selectUsernameStatement, $_) = executeStatement("SELECT id FROM users WHERE username='$username'");
    $selectUsernameStatement -> bind_result($dbUserId);
    $selectUsernameStatement -> fetch();
    if ($selectUsernameStatement -> num_rows <= 0) {
        list($_, $connection) = executeStatement("INSERT INTO users (username) VALUES ('$username')");
        $dbUserId = $connection -> insert_id;
    }
    $_SESSION['userid'] = $dbUserId;
    $_SESSION['username'] = $userData -> name;

    header('location:index.php');
    exit();
}

if ($is_unauthenticated && !$is_google_callback) {
    redirect_to_identity_provider($client);
}

if ($is_google_callback) {
    handle_google_callback($client);
    authenticate_client($client);
}