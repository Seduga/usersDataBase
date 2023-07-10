<?php
require_once "pdo.php";
require_once "util.php";
require_once "head.php";
session_start();
deniedAccess();

if (isset($_POST['cancel'])) {
    header('Location:index.php');
    return;
}
if (!isset($_REQUEST['profile_id'])) {
    $_SESSION['error'] = "Missing profile_id";
    header('Location: index.php');
    return;
};
if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {
    $msg = validateProfile();
    if (is_string($msg)) {
        header('Location: edit.php?profile_id=' . $_REQUEST['profile_id']);
        return;
    }
    $msg = validatePos();
    if (is_string($msg)) {
        header('Location: edit.php?profile_id=' . $_REQUEST['profile_id']);
        return;
    }
    $msg = validateEdu();
    if (is_string($msg)) {
        header('Location: edit.php?profile_id=' . $_REQUEST['profile_id']);
        return;
    }

   updateProfile($pdo,$profile_id);
    $stmt = $pdo->prepare('DELETE FROM position where profile_id=:pid');
    $stmt->execute(array(":pid" => $_REQUEST['profile_id']));
    insertPos($pdo, $_REQUEST['profile_id']);

    $stmt = $pdo->prepare("DELETE FROM education where profile_id = :pid");
    $stmt->execute(array(':pid' => $_REQUEST['profile_id']));
    insertEdu($pdo, $_REQUEST['profile_id']);

    $_SESSION['success'] = "Profile updated";
    header('Location: index.php');
    return;
}
$profile_id = $_REQUEST['profile_id'];

?>
<html>

<head></head>

<body>
    <title>Daniil Sieedugin</title>
    <div class="container">
        <h3>Editing profile for
            <?php echo ($_SESSION['name']) ?>
        </h3>
        <?php flashMessage() ?>
        <form id="myForm" method="post">
            <div id="profileField">
            <input type="hidden" name="profile_id" value="<?= $profile_id ?>">
            </div>
            
            <p>Position: <input type="submit" id="addPos" value="+"> </p>
            <div id="posField">
            </div>
            <p>Education: <input type="submit" id="addEdu" value="+"></p>
            <div id="eduField">
            </div>
            <div id="controls">
                <input type="submit" value="Save">
                <input type="submit" value="Cancel" name="cancel">
            </div>

        </form>
    </div>
    <script src="./JavaScript/edit.js"></script>
    <script>
        let countPos = 0;
        let countEdu = 0;
        function $_GET(key) {
            p = window.location.search;
            p = p.match(new RegExp([key] + '=([^&=]+)'));
        }
    </script>
</body>


</html>
<style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .container {
        border: 1px solid black;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background-color: rgba(248, 240, 250, 1);
        max-width: max-content;
    }

    #controls {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-around;
    }
</style>