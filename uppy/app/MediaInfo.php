<?php
namespace Uppy;
/**
* Class for extracting information from media files with getID3().
*/

class MediaInfo {

    /**
    * Private variables
    */
    public $info   = NULL;


    function getTypeInfo(){
        $mime = $this->info['mime_type']; // возвращает mime-тип
        if (preg_match('/^audio/',  $mime)){ //если MIME тип 'audio' 
            return 'audio'; //эта функция вернёт информацию о медиа файле
        } elseif (preg_match('/^video/',  $mime)) {
            return 'video'; //эта функция вернёт информацию о медиа файле
        } else {
            return false;
        }
    }

    /**
    * Extract information
    *
    */
    public function AudioInfo() {
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

        return $this->result;
    }

    public function VideoInfo() {
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

        return $this->result;
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
