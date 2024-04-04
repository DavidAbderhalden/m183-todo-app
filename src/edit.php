<?php
include 'session/session.php';
include 'fw/db.php';
include 'fw/headers.php';

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    header("Location: /");
    exit();
}

$options = array("Open", "In Progress", "Done");

// read task if possible
$db_title = null;
$db_state = null;
$serialized_id = null;

// FIXME: Basically every user can edit any task..
if (isset($_GET['id'])) {
    $serialized_id = $serialized_username = htmlspecialchars($_GET["id"], ENT_QUOTES, 'UTF-8');

    // FIXME: SQL INJECTION
    list($stmt, $_) = executeStatement("select ID, title, state from tasks where ID = $serialized_id");
    if ($stmt -> num_rows > 0) {
        $stmt -> bind_result($_, $db_title, $db_state);
        $stmt -> fetch();
    }
}

require_once 'fw/header.php';
?>

<?php if (isset($_GET['id'])) { ?>
    <h1>Edit Task</h1>
<?php } else { ?>
    <h1>Create Task</h1>
<?php } ?>

    <form id="form" method="post" action="savetask.php">
        <input type="hidden" name="id" value="<?php echo $serialized_id ?>"/>
        <div class="form-group">
            <label for="title">Description</label>
            <input type="text" class="form-control size-medium" name="title" id="title" value="<?php echo $db_title ?>">
        </div>
        <div class="form-group">
            <label for="state">State</label>
            <select name="state" id="state" class="size-auto">
                <?php for ($i = 0; $i < count($options); $i++) : ?>
                    <span><?php ?></span>
                    <option value='<?= strtolower($options[$i]); ?>' <?= $db_state == strtolower($options[$i]) ? 'selected' : '' ?>><?= $options[$i]; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="submit"></label>
            <input id="submit" type="submit" class="btn size-auto" value="Submit"/>
        </div>
    </form>
    <script>
        $(document).ready(function () {
            $('#form').validate({
                rules: {
                    title: {
                        required: true
                    }
                },
                messages: {
                    title: 'Please enter a description.',
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        });
    </script>

<?php
require_once 'fw/footer.php';
?>