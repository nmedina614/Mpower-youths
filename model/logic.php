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
     * Passes the form data into database query
     *
     * @param data is a list of data for media release - student name, parent name
     * @return if the database submitted successfully
     */
    public static function insertMediaRelease($data)
    {
        Database::connect();

        $userId = unserialize($_SESSION['account'])->getId();
        return Database::insertMediaRelease($data, $userId);
    }

    /**
     * Passes the media release form data into database query
     *
     * @param data is a list of data for media release - [studentName],[school],[grade],[instrument],[parent],[email],
     * [phone],[street1],[street2],[city],[zip],[allergies],[referral],[decision],[takeHomeInstrument]
     * @return if the database submitted successfully
     */
    public static function insertEnrollment($data)
    {
        Database::connect();

        $userId = unserialize($_SESSION['account'])->getId();

        Database::createNotification('application');
        return Database::insertEnrollment($data, $userId);
    }

    /**
     * Method used to process event information.
     *
     * @return array Returns an array if event information.
     */
    public static function getUpcomingEvents()
    {
        $result = array();

        Database::connect();
        $resultDB = Database::getUpcomingEvents();

        foreach ($resultDB as $key => $value) {
            array_push($result, new Event($value['idevent'], $value['title'], $value['description'], $value['dateFormatted']));
        }

        return $result;
    }

    /**
     * Method used to process event information.
     *
     * @return array Returns an array if event information.
     */
    public static function getPastEvents()
    {
        $result = array();

        Database::connect();
        $resultDB = Database::getPastEvents();

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
    public static function getAllStaff($memberType, $idColumnName)
    {
        $result = array();

        Database::connect();
        $resultDB = Database::getAllStaff($memberType);

        foreach ($resultDB as $key => $value) {
            array_push($result,
                new StaffMember($value[$idColumnName], $value['fname'], $value['lname'],
                    $value['title'], $value['biography'], $value['email'],
                    $value['phone'], $value['portraitURL'], $value['pageOrder']));
        }

        return $result;
    }

    public static function getMaxPageOrder($memberType)
    {

        Database::connect();
        return Database::getMaxPageOrder($memberType)[0]['pageOrder'];
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
            $staffMember->getPhone(), $staffMember->getPortraitURL(), 'staff', $staffMember->getPageOrder());
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
     * @return true or false whether the deletion was successful
     */
    public static function deleteMember($id, $memberType, $idColumnName, $imageFolderName)
    {
        Database::connect();

        if ($memberType === 'carousel') {
            $image = Database::getPortraitURL($memberType, $idColumnName, $id, 'imageURL')[0]['imageURL'];
        } else {
            $image = Database::getPortraitUrl($memberType, $idColumnName, $id, 'portraitURL')[0]['portraitURL'];
        }

        $imageNameWithoutFolder = substr($image, strrpos($image, '/') + 1);

        $result = Database::deleteStaffMember($memberType, $idColumnName, $id);

        if ($result) {
            Logic::deleteImage($imageNameWithoutFolder, $imageFolderName);
        }
        echo json_encode($result);
        return result;
    }

    /**
     * Shifts a member up or down on the page
     *
     * @param $id id of the member selected
     * @param $memberType the type of member
     * @param $idColumnName the id column name
     * @param $direction the direction to move the member, 'up' or 'down'
     */
    public static function shiftMember($id, $memberType, $idColumnName, $direction)
    {

        // get member array
        Database::connect();
        $memberArray = Logic::getAllStaff($memberType, $idColumnName);

        // check if there are enough members to swap
        if (sizeof($memberArray) < 2) {
            echo json_encode('not enough members to swap');
            return;
        }

        // get the index of the member in the member array
        $memberIndex = 0;
        while ($memberIndex < sizeof($memberArray) && $memberArray[$memberIndex]->getId() != $id) {
            $memberIndex++;
        }

        // if the member was not found in the member array
        if ($memberIndex == sizeof($memberArray)) {
            echo json_encode('did not find id in member list');
            return;
        }

        $swapIndex = 0;

        // select the swap index depending on shifting up or down
        if ($direction === 'up') {
            $swapIndex = $memberIndex - 1;
        } else if ($direction === 'down') {
            $swapIndex = $memberIndex + 1;
        }

        // if the swap index is in the array bounds, swap
        if ($swapIndex > -1 && $swapIndex < sizeof($memberArray)) {
            Database::swapMember($id, $memberArray[$swapIndex]->getId(), $memberArray[$memberIndex]->getPageOrder(),
                $memberArray[$swapIndex]->getPageOrder(), $memberType, $idColumnName);
            echo json_encode(true);
            return;
        }

        echo json_encode('value is already at the top or bottom');

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
                new StaffMember($value['idbod'], $value['fname'], $value['lname'],
                    $value['title'], $value['biography'], $value['email'],
                    $value['phone'], $value['portraitURL'], $value['pageOrder']));
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
        return Database::addStaffMember($BODMember->getFName(), $BODMember->getLName(), $BODMember->getTitle(),
            $BODMember->getBiography(), $BODMember->getEmail(), $BODMember->getPhone(),
            $BODMember->getPortraitURL(), 'board_of_directors', $BODMember->getPageOrder());
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
     * Method used to get carousel items from the database.
     *
     * @return array Returns an array of carousel information.
     */
    public static function getCarouselItems()
    {
        $result = array();

        Database::connect();
        $resultDB = Database::getCarouselItems($memberType);

        foreach ($resultDB as $key => $value) {
            array_push($result,
                new CarouselItem($value['idcarousel'], $value['header'], $value['paragraph'],
                    $value['imageURL'], $value['buttonLink'], $value['buttonText'], $value['pageOrder']));
        }

        return $result;
    }

    /**
     * Validates and sends a carousel item to the database to be added
     *
     * @param $carouselItem the carousel item to add
     * @return mixed the result of the query
     */
    public static function addCarouselItem($carouselItem)
    {

        // TODO add validation before sending to database

        Database::connect();
        return Database::addCarouselItem($carouselItem->getHeader(), $carouselItem->getParagraph(),
            $carouselItem->getImageURL(), $carouselItem->getButtonLink(),
            $carouselItem->getButtonText(), $carouselItem->getPageOrder());
    }

    /**
     * Validates and sends a carousel item to the database to be updated
     *
     * @param $carouselItem the carousel item to update
     * @return mixed the result of the query
     */
    public static function updateCarouselItem($carouselItem)
    {

        // TODO add validation before sending to database

        Database::connect();
        return Database::updateCarouselItem($carouselItem->getId(), $carouselItem->getHeader(),
            $carouselItem->getParagraph(), $carouselItem->getImageURL(), $carouselItem->getButtonLink(),
            $carouselItem->getButtonText());
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


        if (!Validator::validFileSize($file['size'])) {
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
                    $result['accountId'],
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
                    $result['accountId'],
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


        if (!Validator::validFileSize($file['size'])) {
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

    /**
     * Method that lets you delete notification from the database.
     *
     * @param $id Integer id of notification to delete.
     * @return boolean response of database query.
     */
    public static function deleteNotification($id)
    {
        Database::connect();
        return Database::deleteNotification($id);
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
        return new Account($res['accountId'], $res['username'], NULL, $res['email'], $res['phone'], $res['privilege']);
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

    /**
     * Method that verifies a hash associated with a certain account.
     *
     * @param $hash Hash being evaluated
     * @return mixed Returns if the account was verified.
     */
    public static function verifyAccount($hash)
    {
        Database::connect();
        $result = Database::verifyAccount($hash);
        if(isset($result)) {
            Database::createNotification('new user');
        }

        return $result;
    }

    public static function addEvent($event)
    {
        if($event instanceof Event) {
            Database::connect();
            return Database::addEvent($event->getTitle(), $event->getDescription(), $event->getDate());
        }
    }

    /**
     * Method pulls all notifications from the database
     * and returns them as an array.
     *
     * @return mixed Returns an array containing all rental requests.
     */
    public static function getNotifications()
    {
        Database::connect();

        $result = Database::getNotifications();

        return $result;
    }

    /**
     * Method pulls all notifications from the database
     * and returns them as an array.
     *
     * @return mixed Returns an array containing all rental requests.
     */
    public static function getApplications()
    {
        Database::connect();

        if(Validator::isAdmin()) $result = Database::getApplications();
        else {
            $account = unserialize($_SESSION['account']);
            $result = Database::getAccountApplications($account->getId());
        }

        return $result;
    }

    /**
     * Method pulls all notifications from the database
     * and returns them as an array.
     *
     * @return mixed Returns an array containing all rental requests.
     */
    public static function getVolunteers()
    {
        Database::connect();
        if(Validator::isAdmin()) $result = Database::getVolunteers();
        else {
            $account = unserialize($_SESSION['account']);
            $result = Database::getAccountVolunteers($account->getId());
        }

        return $result;
    }

    /**
     * Method pulls all notifications from the database
     * and returns them as an array.
     *
     * @return mixed Returns an array containing all rental requests.
     */
    public static function getAccounts()
    {
        Database::connect();

        $result = Database::getAccounts();

        return $result;
    }

    /**
     * Method pulls all rentals from the database
     * and returns them as an array.
     *
     * @return mixed Returns an array containing all rental requests.
     */
    public static function getRentalRequests()
    {
        Database::connect();

        if(Validator::isAdmin()) $result = Database::getInstrumentRentals();
        else {
            $account = unserialize($_SESSION['account']);
            $result = Database::getAccountInstrumentRentals($account->getId());
        }

        return $result;

    }

    /**
     * Loads the correct form based on which type of form is chosen
     *
     * @param $type string type of form that is too be loaded
     * @param $formId int FormID to ger the form of
     * @return mixed returns the information of the form
     */
    public static function getForm($type, $formId)
    {
        Database::connect();

        switch($type)
        {
            case 'enrollment':
                return Database::getFormApplication($formId);
            case 'volunteer':
                return Database::getFormVolunteer($formId);
            case 'rental':
                return Database::getFormInstrumentRental($formId);
            default:
                throw new InvalidArgumentException("Invalid type: $type");
        }

    }

    /**
     * Method used to convert the numeric value received into
     * a text string for ease of reading.
     *
     * @param $numericStatus int status from database.
     * @return string String representing the status of a request.
     */
    public static function translateRequestStatus($numericStatus)
    {
        switch($numericStatus)
        {
            case -1:
                return 'Declined';
            case 0:
                return 'Pending';
            case 1:
                return 'Accepted';
        }
    }

    /**
     *
     * Send a Request for an Instrument
     *
     * @param $student string Student Full Name
     * @param $guardian string Parent/Guardians Full Name
     * @param $add1 string Address 1 given
     * @param $add2 string Address 2 given
     * @param $city string City given
     * @param $zip int Zip Code given
     * @param $phone string Telephone Number given
     * @param $school string School of Student
     * @param $grade string Grade in which student is in.
     * @param $instrument string Instrument that the user is Requesting to rent.
     * @param $date mixed date of the instrument request
     * @return mixed true or false based on if statement executed correctly
     */
    public static function requestInstrument($accountId, $student, $guardian, $add1,
                                             $add2, $city, $zip, $phone, $school,
                                             $grade, $instrument, $date)
    {

        Database::connect();

        Database::createNotification('rental');

        return Database::requestInstrument($accountId, $student, $guardian, $add1,
                                           $add2, $city, $zip, $phone, $school,
                                           $grade, $instrument, $date);
    }


    /**
     * Send a Request to be a Volunteer
     *
     * @param $name string full legal name of volunteer
     * @param $address string address of volunteer
     * @param $zip int Zip Code for volunteer
     * @param $dob string date of birth of volunteer
     * @param $phone string Telephone Number for volunteer
     * @param $drivers string Drivers License # of volunteer
     * @param $dateRequested string The date being requested
     * @return mixed true or false based on if statement executed correctly
     */
    public static function volunteerRequest($accountId, $name, $address, $zip, $dob,
                                            $phone, $drivers, $dateRequested)
    {

        Database::connect();

        Database::createNotification('volunteer');

        return Database::volunteerRequest($accountId, $name, $address, $zip, $dob,
            $phone, $drivers, $dateRequested);
    }


    /**
     * Updates the volunteer form based on review from admin
     *
     * @param $submit int status being updated too, 1 being accepted, -1 being denied
     * @param $formId int ID of the form being changed
     * @return bool true or false based on if the statement executed
     */
    public static function updateVolunteer($submit, $formId)
    {
        Database::connect();

        return Database::updateVolunteerStatus($submit, $formId);
    }

    /**
     * Updates the Instrument Information and Status of the Form
     *
     * @param $serial int Model of Instrument
     * @param $contract int contract year of instrument
     * @param $make String Make of the Instrument
     * @param $model string Model of the instrument
     * @param $submit int status being updated too, 1 being accepted, -1 being denied
     * @param $formId int ID of the form being changed
     * @return bool true or false based on if the statement executed
     */
    public static function updateInstrument($serial, $contract, $make, $model, $submit, $formId)
    {
        Database::connect();

        return Database::updateInstrumentStatus($serial, $contract, $make, $model, $submit, $formId);
    }


    /**
     * Updates the enrollment status in the Database
     *
     * @param $submit int status being updated too, 1 being accepted, -1 being denied
     * @param $formId int ID of the form being changed
     * @return bool true or false based on if the statement executed
     */
    public static function updateEnrollment($submit, $formId)
    {
        Database::connect();

        return Database::updateEnrollmentStatus($submit, $formId);
    }

    /**
     * Gets the Email to notify that the status has been changed
     *
     * @param $id ID of the user that filled out the form to send them an email about the change of
     * status
     * @return mixed true or false based on if the statement executed
     */
    public static function getAccountEmail($id)
    {
        Database::connect();

        return Database::getAccountEmail($id);
    }


    /**
     * Gets the photo release forms for the admin to view
     *
     * @return array all the photo release forms that users have filled out
     */
    public static function getAdminRelease()
    {
        Database::connect();

        return Database::getRelease();
    }

    /**
     * Returns the Photo release forms that the user has filled out
     *
     * @param $accountId ID of the account getting the forms from.
     * @return array all the photo release forms that the user has filled out
     */
    public static function getAccountRelease($accountId)
    {
        Database::connect();

        return Database::getAccountRelease($accountId);
    }




}
