<?php
session_start();
require_once "pdo.php";
require_once "util.php";
require_once "head.php";
deniedAccess();
?>
<html>

<head>
    <title>Daniil Sieedugin</title>
</head>

<body>
    <div class="container">
        <h1>Profile information</h1>
        <ul id='mainList'>
        </ul>
        <h4>Positions: </h4>
        <ul id='posList'>
        </ul>
        <h4>Education: </h4>
        <ul id="eduList">
        </ul>
        <?php
        ?>
        <a href="index.php">Done</a>
    </div>
    <script src="./JavaScript/view.js"></script>
    <script>
        let p = '';
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
        background-color: rgba(248, 240, 250, 1);
        max-width: max-content;
    }
</style>