<?php
namespace Uppy;
class MediaInfo
{   
	public static function moveJsonInfoInFile($jsonInfoID3) 
    {
        if (isset($jsonInfoID3)){
            $arrayInfoID3 = json_decode($jsonInfoID3, true);
            return \Uppy\MediaInfo::moveArrayInfoInFile($arrayInfoID3);
        } else {
            return null;
        }    
    }

    public static function moveArrayInfoInFile($id3)
    {
    	$info = array();
        if (isset($id3['mime_type'])){
            $info['mime_type'] = isset($id3['mime_type']) ? $id3['mime_type'] : '';
        } else {
            return null;
        }

        if (\Uppy\MediaInfo::getTypeInfo($info['mime_type']) == 'audio'){
            $info['id3v1']['genre']       = isset($id3['id3v1']['genre'])       ? $id3['id3v1']['genre']       : '';
            $info['id3v1']['album']       = isset($id3['id3v1']['album'])       ? $id3['id3v1']['album']       : '';
            $info['id3v1']['comment']     = isset($id3['id3v1']['comment'])     ? $id3['id3v1']['comment']     : '';
            $info['id3v1']['year']        = isset($id3['id3v1']['year'])        ? $id3['id3v1']['year']        : '';
            $info['audio']['channels']    = isset($id3['audio']['channels'])    ? $id3['audio']['channels']    : '';
            $info['id3v1']['artist']      = isset($id3['id3v1']['artist'])      ? $id3['id3v1']['artist']      : '';
            $info['audio']['sample_rate'] = isset($id3['audio']['sample_rate']) ? $id3['audio']['sample_rate'] : '';
            $info['bitrate']              = isset($id3['bitrate'])              ? $id3['bitrate']              : '';
            $info['playtime_string']      = isset($id3['playtime_string'])      ? $id3['playtime_string']      : '';
            $info['fileformat']           = isset($id3['fileformat'])           ? $id3['fileformat']           : '';
            $info['id3v1']['title']       = isset($id3['id3v1']['title'])       ? $id3['id3v1']['title']       : '';
        }

        if (\Uppy\MediaInfo::getTypeInfo($info['mime_type']) == 'video'){
            $info['video']['resolution_x'] = isset($id3['video']['resolution_x']) ? $id3['video']['resolution_x'] : '';
            $info['video']['resolution_y'] = isset($id3['video']['resolution_y']) ? $id3['video']['resolution_y'] : '';
            $info['video']['frame_rate']   = isset($id3['video']['frame_rate'])   ? $id3['video']['frame_rate']   : '';
            $info['bitrate']               = isset($id3['bitrate'])               ? $id3['bitrate']               : '';
            $info['fileformat']            = isset($id3['fileformat'])            ? $id3['fileformat']            : '';
        }
    }

    public static function getTypeInfo($mime){
        if (preg_match('/^audio/',  $mime)){ //если MIME тип 'audio' 
            return 'audio';
        } elseif (preg_match('/^video/',  $mime)) {//если MIME тип 'video' 
            return 'video'; 
        } else {
            return false;
        }
    }

    public static function getInfoJson($info){
        return json_encode($info);
    }
}