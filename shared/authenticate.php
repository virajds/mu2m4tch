<?php
// MAin Auth function

function validate_session($login_token, $login_key) {
    // Check DB for Urr and validte Key
    $database = new Database();
    $db = $database->getConnection();

    # Get Login infor from DB
    $stmt = $db->prepare('SELECT * FROM user_profile WHERE password = ? LIMIT 1');
    $found = $stmt->execute([$login_token]);
    $arr = $stmt->fetch(PDO::FETCH_ASSOC);

    // Valid results found
    if ($arr['password']){
        $db_key = hash('sha256', $arr['password']."mu2m@tch".date("Y-m-d"));

        if ($db_key == $login_key){
            //Refresh sessions
            setcookie("login_token", $login_token, time() + 3600, "/");
            setcookie("login_key", $login_key, time() + 3600, "/");
            // Closing DB connection
            $db = null;
            return $arr;
        }
        else {
            // Closing DB connection
            $db = null;
            header('Location: /login', TRUE, 302);
            exit;
        }
    }
    else {
        // Closing DB connection
        $db = null;
        header('Location: /login', TRUE, 302);
        exit;
    }

    // Closing DB connection
    $db = null;
}

?>