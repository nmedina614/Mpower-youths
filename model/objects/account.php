<?php
/**
 * Created by PhpStorm.
 * User: kylejohnson
 * Date: 5/6/18
 * Time: 11:49 PM
 */

class account
{
    private $_id;
    private $_username;
    private $_password; // never retrieved from db, used for updating accounts
    private $_email;
    private $_phone;

    /**
     * account constructor.
     * @param $_id
     * @param $_username
     * @param $_password
     * @param $_email
     * @param $_phone
     */
    public function __construct($_id, $_username, $_password, $_email, $_phone)
    {
        $this->_id = $_id;
        $this->_username = $_username;
        $this->_password = $_password;
        $this->_email = $_email;
        $this->_phone = $_phone;
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
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->_username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->_password = $password;
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
}