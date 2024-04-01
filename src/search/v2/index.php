<?php

if (!isset($_GET["userid"]) || !isset($_GET["terms"])) {
    die("Not enough information to search");
}

$userid = $_GET["userid"];
$terms = $_GET["terms"];

include '../../fw/db.php';
list($stmt, $_) = executeStatement("select ID, title, state from tasks where userID = $userid and title like '%$terms%'");
$db_id = null;
$db_title = null;
$db_state = null;
if ($stmt -> num_rows > 0) {
    $stmt -> bind_result($db_id, $db_title, $db_state);
    while ($stmt -> fetch()) {
        echo $db_title . ' (' . $db_state . ')<br />';
    }
}
?>