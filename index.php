<?php
require_once "pdo.php";
require_once "util.php";
require_once "head.php";
session_start();
?>
<html>

<head>
    <title>Daniil Sieedugin</title>
</head>

<body>
    <div class="container">
        <h3>Daniil Sieedugin's Resume Registry</h3>

        <?php
        flashMessage();
        if (!isset($_SESSION['user_id'])) { ?>
        <p><a href="signUp.php" >Sign up</a> or <a href="login.php">Log in</a></p>
        <?php } else { ?>
            <p>
                <a href="logout.php" name="logout">Logout</a>
            </p>
            <p>
                <a href="add.php">Add New Entry</a>
            </p>
            <?php
        }
        ?>
        <table border="1">
            <th>Name</th>
            <th>Headline</th>
            <?php if (isset($_SESSION['user_id'])) { ?>
                <th>Actions</th>
                <?php
            } ?>
            <tbody id="mytab">
            </tbody>
        </table>
    </div>

    <script>
        $.getJSON('./JSON/indexJSON.php', function (data) {
            $('#mytab').empty();
            found = false;
            for (let i = 0; i < data.length; i++) {
                entry = data[i];
                found = true;
                console.log(data[i].profile_id)
                $('#mytab').append('<tr><td>' + '<a href="view.php?profile_id=' + entry.profile_id + '">' + entry.first_name + "\n" + entry.last_name + "</a>" + '</td><td>'
                    + entry.headline + "</td><td>"
                    <?php if (isset($_SESSION['user_id'])) { ?> + '<a href="edit.php?profile_id=' + entry.profile_id + '">' + "Edit</a>" + "\n"
                        + '<a href="delete.php?profile_id=' + entry.profile_id + '">' + "Delete </a> </td></tr>" <?php } ?> )
            }
        if (!found) {
            $("#mytab").append("<tr><td>No entries found </td></tr>")
        }
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
       
        max-width: max-content;
    }
</style>