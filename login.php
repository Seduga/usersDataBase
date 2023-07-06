<?php
require_once "pdo.php";
require_once "util.php";
require_once "head.php";
session_start();

if (isset($_POST['cancel'])) {
    $_SESSION['email'] = '';
    header('Location: index.php');
    return;
}


if (isset($_POST['email']) && isset($_POST['pass'])) {
    unset($_SESSION['email']);
    $salt = 'XyZzy12*_';
    $check = hash('md5', $salt . $_POST['pass']);
    $stmt = $pdo->prepare('SELECT user_id, name FROM users  WHERE email = :em AND password = :pw');
    $stmt->execute(array(':em' => $_POST['email'], ':pw' => $check));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row !== false) {
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['success'] = "Logged in";
        header("Location: index.php");
        return;
    } else {
        $_SESSION['error'] = "Invalid email or password";
        $_SESSION['email'] = $_POST['email'];
        header('Location: login.php');
        return;
    }
}
?>
<html>

<head>
    <title>Daniil Sieedugin</title>
</head>

<body>
    <div class="container">
        <h3>Pleace Log in</h3>

        <?php flashMessage() ?>
        <form action="login.php" method="post" id="myform">
            <p>Email: <input type="text" id="email" name="email"
                    value="<?php if (isset($_SESSION['email'])) {
                        htmlentities($_SESSION['email']);
                    } ?>"> </p>
            <p>Password: <input type="password" name="pass" id="password"> </p>
            <div id="controls">
                <input type="submit" id="submit" onclick="return doValidate()" value="Log In">
                <input type="submit" id="cancel" value="Cancel" name="cancel">
            </div>
        </form>
    </div>
    <script>
        function doValidate() {
            let email = document.getElementById('email').value
            let password = document.getElementById('password').value
            if (email == null || email == "" || password == null || password == "") {
                alert('Both fields must be filled out ')
                return false;
            }
            if (email.indexOf('@') == -1) {
                alert('Invalid email address')
                return false;
            }
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
        max-width: max-content;
    }

    #myform {
        display: flex;
        flex-direction: column;
    }

    #controls {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-around;
    }
</style>