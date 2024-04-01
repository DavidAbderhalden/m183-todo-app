<?php

use JetBrains\PhpStorm\NoReturn;

session_start();

#[NoReturn] function terminateSession(): void {
    session_destroy();
    header('location:/index.php');
    exit();
}