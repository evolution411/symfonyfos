<?php

namespace Acme\HelloBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Siteinfo
 *
 * @ORM\Table(name="siteinfo")
 * @ORM\Entity
 */
class Siteinfo
{
    /**
     * @var string
     *
     * @ORM\Column(name="sitephone", type="string", length=20, nullable=false)
     */
    private $sitephone;

    /**
     * @var string
     *
     * @ORM\Column(name="aboutus", type="string", length=250, nullable=false)
     */
    private $aboutus;

    /**
     * @var string
     *
     * @ORM\Column(name="mission", type="string", length=250, nullable=false)
     */
    private $mission;

    /**
     * @var string
     *
     * @ORM\Column(name="siteemail", type="string", length=255)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $siteemail;



    /**
     * Set sitephone
     *
     * @param string $sitephone
     * @return Siteinfo
     */
    public function setSitephone($sitephone)
    {
        $this->sitephone = $sitephone;

        return $this;
    }

    /**
     * Get sitephone
     *
     * @return string 
     */
    public function getSitephone()
    {
        return $this->sitephone;
    }

    /**
     * Set aboutus
     *
     * @param string $aboutus
     * @return Siteinfo
     */
    public function setAboutus($aboutus)
    {
        $this->aboutus = $aboutus;

        return $this;
    }

    /**
     * Get aboutus
     *
     * @return string 
     */
    public function getAboutus()
    {
        return $this->aboutus;
    }

    /**
     * Set mission
     *
     * @param string $mission
     * @return Siteinfo
     */
    public function setMission($mission)
    {
        $this->mission = $mission;

        return $this;
    }

    /**
     * Get mission
     *
     * @return string 
     */
    public function getMission()
    {
        return $this->mission;
    }

    /**
     * Get siteemail
     *
     * @return string 
     */
    public function getSiteemail()
    {
        return $this->siteemail;
    }
}
