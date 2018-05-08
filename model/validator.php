<?php
/**
 * Class containing functions used to validate input.
 *
 * @author Aaron Melhaff
 * @author Scott Medlock
 * @author Kyle Johnson
 * @author Nolan Medina
 *
 * @since 4/30/2018
 */
class Validator
{
    const MAX_FILE_SIZE = 750000;

    /**
     * Method used to check if a file has correct filepaths.
     *
     * @param $filename String name of file being submitted.
     * @return bool Returns true if valid.
     */
    public static function validImageFile($filepath)
    {
        $extension = strtolower(pathinfo($filepath,PATHINFO_EXTENSION));

        // Return whether file extension is an image.
        return (
            $extension == 'jpg'  ||
            $extension == 'jpeg' ||
            $extension == 'png'  ||
            $extension == 'gif'
        );
    }

    /**
     * Method used to check if a files size is correct.
     *
     * @param $size Size of file being evaluated.
     * @return bool Returns true if size is within parameters.
     */
    public static function validFileSize($size)
    {
        return $size < MAX_FILE_SIZE;
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

