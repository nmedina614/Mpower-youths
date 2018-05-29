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
        if(isset($_SESSION['account'])) {
            $account = unserialize($_SESSION['account']);
            return ($account->getPrivilege() >= 1);
        }

        return false;
    }

    /**
     * Returns whether the user is currently logged in as an administrator.
     *
     * @return bool Returns the administration login status.
     */
    public static function loggedIn() {
        if(isset($_SESSION['account'])) {
            $account = unserialize($_SESSION['account']);
            return ($account->getPrivilege() >= 0);
        }

        return false;

    }

    public static function validateAccountPage($username, $password, $confirmPassword, $email, $phone) {
        $errors = array();

        if(!self::validateAccount($username)) $errors['account'] = "Please enter username under 45 letters with letters and numbers";
        if($password != $confirmPassword) $errors['password'] = "Please enter matching passwords";
        if(strlen($email) > 0 && !self::validateEmail($email)) $errors['email'] = "Please a valid email";
        if(strlen($phone) > 0 && !self::validatePhone($phone)) $errors['phone'] = "Please valid phone number";

        return $errors;
    }

    public static function validateAccount($value) {
        // validate account is letters and digits and fits in database
        // /^(\w|\d){1,45}$/
        $pattern = '/^(\w|\d){1,45}$/';
        return preg_match($pattern, $value);
    }

    public static function validatePhone($value) {
        // phone
        // /^\d{10}$/
        $pattern = '/^\d{10}$/';
        return preg_match($pattern, $value);
    }

    /**
     * Function for validating email addresses.
     *
     * @param $email String address being checked.
     * @return Returns true if input matches email format.
     */
    public static function validateEmail($email)
    {
        if(isset($email)) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        }

        return false;
    }


    public static function validateInstrument($student, $guardian, $zip, $phone,
                                              $instrument){

        $errors = array();
        $instruments = array("Trumpet", "Clarinet", "Violin", "Cello", "Viola", "Trombone", "Flute", "Drums", "Alto Sax");

        if(!self::validateName($student)) $errors['student'] = "Your Student's Name is Invalid Please make sure it contains no numbers";
        if(!self::validateName($guardian)) $errors['guardian'] = "Your Guardians's Name is Invalid Please make sure it contains no numbers";
        if(!self::validateZip($zip)) $errors['zip'] = "Your Zip Code is Invalid, please make sure it is 5 numbers only.";
        if(!self::validatePhone($phone)) $errors['phone'] = "Your Phone number is invalid";
        if(!in_array($instrument, $instruments)) $errors['instrument'] = "Your Instrument is Invalid, please choose an instrument from the previous page";


        return $errors;

    }

    public static function validateName($name){

        $pattern = '/^([^0-9]*)$/';
        return preg_match($pattern, $name);

    }

    public static function validateZip($zip){
        $pattern = '/^\d\d\d\d\d$/';
        return preg_match($pattern, $zip);
    }

}



