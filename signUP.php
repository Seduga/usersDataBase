<?php
require_once 'util.php';
require_once "head.php";
require_once "pdo.php";
session_start();
if (isset($_POST['cancel'])) {
    header('Location: index.php');
    return;
}
if (isset($_POST['email']) && isset($_POST['pass']) && isset($_POST['name'])) {
    $msg = validateSignUp();
    if (is_string($msg)) {;
        header('Location:signUP.php');
        return;
    }
    $user = User::getUser($pdo, $_POST['email']);
    if (!$user) {
        insertUsers($pdo);
        $user = User::getUser($pdo, $_POST['email']);
        if($user){
            $_SESSION['name'] = $user->getName();
            $_SESSION['user_id'] = $user->getUserId();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['success'] = "Success";
            header("Location: index.php");
            return;
        }
        
    } else {
        $_SESSION['error'] = "Account with this email already exists";
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
            <p>Login: <input type="text" name="name"
                    value=""> </p>
                <p>Email: <input type="text" name="email"
                        value=""> </p>
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