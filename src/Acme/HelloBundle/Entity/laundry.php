<?php

namespace Acme\HelloBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Laundry
 *
 * @ORM\Table(name="laundry")
 * @ORM\Entity
 */
class Laundry
{
    /**
     * @var string
     *
     * @ORM\Column(name="laundrytype", type="string", length=50, nullable=false)
     */
    private $laundrytype;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set laundrytype
     *
     * @param string $laundrytype
     * @return Laundry
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
