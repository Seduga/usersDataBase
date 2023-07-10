(function(){
    "use strict"
    $.getJSON('../JSON/profileJSON.php', function (data) {
        $_GET()
        let pfid = p[1] - 1
        console.log(data)
        $('#mainList').empty();
        data = data[pfid]
        $("#mainList").append(
            "<li>First Name: " + data.first_name + "</li>" +
            "<li>Last Name: " + data.last_name + "</li>" +
            "<liEmail: " + data.email + "</li>" +
            "<li>Headline: " + data.headline + "</li>" +
            "<li>Summary: " + data.summary + "</li>"
        )
    })
    $.getJSON('../JSON/educationJSON.php', function (data) {
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
    $.getJSON('../JSON/positionJSON.php', function (data) {
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
})()