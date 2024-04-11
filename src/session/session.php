<?php

use JetBrains\PhpStorm\NoReturn;

session_start();

/*
session_start([
    'cookie_lifetime' => 43200,
    'cookie_secure' => true,
    'cookie_httponly' => true
]);
*/

#[NoReturn] function terminateSession(): void {
    session_destroy();
    header('location:/index.php');
    exit();
}