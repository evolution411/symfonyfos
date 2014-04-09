<?php

namespace Acme\HelloBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


class videos
{
	private $posterid;

    //private $img_name;

    private $path;
	
	private $status;
	
	public function setPosterid($posterid)
    {
        $this->posterid = $posterid;
    
        return $this;
    }
	
    public function getPosterid()
    {
        return $this->posterid;
    }
	/*
	public function setImg_name($img_name)
    {
        $this->img_name = $img_name;
    
        return $this;
    }
	
    public function getImg_name()
    {
        return $this->img_name;
    }
	*/
	
	public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }
	
    public function getPath()
    {
        return $this->path;
    }
	
	public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }
	
    public function getStatus()
    {
        return $this->$status;
	}
}
