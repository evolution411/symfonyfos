<?php

namespace Acme\HelloBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;



class Picture
{
	private $temp;

    /* 
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (is_file($this->getAbsolutePath())) {
            // store the old name to delete after the update
            $this->temp = $this->getAbsolutePath();
        } else {
            $this->path = 'initial';
        }
    }

    
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            $this->path = $this->getFile()->guessExtension();
        }
    }

    
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->temp);
            // clear the temp image path
            $this->temp = null;
        }

        // you must throw an exception here if the file cannot be moved
        // so that the entity is not persisted to the database
        // which the UploadedFile move() method does
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->id.'.'.$this->getFile()->guessExtension()
        );

        $this->setFile(null);
    }

    
    public function storeFilenameForRemove()
    {
        $this->temp = $this->getAbsolutePath();
    }

    public function removeUpload()
    {
        if (isset($this->temp)) {
            unlink($this->temp);
        }
		if ($file = $this->getAbsolutePath()) {
        unlink($file);
		}
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->id.'.'.$this->path;
    }
	*/
	////////////////////////////////////////////
/*	
	protected function getUploadDir()
	{
    return 'uploads/jobs';
	}
 
	protected function getUploadRootDir()
	{
    return __DIR__.'/../../../../web/'.$this->getUploadDir();
	}
 
	public function getWebPath()
	{
    return null === $this->logo ? null : $this->getUploadDir().'/'.$this->logo;
	}
 
	public function getAbsolutePath()
	{
    return null === $this->logo ? null : $this->getUploadRootDir().'/'.$this->logo;
	}
	
	public function preUpload()
	{
	if (null !== $this->file) {
    // do whatever you want to generate a unique name
    $this->logo = uniqid().'.'.$this->file->guessExtension();
	}
	}
 

	public function upload()
	{
	if (null === $this->file) {
    return;
	}
 
  // if there is an error when moving the file, an exception will
  // be automatically thrown by move(). This will properly prevent
  // the entity from being persisted to the database on error
	$this->file->move($this->getUploadRootDir(), $this->logo);
 
	unset($this->file);
	}

	public function removeUpload()
	{
	if ($file = $this->getAbsolutePath()) {
    unlink($file);
	}
	}
*/	
}