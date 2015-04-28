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

    public function saveFile(\Uppy\File $file, \Uppy\FileMapper $fileMapper, \Uppy\UtilHelper $utilHelper, \getID3 $getID3)
    {   
        //Извлечение метаданных с помощью getID3()        
        $ID3 = $getID3->analyze($file->tmpName);
        $file->info = \Uppy\MediaInfo::moveArrayInfoInFile($ID3);
            
        //сохранение файла 
        $fileMapper->saveFile($file);
        $fileName = $file->getFileNameInOS();

        $target = $utilHelper->getPathUpload($file);

        if (move_uploaded_file($file->tmpName, $target)){
            if ($this->isImage($file)){
                $this->resizeImage($fileName);
            }
            return true;
        }
        return false;
    }

    public function resizeImage($filename, $maxSize = 200)
    {
        $path = $this->uploadPath . $filename;
        $imageInfo = getimagesize($path);
        $width = $imageInfo['0'];
        $height = $imageInfo['1'];
        $mime = $imageInfo['mime'];
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
        if ($mime == 'image/jpeg'){
            $source = imagecreatefromjpeg($path);
            //header('Content-Type: image/jpeg');
            // Масштабирование
            //imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            // Вывод
            imagejpeg($thumb, "{$this->uploadPath}thumbs/$filename");
            imagedestroy($source);
        }
        if ($mime == 'image/gif'){
            $source = imagecreatefromgif($path);
            //header('Content-Type: image/jpeg');
            // Масштабирование
            //imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            // Вывод
            imagegif($thumb, "{$this->uploadPath}thumbs/$filename");
        }
        if ($mime == 'image/png'){
            $source = imagecreatefrompng($path);
            //header('Content-Type: image/jpeg');
            // Масштабирование
            //imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            // Вывод
            imagepng($thumb, "{$this->uploadPath}thumbs/$filename");
        }
        
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