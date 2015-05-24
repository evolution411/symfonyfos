<?php

namespace Acme\HelloBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 */
class Comment
{
    /**
     * @var integer
     */
    private $locationid;

    /**
     * @var integer
     */
    private $userid;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var boolean
     */
    private $status;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set locationid
     *
     * @param integer $locationid
     * @return Comment
     */
    public function setLocationid($locationid)
    {
        $this->locationid = $locationid;

        return $this;
    }

    /**
     * Get locationid
     *
     * @return integer 
     */
    public function getLocationid()
    {
        return $this->locationid;
    }

    /**
     * Set userid
     *
     * @param integer $userid
     * @return Comment
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Get userid
     *
     * @return integer 
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Comment
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return Comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Comment
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
