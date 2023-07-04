<?php
session_start();
require_once "pdo.php";
require_once "head.php";

if (isset($_POST['cancel'])) {
    header('Location:index.php');
    return;
}


if (isset($_POST['delete']) && isset($_POST['profile_id'])) {
    $sql = "DELETE FROM profile WHERE profile_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['profile_id']));
    $_SESSION['success'] = "Record deleted";
    header('Location: index.php');
    return;
}
$stmt = $pdo->prepare('SELECT first_name, last_name,profile_id from profile where profile_id = :xyz');
$stmt->execute(array(':xyz' => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    die("ACCESS DENIED");
}
?>
<html>

<head></head>

<body>
    <h1>Confirm deleting:
        <?= htmlentities($row['first_name'] . "\n" . $row['last_name']) ?>
    </h1>
    <form method="post">
        <input type="hidden" name="profile_id" value=<?= $row['profile_id'] ?>>
        <input type="submit" value="Delete" name="delete">
        <input type="submit" name="cancel" value="Cancel">
    </form>
</body>

</html>

<style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        padding: 15px;
        font-size: 18px;
    }
</style>