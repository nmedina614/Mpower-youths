<?php

// index.php -> routes.php -> THIS -> database/messenger/validator -> views

/**
 * TODO
 *
 * User: scottmedlock
 * Date: 4/13/18
 * Time: 12:59 PM
 */
class Logic {

    /**
     * TODO
     *
     * @return mixed
     */
    public static function getGalleryImages() {
        Database::connect();

        $images = Database::pullGalleryImages();

        return $images;

        return $images;
    }

    /**
     * TODO
     *
     * @return array
     */
    public static function getEvents() {
        $result = array();

        Database::connect();
        $resultDB = Database::getAllEvents();

        foreach ($resultDB as $key => $value){
            array_push($result, new Event($value['idevent'], $value['title'], $value['description'], $value['date']));
        }

        return $result;
    }

    /**
     * TODO
     *
     * @return array
     */
    public static function getAllStaff() {
        $result = array();

        Database::connect();
        $resultDB = Database::getAllStaff();

        foreach ($resultDB as $key => $value){
            array_push($result,
                new StaffMember($value['idstaff'], $value['fname'], $value['lname'],
                    $value['title'], $value['biography'], $value['email'],
                    $value['phone'], $value['portraitURL']));
        }

        return $result;
    }

    /**
     * TODO
     *
     * @param $username
     * @param $password
     * @return bool
     */
    public static function adminLogin($username, $password)
    {

        session_reset();

        if(empty($username) || empty($_POST['password'])) {
            return false;
        }

        Database::connect();

        $result = Database::checkCredentials($username, $password);

        if(isset($result['username'])) {
            // Store user information in Session.
            $_SESSION['username'] = $result['username'];

            return true;
        } else return false;

    }

    /**
     * TODO
     *
     * @param $file
     * @param $captions
     */
    public static function submitNewImage($file, $captions)
    {
        var_dump($file);

        $target_dir = 'assets/images/gallery';
        $target_file = $target_dir . basename($file["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        $check = getimagesize($file["tmp_name"]);
        if($check !== false) {
            return "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            return "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "File already exists";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if(Validator::validImageFile($imageFileType)) {
            echo "Only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            return "Your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                return "The file ". basename( $file["name"]). " has been uploaded.";
            } else {
                return "There was an error uploading your file.";
            }
        }

    }
}
