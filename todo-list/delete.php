<?php
    // Check if the user is logged in
    if (!isset($_COOKIE['userid'])) {
        header("Location: /");
        exit();
    }
    $id = $_GET["id"];
    include 'fw/db.php';
    $stmt = executeStatement("select userID from tasks where ID = $id");
    $stmt->bind_result($db_userid);
            $stmt->fetch();
            $userid = $db_userid;

  require_once 'fw/header.php';
if ($userid == $_COOKIE['userid']) {
  $stmt = executeStatement("delete from tasks where ID = $id");
  echo "<span class='info info-success'>Task successfully removed</span>";
} else {
    echo "<span class='info info-error'>Couldn't delete Task</span>";
}
  require_once 'fw/footer.php';

?>
