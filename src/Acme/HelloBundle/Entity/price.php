<?php

namespace Acme\HelloBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * price
 */
class price
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $pricerange;


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
     * Set pricerange
     *
     * @param string $pricerange
     * @return price
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
}
