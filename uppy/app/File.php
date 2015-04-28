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
    public $info;


    public function saveFile()
    {
        
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

    public function getTitle() {
        return (isset($this->info['id3v1']['title']) ? $this->info['id3v1']['title'] : '');
    }

    public function getFileType() {
        return (isset($this->info['fileformat']) ? $this->info['fileformat'] : '');
    }

    public function getPlayTime() {
        return (isset($this->info['playtime_string']) ? $this->info['playtime_string'] : '');
    }

    public function getOverallBitrate() {
        return (isset($this->info['bitrate'])   ? (round ($this->info['bitrate']/1024) . ' кб') : '');
    }

    /**
    * Частота дискретизации
    *
    */
    public function getAudioFrequency() {
        return (isset($this->info['audio']['sample_rate']) ? $this->info['audio']['sample_rate'] : '');
    }
    
    public function getArtistName() {
        return (isset($this->info['id3v1']['artist']) ? $this->info['id3v1']['artist'] : '');
    }

    /**
    * Колличество каналов
    *
    */
    public function getChannelsCount() {
        return (isset($this->info['audio']['channels']) ? $this->info['audio']['channels'] : '');
    }

    public function getYear() {
        return (isset($this->info['id3v1']['year']) ? $this->info['id3v1']['year'] : '');
    }

    public function getComment() {
        return (isset($this->info['id3v1']['comment']) ? $this->info['id3v1']['comment'] : '');
    }
    public function getAlbumName() {
        return (isset($this->info['id3v1']['album']) ? $this->info['id3v1']['album'] : '');
    }

    public function getGenre() {
        return (isset($this->info['id3v1']['genre']) ? $this->info['id3v1']['genre'] : '');
    }

    public function getFrameRate() {
        return (isset($this->info['video']['frame_rate']) ? round($this->info['video']['frame_rate']) : '');
    }

    public function getHeight() {
        return (isset($this->info['video']['resolution_y']) ? $this->info['video']['resolution_y'] : '');
    }

    public function getWidth() {
        return (isset($this->info['video']['resolution_x']) ? $this->info['video']['resolution_x'] : '');
    }

}