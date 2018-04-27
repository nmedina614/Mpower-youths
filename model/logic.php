<?php
/**
 * Created by PhpStorm.
 * User: scottmedlock
 * Date: 4/13/18
 * Time: 12:59 PM
 */

// index.php -> routes.php -> THIS -> database/messenger/validator -> views
class Logic {

    public static function getGalleryImages() {
        Database::connect();

        $images = Database::pullGalleryImages();

        return $images;

        return $images;
    }

    public static function getEvents() {
        $result = array();

        Database::connect();
        $resultDB = Database::getAllEvents();

        foreach ($resultDB as $key => $value){
            array_push($result, new Event($value['idevent'], $value['title'], $value['description'], $value['date']));
        }

        return $result;
    }

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

}
