<?php
require_once 'session.php';
include __DIR__.'/../fw/headers.php';

if ($_SERVER["REQUEST_METHOD"] != "POST" && (empty($_SESSION['username']) || empty($_SESSION['userid']))) {
    header('location:/index.php');
    exit();
}

terminateSession();