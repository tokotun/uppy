<?php
namespace Uppy;
class File
{   
    public $id = '';
    public $name = '';
    public $size = '';
    public $tmpName = '';
    public $key = '';
    public $dateLoad = '';

    /**
    * Даёт имя файла для сохранения на сервер.
    *
    */
    public function getFileNameInOS()
    {
        $fileName = $this->getSavedName();
        //Меняем кодировку имени, если наша ОС принадлежит семейству виндовс
        
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $regexp = '/[ A-Za-zА-Яа-яЁё\d+-\.,!@#$%^&();=#№[\]_]/u';

            preg_match_all($regexp, $fileName, $allowedWord);

            $fileName = implode("", $allowedWord['0']);

            return iconv("UTF-8", "CP1251", $fileName);
        } else {
            return $fileName;
        }
    }

    /**
    * Формирует имя файла, которое он имеет когда лежит в файловой системе.
    *
    */
    public function getSavedName()
    {
        return "$this->id _ $this->name _";
    }


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

    public function getDate(){
        return date("Y-m-d H:i:s", $this->dateLoad);
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
        $path = 'uppy/container/thumbs/' . $this->getFileNameInOS();
        if ((is_file($path)) and (getimagesize($path))) {
            return true;
        } else {
            return false;
        }    
    }

    public function getPathThumbs()
    {
        $path = 'uppy/container/thumbs/' . $this->getFileNameInOS();
        return $path;
    }
    
    public function getDownloadLink($hostname)
    {
        return "$hostname/download/$this->key/" . rawurlencode($this->name);
    }

}