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

    /**
     * Sends an event to the database to be updated.
     *
     * @param $event the event to update
     * @return mixed the result of the update query
     */
    public static function updateEvent($event)
    {

        Database::connect();
        return Database::updateEvent($event->getTitle(), $event->getDescription(), $event->getDate(), $event->getId());
    }

    public static function deleteEvent($idevent)
    {

        Database::connect();
        $result = Database::deleteEvent($idevent);
        echo json_encode($result);
        return $result;

    }

    /**
     * Method used to get information of all of the staff.
     *
     * @return array Returns an array of staff information.
     */
    public static function getAllStaff()
    {
        $result = array();

        Database::connect();
        $resultDB = Database::getAllStaff('staff');

        foreach ($resultDB as $key => $value) {
            array_push($result,
                new StaffMember($value['idstaff'], $value['fname'],
                    $value['lname'], $value['title'], $value['biography'],
                    $value['email'], $value['phone'], $value['portraitURL']));
        }

        return $result;
    }

    /**
     * Validates and sends a staffmember to the database to be added
     *
     * @param $staffMember the staffmember to add
     * @return mixed the result of the query
     */
    public static function addStaffMember($staffMember)
    {

        // TODO add validation before sending to database

        Database::connect();
        return Database::addStaffMember($staffMember->getFName(), $staffMember->getLName(),
            $staffMember->getTitle(), $staffMember->getBiography(), $staffMember->getEmail(),
            $staffMember->getPhone(), $staffMember->getPortraitURL(), 'staff');
    }

    /**
     * Validates and sends a staffmember to the database to be updated
     *
     * @param $staffMember the staffmember to update
     * @return mixed the result of the query
     */
    public static function updateStaffMember($staffMember)
    {

        // TODO add validation before sending to database

        Database::connect();
        return Database::updateStaffMember($staffMember->getID(), $staffMember->getFName(),
            $staffMember->getLName(), $staffMember->getTitle(), $staffMember->getBiography(),
            $staffMember->getEmail(), $staffMember->getPhone(), $staffMember->getPortraitURL(), 'staff', 'idstaff');
    }

    /**
     * Deletes a staff member from the database
     *
     * @param $idstaff the id of the staff member to delete
     */
    public static function deleteMember($id, $memberType, $idColumnName)
    {
        Database::connect();
        $image = Database::getPortraitUrl($memberType, $idColumnName, $id);

        $result = Database::deleteStaffMember($memberType, $idColumnName, $id);

        if ($result) {
            deleteImage($image, 'staffportraits');
        }
        echo json_encode(true);
        return result;
    }

    /**
     * Method used to get information of all members of the board of directors.
     *
     * @return array Returns an array of board of directors information.
     */
    public static function getAllBOD()
    {
        $result = array();

        Database::connect();
        $resultDB = Database::getAllStaff('board_of_directors');

        foreach ($resultDB as $key => $value) {
            array_push($result,
                new StaffMember($value['idbod'], $value['fname'],
                    $value['lname'], $value['title'], $value['biography'],
                    $value['email'], $value['phone'], $value['portraitURL']));
        }

        return $result;
    }

    /**
     * Validates and sends a member of the board of directors to the database to be added
     *
     * @param $BODMember the member of the board of directors to add
     * @return mixed the result of the query
     */
    public static function addBODMember($BODMember)
    {

        // TODO add validation before sending to database

        Database::connect();
        return Database::addStaffMember($BODMember->getFName(), $BODMember->getLName(),
            $BODMember->getTitle(), $BODMember->getBiography(), $BODMember->getEmail(),
            $BODMember->getPhone(), $BODMember->getPortraitURL(), 'board_of_directors');
    }

    /**
     * Validates and sends a member of the board of directors to the database to be updated
     *
     * @param $BODMember the member of the board of directors to update
     * @return mixed the result of the query
     */
    public static function updateBODMember($BODMember)
    {

        // TODO add validation before sending to database

        Database::connect();
        return Database::updateStaffMember($BODMember->getID(), $BODMember->getFName(),
            $BODMember->getLName(), $BODMember->getTitle(), $BODMember->getBiography(),
            $BODMember->getEmail(), $BODMember->getPhone(), $BODMember->getPortraitURL(),
            'board_of_directors', 'idbod');
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
            // Response for normal login
            if($result['privilege'] == 0) {
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
            }

            // Response for admin login.
            if ($result['privilege'] == 1) {
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
     * Method that handles image deletion from a folder.
     *
     * @param $image takes a string filename of the file to be removed.
     * @param $folder takes a string folder name of the folder that contains the image.
     */
    public static function deleteImage($image, $folder)
    {
        $targetFile = 'assets/images/'. $folder . '/' . $image;

        if (file_exists($targetFile)) {
            try {
                unlink($targetFile);
                if ($folder === 'gallery') {
                    Database::connect();
                    Database::deleteGalleryImage($image);
                    echo json_encode(true);
                }
            } catch (Exception $e) {
                echo json_encode($e->getMessage());
            }
        } else {
            echo json_encode("File not found!");
        }
    }

    public static function updateAccount($account)
    {
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

    /**
     * Method for registering a new user.
     *
     * Takes input data from POST array.
     * If the fields are valid, it adds
     * them to the database. If not,
     * it returns an array of strings
     * containing reasons for failure.
     *
     * @return array Returns an array of strings containing reasons for failure.
     */
    public static function register($username, $password1, $password2, $email, $phone)
    {

        $errors = array();
        if(!Validator::validateAccount($username)) $errors['account'] = "Please enter username under 45 letters with letters and numbers";
        if($password1 != $password2) $errors['password'] = "Please enter matching passwords";
        if(!Validator::validateEmail($email)) $errors['email'] = "Please a valid email";
        if(!Validator::validatePhone($phone)) $errors['phone'] = "Please valid phone number";

        if(count($errors) == 0) {
            Database::connect();

            // Launch Query.
            $success = Database::insertAccount($username, $password1, $email, $phone);

            // If the user is inserted, use the new users id to create a verification hash
            // and store it in the db.
            if($success) {
                $hash = hash('sha256', self::randomString());

                $result = Database::insertVerification($hash);

                // If the hash is stored successfully, send an email with the hash.
                if($result) {
                    Messenger::sendMessage($email, 'Account verification',
                        'Thank you for signing up with M-Power Music!
                        in order to activate your account, please open the following link. 
                        ' . ($_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']) ."/verify/$hash"
                    );
                }
            } else {
                $errors[] = 'Username or email is already in use.';
            }
        }

        // Return list of invalid inputs.
        return $errors;



    }

    public static function verifyAccount($hash)
    {
        Database::connect();
        return Database::verifyAccount($hash);
    }

    public static function addEvent($event)
    {
        if($event instanceof Event) {
            Database::connect();
            return Database::addEvent($event->getTitle(), $event->getDescription(), $event->getDate());
        }
    }

    /**
     * Method used to create a notification in the database.
     * Example types include 'rental', 'application', 'notification'.
     *
     * @param $type String containing the type of notification ('notification' by default)
     * @return int Returns the id of the new notification as an int.
     */
    public static function createNotification($type='notification') {

    }
}
