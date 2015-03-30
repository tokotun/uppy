<?php
namespace Uppy;
Class Uploader
{

    public static function createFile(\Uppy\ErrorLoad $errorLoad)
    {
        $file = new \Uppy\File;
        if ($errorLoad->getError() == false) {
            $file->name = $_FILES['file']['name'];
            $file->size = $_FILES['file']['size'];
            $file->tmpName= $_FILES['file']['tmp_name'];
            $file->key = $file->generateKey();
            $file->dateLoad = time();
        }
        return $file;
    }

    public static function createComment()
    {
        if (($_POST['comment']) <> '')  {
            $comment = new \Uppy\Comment;
            $comment->message= $_POST['comment'];
            $comment->dateLoad = time();
            return $comment;
        }
        return false;
    }


    public static function resizeImage($filename, $uploadPath, $maxSize = 200)
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


    public static function getIdParentComment(){
        if (isset($_POST['id_comment'])){
            return $_POST['id_comment'];
        } else {
            return false;
        }
    }
}