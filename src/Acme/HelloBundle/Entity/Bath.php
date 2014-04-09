<?php

namespace Acme\HelloBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bath
 */
class Bath
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $numbath;


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
     * Set numbath
     *
     * @param string $numbath
     * @return Bath
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
}
