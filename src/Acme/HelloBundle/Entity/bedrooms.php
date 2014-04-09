<?php

namespace Acme\HelloBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * bedrooms
 */
class bedrooms
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
//    private $bdid;

    /**
     * @var string
     */
    private $bdroomtype;


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
     * Set bdid
     *
     * @param integer $bdid
     * @return bedrooms
     */
  /*  public function setBdid($bdid)
    {
        $this->bdid = $bdid;
    
        return $this;
    }
*/
    /**
     * Get bdid
     *
     * @return integer 
     */
  /*  public function getBdid()
    {
        return $this->bdid;
    }
*/
    /**
     * Set bdroomtype
     *
     * @param string $bdroomtype
     * @return bedrooms
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
}
