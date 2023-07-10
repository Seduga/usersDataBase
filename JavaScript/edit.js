(function(){
    "use strict"
    $.getJSON('./JSON/profileJSON.php', function (data) {
        $_GET()
        let pfid = p[1] - 1
        console.log(data)
        data = data[pfid]
        $('#profileField').append(
         "<p>First Name:" + ` <input  type="text" name="first_name" value="${data.first_name}" >` + " </p>" 
        +"<p>Last Name:" + `<input  type="text" name="last_name" value="${data.last_name}"> ` + "</p>" 
        +"<p>Email:" + `<input id="email" type="text" name="email" value="${data.email}">` +  "</p>" 
        +"<p>Headline: " + `<input id="headline" type="text" name="headline" value="${data.headline}">` +"</p>" 
        +"<p>Summary: </p>" +  `<textarea name="summary" id="" cols="30" rows="10">${data.summary}</textarea>`
        )
    })
    $.getJSON("./JSON/positionJSON.php", function (data) {
        $_GET();
        let pfid = p[1] - 1;
        console.log(data)
        for (let i = 0; i < data.length; i++) {
            if (data[i].profile_id == pfid + 1) {
                countPos++
                $('#posField').append(
                    `<div id="position${countPos}"`
                    + "<p>Year: "
                    + `<input type="text" name="year${countPos}" value="${data[i].year}">`
                    + "<input type='submit' value='-' onclick='$(\'#position'+ countPos + '\').remove();return false;'>" + "</p>"
                    + "<p>Summary: " + `<textarea name="desc${countPos}" rows="4" cols="20">${data[i].description} ` + "</textarea>" + "</p>"
                    + "</div>"
                )
            }
        }
    })
    $.getJSON("./JSON/educationJSON.php", function (data) {
        $_GET()
        let pfid = p[1] - 1
        console.log(data)
        $('#eduField').empty();
        for (let i = 0; i < data.length; i++) {
            if (data[i].profile_id == pfid + 1) {
                countEdu++
                $('#eduField').append(
                    `<div id="education${countEdu}"`
                    + "<p>Year: "
                    + `<input type="text" name="edu_year${countEdu}" value="${data[i].year}">`
                    + "<input type='submit' value='-' onclick='$(\'#position'+ countPos + '\').remove();return false;'>" + "</p>"
                    + "<p>School: " + `<input name="edu_school${countEdu}" class="school" value="${data[i].name}" >` + "</p>"
                    + "</div>"
                )
            }
        }
    })
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
        })
        $('#addEdu').click(function (event) {
            event.preventDefault();
            if (countEdu >= 9) {
                alert('Maximum position entries exceeded');
                return;
            }
            countEdu++
            $('#eduField').append(
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
    
})()