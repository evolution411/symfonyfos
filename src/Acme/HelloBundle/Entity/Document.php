<?php
 
namespace Acme\HelloBundle\Entity; 
//use Doctrine\ORM\Mapping as ORM;
//use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
 
 
class Document
{
    
    private $file;
    
    private $subDir;
    
    private $filePersistencePath;
	private $fileExt;
	public $uniq_name_of_picture;
    /**
     * file path
     * @var string
     */
    private $filePath;
 
    /**
     * file name
     * @var string
     */
    private $fileName;
    private $fn;
	
    /** @var string */

    protected static $dir = '%kernel.root_dir%/../uploads';
/*
public function setFilePath($dir){
$this->setFilePath($dir);
}
*/
    //////////////////////////
	/*public function __construct($dir = '%kernel.root_dir%/../uploads') {
        $this->setFilePath($dir);
    }
	*/
	public function setFileExt($fileExt) {
        $this->fileExt = $fileExt;
    }
    public function getFileExt() {
        return $this->fileExt;
    }
	public function setFilePath($dir) {
        $this->filePath = $dir;
    }  
    public function getFilePath() {
        return $this->filePath;
    } 
	//////////////////////////////////
    static public function setUploadDirectory($dir)
    {
        self::$uploadDirectory = $dir;
    }
    
	static public function getFixturesPath()
	{
		return $this->getAbsolutePath() . 'web/uploads/';
	}
	
	
    static public function getUploadDirectory()
    {
        if (self::$uploadDirectory === null) {
            throw new \RuntimeException("Trying to access upload directory for profile files");
        }
        return self::$uploadDirectory;
    }
	
    public function setSubDirectory($dir)
    {
         $this->subDir = $dir;
    }
    
    public function getSubDirectory()
    {
        if ($this->subDir === null) {
            throw new \RuntimeException("Trying to access sub directory for profile files");
        }
        return $this->subDir;
    }
    public function setFileName($fileName) {
        $this->fileName = $fileName;
		//return $this->fileName= uniqid();
    }
    public function getFileName() {
        return $this->fileName;
    }
    
	
	
    public function setFile(File $file)
    {
        //$this->file = $file;
		$this->file = $file;
        $origName = $this->getOriginalFileName();
		$a = uniqid();
        $arrName = explode('.', $origName);
        $fileExt = $arrName[sizeof($arrName) - 1];
        if ( (1 < count($arrName)) && (3 <= strlen($fileExt) && 4 >= strlen($fileExt)) ) {
            $this->setFileExt($fileExt);
            array_pop($arrName);
            $origName = implode('.', $arrName);
            $this->setFileName($origName);
        }
    }
    
    public function getFile()
    {
        return new File(self::getUploadDirectory() . "/" . $this->filePersistencePath);
    }
    
     public function getOriginalFileName()
    {
        return $this->file->getClientOriginalName();
    }
    
    public function getFilePersistencePath()
    {
        return $this->file->filePersistencePath;
    }
    
	/* working verstion*/
	public function processFile() {
        if (! ($this->file instanceof UploadedFile) ) {
            return false;
        }
		//$moveuploadedFile = new moveUploadedFile();
        $this->filePersistencePath = $this->moveUploadedFile();
    }
	
	public function setCreateuniqname($fn) {
        $this-> $fn = sha1(uniqid(mt_rand(), true));
		//$this->fn = $fn;
		//return $this->fileName= uniqid();
    }
    public function getCreateuniqname() {
        return $this->fn;
    }
	
	
	
	//public function moveUploadedFile($string) {
	public function moveUploadedFile() {
        $origName = $this->file->getClientOriginalName();
        //$ac = $string; 
             
		$newFilePath = $this->getFilePath() . DIRECTORY_SEPARATOR . sha1(uniqid(mt_rand(), true));
		//$fn = $this->getCreateuniqname();
		//$newFilePath = $this->getFilePath() . DIRECTORY_SEPARATOR . $ac;
		
		
        $fileName = $this->getFileName();
        $fileExt  = $this->getFileExt();
        $n= basename($newFilePath);
		$uniq_name_of_picture = $n . '.' .$fileExt; //concatanate name and extension
		//print_r($uniq_name_of_picture);
		print_r('File transferred successfully');
		//print_r($uniq_name_of_picture);
		
		$idx = 1;
        while (file_exists($newFilePath) && md5_file($this->file->getPath()) != md5_file($newFilePath)) {
            $tmpfileName = $fileName . '_' . (string)$idx;
            $newFilePath = $this->getFilePath() . DIRECTORY_SEPARATOR . $tmpfileName . '.' . $fileExt;
            $this->setFileName($tmpfileName);
            $idx++;
        }
        $targetDir = $this->getFilePath() . DIRECTORY_SEPARATOR;
		echo $targetDir;	
     /* if (!is_dir($targetDir)) {
            $ret = mkdir($targetDir, 0777, true);
            $ret = chmod($targetDir, 0777);
            if (!$ret) {
                throw new \RuntimeException("Could not create target directory to move temporary file into.");
            }
        }*/
        
        //if (!file_check_directory($targetDir, FILE_CREATE_DIRECTORY) && ! mkdir($targetDir, 0775, TRUE)) 
        
        $this->file->move($targetDir, basename($uniq_name_of_picture));
        return str_replace($this->getFilePath() . DIRECTORY_SEPARATOR, "", $newFilePath);
    } 
	
	
}
?>