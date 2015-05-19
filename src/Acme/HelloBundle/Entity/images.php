<?php

namespace Acme\HelloBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Images
 *
 * @ORM\Table(name="images")
 * @ORM\Entity
 */
class Images
{
    /**
     * @var integer
     *
     * @ORM\Column(name="posterid", type="integer", nullable=false)
     */
    private $posterid;

    /**
     * @var string
     *
     * @ORM\Column(name="img_name", type="string", length=200, nullable=false)
     */
    private $imgName;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=100, nullable=false)
     */
    private $path;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set posterid
     *
     * @param integer $posterid
     * @return Images
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
     * @return Images
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
     * @return Images
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
     * @return Images
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
