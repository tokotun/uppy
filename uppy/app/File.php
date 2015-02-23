<?php
namespace Uppy;
class File
{
    public $name = '';
    public $size = '';
    public $tmpName = '';
    public $key = '';
    public $dateLoad = '';

    public function getSize()
    {
        $size = $this->size;

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

    public function generateKey()
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $numChars = strlen($chars);
        $key = '';
        for ($i = 0; $i < 10; $i++) {
            $key .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $key;
    }

    public function isImage(){
        $path = 'uppy/container/thumbs/' . $this->key;
        if ((is_file($path)) and (getimagesize($path))) {
            return TRUE;
        } else {
            return FALSE;
        }    
    }

    public function getPathThumbs()
    {
        $path = 'uppy/container/thumbs/' . $this->key;
        return $path;
    }
    
    public function getDownloadLink($hostname)
    {
        return $hostname . '/download/' . $this->key;
    }

}