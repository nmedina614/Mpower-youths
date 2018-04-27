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
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "File already exists";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

    }
}
