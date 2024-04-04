<?php
include 'session/session.php';
include 'fw/headers.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit();
}

$serialized_username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');

require_once 'fw/header.php';
?>
    <h2>Welcome, <?php echo $serialized_username ?>!</h2>


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