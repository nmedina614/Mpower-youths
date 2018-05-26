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
        $sql = 'SELECT idaccount, username, password, email, phone, privilege FROM account WHERE username=:username AND password=:password AND privilege>=:privilege';

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
    public static function getAllEvents()
    {
        // Prepare a select to check if db contains queried params.
        $sql = 'SELECT idevent, title, description, DATE_FORMAT(`date`, "%m/%d/%Y") AS `dateFormatted` FROM event ORDER BY `date`';

        $statement = self::$_dbh->prepare($sql);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
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
        $sql = 'SELECT * FROM '.$memberType;

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
    public static function addStaffMember($fname, $lname, $title, $biography, $email, $phone, $portraitURL, $memberType)
    {
        // Prepare a select to check if db contains queried params.
        $sql = 'INSERT INTO '.$memberType.' (fname, lname, title, biography, email, phone, portraitURL)
                VALUES (:fname, :lname, :title, :biography, :email, :phone, :portraitURL)';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':fname', $fname, PDO::PARAM_STR);
        $statement->bindParam(':lname', $lname, PDO::PARAM_STR);
        $statement->bindParam(':title', $title, PDO::PARAM_STR);
        $statement->bindParam(':biography', $biography, PDO::PARAM_STR);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->bindParam(':phone', $phone, PDO::PARAM_STR);
        $statement->bindParam(':portraitURL', $portraitURL, PDO::PARAM_STR);

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
        $sql = 'SELECT idaccount, username, email, phone, privilege FROM account WHERE username = :username';

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
                WHERE idaccount = :id';

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
                WHERE idaccount = :id';

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
            $sql2 = 'UPDATE account SET privilege=0 WHERE idaccount=:userid';

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

}
