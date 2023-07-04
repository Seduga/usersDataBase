<?php
require_once 'util.php';
require_once "head.php";
   session_start();

   if(isset($_POST['cancel'])){
    header('Location: index.php');
    return;
   }
if(isset($_POST['email']) && isset($_POST['pass']) && isset($_POST['name'])){
 if(strlen($_POST['email']) < 1  || strlen($_POST['pass']) < 1){
    $_SESSION['error'] = "All fields are required";
    header('Location: signUp.php');
    return;
 }
 if(strpos($_POST['email'] , "@") === false ){
    $_SESSION['error'] = "Invalid email adress";
    header('Location:signUp.php');
    return;
 }
 if(strlen($_POST['pass']) < 3){
    $_SESSION['error'] = "Password is to short";
    header('Location: signUp.php');
    return;
 };
    $stmt = $pdo->prepare('SELECT email FROM users where email = :em');
    $stmt->execute(array(
        "em" => $_POST['email']
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row['email'] != $_POST['email'] ) {;
        $salt = 'XyZzy12*_';
        $pass = hash('md5' ,  $salt . $_POST['pass']); 
        $sql = ("INSERT INTO users(name,email,password) VALUES (:nam,:em,:pass)");
       $stmt = $pdo ->prepare($sql);
       $stmt->execute(array(
           ':nam' => $_POST['name'],
           ':em' => $_POST['email'],
           ':pass' => $pass
       ));
        $_SESSION['success'] = "Success";
        header("Location: index.php");
        return;
    } else {
        $_SESSION['error'] = "Account with this email exists";
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
            <p>Login: <input type="text" name="name"> </p>
            <p>Email: <input type="text" name="email"> </p>
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