<?php
    session_start();

    function terminateSession(): void {
        session_start();
        session_destroy();
        header('location:index.php');
    }