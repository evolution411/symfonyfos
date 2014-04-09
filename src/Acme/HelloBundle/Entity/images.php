<?php

namespace Acme\HelloBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * images
 */
class images
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $posterid;

    /**
     * @var string
     */
    private $imgName;

    /**
     * @var string
     */
    private $path;

    /**
     * @var integer
     */
    private $status;


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
     * Set posterid
     *
     * @param integer $posterid
     * @return images
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
     * Set imgName
     *
     * @param string $imgName
     * @return images
     */
    public function setImgName($imgName)
    {
        $this->imgName = $imgName;
    
        return $this;
    }

    /**
     * Get imgName
     *
     * @return string 
     */
    public function getImgName()
    {
        return $this->imgName;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return images
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
     * @param integer $status
     * @return images
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
