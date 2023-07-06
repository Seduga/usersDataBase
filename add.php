<?php
session_start();
require_once "pdo.php";
require_once "util.php";
require_once "head.php";
if (deniedAccess()) {
} else {
    if (isset($_POST['cancel'])) {
        header('Location: index.php');
        return;
    }

    if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {
        $msg = validateProfile();
        if (is_string($msg)) {
            $_SESSION['error'] = "All fields are required";
            header('Location: add.php');
            return;
        } else {

            $sql = 'INSERT INTO profile(user_id,first_name,last_name,email,headline,summary ) VALUES (:uid,:fn,:ln,:em,:he,:sum)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(
                array(
                    ":uid" => ($_SESSION['user_id']),
                    ':fn' => ($_POST['first_name']),
                    ':ln' => ($_POST['last_name']),
                    ':em' => ($_POST['email']),
                    ':he' => ($_POST['headline']),
                    ':sum' => ($_POST['summary']),
                )
            );
            $profile_id = $pdo->lastInsertId();
            insertEdu($pdo, $profile_id);
            insertPos($pdo, $profile_id);
            $_SESSION['success'] = "Added";
            header('Location: index.php');
            return;
        }

    }

}

?>
<html>

<head>
    <title>Daniil Sieedugin</title>
</head>

<body>
    <div class="container">
        <h3>Adding profile for
            <?= ($_SESSION['name']) ?>
        </h3>
        <?php
        flashMessage()
            ?>
        <form action="" method="post" id="myForm">
            <p>First Name: <input type="text" name="first_name" id="fname"> </p>
            <p>Last Name: <input type="text" name="last_name" id="lname"> </p>
            <p>Email: <input type="text" name="email" id="email"> </p>
            <p>Headline: <input type="text" name="headline" id="headline"> </p>
            <p>Summary:</p> <textarea name="summary" id="" cols="15" rows="4" id="summary"></textarea>
            <p>
                Position: <input type="submit" id="addPos" value="+">
            <div id="posField">
            </div>
            </p>
            <p>
                Education: <input type="submit" id="addEducation" value="+">
            <div id=educationField>

            </div>
            </p>
            <div id="controls">
                <input type="submit" value="Add">
                <input type="submit" name="cancel" value="Cancel">
            </div>
        </form>
    </div>
    <script>
        countPos = 0;
        countEdu = 0;
        $(document).ready(function () {
            $('#addPos').click(function (event) {
                event.preventDefault();
                if (countPos >= 9) {
                    alert('Maximum position entries exceeded');
                    return;
                }
                countPos++;
                $('#posField').append(
                    '<div id="position' + countPos + '">\
            <p>Year: <input type="text" name="year'+ countPos + '" value="">\
            <input type="button" value="-" \
            onclick="$(\'#position'+ countPos + '\').remove();return false;"</p> \
            <p>Description: </p>\
            <textarea name="desc'+ countPos + '" rows="4" cols="15"></textarea> \
            </div>'
                )
            })
            $('#addEducation').click(function (event) {
                event.preventDefault();
                if (countEdu >= 9) {
                    alert('Maximum position entries exceeded');
                    return;
                }
                countEdu++
                $('#educationField').append(
                    '<div id="education' + countEdu + '">\
                <p>Year: <input type="text" name="edu_year'+ countEdu + '" value="">\
                <input type="button" value="-" \
            onclick="$(\'#education'+ countEdu + '\').remove();return false;"</p> \
                <p>School : <input type="text" class="school" name="edu_school'+ countEdu + '" value=""/>\
                </div>'

                )
                $('.school').autocomplete({ source: "school.php" });
            })
        })
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