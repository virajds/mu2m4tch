<?php
include_once '../db/db_connect.php';
include_once '../shared/authenticate.php';
include_once '../json/json_key.php';

// JSON key
$json_key = get_json_key();

if(isset($_COOKIE['login_token']) && isset($_COOKIE['login_key'])){
    $user_profile = validate_session($_COOKIE['login_token'],$_COOKIE['login_key']);

    //Get Profiles
    $jsonurl = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/json?action=profiles&key=".$json_key."&user_id=".$user_profile['id'];
    $json = file_get_contents($jsonurl);
    $profile_arr = json_decode($json);
}
else {
    header('Location: /login', TRUE, 302);
    exit;
}

include '../shared/header.php';
?>
        <div class="container" data-v-2d064d84>
        <link href="https://fonts.googleapis.com/css?family=DM+Serif+Display|Nunito&display=swap" rel="stylesheet" data-v-2d064d84>
        <div class="mx-auto col-12 col-lg-11" data-v-2d064d84>
            <section class="my-3 text-center text-lg-left" data-v-2d064d84>
                <h2 data-v-2d064d84>Muzmatch coding Test - Viraj De Silva</h2>
                <div class="top-button-block" data-v-2d064d84>
                    <a href="/user/gallery" class="main-button" data-v-2d064d84>Gallery</a>
                    <a href="#" onclick="LogOut()" class="main-button" data-v-2d064d84 id="buttonLogout">Logout</a>
                </div>
                <div data-v-2d064d84>
                    <p class="title-text mb-0" data-v-2d064d84>Your Profile</p>
                </div>
                <?php
                    $file_url = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/user/profile_images/".$user_profile['id'].".jpeg";

                    if ($user_profile['image_available'] == "y") {
                        echo '<p><img src="'.$file_url.'" height=100 width=100 alt="'.$user_profile['name'].'" title="'.$user_profile['name'].'"></p>';
                    }
                ?>
                <div class="top-button-block" data-v-2d064d84>
                    <p>Name: <?php echo $user_profile['name'] ?></p>
                    <p>Email: <?php echo $user_profile['email'] ?></p>
                    <p>Gender: <?php echo $user_profile['gender'] ?></p>
                    <p>Age: <?php echo $user_profile['age'] ?></p>
                </div>
                <div data-v-2d064d84>
                    <p class="title-text mb-0" data-v-2d064d84>Matched Profiles (When the male is maximum two years younger then female <strong>or</strong> female is always younger than male)</p>
                    <p><span style='font-size: 30px; color:gold'><strong>&#9734;</strong></span> (More than 10 Swipes), <span style='font-size: 30px; color:silver'><strong>&#9734;</strong></span> (Between 5-9 Swipes), <span style='font-size: 30px; color:#cd7f32'><strong>&#9734;</strong></span> (Between 1-4 Swipes)</p>
                </div>
                <div class="top-button-block" data-v-2d064d84>
                    <p>
                        <label>Age Filter: </label> <input type="text" id="live_range_val_age" readonly style="border:0; color:#ff006e; font-weight:bold;">
                    </p>
                    <div id="age_range" style="width:600px"></div>
                    <div style="padding-bottom: 20px;"></div>
                    <p>
                        <label>Distance Filter: </label> <input type="text" id="live_range_val_distance" readonly style="border:0; color:#ff006e; font-weight:bold;">
                    </p>
                    <div id="distance_range" style="width:600px"></div>
                    <div style="padding-bottom: 20px;"></div>
                    <p>
                        <label>Rating Filter: </label> <input type="text" id="live_range_val_rating" readonly style="border:0; color:#ff006e; font-weight:bold;padding-bottom: 0px;">
                    </p>
                    <div id="rating_range" style="width:600px"></div>
                    <div style="padding-bottom: 20px;"></div>

                    <table width="100%" id="profileTable">
                        <thead>
                            <tr>
                                <th></th><th>Name</th><th>age</th><th>email (Only shown when accepted)</th><th>Distance (miles)</th><th>Date Registered</th><th>Rating</th><th>Swiped/Swipe</th><th>Accepted</th><th style="display:none;"></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            for($i = 0; $i < count($profile_arr); ++$i) {
                                $imageHTML = "";
                                $profileEmail = "";
                                $starRating = "";
                                $swipeHtml = "";
                                $acceptedHtml = "";

                                if ($profile_arr[$i]->image_available == "y"){
                                    $imageHTML = '<img src="'.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/user/profile_images/'.$profile_arr[$i]->id.'.jpeg" height=50 width=50 alt="'.$profile_arr[$i]->name.'" title="'.$profile_arr[$i]->name.'">';
                                }

                                if ($profile_arr[$i]->swiped_count >= 10) {
                                    $starRating = "<p style='color:gold'><span style='font-size: 30px'><strong>&#9734;</strong></span> (Gold)</p>";
                                }
                                elseif ($profile_arr[$i]->swiped_count < 10 && $profile_arr[$i]->swiped_count >= 5) {
                                    $starRating = "<p style='color:silver'><span style='font-size: 30px'><strong>&#9734;</strong></span> (Silver)</p>";
                                }
                                elseif ($profile_arr[$i]->swiped_count < 5 && $profile_arr[$i]->swiped_count >= 1) {
                                    $starRating = "<p style='color:#cd7f32'><span style='font-size: 30px'><strong>&#9734;</strong></span> (Bronz)</p>";
                                }

                                if ($profile_arr[$i]->accepted == "y") {
                                    $profileEmail = $profile_arr[$i]->email;
                                    $acceptedHtml = '<img src="https://img.apksum.com/dc/com.muzmatch.muzmatchapp/5.2.3a/icon.png" height="50" width="50">';
                                }

                                if ($profile_arr[$i]->swiped == "y") {
                                    $swipeHtml = '<img src="https://img.apksum.com/dc/com.muzmatch.muzmatchapp/5.2.3a/icon.png" height="50" width="50">';
                                }
                                else {
                                    $swipeHtml = '<div class="top-button-block" data-v-2d064d84><a href="/swipe?target='.$profile_arr[$i]->id.'" class="main-button" data-v-2d064d84>Swipe</a></div>';
                                }

                                echo('<tr><td>'.$imageHTML.'</td><td>'.$profile_arr[$i]->name.'</td><td>'.$profile_arr[$i]->age.'</td><td>'.$profileEmail.'</td><td>'.$profile_arr[$i]->distance_miles.'</td><td>'.substr ( $profile_arr[$i]->created ,0, 10).'</td><td>'.$starRating.'</td><td>'.$swipeHtml.'</td><td>'.$acceptedHtml.'</td><td style="display:none;">'.$profile_arr[$i]->swiped_count.'</td></tr>');
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="row" data-v-2d064d84>
                    <div class="col-12 col-lg-6" data-v-2d064d84>
                        <div class="header-intro" data-v-2d064d84>
                            <div class="top-button-block" data-v-2d064d84><a href="/" class="main-button" data-v-2d064d84>Back to Home</a></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
<?php include '../shared/footer.php'; ?>