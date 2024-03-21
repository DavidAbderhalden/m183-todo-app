<?php
include 'session/session.php';

// Check if the user is logged in
// FIXME: Use session to store use information
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once 'fw/header.php';
?>
<h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>


<?php 
    if (isset($_SESSION['userid'])) {
        require_once 'user/tasklist.php';
        echo "<hr />";
        require_once 'user/backgroundsearch.php';
    }
?>


<?php
    require_once 'fw/footer.php';
?>