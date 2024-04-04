<?php
include __DIR__.'/../fw/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// FIXME: SQL INJECTION
list($stmt, $_) = executeStatement("SELECT users.ID, users.username, users.password, roles.title FROM users inner join permissions on users.ID = permissions.userID inner join roles on permissions.roleID = roles.ID order by username");
// values from db are already serialized (no xss danger)
$db_id = null;
$db_username = null;
$db_password = null;
$db_title = null;
// Bind the result variables
$stmt -> bind_result($db_id, $db_username, $db_password, $db_title);

require_once '../fw/header.php';
?>
    <h2>User List</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
        </tr>
        <?php
        // Fetch the result
        while ($stmt -> fetch()) {
            echo "<tr><td>$db_id</td><td>$db_username</td><td>$db_title</td></tr>";
        }
        ?>
    </table>

<?php
require_once '../fw/footer.php';
?>