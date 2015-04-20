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
    protected $info;

    /**
    * Даёт имя файла в том виде, в котором он сохранится на сервер
    *
    */
    public function getFileNameInOS()
    {
        $fileName = $this->getSavedName();
        //Меняем кодировку имени, если наша ОС принадлежит семейству виндовс
        
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
   
            $regexp = "/[^ A-Za-zА-Яа-яЁё\d+-\.,!@#$%^&();=#№[\]_]/u";

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
            
            $regexp = "/[^ A-Za-zА-Яа-яЁё\d+-\.,!@#$%^&();=#№[\]_]/u";

            $fileName = preg_replace($regexp  , "_", $fileName);
            
            return "$this->id _ $fileName _";
        }

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
    
    /**
    * Возвращает текст ссылки для загрузки файла с сервера.
    *
    */
    public function getDownloadLink($hostname)
    {
        return "$hostname/download/file/$this->key/" . rawurlencode($this->name);
    }

    public function moveJsonInfoInFile($jsonInfoID3) 
    {
        if (isset($jsonInfoID3)){
            $arrayInfoID3 = json_decode($jsonInfoID3, true);
            $this->moveArrayInfoInFile($arrayInfoID3);
        } else {
            $this->info = null;
        }    
    }

    public function moveArrayInfoInFile($arrayInfoID3)
    {
        if (isset($arrayInfoID3['mime_type'])){
            $this->info['mime_type'] = isset($arrayInfoID3['mime_type']) ? $arrayInfoID3['mime_type'] : '';
        } else {
            $this->info = null;
        }

        if ($this->getTypeInfo() == 'audio'){
            $this->info['id3v1']['genre']       = isset($arrayInfoID3['id3v1']['genre'])       ? $arrayInfoID3['id3v1']['genre']       : '';
            $this->info['id3v1']['album']       = isset($arrayInfoID3['id3v1']['album'])       ? $arrayInfoID3['id3v1']['album']       : '';
            $this->info['id3v1']['comment']     = isset($arrayInfoID3['id3v1']['comment'])     ? $arrayInfoID3['id3v1']['comment']     : '';
            $this->info['id3v1']['year']        = isset($arrayInfoID3['id3v1']['year'])        ? $arrayInfoID3['id3v1']['year']        : '';
            $this->info['audio']['channels']    = isset($arrayInfoID3['audio']['channels'])    ? $arrayInfoID3['audio']['channels']    : '';
            $this->info['id3v1']['artist']      = isset($arrayInfoID3['id3v1']['artist'])      ? $arrayInfoID3['id3v1']['artist']      : '';
            $this->info['audio']['sample_rate'] = isset($arrayInfoID3['audio']['sample_rate']) ? $arrayInfoID3['audio']['sample_rate'] : '';
            $this->info['bitrate']              = isset($arrayInfoID3['bitrate'])              ? $arrayInfoID3['bitrate']              : '';
            $this->info['playtime_string']      = isset($arrayInfoID3['playtime_string'])      ? $arrayInfoID3['playtime_string']      : '';
            $this->info['fileformat']           = isset($arrayInfoID3['fileformat'])           ? $arrayInfoID3['fileformat']           : '';
            $this->info['id3v1']['title']       = isset($arrayInfoID3['id3v1']['title'])       ? $arrayInfoID3['id3v1']['title']       : '';
        }

        if ($this->getTypeInfo() == 'video'){
            $this->info['video']['resolution_x'] = isset($arrayInfoID3['video']['resolution_x']) ? $arrayInfoID3['video']['resolution_x'] : '';
            $this->info['video']['resolution_y'] = isset($arrayInfoID3['video']['resolution_y']) ? $arrayInfoID3['video']['resolution_y'] : '';
            $this->info['video']['frame_rate']   = isset($arrayInfoID3['video']['frame_rate'])   ? $arrayInfoID3['video']['frame_rate']   : '';
            $this->info['bitrate']               = isset($arrayInfoID3['bitrate'])               ? $arrayInfoID3['bitrate']               : '';
            $this->info['fileformat']            = isset($arrayInfoID3['fileformat'])            ? $arrayInfoID3['fileformat']            : '';
        }
    }

    public function getTypeInfo(){
        $mime = isset($this->info['mime_type']) ? $this->info['mime_type'] : '';
        if (preg_match('/^audio/',  $mime)){ //если MIME тип 'audio' 
            return 'audio';
        } elseif (preg_match('/^video/',  $mime)) {//если MIME тип 'video' 
            return 'video'; 
        } else {
            return false;
        }
    }

    public function getInfoJson(){
        return json_encode($this->info);
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