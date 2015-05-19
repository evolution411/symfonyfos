<?php

namespace Acme\HelloBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bedrooms
 *
 * @ORM\Table(name="bedrooms")
 * @ORM\Entity
 */
class Bedrooms
{
    /**
     * @var string
     *
     * @ORM\Column(name="bdroomtype", type="string", length=50, nullable=false)
     */
    private $bdroomtype;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set bdroomtype
     *
     * @param string $bdroomtype
     * @return Bedrooms
     */
    public function setBdroomtype($bdroomtype)
    {
        $this->bdroomtype = $bdroomtype;

        return $this;
    }

    /**
     * Get bdroomtype
     *
     * @return string 
     */
    public function getBdroomtype()
    {
        return $this->bdroomtype;
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
