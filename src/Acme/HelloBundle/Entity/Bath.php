<?php

namespace Acme\HelloBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bath
 *
 * @ORM\Table(name="bath")
 * @ORM\Entity
 */
class Bath
{
    /**
     * @var string
     *
     * @ORM\Column(name="numbath", type="string", length=50, nullable=false)
     */
    private $numbath;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



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
