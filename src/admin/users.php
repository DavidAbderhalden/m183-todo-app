<?php
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// FIXME: Use special db.php file to execute these statements...
$db_host = $_ENV["DB_HOST"];
$db_user = $_ENV["DB_USER"];
$db_pass = $_ENV["DB_PASS"];
$db_name = $_ENV["DB_NAME"];

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn -> connect_error) {
    die("Connection failed: " . $conn -> connect_error);
}
// Prepare SQL statement to retrieve user from database
$stmt = $conn -> prepare("SELECT users.ID, users.username, users.password, roles.title FROM users inner join permissions on users.ID = permissions.userID inner join roles on permissions.roleID = roles.ID order by username");
// Execute the statement
$stmt -> execute();
// Store the result
$stmt -> store_result();
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
            echo "<tr><td>$db_id</td><td>$db_username</td><td>$db_title</td><input type='hidden' name='password' value='$db_password' /></tr>";
        }
        ?>
    </table>

<?php
require_once '../fw/footer.php';
?>