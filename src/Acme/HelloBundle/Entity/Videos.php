<?php

namespace Acme\HelloBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Videos
 */
class Videos
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var boolean
     */
    private $status;

    /**
     * @var integer
     */
    private $posterid;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set path
     *
     * @param string $path
     * @return Videos
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Videos
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
     * Set posterid
     *
     * @param integer $posterid
     * @return Videos
     */
    public function setPosterid($posterid)
    {
        $this->posterid = $posterid;
    
        return $this;
    }

    /**
     * Get posterid
     *
     * @return integer 
     */
    public function getPosterid()
    {
        return $this->posterid;
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
    /**
     * @var integer
     */
    private $idcode;


    /**
     * Set idcode
     *
     * @param integer $idcode
     * @return Videos
     */
    public function setIdcode($idcode)
    {
        $this->idcode = $idcode;
    
        return $this;
    }

    /**
     * Get idcode
     *
     * @return integer 
     */
    public function getIdcode()
    {
        return $this->idcode;
    }
}