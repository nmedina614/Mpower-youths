<?php

// index.php -> routes.php -> THIS -> database/messenger/validator -> views

/**
 * Class used to perform business logic operations
 * and data manipulation.
 *
 * @author Aaron Melhaff
 * @author Scott Medlock
 * @author Kyle Johnson
 * @author Nolan Medina
 *
 * @since 4/30/2018
 */
class Logic
{

    /**
     * Method used to process information regarding
     * gallery images.
     *
     * @return mixed Returns the images from the galler.
     */
    public static function getGalleryImages()
    {
        Database::connect();

        $images = Database::pullGalleryImages();

        return $images;

    }

    /**
     * Method used to process event information.
     *
     * @return array Returns an array if event information.
     */
    public static function getEvents()
    {
        $result = array();

        Database::connect();
        $resultDB = Database::getAllEvents();

        foreach ($resultDB as $key => $value) {
            array_push($result, new Event($value['idevent'], $value['title'], $value['description'], $value['dateFormatted']));
        }

        return $result;
    }

    public static function updateEvent($event)
    {

        Database::connect();
        return Database::updateEvent($event->getTitle(), $event->getDescription(), $event->getDate(), $event->getId());
    }

    /**
     * Method used to process staff information.
     *
     * @return array Returns an array of staff information.
     */
    public static function getAllStaff()
    {
        $result = array();

        Database::connect();
        $resultDB = Database::getAllStaff();

        foreach ($resultDB as $key => $value) {
            array_push($result,
                new StaffMember($value['idstaff'], $value['fname'],
                    $value['lname'], $value['title'], $value['biography'],
                    $value['email'], $value['phone'], $value['portraitURL']));
        }

        return $result;
    }

    public static function addStaffMember($staffMember)
    {

        // TODO add validation before sending to database

        Database::connect();
        return Database::addStaffMember($staffMember->getFName(), $staffMember->getLName(),
            $staffMember->getTitle(), $staffMember->getBiography(), $staffMember->getEmail(),
            $staffMember->getPhone(), $staffMember->getPortraitURL());
    }

    public static function updateStaffMember($staffMember)
    {

        // TODO add validation before sending to database

        Database::connect();
        return Database::updateStaffMember($staffMember->getID(), $staffMember->getFName(),
            $staffMember->getLName(), $staffMember->getTitle(), $staffMember->getBiography(),
            $staffMember->getEmail(), $staffMember->getPhone(), $staffMember->getPortraitURL());
    }

    /**
     * Method used to process and submit a new image
     * to the server.
     *
     * @param $file File being processed.
     * @param $folder the folder within the image folder to add the image to
     *
     * @return String Returns the result of the submission as a string.
     */
    public static function submitImageToFolder($file, $folder)
    {
        $targetDir  = 'assets/images/' . $folder . '/';
        $targetFile = $targetDir . basename($file["name"]);
        $extension  = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
        $newName    = self::randomString(60) . ".$extension";
        $newFile    = $targetDir . $newName;


        if (Validator::validFileSize($file['size'])) {
            return "File is too large.";
        }

        // Check if file already exists
        while (file_exists($newFile)) {
            $newName    = self::randomString(60) . ".$extension";
            $newFile    = $targetDir . $newName;
        }

        // Allow certain file formats
        if (!Validator::validImageFile($targetFile)) {
            return "Only JPG, JPEG, PNG & GIF files are allowed.";
        }

        if (move_uploaded_file($file["tmp_name"], $newFile)) {
            //Database::connect();

            //Database::insertGalleryImage($newName, $caption);

            return $newFile;


        } else {
            return null;
        }

    }

    /**
     * Method used to check if a user is an administrator.
     *
     * @param $username String username being evaluated.
     * @param $password String password being evaluated.
     * @return bool Returns true if the user is an admin.
     */
    public static function adminLogin($username, $password)
    {

        session_reset();

        if (empty($username) || empty($_POST['password'])) {
            return false;
        }

        Database::connect();

        $result = Database::login($username, $password, 1);

        if (isset($result['username'])) {
            // Store user information in Session.
            $account = new Admin(
                $result['idaccount'],
                $result['username'],
                $result['password'],
                $result['email'],
                $result['phone'],
                $result['privilege']
            );
            $_SESSION['account'] = serialize($account);

            return true;
        } else return false;
    }

    /**
     * Method used to check if a user has a valid account.
     *
     * @param $username String username being evaluated.
     * @param $password String password being evaluated.
     * @return bool Returns true if the user is an admin.
     */
    public static function login($username, $password)
    {

        session_reset();

        if (empty($username) || empty($_POST['password'])) {
            return false;
        }

        Database::connect();

        $result = Database::login($username, $password, 0);

        if (isset($result['username'])) {
            // Store user information in Session.
            $account = new Account(
                $result['idaccount'],
                $result['username'],
                $result['password'],
                $result['email'],
                $result['phone'],
                $result['privilege']
            );
            $_SESSION['account'] = serialize($account);

            return true;
        } else return false;
    }



    /**
     * Method used to process and submit a new image
     * to the server.
     *
     * @param $file File being processed.
     * @param $captions String caption to go along with the file.
     * @param $folder the folder within the image folder to add the image to
     *
     * @return String Returns the result of the submission as a string.
     */
    public static function submitNewImage($file, $caption, $folder)
    {
        $targetDir  = 'assets/images/' . $folder . '/';
        $targetFile = $targetDir . basename($file["name"]);
        $extension  = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
        $newName    = self::randomString(60) . ".$extension";
        $newFile    = $targetDir . $newName;


        if (Validator::validFileSize($file['size'])) {
            return "File is too large.";
        }

        // Check if file already exists
        while (file_exists($newFile)) {
            $newName    = self::randomString(60) . ".$extension";
            $newFile    = $targetDir . $newName;
        }

        // Allow certain file formats
        if (!Validator::validImageFile($targetFile)) {
            return "Only JPG, JPEG, PNG & GIF files are allowed.";
        }

        if (move_uploaded_file($file["tmp_name"], $newFile)) {
            Database::connect();

            Database::insertGalleryImage($newName, $caption);

            return true;


        } else {
            return "There was an error uploading your file.";
        }

    }

    /**
     * Method that handles image deletion from the gallery.
     *
     * @param $image takes a string filename of the file to be removed.
     */
    public static function deleteGalleryImage($image)
    {
        $targetFile = 'assets/images/gallery/' . $image;

        if (file_exists($targetFile)) {
            try {
                unlink($targetFile);
                Database::connect();
                Database::deleteGalleryImage($image);
                echo json_encode(true);
            } catch (Exception $e) {
                echo json_encode($e->getMessage());
            }
        } else {
            echo json_encode("File not found!");
        }
    }

    public static function updateAccount($account){
        if($account instanceof account) {
            Database::connect();

            $activeAccData = Logic::accountData($GLOBALS['f3']->get('username'));
            $id = $account->getId();

            if(strlen($account->getUsername()) == 0){
                $username = $activeAccData->getUsername();
            }else{
                $username = $account->getUsername();
            }

            if(strlen($account->getPassword()) == 0){
                $password = NULL;
            }else{
                $password = $account->getPassword();
            }

            if(strlen($account->getEmail()) == 0){
                $email = $activeAccData->getEmail();
            }else{
                $email = $account->getEmail();
            }

            if(strlen($account->getPhone()) == 0){
                $phone = $activeAccData->getPhone();
            }else{
                $phone = $account->getPhone();
            }

            if(is_null($password)){
                $result = Database::updateAccountWithoutPwd($id, $username, $email, $phone);
            }else{
                $result = Database::updateAccount($id, $username, $password, $email, $phone);
            }

            return $result;
        }
    }


    /**
     * Method that generates a string of random characters.
     *
     * @param int $length Integer length of the generated string (10 by default)
     * @return string Returns a string of random characters.
     */
    public static function randomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function accountData($username)
    {
        Database::connect();
        $res = Database::getAccountByUsername($username);
        return new Account($res['idaccount'], $res['username'], NULL, $res['email'], $res['phone'], $res['privilege']);
    }

    public static function addEvent($event)
    {
        if($event instanceof Event) {
            Database::connect();
            return Database::addEvent($event->getTitle(), $event->getDescription(), $event->getDate());
        }
    }
}
