<?php

include_once '../../json/json_key.php';

// JSON key
$json_key = get_json_key();

include '../../shared/header.php';
?>
        <!-- Create User JSON -->
    <script type = "text/javascript" language="javascript">
        $(document).ready(function() {
            createUser('<?php echo $json_key ?>');
        });
    </script>
    <div class="container" data-v-2d064d84>
        <div class="mx-auto col-12 col-lg-11" data-v-2d064d84>
            <section class="my-3 text-center text-lg-left" data-v-2d064d84>
                <div class="col-12 col-lg-6" data-v-2d064d84>
                    <div class="header-intro" data-v-2d064d84>
                        <a id="main_section"></a>
                        <h2 data-v-2d064d84>Muzmatch coding Test - Viraj De Silva</h2>
                        <div class="top-button-block" data-v-2d064d84>
                            <a href="/user/create?#main_section" class="main-button" data-v-2d064d84>Refresh</a>
                            <a href="/" class="main-button" data-v-2d064d84>Back to Home</a>
                            <a href="/login" class="main-button" data-v-2d064d84>Login</a>
                        </div>
                        <div data-v-2d064d84>
                            <p class="title-text mb-0" data-v-2d064d84>A random User created</p>
                        </div>
                        <div class="top-button-block" data-v-2d064d84>
                            <div id = "userData">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

<?php

// Include Footer section
include '../../shared/footer.php';

?>