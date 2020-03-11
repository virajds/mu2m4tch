<?php
include_once '../db/db_connect.php';
include_once '../shared/authenticate.php';
include_once '../json/json_key.php';

// JSON key
$json_key = get_json_key();

if(isset($_COOKIE['login_token']) && isset($_COOKIE['login_key'])){
    $user_profile = validate_session($_COOKIE['login_token'],$_COOKIE['login_key']);
}
else {
    header('Location: /login', TRUE, 302);
    exit;
}

include '../shared/header.php';
?>
    <!-- Profiles JSON/Read Sessions -->
    <script type = "text/javascript" language="javascript">
        $(document).ready(function() {
            swipeUser('<?php echo $json_key ?>','<?php echo $user_profile['id'] ?>','<?php echo $_REQUEST['target'] ?>');
        });
    </script>

    <div class="container" data-v-2d064d84>
        <link href="https://fonts.googleapis.com/css?family=DM+Serif+Display|Nunito&display=swap" rel="stylesheet" data-v-2d064d84>
        <div class="mx-auto col-12 col-lg-11" data-v-2d064d84>
            <section class="my-3 text-center text-lg-left" data-v-2d064d84>
                <h2 data-v-2d064d84>Muzmatch coding Test - Viraj De Silva</h2>
                <div class="top-button-block" data-v-2d064d84>
                    <a href="/profiles" class="main-button" data-v-2d064d84>Profiles</a>
                    <a href="/user/gallery" class="main-button" data-v-2d064d84>Gallery</a>
                    <a href="#" onclick="LogOut()" class="main-button" data-v-2d064d84 id="buttonLogout">Logout</a>
                </div>
                <div data-v-2d064d84>
                    <p class="title-text mb-0" id="swipeRes" data-v-2d064d84></p>
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