<?php
/**
 * Created by PhpStorm.
 * User: scottmedlock
 * Date: 4/26/18
 * Time: 11:53 PM
 */

class StaffMember
{

/*$value['idstaff'], $value['fname'], $value['lname'], $value['title'],
$value['biography'],
$value['email'], $value['phone'], $value['portraitURL']*/
    private $_id;
    private $_fname;
    private $_lname;
    private $_title;
    private $_biography;
    private $_email;
    private $_phone;
    private $_portraitURL;

    /**
     * event constructor.
     * @param $id
     * @param $fname
     * @param $lname
     * @param $title
     * @param $biography
     * @param $email
     * @param $phone
     * @param $portraitURL
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
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return mixed
     */
    public function getFName()
    {
        return $this->_fname;
    }

    /**
     * @param mixed $fname
     */
    public function setFName($fname)
    {
        $this->_fname = $fname;
    }

    /**
     * @return mixed
     */
    public function getLName()
    {
        return $this->_lname;
    }

    /**
     * @param mixed $lname
     */
    public function setLName($lname)
    {
        $this->_lname = $lname;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->_title = $title;
    }

    /**
     * @return mixed
     */
    public function getBiography()
    {
        return $this->_biography;
    }

    /**
     * @param mixed $biography
     */
    public function setBiography($biography)
    {
        $this->_biography = $biography;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->_phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->_phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getPortraitURL()
    {
        return $this->_portraitURL;
    }

    /**
     * @param mixed $portraitURL
     */
    public function setPortraitURL($portraitURL)
    {
        $this->_portraitURL = $portraitURL;
    }

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