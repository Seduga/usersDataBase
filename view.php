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
    <script>
        let p = '';
        function $_GET(key) {
            p = window.location.search;
            p = p.match(new RegExp([key] + '=([^&=]+)'));
        }
        $.getJSON('./JSON/profileJSON.php', function (data) {
            $_GET()
            let pfid = p[1] - 1
            console.log(data)
            $('#mainList').empty();
            entry = data[pfid];
            $("#mainList").append(
                "<li>First Name: " + entry.first_name + "</li>" +
                "<li>Last Name: " + entry.last_name + "</li>" +
                "<liEmail: " + entry.email + "</li>" +
                "<li>Headline: " + entry.headline + "</li>" +
                "<li>Summary: " + entry.summary + "</li>"
            )
        })
        $.getJSON('./JSON/educationJSON.php', function (data) {
            $_GET()
            let pfid = p[1] - 1
            $('#eduList').empty();
            for (let i = 0; i < data.length; i++) {
                if (data[i].profile_id == pfid + 1) {
                    $('#eduList').append(
                        "<li>" + data[i].year + ":" + "\n" + data[i].name + "</li>"
                    )
                }
            }
        })
        $.getJSON('./JSON/positionJSON.php', function (data) {
            $_GET();
            let pfid = p[1] - 1
            console.log(data)
            $('#posList').empty();
            for (let i = 0; i < data.length; i++) {
                if (data[i].profile_id == pfid + 1) {
                    $('#posList').append(
                        "<li>" + data[i].year + ":" + "\n" + data[i].description + "</li>"
                    )
                }
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
        background-color: rgba(248, 240, 250, 1);
        max-width: max-content;
    }
</style>