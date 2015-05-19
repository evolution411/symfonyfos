<?php

namespace Acme\HelloBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Admin
 *
 * @ORM\Table(name="admin")
 * @ORM\Entity
 */
class Admin
{
    /**
     * @var string
     *
     * @ORM\Column(name="adminname", type="string", length=100, nullable=false)
     */
    private $adminname;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100, nullable=false)
     */
    private $password;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="iplog", type="string", length=50, nullable=false)
     */
    private $iplog;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set adminname
     *
     * @param string $adminname
     * @return Admin
     */
    public function setAdminname($adminname)
    {
        $this->adminname = $adminname;

        return $this;
    }

    /**
     * Get adminname
     *
     * @return string 
     */
    public function getAdminname()
    {
        return $this->adminname;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Admin
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Admin
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
     * Set iplog
     *
     * @param string $iplog
     * @return Admin
     */
    public function setIplog($iplog)
    {
        $this->iplog = $iplog;

        return $this;
    }

    /**
     * Get iplog
     *
     * @return string 
     */
    public function getIplog()
    {
        return $this->iplog;
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
