<?php
namespace Uppy;
Class Uploader
{
    public $uploadPath;

    public function __construct($uploadPath)
    {
        $this->uploadPath = $uploadPath;       
    }

    public function createFile()
    {
        $file = new \Uppy\File;
        $file->name = $_FILES['file']['name'];
        $file->size = $_FILES['file']['size'];
        $file->tmpName= $_FILES['file']['tmp_name'];
        $file->key = $file->generateKey();
        $file->dateLoad = time();

        return $file;
    }


    public function resizeImage($filename, $uploadPath, $maxSize = 200)
    {
        $path = $uploadPath . $filename;
        list($width, $height) = getimagesize($path);

        if (($width > 0) and ($height > 0)) {
            if ($width > $height) {
                $newWidth = $maxSize;
                $newHeight = $maxSize * ($height/$width);
            } else {
                $newHeight = $maxSize;
                $newWidth = $maxSize * ($width/$height);
            }
        }

        // Загрузка
        $thumb = imagecreatetruecolor($newWidth, $newHeight);
        $source = imagecreatefromjpeg($path);
        //header('Content-Type: image/jpeg');
        // Масштабирование
        //imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        // Вывод
        imagejpeg($thumb, "uppy/container/thumbs/$filename");
    }


    public function getIdParentComment(){
        if (isset($_POST['id_comment'])){
            return $_POST['id_comment'];
        } else {
            return false;
        }
    }

    public function validateLoadFile($maxFileSize)
    {
        $errorLoad = new \Uppy\ErrorLoad;
        if (isset($_FILES)){
            $errorLoad->setError($_FILES['file']['error']);
            $errorLoad->setErrorSize($_FILES['file']['size'], $maxFileSize);
        }
        return $errorLoad;
    }

    public function isImage(\Uppy\File $file){
        $path = $this->uploadPath . $file->getFileNameInOS();
        if ((is_file($path)) and (getimagesize($path))) {
            return true;
        } else {
            return false;
        }    
    }
}