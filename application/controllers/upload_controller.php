<?php

/**
 * Created by PhpStorm.
 * User: Gold Service
 * Date: 18/05/2017
 * Time: 12:32
 */
require_once ABS_PATH.'/application/models/User.php';

class UploadController
{
    public static function profile_image() {
        $user = User::get_user($_POST['id']);
        $target_dir = ABS_PATH."/uploads/image-profiles/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        $target_file = $target_dir . basename($user->generateFileName().'.'.$imageFileType);
        //$uploadOk = 1;
        //$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        //header('location: profile');
        // Check if image file is a actual image or fake image
        /*if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["file"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }*/
        array_map('unlink', glob($target_dir.$user->generateFileName().".*"));
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
            header('location: profile');
        } else {
            echo "Errore nel caricamento del file";
        }
    }
}