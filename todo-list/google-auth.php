<?php
require_once 'vendor/autoload.php';

session_start();

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

function terminateSession(): void {
    $_SESSION['google_oauth2_access_token'] = null;
    $_SESSION['code_verifier'] = null;
    $_SESSION['username'] = null;
}

if ($is_unauthenticated) {
    $_SESSION['code_verifier'] = $client -> getOAuth2Service() -> generateCodeVerifier();
    $identityProviderUrl = $client -> createAuthUrl();
    header('Location: '.$identityProviderUrl);
}

if ($is_google_callback) {
    $accessToken = $client -> fetchAccessTokenWithAuthCode($_GET['code'], $_SESSION['code_verifier']);
    $client -> setAccessToken($accessToken);
    $_SESSION['google_oauth2_access_token'] = $accessToken;

    // redirects to itself so the user information is loaded
    header('Location: '.$_SERVER['PHP_SELF']);
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

    // TODO: Check if email in db, add if not present
    $_SESSION['username'] = $userData -> name;
    // TODO: Also store user id from db in session

    header('Location: index.php');
}

if ($is_sign_out_request) {
    terminateSession();
    header('Location: index.php');
}