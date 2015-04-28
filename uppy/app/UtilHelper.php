<?php
namespace Uppy;
Class UtilHelper
{   
    public $dirHost = '';
	public $hostName = '';
	public $uploadPath = '';

	public function __construct($dirHost, $hostName, $uploadPath)
    {
        $this->dirHost = $dirHost;
        $this->hostName = $hostName;
        $this->uploadPath = $uploadPath;
    }

	public function getDownloadLink( $file )
    {
        return "{$this->hostName}/download/file/{$file->key}/" . rawurlencode($file->name);
    }

    public function isImage( $file ){
        $path = "{$this->uploadPath}/thumbs/" . $file->getFileNameInOS();
        if ((is_file($path)) and (getimagesize($path))) {
            return true;
        } else {
            return false;
        }    
    }

    public function getPathThumbs( $file )
    {
        $path = "/uppy/{$this->uploadPath}thumbs/" . $file->getSavedName();
        return $path;
    }

    public function getPathUpload( $file )
    {
        $path = "{$this->dirHost}/{$this->uploadPath}/" . $file->getFileNameInOS();
        return $path;
    }

    public function getSize($size)
    {
        if ($size < 1500){
            return $size . ' байт';
        } else {
            $size = round(($size /1024), 1);
        }

        if ($size < 1500){
            return $size . ' Кб';
        } else {
            $size = round(($size /1024), 1);
        }

        return $size . ' Мб';
    }
}