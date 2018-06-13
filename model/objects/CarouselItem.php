<?php
/**
 * Created by PhpStorm.
 * User: scottmedlock
 * Date: 6/12/18
 * Time: 1:26 PM
 */

class CarouselItem
{
    private $_id;
    private $_header;
    private $_paragraph;
    private $_imageURL;
    private $_buttonLink;
    private $_buttonText;
    private $_pageOrder;

    /**
     * CarouselItem constructor.
     *
     * @param $id database ID number
     * @param $header
     * @param $paragraph
     * @param $imageURL
     * @param $buttonLink
     * @param $buttonText
     * @param $pageOrder
     */
    public function __construct($id, $header, $paragraph, $imageURL, $buttonLink, $buttonText, $pageOrder)
    {
        $this->_id = $id;
        $this->_header = $header;
        $this->_paragraph = $paragraph;
        $this->_imageURL = $imageURL;
        $this->_buttonLink = $buttonLink;
        $this->_buttonText = $buttonText;
        $this->_pageOrder = $pageOrder;
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
     * Gets the header of the carousel item
     *
     * @return mixed header of the carousel item
     */
    public function getHeader()
    {
        return $this->_header;
    }

    /**
     * Sets the header of the carousel item
     *
     * @param mixed $header header of the carousel item
     */
    public function setHeader($header)
    {
        $this->_header = $header;
    }

    /**
     * Gets the paragraph of the carousel item
     *
     * @return mixed paragraph of the carousel item
     */
    public function getParagraph()
    {
        return $this->_paragraph;
    }

    /**
     * Sets the paragraph of the carousel item
     *
     * @param mixed $paragraph of the carousel item
     */
    public function setParagraph($paragraph)
    {
        $this->_paragraph = $paragraph;
    }

    /**
     * Gets the image URL of the carousel item
     *
     * @return mixed image URL of the carousel item
     */
    public function getImageURL()
    {
        return $this->_imageURL;
    }

    /**
     * Sets the image URL of the carousel item
     *
     * @param mixed $imageURL the image URL of the carousel item to set
     */
    public function setImageURL($imageURL)
    {
        $this->_imageURL = $imageURL;
    }

    /**
     * Gets the button link of the carousel item
     *
     * @return mixed button link of the carousel item
     */
    public function getButtonLink()
    {
        return $this->_buttonLink;
    }

    /**
     * Sets the button link of the carousel item
     *
     * @param mixed $buttonLink button link of the carousel item to set
     */
    public function setButtonLink($buttonLink)
    {
        $this->_buttonLink = $buttonLink;
    }

    /**
     * Gets the button text of the carousel item
     *
     * @return mixed button text of the carousel item
     */
    public function getButtonText()
    {
        return $this->_buttonText;
    }

    /**
     * Sets the button text of the carousel item
     *
     * @param mixed $buttonText button text of the carousel item to set
     */
    public function setButtonText($buttonText)
    {
        $this->_buttonText = $buttonText;
    }

    /**
     * Gets the page order of the carousel item
     *
     * @return mixed page order of the carousel item
     */
    public function getPageOrder()
    {
        return $this->_pageOrder;
    }

    /**
     * Sets the page order of the carousel item
     *
     * @param mixed $pageOrder page order to set for the carousel item
     */
    public function setPageOrder($pageOrder)
    {
        $this->_pageOrder = $pageOrder;
    }

    /**
     * Returns a string representation of the carousel item
     *
     * @return string String representation of this carousel item
     */
    public function __toString()
    {
        return "".self::getId()." "
            .self::getHeader()." "
            .self::getParagraph()." "
            .self::getImageURL()." "
            .self::getButtonLink()." "
            .self::getButtonText()." "
            .self::getPageOrder();
    }
}