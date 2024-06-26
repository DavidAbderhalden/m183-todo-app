<?php
include '../../fw/db.php';

if (!isset($_GET["userid"]) || !isset($_GET["terms"])) {
    die("Not enough information to search");
}

$userid = htmlspecialchars($_GET["userid"], ENT_QUOTES, 'UTF-8');
$terms = htmlspecialchars($_GET["terms"], ENT_QUOTES, 'UTF-8');

$sql = "SELECT ID, title, state FROM tasks WHERE userID = ? AND title LIKE '%?%'";
list($stmt, $_) = executeStatement($sql, array($userid, $terms));
// values from db are already serialized (no xss danger)
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