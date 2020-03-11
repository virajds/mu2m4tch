<?php

// Main Random Data Function

function generate_random_data($username_length) {
    // Chars to be in username and email
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $tld = array("co.uk", "org", "com", "net", "biz");
    $dom = array("example","gmail", "hotmail", "aol", "yahoo", "mail");
    $gender = array("m", "f");
    $generated_user = array();
    $randomName = "";

    for($j=0; $j<$username_length; $j++){
        $randomName .= $characters[rand(0, strlen($characters) -1)];
    }
    // Push Neme - No surname considered
    $generated_user[] = $randomName;
    // Push Gender
    $l = array_rand($gender);
    $generated_user[] = $gender[$l];
    // Push email
    $k = array_rand($tld);
    $m = array_rand($dom);
    $generated_user[] = $randomName. "@" .$dom[$m].".".$tld[$k];
    // Push age between 18-60
    $generated_user[] = rand(18,60);
    // Push password
    $generated_user[] = generate_password(8);
    // Push Geo Location between Latitude 51.4552307(bristol)/55.8651505(Glasgow) AND Longitude -4.2576299(Glasgow)/-0.10304(Islington)
    $generated_user[] = strval( rand(514552307,558651505)/10000000).",".strval( rand(-42576299,-1030400)/10000000);

    // retrun array [Name,Gender,Email,Age,Password]
    return($generated_user);
}

// Generate Random password
function generate_password($length = 8){
    // PWD Chars
    $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.
        '0123456789`-=~!@#$%^&*()_+,./<>?;:[]{}\|';

    $str = '';
    $max = strlen($chars) - 1;

    for ($i=0; $i < $length; $i++)
        $str .= $chars[random_int(0, $max)];

    return $str;
}

?>