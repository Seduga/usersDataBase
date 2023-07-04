<?php
session_start();
require_once "pdo.php";
require_once "util.php";
require_once "head.php";
deniedAccess();


$positions = loadPos($pdo, $_REQUEST['profile_id']);
$educations = loadEducation($pdo,$_REQUEST['profile_id']);


?>
<html>

<head>
    <title>Daniil Sieedugin</title>
</head>

<body>
    <div class="container">
        <h1>Profile information</h1>
        <ul id='myList'>

        </ul>
        <?php
        $stmt = $pdo->prepare("SELECT * from profile where profile_id = :xyz");
        $stmt->execute(array(":xyz" => $_GET['profile_id']));
        echo ("<ul>");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo ("<p>First Name: " . "\n");
            echo (htmlentities($row['first_name']) . "</p>");
            echo ("<p>Last Name:" . "\n");
            echo (htmlentities($row['last_name']) . "</p>");
            echo ("<p>Email:" . "\n");
            echo (htmlentities($row['email']) . "</p>");
            echo ("<p>Headline:" . "\n");
            echo (htmlentities($row['headline']) . "</p>");
            echo ("<p>Summary:" . "\n");
            echo (htmlentities($row['summary']) . "</p>");
            echo ("<p>Position: </p>");
            $pos = 0;
            foreach ($positions as $position) {
                $pos++;
                echo ("<li>" . $position['year'] . "\n" . ":" . "\n");
                echo ($position['description'] . "</li>");
            }
        }
        echo ("<p>Education: </p>");
        $edu = 0;
        foreach($educations as $education){
            $edu++;
            echo('<li>' . $education['year'] . "\n" . ":" . "\n");
            echo($education['name'] . "</li>");
        }


        ?>
        <a href="index.php">Done</a>
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
     background-color: rgba(248, 240, 250, 1);
     max-width: max-content;
    }
</style>