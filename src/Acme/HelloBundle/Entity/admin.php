<?php

namespace Acme\HelloBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * admin
 */
class admin
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $adminname;

    /**
     * @var string
     */
    private $password;

    /**
     * @var integer
     */
    private $status;

    /**
     * @var string
     */
    private $iplog;


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
     * Set adminname
     *
     * @param string $adminname
     * @return admin
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
     * @return admin
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
     * @return admin
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
     * @return admin
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
}
