<?php
namespace Uppy;
/**
* Class for extracting information from media files with getID3().
*/

class MediaInfo {

    /**
    * Private variables
    */
    private $result = NULL;
    private $info   = NULL;

    /**
    * Constructor
    */

    public function __construct() {
        // Initialize getID3 engine
        $this->getID3 = new \getID3;
        $this->getID3->option_md5_data        = true;
        $this->getID3->option_md5_data_source = true;
        $this->getID3->encoding               = 'UTF-8';
    }


    function getMediaInfo($filePath){
        $finfo = finfo_open(FILEINFO_MIME_TYPE); // возвращает mime-тип
        if (preg_match('/^audio/',  finfo_file($finfo, $filePath))){ //если MIME тип 'audio' 
            finfo_close($finfo); 
            return $this->AudioInfo($filePath); //эта функция вернёт информацию о медиа файле
        } elseif (preg_match('/^video/',  finfo_file($finfo, $filePath))) {
            finfo_close($finfo); 
            return $this->VideoInfo($filePath); //эта функция вернёт информацию о медиа файле
        } else {
            return false;
        }
    }

    /**
    * Extract information - only public function
    *
    */
    public function AudioInfo($file) {

        // Analyze file
        $this->info = $this->getID3->analyze($file);

        // Exit here on error
        if (isset($this->info['error'])) {
            return array ('error' => $this->info['error']);
        }

        // Init wrapper object
        $this->result = array();
        $this->result['type']            =  'audio';
        $this->result['title']           = (isset($this->info['id3v1']['title'])        ? $this->info['id3v1']['title']         : '');
        $this->result['artist']          = (isset($this->info['id3v1']['artist'])       ? $this->info['id3v1']['artist']        : '');
        $this->result['album']           = (isset($this->info['id3v1']['album'])        ? $this->info['id3v1']['album']         : '');
        $this->result['year']            = (isset($this->info['id3v1']['year'])         ? $this->info['id3v1']['year']          : '');
        $this->result['comment']         = (isset($this->info['id3v1']['comment'])      ? $this->info['id3v1']['comment']       : '');
        $this->result['track_number']    = (isset($this->info['id3v1']['track'])        ? $this->info['id3v1']['track']         : '');
        $this->result['genre']           = (isset($this->info['id3v1']['genre'])        ? $this->info['id3v1']['genre']         : '');
        $this->result['mime_type']       = (isset($this->info['mime_type'])             ? $this->info['mime_type']              : '');
        $this->result['playtime_string'] = (isset($this->info['playtime_string'])       ? $this->info['playtime_string']        : '');
        $this->result['bitrate']         = (isset($this->info['bitrate'])               ? round(($this->info['bitrate']/1000)).'kbps'               : '');
        $this->result['sample_rate']     = (isset($this->info['audio']['sample_rate'])  ? round(($this->info['audio']['sample_rate']/1000)).'kHz'   : '');
        $this->result['channels']        = (isset($this->info['audio']['channels'])     ? $this->info['audio']['channels']      : '');
        $this->result['dataformat']      = (isset($this->info['audio']['dataformat'])   ? $this->info['audio']['dataformat']    : '');

        // Post getID3() data handling based on file format
        $method = (isset($this->info['fileformat']) ? $this->info['fileformat'] : '').'Info';
        if ($method && method_exists($this, $method)) {
            $this->$method();
        }

        return $this->result;
    }

    function VideoInfo($file) {
        // Analyze file
        $this->info = $this->getID3->analyze($file);

        //var_dump($this->info);
        // Exit here on error
        if (isset($this->info['error'])) {
            return array ('error' => $this->info['error']);
        }

        // Init wrapper object
        $this->result = array();
        $this->result['type']               =   'video';
        $this->result['playtime_seconds']   = (isset($this->info['playtime_seconds'])       ? round($this->info['playtime_seconds']).'сек'  : '');
        $this->result['dataformat']         = (isset($this->info['video']['dataformat'])    ? $this->info['video']['dataformat']            : '');
        $this->result['bitrate']            = (isset($this->info['bitrate'])                ? round($this->info['bitrate']/1024) . "kbps"   : '');
        $this->result['resolution_x']       = (isset($this->info['video']['resolution_x'])  ? $this->info['video']['resolution_x']          : '');
        $this->result['resolution_y']       = (isset($this->info['video']['resolution_y'])  ? $this->info['video']['resolution_y']          : '');
        $this->result['frame_rate']         = (isset($this->info['video']['frame_rate'])    ? round($this->info['video']['frame_rate'])     : '');
        
        // Post getID3() data handling based on file format
        $method = (isset($this->info['fileformat']) ? $this->info['fileformat'] : '').'Info';
        if ($method && method_exists($this, $method)) {
            $this->$method();
        }

        return $this->result;
    }
}
