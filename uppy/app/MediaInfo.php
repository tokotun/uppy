<?php
namespace Uppy;
class MediaInfo
{   
    public $info = array();

	public function moveJsonInfoInFile($jsonInfoID3) 
    {
        if (isset($jsonInfoID3)){
            $arrayInfoID3 = json_decode($jsonInfoID3, true);
            return $this->moveArrayInfoInFile($arrayInfoID3);
        }    
    }

    public function moveArrayInfoInFile($id3)
    {
    	$this->info = array();
        if (isset($id3['mime_type'])){
            $this->info['mime_type'] = isset($id3['mime_type']) ? $id3['mime_type'] : '';
        } else {
            return;
        }

        if ( $this->getTypeInfo() == 'audio'){
            $this->info['id3v1']['genre']       = isset($id3['id3v1']['genre'])       ? $id3['id3v1']['genre']       : '';
            $this->info['id3v1']['album']       = isset($id3['id3v1']['album'])       ? $id3['id3v1']['album']       : '';
            $this->info['id3v1']['comment']     = isset($id3['id3v1']['comment'])     ? $id3['id3v1']['comment']     : '';
            $this->info['id3v1']['year']        = isset($id3['id3v1']['year'])        ? $id3['id3v1']['year']        : '';
            $this->info['audio']['channels']    = isset($id3['audio']['channels'])    ? $id3['audio']['channels']    : '';
            $this->info['id3v1']['artist']      = isset($id3['id3v1']['artist'])      ? $id3['id3v1']['artist']      : '';
            $this->info['audio']['sample_rate'] = isset($id3['audio']['sample_rate']) ? $id3['audio']['sample_rate'] : '';
            $this->info['bitrate']              = isset($id3['bitrate'])              ? $id3['bitrate']              : '';
            $this->info['playtime_string']      = isset($id3['playtime_string'])      ? $id3['playtime_string']      : '';
            $this->info['fileformat']           = isset($id3['fileformat'])           ? $id3['fileformat']           : '';
            $this->info['id3v1']['title']       = isset($id3['id3v1']['title'])       ? $id3['id3v1']['title']       : '';
        }

        if ( $this->getTypeInfo()  == 'video'){
            $this->info['video']['resolution_x'] = isset($id3['video']['resolution_x']) ? $id3['video']['resolution_x'] : '';
            $this->info['video']['resolution_y'] = isset($id3['video']['resolution_y']) ? $id3['video']['resolution_y'] : '';
            $this->info['video']['frame_rate']   = isset($id3['video']['frame_rate'])   ? $id3['video']['frame_rate']   : '';
            $this->info['bitrate']               = isset($id3['bitrate'])               ? $id3['bitrate']               : '';
            $this->info['fileformat']            = isset($id3['fileformat'])            ? $id3['fileformat']            : '';
        }
    }

    public function getTypeInfo(){
        if (!isset($this->info['mime_type'])) {
            return false;
        }
        if (preg_match('/^audio/',  $this->info['mime_type'])){ //если MIME тип 'audio' 
            return 'audio';
        } elseif (preg_match('/^video/',  $this->info['mime_type'])) {//если MIME тип 'video' 
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