<?php
include __DIR__.'/../fw/db.php';

if (!isset($_SESSION['username'])) {
    header("location:../login.php");
    exit();
}

$userid = $_SESSION['userid'];
list($stmt, $_) = executeStatement("select ID, title, state from tasks where UserID = $userid");

$db_id = null;
$db_title = null;
$db_state = null;
// Bind the result variables
$stmt -> bind_result($db_id, $db_title, $db_state);
?>
<section id="list">
    <a href="edit.php">Create Task</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Description</th>
            <th>State</th>
            <th></th>
        </tr>
        <?php while ($stmt -> fetch()) { ?>
            <tr>
                <td><?php echo $db_id ?></td>
                <td class="wide"><?php echo $db_title ?></td>
                <td><?php echo ucfirst($db_state) ?></td>
                <td>
                    <!-- FIXME: Remove bullshit -->
                    <a href="edit.php?id=<?php echo $db_id ?>">edit</a> | <a href="delete.php?id=<?php echo $db_id ?>">delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</section>