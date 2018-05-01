<?php
/**
 * Created by PhpStorm.
 * User: Scott Medlock
 * Date: 4/26/18
 * Time: 11:53 PM
 */

/**
 * Class StaffMember
 * This class stores information about a staff member
 */
class StaffMember
{

    private $_id;
    private $_fname;
    private $_lname;
    private $_title;
    private $_biography;
    private $_email;
    private $_phone;
    private $_portraitURL;

    /**
     * StaffMember constructor.
     *
     * @param $id Database ID number
     * @param $fname First name of staff member
     * @param $lname Last name of staff member
     * @param $title Title of staff member
     * @param $biography Staff member's biography
     * @param $email Email of staff member
     * @param $phone Staff member's phone number
     * @param $portraitURL URL of staff member's portrait
     */
    public function __construct($id, $fname, $lname, $title, $biography, $email, $phone, $portraitURL)
    {
        $this->_id = $id;
        $this->_fname = $fname;
        $this->_lname = $lname;
        $this->_title = $title;
        $this->_biography = $biography;
        $this->_email = $email;
        $this->_phone = $phone;
        $this->_portraitURL = $portraitURL;
    }

    /**
     * Gets the ID of staff member
     *
     * @return mixed ID of staff member
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Sets the ID of the staff member
     *
     * @param mixed $id ID to set for staff member
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * Gets the first name of staff member
     *
     * @return mixed First name of staff member
     */
    public function getFName()
    {
        return $this->_fname;
    }

    /**
     * Sets the first name of staff member
     *
     * @param mixed $fname First name to set for staff member
     */
    public function setFName($fname)
    {
        $this->_fname = $fname;
    }

    /**
     * Gets the last name of staff member
     *
     * @return mixed Last name of staff member
     */
    public function getLName()
    {
        return $this->_lname;
    }

    /**
     * Sets the last name of staff member
     *
     * @param mixed $lname Last name to set for staff member
     */
    public function setLName($lname)
    {
        $this->_lname = $lname;
    }

    /**
     * Gets the title of staff member
     *
     * @return mixed Title of staff member
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Sets the title of staff member
     *
     * @param mixed $title Title to set for staff member
     */
    public function setTitle($title)
    {
        $this->_title = $title;
    }

    /**
     * Gets the biography of staff member
     *
     * @return mixed Biography of staff member
     */
    public function getBiography()
    {
        return $this->_biography;
    }

    /**
     * Sets the biography of staff member
     *
     * @param mixed $biography Biography to set for staff member
     */
    public function setBiography($biography)
    {
        $this->_biography = $biography;
    }

    /**
     * Gets the email of staff member
     *
     * @return mixed Email of staff member
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * Sets the email of staff member
     *
     * @param mixed $email Email to set for staff member
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * Gets the phone number of staff member
     *
     * @return mixed Phone number of staff member
     */
    public function getPhone()
    {
        return $this->_phone;
    }

    /**
     * Sets the phone number of staff member
     *
     * @param mixed $phone Phone number to set for staff member
     */
    public function setPhone($phone)
    {
        $this->_phone = $phone;
    }

    /**
     * Gets the portrait URL of staff member
     *
     * @return mixed portrait URL of staff member
     */
    public function getPortraitURL()
    {
        return $this->_portraitURL;
    }

    /**
     * Sets the portrait URL of staff member
     *
     * @param mixed $portraitURL portrait URL to set for staff member
     */
    public function setPortraitURL($portraitURL)
    {
        $this->_portraitURL = $portraitURL;
    }

    /**
     * Returns a string representation of the staff member
     *
     * @return string String representation of this staff member
     */
    public function __toString()
    {
        return "".self::getId()." "
            .self::getFname()." "
            .self::getLname()." "
            .self::getTitle()." "
            .self::getBiography()." "
            .self::getEmail()." "
            .self::getPhone()." "
            .self::getPortraitURL();
    }

}