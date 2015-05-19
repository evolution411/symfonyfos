<?php

namespace Acme\HelloBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Price
 *
 * @ORM\Table(name="price")
 * @ORM\Entity
 */
class Price
{
    /**
     * @var string
     *
     * @ORM\Column(name="pricerange", type="string", length=50, nullable=false)
     */
    private $pricerange;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set pricerange
     *
     * @param string $pricerange
     * @return Price
     */
    public function setPricerange($pricerange)
    {
        $this->pricerange = $pricerange;

        return $this;
    }

    /**
     * Get pricerange
     *
     * @return string 
     */
    public function getPricerange()
    {
        return $this->pricerange;
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
