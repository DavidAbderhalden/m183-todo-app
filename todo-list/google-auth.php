<?php
require_once 'vendor/autoload.php';

include 'session/session.php';
include 'fw/db.php';

use Google\Client;

const OAUTH_CONFIG_FILE = 'google.json';

$redirectUrl = 'https://redirectmeto.com/http://'.$_SERVER['HTTP_HOST'].'/google-auth.php';
$identityProviderUrl = null;

$client = new Client();
// FIXME: Add try catch?
$client -> setAuthConfig(OAUTH_CONFIG_FILE);
$client -> setRedirectUri($redirectUrl);
$client -> addScope([
    'https://www.googleapis.com/auth/userinfo.profile',
    'https://www.googleapis.com/auth/userinfo.email'
]);

$is_unauthenticated = !isset($_GET['code']) && empty($_SESSION['google_oauth2_access_token']);
$is_google_callback = isset($_GET['code']);
$is_authenticated = !empty($_SESSION['google_oauth2_access_token']);
$is_sign_out_request = isset($_GET['sign-out']);

if ($is_unauthenticated) {
    $_SESSION['code_verifier'] = $client -> getOAuth2Service() -> generateCodeVerifier();
    $identityProviderUrl = $client -> createAuthUrl();
    header('location:'.$identityProviderUrl);
}

if ($is_google_callback) {
    $code = $_GET['code'];
    $codeVerifier = $_SESSION['code_verifier'];
    $accessToken = $client -> fetchAccessTokenWithAuthCode($code, $codeVerifier);
    $client -> setAccessToken($accessToken);
    $_SESSION['google_oauth2_access_token'] = $accessToken;

    // redirects to itself so the user information is loaded
    header('location:'.$_SERVER['PHP_SELF']);
}

if ($is_authenticated) {
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

    // TODO: Email? Double usernames?
    $selectUsernameStatement = executeStatement("SELECT id FROM users WHERE username='$username'");
    if ($selectUsernameStatement -> num_rows <= 0) {
        // FIXME: WTF todo with the password???
        $insertNewUserStatement = executeStatement("INSERT INTO users (username) VALUES ('$username')");
    }
    $selectUsernameStatement -> bind_result($dbUserId);
    $selectUsernameStatement -> fetch();
    $_SESSION['userid'] = $dbUserId;
    $_SESSION['username'] = $userData -> name;

    header('location:index.php');
}

if ($is_sign_out_request) {
    terminateSession();
}