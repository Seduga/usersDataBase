<?php
require_once 'util.php';
require_once "head.php";
require_once "pdo.php";
   session_start();
   if(isset($_POST['cancel'])){
    $_SESSION['email_value'] = '';
    $_SESSION['name_value'] = '';
    header('Location: index.php');
    return;
   }
if(isset($_POST['email']) && isset($_POST['pass']) && isset($_POST['name'])){
    $msg = validateSignUp();
    if(is_string($msg)){
        $_SESSION['name_value'] = $_POST['name'];
        $_SESSION['email_value'] = $_POST['email'];
        header('Location:signUP.php');
        return;
    }
    $stmt = $pdo->prepare('SELECT email FROM users where email = :em');
    $stmt->execute(array(
        "em" => $_POST['email']
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row['email'] != $_POST['email'] ) {;
        insertUsers($pdo);
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['success'] = "Success";
        header("Location: index.php");
        return;
    } else {
        $_SESSION['error'] = "Account with this email already exists";
        $_SESSION['name_value'] = $_POST['name'];
        $_SESSION['email_value'] = $_POST['email'];
        header('Location: signUp.php');
        return;
}

}

?>

<html>
    <head></head>
    <body>
        <div class="container">
            <h1>Sign Up</h1>
            <?= flashMessage() ?>
         <form method="post">
            <p>Login: <input type="text" name="name" value = "<?= htmlentities($_SESSION['name_value']) ?>"> </p>
            <p>Email: <input type="text" name="email" value= "<?= htmlentities($_SESSION['email_value']) ?>"> </p>
            <p>Password: <input type="password" name="pass"> </p>
            <input type="submit" value="Sign up">
            <input type="submit" value="Cancel" name="cancel">

         </form>
        </div>
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
        max-width: max-content;
    }
</style>