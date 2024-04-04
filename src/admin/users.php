<?php
include __DIR__.'/../fw/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$sql = "SELECT users.ID, users.username, roles.title FROM users INNER JOIN permissions ON users.ID = permissions.userID INNER JOIN roles ON permissions.roleID = roles.ID ORDER BY username";
list($stmt, $_) = executeStatement($sql);
// values from db are already serialized (no xss danger)
$db_id = null;
$db_username = null;
$db_title = null;
// Bind the result variables
$stmt -> bind_result($db_id, $db_username, $db_title);

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