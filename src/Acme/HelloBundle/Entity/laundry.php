<?php

namespace Acme\HelloBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * laundry
 */
class laundry
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $laundrytype;


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
     * Set laundrytype
     *
     * @param string $laundrytype
     * @return laundry
     */
    public function setLaundrytype($laundrytype)
    {
        $this->laundrytype = $laundrytype;
    
        return $this;
    }

    /**
     * Get laundrytype
     *
     * @return string 
     */
    public function getLaundrytype()
    {
        return $this->laundrytype;
    }
}
