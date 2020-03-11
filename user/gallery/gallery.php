<?php
include_once '../../shared/authenticate.php';
include_once '../../db/db_connect.php';
include_once '../../json/json_key.php';
// JSON key
$json_key = get_json_key();

if(isset($_COOKIE['login_token']) && isset($_COOKIE['login_key'])){
    $user_profile = validate_session($_COOKIE['login_token'],$_COOKIE['login_key']);
}
else {
    header('Location: /login', TRUE, 302);
    exit;
}

include '../../shared/header.php';
?>
    <!-- Profiles JSON/Read Sessions -->
    <script type = "text/javascript" language="javascript">
        $(document).ready(function() {

        });
    </script>

    <div class="container" data-v-2d064d84>
        <link href="https://fonts.googleapis.com/css?family=DM+Serif+Display|Nunito&display=swap" rel="stylesheet" data-v-2d064d84>
        <div class="mx-auto col-12 col-lg-11" data-v-2d064d84>
            <section class="my-3 text-center text-lg-left" data-v-2d064d84>
                <h2 data-v-2d064d84>Muzmatch coding Test - Viraj De Silva</h2>
                <div class="top-button-block" data-v-2d064d84>
                    <a href="/profiles" class="main-button" data-v-2d064d84>Profiles</a>
                    <a href="#" onclick="LogOut()" class="main-button" data-v-2d064d84 id="buttonLogout">Logout</a>
                </div>
                <div data-v-2d064d84>
                    <p class="title-text mb-0" data-v-2d064d84>Gallery</p>
                </div>
                <div class="top-button-block" data-v-2d064d84>
                    <?php
                        include_once '../../shared/image_upload.php';

                        $file_url = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/user/profile_images/".$user_profile['id'].".jpeg";

                        if ($user_profile['image_available'] == "y") {
                            echo '<p><img src="'.$file_url.'" height=100 width=100 alt="'.$user_profile['name'].'" title="'.$user_profile['name'].'"></p>';
                        }
                    ?>
                    <p>Name: <?php echo $user_profile['name'] ?></p>
                    <p>Email: <?php echo $user_profile['email'] ?></p>
                    <p>Gender: <?php echo $user_profile['gender'] ?></p>
                    <p>Age: <?php echo $user_profile['age'] ?></p>
                </div>
                <div data-v-2d064d84>
                    <p class="title-text mb-0" data-v-2d064d84>Please update your profile image below</p>
                </div>
                <div class="top-button-block" data-v-2d064d84>
                    <form action="/user/gallery/" method="post" enctype="multipart/form-data">
                        <input type="file" class="main-button" data-v-2d064d84 name="myfile" id="fileToUpload">
                        <input type="submit" class="main-button" data-v-2d064d84 name="submit" value="Upload File Now" >
                    </form>
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
<?php include '../../shared/footer.php'; ?>