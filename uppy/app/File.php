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
    public $mediaInfo;


    public function __construct()
    {
        $this->mediaInfo = new \Uppy\MediaInfo;
    }
    /**
    * Даёт имя файла в том виде, в котором он сохранится на сервер
    *
    */
    public function getFileNameInOS()
    {
        $fileName = $this->getSavedName();
        //Меняем кодировку имени, если наша ОС принадлежит семейству виндовс
        if (strtoupper(substr(php_uname(), 0, 7)) === 'WINDOWS') {
   
            $regexp = "/[^ A-Za-zА-Яа-яЁё\d+\-\.,!@#$%^&();=#№[\]_]/u";

            $fileName = preg_replace($regexp  , "_", $fileName);
            
            return iconv("UTF-8", "CP1251", $fileName);
        } else {
            return $fileName;
        }
    }

    /**
    * Возвращает имя файла, которое он имеет когда лежит в файловой системе.
    *
    */
    public function getSavedName()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $fileName = $this->name;
            
            $regexp = "/[^ A-Za-zА-Яа-яЁё\d+\-\.,!@#$%^&();=#№[\]_]/u";

            $fileName = preg_replace($regexp  , "_", $fileName);
            
            return "{$this->id}_{$fileName}_";
        }

        return "{$this->id}_{$this->name}_";
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

}