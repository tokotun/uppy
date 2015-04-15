<?php
namespace Uppy;
/**
* Класс для извлечения иформации getID3().
*/

class MediaInfo {

    protected $info;

    public function __construct(array $ID3)
    {
        if (isset($ID3['mime_type'])){
            $this->info['mime_type'] = isset($ID3['mime_type']) ? $ID3['mime_type'] : '';
        } else {
            $this->info = null;
        }

        if ($this->getTypeInfo() == 'audio'){
            $this->info['id3v1']['genre']       = isset($ID3['id3v1']['genre'])       ? $ID3['id3v1']['genre']       : '';
            $this->info['id3v1']['album']       = isset($ID3['id3v1']['album'])       ? $ID3['id3v1']['album']       : '';
            $this->info['id3v1']['comment']     = isset($ID3['id3v1']['comment'])     ? $ID3['id3v1']['comment']     : '';
            $this->info['id3v1']['year']        = isset($ID3['id3v1']['year'])        ? $ID3['id3v1']['year']        : '';
            $this->info['audio']['channels']    = isset($ID3['audio']['channels'])    ? $ID3['audio']['channels']    : '';
            $this->info['id3v1']['artist']      = isset($ID3['id3v1']['artist'])      ? $ID3['id3v1']['artist']      : '';
            $this->info['audio']['sample_rate'] = isset($ID3['audio']['sample_rate']) ? $ID3['audio']['sample_rate'] : '';
            $this->info['bitrate']              = isset($ID3['bitrate'])              ? $ID3['bitrate']              : '';
            $this->info['playtime_string']      = isset($ID3['playtime_string'])      ? $ID3['playtime_string']      : '';
            $this->info['fileformat']           = isset($ID3['fileformat'])           ? $ID3['fileformat']           : '';
            $this->info['id3v1']['title']       = isset($ID3['id3v1']['title'])       ? $ID3['id3v1']['title']       : '';
        }

        if ($this->getTypeInfo() == 'video'){
            $this->info['video']['resolution_x'] = isset($ID3['video']['resolution_x']) ? $ID3['video']['resolution_x'] : '';
            $this->info['video']['resolution_y'] = isset($ID3['video']['resolution_y']) ? $ID3['video']['resolution_y'] : '';
            $this->info['video']['frame_rate']   = isset($ID3['video']['frame_rate'])   ? $ID3['video']['frame_rate']   : '';
            $this->info['bitrate']               = isset($ID3['bitrate'])               ? $ID3['bitrate']               : '';
            $this->info['fileformat']            = isset($ID3['fileformat'])            ? $ID3['fileformat']            : '';
        }
    }

    function getTypeInfo(){
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
