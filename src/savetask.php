<?php
include 'session/session.php';
include 'fw/db.php';
include 'fw/headers.php';

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    header("Location: /");
    exit();
}

$id = "";
// see if the id exists in the database
if (isset($_POST['id']) && strlen($_POST['id']) != 0) {
    $id = htmlspecialchars($_POST["id"], ENT_QUOTES, 'UTF-8');
    // FIXME: SQL INJECTION
    list($stmt, $_) = executeStatement("select ID, title, state from tasks where ID = $id");
    if ($stmt -> num_rows == 0) {
        $id = "";
    }
}

require_once 'fw/header.php';

if (isset($_POST['title']) && isset($_POST['state'])) {
    $state = htmlspecialchars($_POST["state"], ENT_QUOTES, 'UTF-8');
    $title = htmlspecialchars($_POST["title"], ENT_QUOTES, 'UTF-8');
    $userid = $_SESSION['userid'];

    if ($id == "") {
        // FIXME: SQL INJECTION
        list($stmt, $_) = executeStatement("insert into tasks (title, state, userID) values ('$title', '$state', '$userid')");
    } else {
        // FIXME: SQL INJECTION
        list($stmt, $_) = executeStatement("update tasks set title = '$title', state = '$state' where ID = $id");
    }

    echo "<span class='info info-success'>Update successfull</span>";
} else {
    echo "<span class='info info-error'>No update was made</span>";
}

require_once 'fw/footer.php';
?>
