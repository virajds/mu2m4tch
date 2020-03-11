<?php
// Get JSON key

function get_json_key() {
    //JSON key is bound to a keyword and date

    return hash('sha256', "mu2m@tch".date("Y-m-d"));
}
?>