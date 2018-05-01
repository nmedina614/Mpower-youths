<?php

/**
 * Event object for events the organization has scheduled
 */
class Event
{
    private $_id;
    private $_title;
    private $_description;
    private $_date;

    /**
     * Creates an event object.
     * @param $_id in the database
     * @param $_title of event
     * @param $_description of event
     * @param $_date of event occurrence
     */
    public function __construct($_id, $_title, $_description, $_date)
    {
        $this->_id = $_id;
        $this->_title = $_title;
        $this->_description = $_description;
        $this->_date = $_date;
    }

    /**
     * Retrieves int of event id
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Sets event id
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * Gets the title of event as string
     * @return mixed
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Sets title of the event as string
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->_title = $title;
    }

    /**
     * Gets the description of event as string
     * @return mixed
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * Sets the description of event as string
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->_description = $description;
    }

    /**
     * Gets date event is occurring
     * @return mixed
     */
    public function getDate()
    {
        return $this->_date;
    }

    /**
     * Sets the date the event is occurring
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->_date = $date;
    }

    public function __toString()
    {
        return "".self::getId()." "
            .self::getTitle()." "
            .self::getDescription()." "
            .self::getDate();
    }

}