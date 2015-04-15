<?php
namespace Uppy;
class MediaInfoMapper
{
    protected $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function loadMediaInfo($fileId)
    {

        $sql = "SELECT `ID3`  FROM file_info WHERE `file_id`=:file_id";
        $statment = $this->db->prepare($sql);
        $statment->bindValue(':file_id', $fileId);
        $statment->execute();
        $result = $statment->fetchColumn();
        
        if ((!$result) or ($result == 'null')) {
            return false;
        }

        $ID3 = json_decode($result, true);

        $mediaInfo = new \Uppy\MediaInfo($ID3);
        return $mediaInfo;
    }
}