<?php

/**
 * Class used to perform database transactions.
 *
 * @author Aaron Melhaff
 * @author Scott Medlock
 * @author Kyle Johnson
 * @author Nolan Medina
 *
 * @since 4/30/2018
 */
require $_SERVER['DOCUMENT_ROOT'] . "/../config/bsharp-config.php";


class Database
{


    private static $_dbh;

    /**
     * Method used to establish a database connection.
     */
    public static function connect()
    {

        if(!isset($_dbh)) {
            try {
                // instantiate pdo object.
                self::$_dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }

    /**
     * Method used to check if a user is an admin.
     *
     * @param $username String username of user.
     * @param $password String password of User.
     * @param $minimumPrivilege int required privilege level.
     *
     * @return mixed Returns true or false if a matching user is found.
     */
    public static function login($username, $password, $minimumPrivilege)
    {

        // Prepare a select to check if db contains queried params.
        $sql = 'SELECT accountId, username, password, email, phone, privilege FROM account WHERE username=:username AND password=:password AND privilege>=:privilege';

        $statement = self::$_dbh->prepare($sql);

        $statement->bindParam(':username', $username, PDO::PARAM_STR);

        $statement->bindParam(':password', hash('sha256', $password, false), PDO::PARAM_STR);

        $statement->bindParam(':privilege', $minimumPrivilege, PDO::PARAM_INT);

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * Method used to pull all events from the Database.
     *
     * @return mixed Returns an associative array of results.
     */
    public static function getUpcomingEvents()
    {
        // Prepare a select to check if db contains queried params.
        $sql = 'SELECT idevent, title, description, DATE_FORMAT(`date`, "%m/%d/%Y") AS `dateFormatted`
                FROM event 
                WHERE `date` >= DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -1 DAY)
                ORDER BY `date`';

        $statement = self::$_dbh->prepare($sql);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * Method used to pull all events from the Database.
     *
     * @return mixed Returns an associative array of results.
     */
    public static function getPastEvents()
    {
        // Prepare a select to check if db contains queried params.
        $sql = 'SELECT idevent, title, description, DATE_FORMAT(`date`, "%m/%d/%Y") AS `dateFormatted`
                FROM event
                WHERE `date` <= DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -1 DAY)
                ORDER BY `date` DESC';

        $statement = self::$_dbh->prepare($sql);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * Method used to insert a media release form to database
     * @param form data for each element
     * @param account that is signed in
     * @return if the query executed successfully
     */
    public static function insertMediaRelease($formData, $accountId)
    {
        // Prepare a select to check if db contains queried params.
        $sql = 'INSERT INTO formMediaRelease (accountId, childName, parentName) VALUES (:accountId, :childName, :parentName)';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':accountId', $accountId, PDO::PARAM_STR);
        $statement->bindParam(':childName', $formData[0], PDO::PARAM_STR);
        $statement->bindParam(':parentName', $formData[1], PDO::PARAM_STR);

        return $statement->execute();
    }

    /**
     * Method used to insert a enrollment form to database
     * @param form data for each element
     * @param account that is signed in
     * @return if the query executed successfully
     */
    public static function insertEnrollment($formData, $accountId)
    {
        // Prepare a select to check if db contains queried params.
        $sql = 'INSERT INTO formEnrollment (accountId, studentName, school, grade, instrument, 
        parent, email, phone, street1, street2, city, zip, allergies, referral, decision,
        takeHomeInstrument) VALUES (:accountId, :studentName, :school, :grade, :instrument, 
        :parent, :email, :phone, :street1, :street2, :city, :zip, :allergies, :referral, :decision,
        :takeHomeInstrument)';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':accountId', $accountId, PDO::PARAM_STR);
        $statement->bindParam(':studentName', $formData[0], PDO::PARAM_STR);
        $statement->bindParam(':school', $formData[1], PDO::PARAM_STR);
        $statement->bindParam(':grade', $formData[2], PDO::PARAM_STR);
        $statement->bindParam(':instrument', $formData[3], PDO::PARAM_STR);
        $statement->bindParam(':parent', $formData[4], PDO::PARAM_STR);
        $statement->bindParam(':email', $formData[5], PDO::PARAM_STR);
        $statement->bindParam(':phone', $formData[6], PDO::PARAM_STR);
        $statement->bindParam(':street1', $formData[7], PDO::PARAM_STR);
        $statement->bindParam(':street2', $formData[8], PDO::PARAM_STR);
        $statement->bindParam(':city', $formData[9], PDO::PARAM_STR);
        $statement->bindParam(':zip', $formData[10], PDO::PARAM_STR);
        $statement->bindParam(':allergies', $formData[11], PDO::PARAM_STR);
        $statement->bindParam(':referral', $formData[12], PDO::PARAM_STR);
        $statement->bindParam(':decision', $formData[13], PDO::PARAM_STR);
        $statement->bindParam(':takeHomeInstrument', $formData[14], PDO::PARAM_STR);

        return $statement->execute();
    }

    /**
     * Method used to pull all gallery data from the database.
     *
     * @return mixed Returns the data as an associative array.
     */
    public static function pullGalleryImages()
    {
        $statement = self::$_dbh->query('SELECT * FROM `gallery` ORDER BY postDate DESC');

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * Method used to insert gallery images into the database.
     *
     * @param $path String path of new image.
     * @param $caption String caption of new image.
     *
     * @return mixed Returns the result of the insertion.
     */
    public static function insertGalleryImage($path, $caption)
    {
        $sql = 'INSERT INTO gallery (url, caption, postDate) VALUES (:path, :caption, NOW());';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':path', $path, PDO::PARAM_STR);
        $statement->bindParam(':caption', $caption, PDO::PARAM_STR);

        return $statement->execute();
    }

    /**
     * Method used to delete image entries from the database.
     *
     * @param $path Takes a string path of the image file.
     * @return mixed Returns the result of the query.
     */
    public static function deleteGalleryImage($path)
    {
        $sql = 'DELETE FROM gallery WHERE url=:path';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':path', $path, PDO::PARAM_STR);

        return $statement->execute();
    }

    /**
     * Method used to pull all staff information from
     * the database.
     *
     * @return mixed Returns an associative array of staff information.
     */
    public static function getAllStaff($memberType)
    {
        // Prepare a select to check if db contains queried params.
        $sql = 'SELECT * FROM '.$memberType.' ORDER BY pageOrder';

        $statement = self::$_dbh->prepare($sql);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * Method used to get a member's portrait location
     *
     * @param $memberType the type of member
     * @param $idColumnName the name of the id column
     * @param $id the id of the member
     * @return the portrait url of the member. false if the query fails
     */
    public static function getPortraitUrl($memberType, $idColumnName, $id)
    {
        // Prepare a select to check if db contains queried params.
        $sql = 'SELECT portraitURL FROM '.$memberType.' WHERE '.$idColumnName.'=:id';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * Method used to get a member's portrait location
     *
     * @param $memberType the type of member
     * @return the max page order of the member table. false if the query fails
     */
    public static function getMaxPageOrder($memberType)
    {
        // Prepare a select to check if db contains queried params.
        $sql = 'SELECT MAX(pageOrder) AS pageOrder FROM '.$memberType;

        $statement = self::$_dbh->prepare($sql);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * Method used to add a member
     *
     * @param $fname first name
     * @param $lname last name
     * @param $title title
     * @param $biography biography of the staff member
     * @param $email staff member's email
     * @param $phone staff member's phone number
     * @param $portraitURL URL of staff member's portrait
     * @return mixed true or false based on if statement executed correctly
     */
    public static function addStaffMember($fname, $lname, $title, $biography, $email, $phone, $portraitURL, $memberType, $pageOrder)
    {
        // Prepare a select to check if db contains queried params.
        $sql = 'INSERT INTO '.$memberType.' (fname, lname, title, biography, email, phone, portraitURL, pageOrder)
                VALUES (:fname, :lname, :title, :biography, :email, :phone, :portraitURL, :pageOrder)';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':fname', $fname, PDO::PARAM_STR);
        $statement->bindParam(':lname', $lname, PDO::PARAM_STR);
        $statement->bindParam(':title', $title, PDO::PARAM_STR);
        $statement->bindParam(':biography', $biography, PDO::PARAM_STR);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->bindParam(':phone', $phone, PDO::PARAM_STR);
        $statement->bindParam(':portraitURL', $portraitURL, PDO::PARAM_STR);
        $statement->bindParam(':pageOrder', $pageOrder, PDO::PARAM_INT);

        return $statement->execute();
    }

    /**
     * Method used to update a member.
     *
     * @param $idstaff id of the staff member
     * @param $fname first name
     * @param $lname last name
     * @param $title title
     * @param $biography biography of the staff member
     * @param $email staff member's email
     * @param $phone staff member's phone number
     * @param $portraitURL URL of staff member's portrait
     * @param $memberType the type of member
     * @param $idColumnName the name of the id column
     * @return mixed true or false based on if statement executed correctly
     */
    public static function updateStaffMember($id, $fname, $lname, $title, $biography, $email, $phone, $portraitURL, $memberType, $idColumnName)
    {
        // Prepare a select to check if db contains queried params.
        $sql = 'UPDATE '.$memberType.' SET fname=:fname, lname=:lname, title=:title, biography=:biography, email=:email, phone=:phone, portraitURL=:portraitURL WHERE '.$idColumnName.'=:id';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':fname', $fname, PDO::PARAM_STR);
        $statement->bindParam(':lname', $lname, PDO::PARAM_STR);
        $statement->bindParam(':title', $title, PDO::PARAM_STR);
        $statement->bindParam(':biography', $biography, PDO::PARAM_STR);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->bindParam(':phone', $phone, PDO::PARAM_STR);
        $statement->bindParam(':portraitURL', $portraitURL, PDO::PARAM_STR);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        return $statement->execute();
    }

    /**
     * Deletes a member.
     *
     * @param $memberType the type of member
     * @param $idColumnName the name of the id column
     * @param $id the id of the member
     * @return true or false whether the member is deleted
     */
    public static function deleteStaffMember($memberType, $idColumnName, $id)
    {
        // Prepare a select to check if db contains queried params.
        $sql = 'DELETE FROM '.$memberType.' WHERE '.$idColumnName.'=:id';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        $result = $statement->execute();

        return $result;
    }

    /**
     * Swaps the page order value of two members.
     *
     * @param $id1 id of member 1
     * @param $id2 id of member 2
     * @param $pageOrder1 page order of member 1
     * @param $pageOrder2 page order of member 2
     * @param $memberType the type of member
     * @param $idColumnName the name of the id column
     * @return mixed true or false based on if statement executed correctly
     */
    public static function swapMember($id1, $id2, $pageOrder1, $pageOrder2, $memberType, $idColumnName) {

        $sql = 'UPDATE '.$memberType.' SET pageOrder=:pageOrder2 WHERE '.$idColumnName.'=:id1;
                UPDATE '.$memberType.' SET pageOrder=:pageOrder1 WHERE '.$idColumnName.'=:id2';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':id1', $id1, PDO::PARAM_INT);
        $statement->bindParam(':id2', $id2, PDO::PARAM_INT);
        $statement->bindParam(':pageOrder1', $pageOrder1, PDO::PARAM_INT);
        $statement->bindParam(':pageOrder2', $pageOrder2, PDO::PARAM_INT);

        $result = $statement->execute();

        return $result;
    }

    /**
     * Method used to pull all staff information from
     * the database.
     *
     * @return mixed Returns an associative array of staff information.
     */
    public static function getCarouselItems()
    {
        // Prepare a select to check if db contains queried params.
        $sql = 'SELECT * FROM carousel ORDER BY pageOrder';

        $statement = self::$_dbh->prepare($sql);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * Pulls relevant account information.
     *
     * @param $id id of account to fetch
     * @return mixed Returns an associative array of staff information.
     */
    public static function getAccountByUsername($username)
    {
        // Prepare a select to check if db contains queried params.
        $sql = 'SELECT accountId, username, email, phone, privilege FROM account WHERE username = :username';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':username', $username, PDO::PARAM_STR);

        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Pulls relevant account information.
     *
     * @param $id id of account to fetch
     * @return mixed Returns an associative array of staff information.
     */
    public static function updateAccount($id, $username, $password, $email, $phone)
    {
        // Prepare a select to check if db contains queried params.
        $sql = 'UPDATE account 
                SET username = :username, password = sha2(:password, 256), email = :email, phone = :phone 
                WHERE accountId = :id';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_STR);
        $statement->bindParam(':username', $username, PDO::PARAM_STR);
        $statement->bindParam(':password', $password, PDO::PARAM_STR);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->bindParam(':phone', $phone, PDO::PARAM_STR);

        return $statement->execute();
    }

    /**
     * Pulls relevant account information.
     *
     * @param $id id of account to fetch
     * @return mixed Returns an associative array of staff information.
     */
    public static function updateAccountWithoutPwd($id, $username, $email, $phone)
    {
        // Prepare a select to check if db contains queried params.
        $sql = 'UPDATE account 
                SET username = :username, email = :email, phone = :phone 
                WHERE accountId = :id';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_STR);
        $statement->bindParam(':username', $username, PDO::PARAM_STR);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->bindParam(':phone', $phone, PDO::PARAM_STR);

        return $statement->execute();
    }

    /**
     * Updates an event
     *
     * @param $title title of the event
     * @param $desc description of the event
     * @param $date date of the vent
     * @param $id id of the event
     * @return mixed true or false based on if statement executed correctly
     */
    public static function updateEvent($title, $desc, $date, $id)
    {
        // Prepare a select to check if db contains queried params.
        $sql = 'UPDATE event SET title=:title, description=:desc, date=:date WHERE idevent=:id';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':title', $title, PDO::PARAM_STR);
        $statement->bindParam(':desc', $desc, PDO::PARAM_STR);
        $statement->bindParam(':date', $date, PDO::PARAM_STR);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        return $statement->execute();
    }

    /**
     * deletes the event with the given id
     *
     * @param $id id of the event to delete
     * @return mixed true or false whether the event was deleted
     */
    public static function deleteEvent($id) {

        $sql = 'DELETE FROM event WHERE idevent=:id';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        return $statement->execute();
    }

    /**
     * Inserts an account to the accound table.
     *
     * @param $username username of the account
     * @param $password password of the account
     * @param $email email of the account
     * @param $phone phone of the account
     * @return mixed true or false based on if statement executed correctly
     */
    public static function insertAccount($username, $password, $email, $phone)
    {
        $hashedPass = hash('sha256', $password);
        $sql = 'INSERT INTO `account`(`username`, `password`, `email`, `phone`) VALUES ( :username, :password, :email, :phone)';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':username', $username, PDO::PARAM_STR);
        $statement->bindParam(':password', $hashedPass, PDO::PARAM_STR);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->bindParam(':phone', $phone, PDO::PARAM_STR);

        return $statement->execute();
    }


    public static function insertVerification($code)
    {
        $id = self::$_dbh->lastInsertId();

        $verifyInsert = 'INSERT INTO `verification`(`userid`, `verifyCode`) VALUES (:id, :hash)';

        $stmt = self::$_dbh->prepare($verifyInsert);
        $stmt->bindParam(':id',   $id,   PDO::PARAM_INT);
        $stmt->bindParam(':hash', $code, PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Method for verifying an account email.
     *
     * Takes a given hash and checks that there
     * is a corresponding hash in the database.
     * If there is, it takes the userid associated with
     * the hash and changes the users privilege level to
     * basic. Then deletes the hash from the db.
     *
     * @param $hash String verification hash being compared.
     * @return mixed true or false based on if statement executed correctly
     */
    public static function verifyAccount($hash)
    {
        $sql = 'SELECT userid FROM verification WHERE verifyCode=:hash';

        $searchQuery = self::$_dbh->prepare($sql);

        $searchQuery->bindParam(':hash', $hash, PDO::PARAM_STR);

        $searchQuery->execute();

        $result = $searchQuery->fetch();

        // If result comes back positive, then activate account.
        if(isset($result['userid'])) {
            $userid = $result['userid'];
            $sql2 = 'UPDATE account SET privilege=0 WHERE accountId=:userid';

            $updateQuery = self::$_dbh->prepare($sql2);

            $updateQuery->bindParam(':userid', $userid, PDO::PARAM_INT);

            $success = $updateQuery->execute();

            if($success) {
                $sql3 = 'DELETE FROM verification WHERE verifyCode=:hash';

                $deleteQuery = self::$_dbh->prepare($sql3);

                $deleteQuery->bindParam(':hash', $hash, PDO::PARAM_STR);

                return $deleteQuery->execute();
            } else {
                return false;
            }



        } else return false;
    }

    /**
     * Add an event.
     *
     * @param $title title of the event
     * @param $desc description of the event
     * @param $date date of the event
     * @return mixed true or false based on if statement executed correctly
     */
    public static function addEvent($title, $desc, $date)
    {
        // Prepare a select to check if db contains queried params.
        $sql = 'INSERT INTO event (title, description, date) VALUES (:title, :desc, :date)';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':title', $title, PDO::PARAM_STR);
        $statement->bindParam(':desc', $desc, PDO::PARAM_STR);
        $statement->bindParam(':date', $date, PDO::PARAM_STR);

        return $statement->execute();
    }

    /**
     * Method pulls all notifications from the database
     * and returns them as an array.
     *
     * @return mixed Returns an array containing all rental requests.
     */
    public static function getNotifications()
    {
        $sql = 'SELECT * FROM notification ORDER BY time';

        $result = self::$_dbh->query($sql);

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Method used to create a notification in the database.
     * Example types include 'rental', 'application', 'notification'.
     *
     * @param $type String containing the type of notification ('notification' by default)
     * @return int Returns the id of the new notification as an int.
     */
    public static function createNotification($type='notification') {
        $sql = 'INSERT INTO `notification`(`type`, `status`) VALUES (:type, 0)';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':type', $type, PDO::PARAM_STR);

        return $statement->execute();

    }

    /**
     * Method pulls all rentals from the database
     * and returns them as an array.
     *
     * @return mixed Returns an array containing all rental requests.
     */
    public static function getInstrumentRentals()
    {
        $sql = 'SELECT accountId, formId, studentName, instrument, dateSubmited, requestStatus FROM `formInstrumentRequest`';

        $result = self::$_dbh->query($sql);

        return (!empty($result)) ? $result->fetchAll(PDO::FETCH_ASSOC) : false;
    }

    /**
     * Method pulls all applications from the database
     * and returns them as an array.
     *
     * @return mixed Returns an array containing all application requests.
     */
    public static function getApplications()
    {
        $sql = 'SELECT accountId, formId, studentName, grade, submissionDate, decision FROM formEnrollment';

        $result = self::$_dbh->query($sql);

        return (!empty($result)) ? $result->fetchAll(PDO::FETCH_ASSOC) : false;
    }

    /**
     * Method pulls all volunteer applications from the database
     * and returns them as an array.
     *
     * @return mixed Returns an array containing all volunteer applications requests.
     */
    public static function getVolunteers()
    {
        $sql = 'SELECT accountId, formId, name, phone, dateRequested, requestStatus FROM formVolunteer';

        $result = self::$_dbh->query($sql);

        return (!empty($result)) ? $result->fetchAll(PDO::FETCH_ASSOC) : false;
    }

    public static function getAccounts()
    {
        $sql = 'SELECT username, email, phone, privilege FROM account';

        $result = self::$_dbh->query($sql);

        return (!empty($result)) ? $result->fetchAll(PDO::FETCH_ASSOC) : false;
    }

    /**
     * Method pulls all rentals from the database
     * and returns them as an array.
     *
     * @return mixed Returns an array containing all rental requests.
     */
    public static function getAccountInstrumentRentals($accountId)
    {
        $sql = 'SELECT accountId, formId, studentName, dateSubmited, requestStatus FROM `formInstrumentRequest` WHERE accountId=:accountId';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':accountId', $accountId, PDO::PARAM_INT);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        // If there are results, return them. Otherwise return false.
        return (!empty($result)) ? $result : false;
    }

    /**
     * Method pulls all applications from the database
     * and returns them as an array.
     *
     * @return mixed Returns an array containing all application requests.
     */
    public static function getAccountApplications($accountId)
    {
        $sql = 'SELECT accountId, formId, studentName, grade, submissionDate, decision FROM formEnrollment WHERE accountId=:accountId';


        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':accountId', $accountId, PDO::PARAM_INT);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        // If there are results, return them. Otherwise return false.
        return (!empty($result)) ? $result : false;
    }

    /**
     * Method pulls all volunteer applications from the database
     * and returns them as an array.
     *
     * @return mixed Returns an array containing all volunteer applications requests.
     */
    public static function getAccountVolunteers($accountId)
    {
        $sql = 'SELECT accountId, formId, name, dateRequested, requestStatus FROM formVolunteer WHERE accountId=:accountId';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':accountId', $accountId, PDO::PARAM_INT);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);


        // If there are results, return them. Otherwise return false.
        return (!empty($result)) ? $result : false;
    }

    /**
     * TODO
     *
     * @return mixed Returns an array containing all rental requests.
     */
    public static function getFormInstrumentRental($formId)
    {
        $sql = 'SELECT * FROM `formInstrumentRequest` WHERE formId=:formId';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':formId', $formId, PDO::PARAM_INT);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        // If there are results, return them. Otherwise return false.
        return (!empty($result)) ? $result : false;
    }

    /**
     * TODO
     *
     * @return mixed Returns an array containing all application requests.
     */
    public static function getFormApplication($formId)
    {
        $sql = 'SELECT * FROM formEnrollment WHERE formId=:formId';


        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':formId', $formId, PDO::PARAM_INT);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        // If there are results, return them. Otherwise return false.
        return (!empty($result)) ? $result : false;
    }

    /**
     * TODO
     *
     * @return mixed Returns an array containing all volunteer applications requests.
     */
    public static function getFormVolunteer($formId)
    {
        $sql = 'SELECT * FROM formVolunteer WHERE formId=:formId';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':formId', $formId, PDO::PARAM_INT);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);


        // If there are results, return them. Otherwise return false.
        return (!empty($result)) ? $result : false;
    }



    /**
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
        // Prepare a select to check if db contains queried params.
        $sql = "INSERT INTO formInstrumentRequest (accountId, studentName, guardianName,
                address1, address2, city, zip, phone, school, grade, instrument,
                dateSubmited, requestStatus, formType) VALUES (:accountId, :studentName, :guardName, :add1,
                :add2, :city, :zip, :phone, :school, :grade, :instrument, :date, 0, 4)";

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':accountId', $accountId, PDO::PARAM_INT);
        $statement->bindParam(':studentName', $student, PDO::PARAM_STR);
        $statement->bindParam(':guardName', $guardian, PDO::PARAM_STR);
        $statement->bindParam(':add1', $add1, PDO::PARAM_STR);
        $statement->bindParam(':add2', $add2, PDO::PARAM_STR);
        $statement->bindParam(':city', $city, PDO::PARAM_STR);
        $statement->bindParam(':zip', $zip, PDO::PARAM_INT);
        $statement->bindParam(':phone', $phone, PDO::PARAM_STR);
        $statement->bindParam(':school', $school, PDO::PARAM_STR);
        $statement->bindParam(':grade', $grade, PDO::PARAM_STR);
        $statement->bindParam(':instrument', $instrument, PDO::PARAM_STR);
        $statement->bindParam(':date', $date, PDO::PARAM_STR);

        return $statement->execute();
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

        // Prepare a select to check if db contains queried params.
        $sql = 'INSERT INTO `formVolunteer` (`accountId`, `name`, `address`, `zip`, `dob`, `phone`, 
                `drivers`, `dateRequested`, `requestStatus`, `formType`) VALUES (
                :accountId, :name, :address, :zip, :dob, :phone, :drivers, :dateRequested, 0, 1)';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':accountId', $accountId, PDO::PARAM_INT);
        $statement->bindParam(':name', $name, PDO::PARAM_STR);
        $statement->bindParam(':address', $address, PDO::PARAM_STR);
        $statement->bindParam(':zip', $zip, PDO::PARAM_INT);
        $statement->bindParam(':dob', $dob, PDO::PARAM_STR);
        $statement->bindParam(':phone', $phone, PDO::PARAM_STR);
        $statement->bindParam(':drivers', $drivers, PDO::PARAM_STR);
        $statement->bindParam(':dateRequested', $dateRequested, PDO::PARAM_STR);

        echo $statement->debugDumpParams();

        return $statement->execute();
    }



    /**
     * TODO
     */
    public static function updateVolunteerStatus($submit, $formId)
    {
        $sql = 'UPDATE `formVolunteer` SET `requestStatus`= :status,`dateApproved`=CURRENT_DATE WHERE `formId`= :formId';


        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':status', $submit, PDO::PARAM_INT);
        $statement->bindParam(':formId', $formId, PDO::PARAM_INT);

        return $statement->execute();

    }

    /**
     * TODO
     */
    public static function updateInstrumentStatus($serial, $contract, $make, $model, $submit, $formId)
    {
        $sql = 'UPDATE `formInstrumentRequest` SET `serialNum`=:serial,`contractYear`= :contract,
                `make`=:make,`model`=:model,`requestStatus`=:status,`dateApproved`= CURRENT_DATE WHERE `formId`=:formId';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':serial', $serial, PDO::PARAM_INT);
        $statement->bindParam(':contract', $contract, PDO::PARAM_STR);
        $statement->bindParam(':make', $make, PDO::PARAM_INT);
        $statement->bindParam(':model', $model, PDO::PARAM_INT);
        $statement->bindParam(':status', $submit, PDO::PARAM_INT);
        $statement->bindParam(':formId', $formId, PDO::PARAM_INT);

        return $statement->execute();
    }


    /**
     * TODO
     */
    public static function updateEnrollmentStatus($submit, $formId)
    {
        $sql = 'UPDATE `formEnrollment` SET `requestStatus`= :status WHERE `formId`= :formId';


        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':status', $submit, PDO::PARAM_INT);
        $statement->bindParam(':formId', $formId, PDO::PARAM_INT);

        return $statement->execute();
    }
}
