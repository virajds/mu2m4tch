<?php

include_once './json_key.php';
include_once '../db/db_connect.php';
include_once '../shared/geo_distance.php';
include_once '../shared/random_user_generator.php';

$json_key = get_json_key();
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

//Error holder
$erros = array();

if ($_REQUEST['key'] == $json_key) {
    if ($_REQUEST['action'] == "create") {
        // Get random_user = [Name,Gender,Email,Age,Password]
        $random_user = generate_random_data(10);

        try {
            # Inset users to DB - Preventing SQL injection
            $stmt = $db->prepare('INSERT INTO user_profile (name, email, password, gender, age, geo_location, created, modified) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())');
            $stmt->execute([$random_user[0], $random_user[2], hash('sha256', $random_user[4]), $random_user[1], $random_user[3], $random_user[5]]);
            $last_id = $db->lastInsertId();

            $data_array = array('id' => $last_id, 'name' => $random_user[0], 'email' => $random_user[2], 'password' => $random_user[4], 'gender' => $random_user[1], 'age' => $random_user[3], 'geo_location' => $random_user[5]);
            $myJSON = json_encode($data_array);
        } catch (Exception $e) {
            //SQL Error
            $erros[] = 'SQL Error: ' . $e->getMessage();
            $myJSON = json_encode($erros);
        }
    }
    elseif ($_REQUEST['action'] == "login"){
        if (!$_REQUEST['email']){
            $erros[] = "Email Cannot be blank";
        }
        elseif (!filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) {
            $erros[] = "Invalid Email";
        }

        if (!$_REQUEST['pwd']){
            $erros[] = "Password Cannot be blank";
        }

        #IF no errors
        if (empty ($erros)){
            # Get Login infor from DB
            $stmt = $db->prepare('SELECT * FROM user_profile WHERE email= ? AND password=? LIMIT 1');
            $found = $stmt->execute([$_REQUEST['email'], hash('sha256', $_REQUEST['pwd'])]);
            $arr = $stmt->fetch(PDO::FETCH_ASSOC);

            // Valid results found
            if ($arr['password']){
                $myJSON = json_encode(array('login_token' => $arr['password'], 'login_key' => hash('sha256', $arr['password']."mu2m@tch".date("Y-m-d"))));
            }
            else {
                $erros[] = "No results found";
                $myJSON = json_encode($erros);
            }
        }
        else {
            $myJSON = json_encode($erros);
        }
    }
    elseif ($_REQUEST['action'] == "profiles"){
            # Select matching profiles
            $stmt = $db->prepare('SELECT up.geo_location as user_geo_location, up2.*,
                                                    count(us3.swiped_id) as swiped_count, 
                                                    IF(us.swiped_id IS NOT NULL,"y","n") as swiped, 
                                                    IF(us2.id IS NOT NULL,"y","n") as accepted
                                                FROM user_profile up
                                                INNER JOIN user_profile up2 ON IF(up.gender="m",(up2.gender="f" AND up.age-up2.age >= -2),(up2.gender="m" AND up2.age-up.age >= -2))
                                                LEFT JOIN user_swipes us ON us.id=up.id AND us.swiped_id=up2.id
                                                LEFT JOIN user_swipes us2 ON us2.id=up2.id AND us2.id=us.swiped_id 
                                                LEFT JOIN user_swipes us3 ON up2.id=us3.swiped_id 
                                                WHERE
                                            up.id = ? 
                                                GROUP BY up2.id 
                                                ORDER BY accepted DESC,swiped DESC,up2.id');
            $found = $stmt->execute([$_REQUEST['user_id']]);
            $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($arr) > 1){
                if ($arr[0]['user_geo_location'] && $arr[0]['geo_location']){
                    $user_lat_lon = explode(",", $arr[0]['user_geo_location']);

                    for($i = 0; $i < count($arr); ++$i) {
                        $swiper_lat_lon = explode(",", $arr[$i]['geo_location']);
                        // instantiate geo distance product object
                        $geo = new GeoDistanceCalc(floatval($user_lat_lon[0]),floatval($user_lat_lon[1]),floatval($swiper_lat_lon[0]),floatval($swiper_lat_lon[1]));
                        $arr[$i]['distance_miles'] = $geo->distance_miles;
                        $arr[$i]['distance_in_kms'] = $geo->distance_km;
                    }

                    //Commet out Google distance checker API methode
                    //$origin = $arr[0]['user_geo_location'];
                    //$destinations = $arr[0]['geo_location'];

                    //foreach ($arr as $row) {
                    //    $destinations .= "|".$row['geo_location'];
                    //}

                    //Getting distances from Google API
                    //$jsonurl = "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=".$origin."&destinations=".$destinations."&key=<API KEY>";
                    //$json = file_get_contents($jsonurl);
                    //$location_arr = json_decode($json);

                    //for($i = 0; $i < count($location_arr->rows[0]->elements); ++$i) {
                    //    if ($location_arr->rows[0]->elements[$i]->status == "OK") {
                    //        $arr[$i]['distance_text'] = $location_arr->rows[0]->elements[$i]->distance->text;
                    //        $arr[$i]['distance_in_meters'] = $location_arr->rows[0]->elements[$i]->distance->value;
                    //    }
                    //}

                }
            }

            $myJSON = json_encode($arr);
    }
    elseif ($_REQUEST['action'] == "swipe") {
        try {
            # Inset users to DB - Preventing SQL injection
            $stmt = $db->prepare('INSERT INTO user_swipes (id, swiped_id, created) VALUES (?, ?, NOW())');
            $stmt->execute([$_REQUEST['user_id'], $_REQUEST['taregetid']]);
            $last_id = $db->lastInsertId();

            $data_array = array('id' => $last_id);

            $myJSON = json_encode($data_array);
        } catch (Exception $e) {
            //SQL Error
            $erros[] = 'SQL Error: ' . $e->getMessage();
            $myJSON = json_encode($erros);
        }
    }
    elseif ($_REQUEST['action'] == "image") {
        try {
            # Inset users to DB - Preventing SQL injection
            $stmt = $db->prepare('UPDATE user_profile SET image_available="y" WHERE id=? LIMIT 1');
            $stmt->execute([$_REQUEST['user_id']]);
            $last_id = $db->lastInsertId();


            $data_array = array('id' => $last_id);

            $myJSON = json_encode($data_array);
        } catch (Exception $e) {
            //SQL Error
            $erros[] = 'SQL Error: ' . $e->getMessage();
            $myJSON = json_encode($erros);
        }
    }
}
else{
    $myJSON = json_encode(array('error'=> 'Invalid Request'));
}

// Closing DB connection
$db = null;

header('Content-type: application/json');

echo $myJSON;
?>
