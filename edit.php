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
}
$stmt = $pdo->prepare('SELECT * FROM profile WHERE profile_id = :xyz ');
$stmt->execute(
    array(
        ":xyz" => $_GET['profile_id']
    )
);
if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {
    $msg = validateProfile();
    if (is_string($msg)) {
        $_SESSION['error'] = "All fields are required";
        header('Location: edit.php?profile_id=' . $_REQUEST['profile_id']);
        return;
    }
    $msg = validatePos();
    if (is_string($msg)) {
        $_SESSION['error'] = "All fields are required";
        header('Location: edit.php?profile_id=' . $_REQUEST['profile_id']);
        return;
    }
    $msg = validateEdu();
    if (is_string($msg)) {
        $_SESSION['error'] = "All fields are required";
        header('Location: edit.php?profile_id=' . $_REQUEST['profile_id']);
        return;
    }
    $sql = "UPDATE profile SET  first_name = :fn, last_name = :ln , email = :em , headline = :he, summary = :sum  WHERE profile_id = :pfid ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        array(
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST['last_name'],
            ':em' => $_POST['email'],
            ':he' => $_POST['headline'],
            ':sum' => $_POST['summary'],
            ':pfid' => $profile_id,
        )
    );


    $stmt = $pdo->prepare('DELETE FROM position where profile_id=:pid');
    $stmt->execute(array(":pid" => $_REQUEST['profile_id']));
    insertPos($pdo,$_REQUEST['profile_id']);

    
    $stmt = $pdo->prepare("DELETE FROM education where profile_id = :pid");
    $stmt->execute(array(':pid' => $_REQUEST['profile_id']));
    insertEdu($pdo, $_REQUEST['profile_id']);



    $_SESSION['success'] = "Profile updated";
    header('Location: index.php');
    return;
}

$educations = loadEducation($pdo, $_REQUEST['profile_id']);
$positions = loadPos($pdo, $_REQUEST['profile_id']);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    die("ACCESS DENIED");
}
$fn = htmlentities($row['first_name']);
$ln = htmlentities($row['last_name']);
$em = htmlentities($row['email']);
$headl = htmlentities($row['headline']);
$sum = htmlentities($row['summary']);
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
        <form method="post">
            <p>First Name: <input type="text" name="first_name" value="<?= $fn ?> "> </p>
            <p>Last Name: <input type="text" name="last_name" value="<?= $ln ?> "> </p>
            <p>Email: <input type="text" name="email" value="<?= $em ?> "> </p>
            <p>Headline: <input type="text" name="headline" value="<?= $headl ?> "> </p>
            <p>Summary: </p> <textarea name="summary" id="" cols="30" rows="10"><?= $sum ?></textarea> 
            <input type="hidden" name="profile_id" value="<?= $profile_id ?>">
            <?php
            $pos = 0;
            echo ('<p>Position: <input type="submit" id="addPos" value="+" </p>' . "\n");
            echo ('<div id="posField">');
            foreach ($positions as $position) {
                $pos++;
                echo ('<div id="position' . $pos . '">');
                echo ('<p>Year: <input type="text" name="year' . $pos . '"');
                echo ('value="' . $position['year'] . '"/>');
                echo ('<input type="submit" value="-"');
                echo ('onclick="$(\'#position' . $pos . '\').remove();return false;">');
                echo ("</p>");
                echo ('<p>Summary:<textarea name="desc' . $pos . '" rows="4" , cols="20">');
                echo (htmlentities($position['description']));
                echo ("</textarea></p></div>");
            }
            echo ("</div></p>");
            $edu = 0;
            echo ('<p>Education: <input type="submit" id="addEducation" value="+"> </p>' . "\n");
            echo ('<div id="educationField">');
            foreach ($educations as $education) {
                $edu++;
                echo ('<div id="education' . $edu . '">');
                echo ('<p>Year: <input type="text" name="edu_year' . $edu . '"');
                echo ('value="' . $education['year'] . '"/>');
                echo ('<input type="submit" value="-"');
                echo ('onclick="$(\'#education' . $edu . '\').remove();return false;">');
                echo ('</p>');
                echo ('<p>School: <input type="text" class="school" name="edu_school' . $edu . '"');
                echo ('value="' . $education['name'] . '"/>');
                echo ('</p></div>');

            }
            echo ('</div></p>');
            ?>
            <div id="controls">
                <input type="submit" value="Save">
                <input type="submit" value="Cancel" name="cancel">
            </div>

        </form>
    </div>
    <script>
    countPos = <?= $pos ?>;
    countEdu = <?= $edu ?>;
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
            <p> Description: </p> \
            <textarea name="desc'+ countPos + '" rows="4" cols="15"></textarea>  \
            </div>'
            )
            console.log(countPos)
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