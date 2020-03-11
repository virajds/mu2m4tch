<?php
$error =  "";
$currentDir = getcwd();
$uploadDirectory = "/../profile_images/";

$errors = []; // Store all foreseen and unforseen errors here

$fileExtensions = ['jpeg']; // Get all the file extensions

$fileName = $_FILES['myfile']['name'];
$fileSize = $_FILES['myfile']['size'];
$fileTmpName  = $_FILES['myfile']['tmp_name'];
$fileType = $_FILES['myfile']['type'];
$fileExtension = strtolower(end(explode('.',$fileName)));

$uploadPath = $currentDir . $uploadDirectory . $user_profile['id'].".jpeg";

if (isset($_POST['submit'])) {

    if (! in_array($fileExtension,$fileExtensions)) {
        $errors[] = "<p style='color: red'>This file extension is not allowed. Please upload a file with JPEG file extension</p>";
    }

    if ($fileSize > 500000) {
        $errors[] = "<p style='color: red'>This file is more than 500KB. Sorry, it has to be less than or equal to 500KB</p>";
    }

    if (empty($errors)) {
        $size = getimagesize($fileTmpName);

        $didUpload = false;

        if ($size[0] != 100 OR $size[1] !=100)
        {
            echo "<p style='color: red'>Invalid file dimensions. File must be 100x100 pixels.</p>";
            unlink($fileTmpName);
        }
        else {
            $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
        }

        if ($didUpload) {
            //Update Image record
            $jsonurl = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/json?action=image&key=".$json_key."&user_id=".$user_profile['id'];
            $json = file_get_contents($jsonurl);
            $image_arr = json_decode($json);

            $user_profile['image_available'] = "y";

            echo "<p style='color: green'>The file " . basename($fileName) . " has been uploaded</p>";
        } else {
            echo "<p style='color: red'>An error occurred somewhere. Try again or contact the admin</p>";
        }
    } else {
        foreach ($errors as $error) {
            echo $error . "<p style='color: red'>Errors found: </p>" . "\n";
        }
    }
}
?>