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
     * @param $requiredPrivilege int required privilege level.
     *
     * @return mixed Returns true or false if a matching user is found.
     */
    public static function login($username, $password, $requiredPrivilege) {

        // Prepare a select to check if db contains queried params.
        $sql = 'SELECT idaccount, username, password, email, phone, privilege FROM account WHERE username=:username AND password=:password AND privilege=:privilege';

        $statement = self::$_dbh->prepare($sql);

        $statement->bindParam(':username', $username, PDO::PARAM_STR);

        $statement->bindParam(':password', hash('sha256', $password, false), PDO::PARAM_STR);

        $statement->bindParam(':privilege', $requiredPrivilege, PDO::PARAM_INT);

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * Method used to pull all events from the Database.
     *
     * @return mixed Returns an associative array of results.
     */
    public static function getAllEvents() {
        // Prepare a select to check if db contains queried params.
        $sql = 'SELECT idevent, title, description, DATE_FORMAT(`date`, "%m/%d/%Y") AS `date` FROM event';

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
    public static function getAllStaff() {
        // Prepare a select to check if db contains queried params.
        $sql = 'SELECT * FROM staff';

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
    public static function getAccountById($id) {
        // Prepare a select to check if db contains queried params.
        $sql = 'SELECT username, email, phone FROM account WHERE idaccount = :accountID';

        $statement = self::$_dbh->prepare($sql);
        $statement->bindParam(':accountID', $id, PDO::PARAM_STR);

        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Pulls relevant account information.
     *
     * @param $id id of account to fetch
     * @return mixed Returns an associative array of staff information.
     */
    public static function updateAccount($id, $username, $password, $email, $phone) {
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
}
