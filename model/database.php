<?php
/**
 * Created by PhpStorm.
 * User: scottmedlock
 * Date: 4/13/18
 * Time: 12:59 PM
 */


require $_SERVER['DOCUMENT_ROOT'] . "/../config/bsharp-config.php";


class Database
{


    private static $_dbh;

    /**
     * TODO
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
     * TODO
     *
     * @param $username
     * @param $password
     * @return mixed
     */
    public static function checkCredentials($username, $password) {

        // Prepare a select to check if db contains queried params.
        $sql = 'SELECT username FROM account WHERE username=:username AND password=:password';

        $statement = self::$_dbh->prepare($sql);

        $statement->bindParam(':username', $username, PDO::PARAM_STR);

        $statement->bindParam(':password', hash('sha256', $password, false), PDO::PARAM_STR);

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * TODO
     *
     * @return mixed
     */
    public static function getAllEvents() {
        // Prepare a select to check if db contains queried params.
        $sql = 'SELECT * FROM event';

        $statement = self::$_dbh->prepare($sql);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * TODO
     *
     * @return mixed
     */
    public static function pullGalleryImages()
    {
        $statement = self::$_dbh->query('SELECT * FROM gallery');

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}
