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
        return $size < self::MAX_FILE_SIZE;
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

    public static function validAccountPage($username, $password, $confirmPassword, $email, $phone) {
        $errors = array();

        if(!self::validAccount($username)) $errors['account'] = "Please enter username under 45 letters with letters and numbers";
        if($password != $confirmPassword) $errors['password'] = "Please enter matching passwords";
        if(strlen($email) > 0 && !self::validEmail($email)) $errors['email'] = "Please a valid email";
        if(strlen($phone) > 0 && !self::validPhone($phone)) $errors['phone'] = "Please valid phone number";

        return $errors;
    }

    public static function validInstrumentPage($student, $guardian, $zip, $phone,
                                              $instrument){

        $errors = array();
        $instruments = array("Trumpet", "Clarinet", "Violin", "Cello", "Viola", "Trombone", "Flute", "Drums", "Alto Sax");

        if(!self::validName($student)) $errors['student'] = "Your Student's Name is Invalid Please make sure it contains no numbers";
        if(!self::validName($guardian)) $errors['guardian'] = "Your Guardians's Name is Invalid Please make sure it contains no numbers";
        if(!self::validZip($zip)) $errors['zip'] = "Your Zip Code is Invalid, please make sure it is 5 numbers only.";
        if(!self::validPhone($phone)) $errors['phone'] = "Your Phone number is invalid";
        if(!in_array($instrument, $instruments)) $errors['instrument'] = "Your Instrument is Invalid, please choose an instrument from the previous page";


        return $errors;

    }

    public static function validMediaRelease($student, $parent) {
        $errors = array();

        if(!self::validName($student)) $errors['student'] = "Please enter name under 60 characters with letters and numbers";
        if(!self::validName($parent)) $errors['parent'] = "Please enter name under 60 characters with letters and numbers";

        return $errors;
    }

    public static function validVolunteer($name, $address, $zip, $date, $phone, $license){
        $errors = array();

        if(!self::validName($name)) $errors['name'] = "Your name is invalid please make sure it contains no numbers";
        if(!self::validAddress($address)) $errors['address'] = "Your address is invalid please make sure it contains no numbers";
        if(!self::validZip($zip)) $errors['zip'] = "Your zip is invalid please make sure it contains no numbers";
        if(!self::validDateYMD($date)) $errors['date'] = "Your date is invalid please make sure it contains no numbers";
        if(!self::validPhone($phone)) $errors['phone'] = "Your phone number is invalid";
        if(!self::validLicense($license)) $errors['license'] = "Your license is invalid please make sure it contains no numbers";

        return $errors;
    }

    public static function validEnrollment($studentName, $school, $grade, $instrument, $parentName, $email, $phone,
                                           $street1, $street2, $city, $zip, $allergies, $referral, $decision,
                                           $takeHomeInstrument){
        $errors = array();

        if(!self::validName($studentName)) $errors['student name'] = "Your student name is invalid please make sure there are no numbers";
        if(!self::validSchool($school)) $errors['school'] = "Your school is invalid please make sure it contains no numbers";
        if(!self::validGrade($grade)) $errors['grade'] = "Your grade is invalid please make sure it is 2 numbers";
        if(!self::validInstrument($instrument)) $errors['instrument'] = "Your instrument is invalid please make sure it contains no numbers";
        if(!self::validName($parentName)) $errors['parent name'] = "Your parent name is invalid please make sure there is no numbers";
        if(!self::validEmail($email)) $errors['email'] = "Your email is invalid";
        if(!self::validPhone($phone)) $errors['phone'] = "Your phone must be 10 numbers";
        if(!self::validAddress($street1)) $errors['address1'] = "Your address 1 is invalid";
        if (strlen($street2) != 0){
            if(!self::validAddress($street2)) $errors['address2'] = "Your address 2 is invalid";
        }
        if(!self::validCity($city)) $errors['city'] = "Your city is invalid please make sure it contains no numbers";
        if(!self::validZip($zip)) $errors['zip'] = "Your zip is invalid please make sure it contains 5 numbers";
        if(!self::validAllergies($allergies)) $errors['allergies'] = "Your allergies is invalid please make sure it under 160 characters";

        /*if ($referral != 0 || $referral != 1)
            $errors['referral'] = "Your referral value is invalid";

        if ($decision != 0 || $decision != 1 || $decision != 2)
            $errors['decision'] = "Your decision value is invalid";

        if ($takeHomeInstrument != 0 || $takeHomeInstrument != 1)
            $errors['take home instrument'] = "Your choice of take home instruments is invalid";*/

        return $errors;
    }

    public static function validName($name){
        $pattern = '/^([^0-9]{1,60})$/';
        return preg_match($pattern, $name);
    }

    public static function validZip($zip){
        $pattern = '/^\\d\\d\\d\\d\\d$/';
        return preg_match($pattern, $zip);
    }

    public static function validAccount($value) {
        // validate account is letters and digits and fits in database
        $pattern = '/^(\\w|\\d){1,60}$/';
        return preg_match($pattern, $value);
    }

    public static function validPhone($value) {
        // phone
        // /^\d{10}$/
        $pattern = '/^\\d{10}$/';
        return preg_match($pattern, $value);
    }

    public static function validSchool($value) {
        $pattern = '/^([^0-9]{1,60})$/';
        return preg_match($pattern, $value);
    }

    public static function validGrade($value) {
        $pattern = '/^([0-9]{1,2})$/';
        return preg_match($pattern, $value);
    }

    public static function validInstrument($value) {
        $pattern = '/^([^0-9]{1,60})$/';
        return preg_match($pattern, $value);
    }

    public static function validDateYMD($value) {
        $pattern = '/^((?:19|20)\\d\\d)[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/';
        return preg_match($pattern, $value);
    }

    public static function validAddress($value) {
        $pattern = '/^[\\w ]{1,60}$/';
        return preg_match($pattern, $value);
    }

    public static function validCity($value) {
        $pattern = '/^([^0-9]{1,60})$/';
        return preg_match($pattern, $value);
    }

    public static function validAllergies($value) {
        $pattern = '/^([^0-9]{1,60})$/';
        return preg_match($pattern, $value);
    }

    public static function validSerial($value) {
        $pattern = '/^([0-9]{1,20})$/';
        return preg_match($pattern, $value);
    }

    public static function validMake($value) {
        $pattern = '/^([^0-9]{1,30})$/';
        return preg_match($pattern, $value);
    }

    public static function validModel($value) {
        $pattern = '/^[\w\d]{1,30}$/';
        return preg_match($pattern, $value);
    }

    public static function validLicense($value) {
        $pattern = '/^.{1,40}$/';
        return preg_match($pattern, $value);
    }

    public static function validYear($value) {
        $pattern = '/^[\\d]{4}$/';
        return preg_match($pattern, $value);
    }

    public static function validMessage($value) {
        $pattern = '/^[\\w ]{1,1000}$/';
        return preg_match($pattern, $value);
    }

    /**
     * Function for validating email addresses.
     *
     * @param $email String address being checked.
     * @return Returns true if input matches email format.
     */
    public static function validEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}



