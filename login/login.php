<?php
include_once '../db/db_connect.php';
include_once '../shared/authenticate.php';
include_once '../json/json_key.php';

// JSON key
$json_key = get_json_key();

if(isset($_COOKIE['login_token']) && isset($_COOKIE['login_key'])){
    $user_profile = validate_session($_COOKIE['login_token'],$_COOKIE['login_key']);

    if (array_key_exists('id', $user_profile ))
    {
        header('Location: /profiles', TRUE, 302);
        exit;
    }
}

include '../shared/header.php';
?>
    <div class="container" data-v-2d064d84>
        <link href="https://fonts.googleapis.com/css?family=DM+Serif+Display|Nunito&display=swap" rel="stylesheet" data-v-2d064d84>
        <div class="mx-auto col-12 col-lg-11" data-v-2d064d84>
            <section class="my-3 text-center text-lg-left" data-v-2d064d84>
                <div class="row" data-v-2d064d84>
                    <div class="header-intro" data-v-2d064d84>
                        <h2 data-v-2d064d84>Muzmatch coding Test - Viraj De Silva</h2>
                        <div class="top-button-block" data-v-2d064d84>
                            <a href="/" class="main-button" data-v-2d064d84>Back to Home</a>
                        </div>
                        <div data-v-2d064d84>
                            <p class="title-text mb-0" data-v-2d064d84>User Login</p>
                        </div>
                        <div id="errors" style="color:red;font-weight: bold;"></div>
                        <form action="/login" method="post">
                        <div class="top-button-block" data-v-2d064d84>
                            <p>Email: <input type="text" name="email" placeholder="Email" id="email" required></p>
                            <p>Password: <input type="password" name="password" placeholder="Password" id="password" required></p>
                        </div>
                        <a href="#" onclick="validateInputs('<?php echo $json_key ?>')" class="main-button" data-v-2d064d84>Login</a>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
<?php include '../shared/footer.php'; ?>