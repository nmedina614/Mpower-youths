<?php
/**
 * Created by PhpStorm.
 * User: scottmedlock
 * Date: 4/13/18
 * Time: 1:00 PM
 */
class Validator
{
    /**
     * TODO
     *
     * @param $filename
     * @return bool
     */
    public static function validImageFile($filepath)
    {
        $extension = strtolower(pathinfo($filepath,PATHINFO_EXTENSION));;

        // Return whether file extension is an image.
        return (
            $extension == 'jpg'  ||
            $extension == 'jpeg' ||
            $extension == 'png'  ||
            $extension == 'gif'
        );
    }

    /**
     * TODO
     *
     * @param $size
     * @return bool
     */
    public static function validFileSize($size)
    {
        return $size > 500000;
    }

    /**
     * Returns whether the user is currently logged in as an administrator.
     *
     * @return bool Returns the administration login status.
     */
    public static function isAdmin() {
        return isset($_SESSION['username']);
    }
}

