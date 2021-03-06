//AJAX create Random user
function createUser(json_key) {
    $.ajax({
        type: 'GET',
        url: '/json',
        //JSON Params
        data: {
            action: 'create',
            key: json_key,
        },
        dataType: 'json',
        success: function (data) {
            $('#userData').html('<p>User ID: ' + data.id + '</p>');
            $('#userData').append('<p>Email: ' + data.email + '</p>');
            $('#userData').append('<p>Password: ' + data.password + '</p>');
            $('#userData').append('<p>Name: ' + data.name + '</p>');
            $('#userData').append('<p>Gender: ' + data.gender + '</p>');
            $('#userData').append('<p>Age: ' + data.age + '</p>');
            $('#userData').append('<p>GEO Location: ' + data.geo_location + '</p>');
        }
    });
}

//Set Sessions
function JSsetCookie(cname, cvalue, exhours) {
    var d = new Date();
    d.setTime(d.getTime() + (exhours*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

//LogOut
function LogOut() {
    document.cookie = "login_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    document.cookie = "login_key=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    window.location.href = '/login/';
}

//Validate Login inputss
function validateInputs(json_key) {
    $.ajax({
        type: 'GET',
        url: '/json',
        //JSON Params
        data: {
            action : 'login',
            email : $("#email").val(),
            pwd : $("#password").val(),
            key: json_key,
        },
        dataType: 'json',
        success: function(data) {
            if ("login_token" in data)
            {
                jQuery( document ).ajaxSuccess(function( ) {
                    var expDate = new Date();
                    expDate.setTime(expDate.getTime() + (60 * 60 * 1000)); // add 60 minutes
                    JSsetCookie("login_token", data.login_token, 1);
                    JSsetCookie("login_key", data.login_key, 1);
                    window.location.href = '/profiles';
                });
            }
            else {
                $('#errors').html('<p>Error: ' + data[0] + '</p>');
            }
        }
    });
}

//Get Matching profiles
function getProfiles(json_key,userid) {
    $.ajax({
        type: 'GET',
        url: '/json',
        //JSON Params
        data: {
            action : 'profiles',
            user_id : userid,
            key: json_key,
        },
        dataType: 'json',
        success: function (data) {
            $.each(data, function(index, element) {
                var profileEmail = "";
                var starRating = "";
                var swipeHtml = "";
                var acceptedHtml = "";

                if (element.swiped_count >= 10){
                    starRating = "<p style='color:gold'><span style='font-size: 30px'><strong>&#9734;</strong></span> (Gold)</p>";
                }
                else {
                    if (element.swiped_count < 10 && element.swiped_count >= 5){
                        starRating = "<p style='color:silver'><span style='font-size: 30px'><strong>&#9734;</strong></span> (Silver)</p>";
                    }
                    else {
                        if (element.swiped_count < 5 && element.swiped_count >= 1){
                            starRating = "<p style='color:#cd7f32'><span style='font-size: 30px'><strong>&#9734;</strong></span> (Bronz)</p>";
                        }
                    }
                }

                if (element.accepted == "y")
                {
                    profileEmail = element.email;
                    acceptedHtml = '<img src="https://img.apksum.com/dc/com.muzmatch.muzmatchapp/5.2.3a/icon.png" height="50" width="50">';
                }

                if (element.swiped == "y")
                {
                    swipeHtml = '<img src="https://img.apksum.com/dc/com.muzmatch.muzmatchapp/5.2.3a/icon.png" height="50" width="50">';
                }
                else
                {
                    swipeHtml = '<div class="top-button-block" data-v-2d064d84><a href="/swipe?target='+ element.id +'" class="main-button" data-v-2d064d84>Swipe</a></div>';
                }

                $('#sort_table > tbody:last-child').append('<tr><td>'+element.name+'</td><td>'+element.age+'</td><td>'+profileEmail+'</td><td>'+element.distance_miles+'</td><td>'+element.created.substring(0, 10)+'</td><td>'+starRating+'</td><td>'+swipeHtml+'</td><td>'+acceptedHtml+'</td></tr>');
            })
        }
    });
}

//Table range options

var age_range;
var distance_range;
var rating_range;

$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        var min = parseFloat(age_range.slider( "values", 0 ));
        var max = parseFloat(age_range.slider( "values", 1 ));
        var col = parseFloat( data[2] ) || 0; // data[number] = column number
        if ( ( isNaN( min ) && isNaN( max ) ) ||
            ( isNaN( min ) && col <= max ) ||
            ( min <= col   && isNaN( max ) ) ||
            ( min <= col   && col <= max ) )
        {
            return true;
        }
        return false;
    },
    function( settings, data, dataIndex ) {
        var min = parseFloat(distance_range.slider( "values", 0 ));
        var max = parseFloat(distance_range.slider( "values", 1 ));
        var col = parseFloat( data[4] ) || 0; // data[number] = column number
        if ( ( isNaN( min ) && isNaN( max ) ) ||
            ( isNaN( min ) && col <= max ) ||
            ( min <= col   && isNaN( max ) ) ||
            ( min <= col   && col <= max ) )
        {
            return true;
        }
        return false;
    },
    function( settings, data, dataIndex ) {
        var min = parseFloat(rating_range.slider( "values", 0 ));
        var max = parseFloat(rating_range.slider( "values", 1 ));
        var col = parseFloat( data[9] ) || 0; // data[number] = column number
        if ( ( isNaN( min ) && isNaN( max ) ) ||
            ( isNaN( min ) && col <= max ) ||
            ( min <= col   && isNaN( max ) ) ||
            ( min <= col   && col <= max ) )
        {
            return true;
        }
        return false;
    }
);

$(document).ready(function() {
    age_range = $( "#age_range" );
    distance_range = $( "#distance_range" );
    rating_range = $( "#rating_range" );
    var live_range_val_age = $( "#live_range_val_age" );
    var live_range_val_distance =$( "#live_range_val_distance" );
    var live_range_val_rating =$( "#live_range_val_rating" );
    age_range.slider({
        range: true,
        min: 18,
        max: 70,
        step: 1,
        values: [ 18, 70 ],
        slide: function( event, ui ) {
            live_range_val_age.val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
        },
        stop: function( event, ui ) {
            table.draw();
        }
    });
    distance_range.slider({
        range: true,
        min: 0,
        max: 1000,
        step: 1,
        values: [ 0, 1000 ],
        slide: function( event, ui ) {
            live_range_val_distance.val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
        },
        stop: function( event, ui ) {
            table.draw();
        }
    });
    rating_range.slider({
        range: true,
        min: 0,
        max: 100,
        step: 1,
        values: [ 0, 1000 ],
        slide: function( event, ui ) {
            live_range_val_rating.val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
        },
        stop: function( event, ui ) {
            table.draw();
        }
    });
    live_range_val_age.val(age_range.slider( "values", 0 ) + " - " + age_range.slider( "values", 1 ) );
    live_range_val_distance.val(distance_range.slider( "values", 0 ) + " - " + distance_range.slider( "values", 1 ) );
    live_range_val_rating.val(rating_range.slider( "values", 0 ) + " - " + rating_range.slider( "values", 1 ) );

    var table = $( "#profileTable" ).DataTable({
        //"bPaginate": false,
        //"bFilter": true,
    });
});


//swipe User
function swipeUser(json_key,userid,taregetid) {
    $.ajax({
        type: 'GET',
        url: '/json',
        //JSON Params
        data: {
            action : 'swipe',
            user_id : userid,
            taregetid : taregetid,
            key: json_key,
        },
        dataType: 'json',
        success: function (data) {
            if (data.id) {
                $('#swipeRes').html('User successfully swiped');
            }
            else {
                $('#swipeRes').html('Error swiping user');
            }
        }
    });
}