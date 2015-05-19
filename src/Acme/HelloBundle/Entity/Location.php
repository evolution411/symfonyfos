<?php

namespace Acme\HelloBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Location
 *
 * @ORM\Table(name="location")
 * @ORM\Entity
 */
class Location
{
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=20, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=15, nullable=false)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="avenue", type="string", length=15, nullable=false)
     */
    private $avenue;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="contact", type="string", length=15, nullable=false)
     */
    private $contact;

    /**
     * @var integer
     *
     * @ORM\Column(name="posterId", type="integer", nullable=false)
     */
    private $posterid;

    /**
     * @var integer
     *
     * @ORM\Column(name="renttype", type="integer", nullable=false)
     */
    private $renttype;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="postdate", type="date", nullable=false)
     */
    private $postdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Editdate", type="date", nullable=false)
     */
    private $editdate;

    /**
     * @var string
     *
     * @ORM\Column(name="zipcode", type="string", length=5, nullable=true)
     */
    private $zipcode;

    /**
     * @var string
     *
     * @ORM\Column(name="numbath", type="string", length=50, nullable=true)
     */
    private $numbath;

    /**
     * @var string
     *
     * @ORM\Column(name="rentamount", type="string", length=50, nullable=false)
     */
    private $rentamount;

    /**
     * @var string
     *
     * @ORM\Column(name="laundryfacility", type="string", length=50, nullable=true)
     */
    private $laundryfacility;

    /**
     * @var integer
     *
     * @ORM\Column(name="area", type="integer", nullable=false)
     */
    private $area;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
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
     * Set title
     *
     * @param string $title
     * @return Location
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return Location
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set avenue
     *
     * @param string $avenue
     * @return Location
     */
    public function setAvenue($avenue)
    {
        $this->avenue = $avenue;

        return $this;
    }

    /**
     * Get avenue
     *
     * @return string 
     */
    public function getAvenue()
    {
        return $this->avenue;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Location
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set contact
     *
     * @param string $contact
     * @return Location
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string 
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set posterid
     *
     * @param integer $posterid
     * @return Location
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
     * Set renttype
     *
     * @param integer $renttype
     * @return Location
     */
    public function setRenttype($renttype)
    {
        $this->renttype = $renttype;

        return $this;
    }

    /**
     * Get renttype
     *
     * @return integer 
     */
    public function getRenttype()
    {
        return $this->renttype;
    }

    /**
     * Set postdate
     *
     * @param \DateTime $postdate
     * @return Location
     */
    public function setPostdate($postdate)
    {
        $this->postdate = $postdate;

        return $this;
    }

    /**
     * Get postdate
     *
     * @return \DateTime 
     */
    public function getPostdate()
    {
        return $this->postdate;
    }

    /**
     * Set editdate
     *
     * @param \DateTime $editdate
     * @return Location
     */
    public function setEditdate($editdate)
    {
        $this->editdate = $editdate;

        return $this;
    }

    /**
     * Get editdate
     *
     * @return \DateTime 
     */
    public function getEditdate()
    {
        return $this->editdate;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     * @return Location
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string 
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set numbath
     *
     * @param string $numbath
     * @return Location
     */
    public function setNumbath($numbath)
    {
        $this->numbath = $numbath;

        return $this;
    }

    /**
     * Get numbath
     *
     * @return string 
     */
    public function getNumbath()
    {
        return $this->numbath;
    }

    /**
     * Set rentamount
     *
     * @param string $rentamount
     * @return Location
     */
    public function setRentamount($rentamount)
    {
        $this->rentamount = $rentamount;

        return $this;
    }

    /**
     * Get rentamount
     *
     * @return string 
     */
    public function getRentamount()
    {
        return $this->rentamount;
    }

    /**
     * Set laundryfacility
     *
     * @param string $laundryfacility
     * @return Location
     */
    public function setLaundryfacility($laundryfacility)
    {
        $this->laundryfacility = $laundryfacility;

        return $this;
    }

    /**
     * Get laundryfacility
     *
     * @return string 
     */
    public function getLaundryfacility()
    {
        return $this->laundryfacility;
    }

    /**
     * Set area
     *
     * @param integer $area
     * @return Location
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return integer 
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Location
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
