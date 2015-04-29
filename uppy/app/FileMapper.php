<?php
namespace Uppy;
class FileMapper
{
    protected $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function saveFile(\Uppy\File $file)
    {
        $sql = "INSERT INTO files VALUES (NULL, :name, :file_key, FROM_UNIXTIME(:date_load), :size, :id3)";
        $statment = $this->db->prepare($sql);
        $statment->bindValue(':name', $file->name);
        $statment->bindValue(':file_key', $file->key);
        $statment->bindValue(':date_load', $file->dateLoad);
        $statment->bindValue(':size', $file->size);
        $statment->bindValue(':id3', $file->mediaInfo->getInfoJson());
        $statment->execute();

        $lastId = $this->db->lastInsertId();
        $file->id = $lastId;
    }

    public function getId($key)
    {
        $sql = "SELECT `id` FROM files WHERE `file_key`=:file_key";
        $statment = $this->db->prepare($sql);
        $statment->bindValue(':file_key', $key);
        $statment->execute();
        $result = $statment->fetchColumn();

        return $result;
    }

    public function selectFile($result)
    {
        $file = new \Uppy\File;
        $file->id = $result['id'];
        $file->name = $result['name'];
        $file->size = $result['size'];
        $file->key = $result['file_key'];
        $file->dateLoad = strtotime($result['date_load']);
        $file->mediaInfo->moveJsonInfoInFile($result['id3']);
        return $file;
    }

    public function getFiles()
    {
        $sql = "SELECT * FROM files ORDER BY date_load DESC LIMIT 100";
        $statment = $this->db->prepare($sql);
        $statment->execute();
        $results = $statment->fetchAll(\PDO::FETCH_ASSOC);
        $listFiles = array();
        foreach ($results as $result) {
            $listFiles[] = $this->selectFile($result);
        }
            
        return $listFiles;
    }

    public function loadFile($key)
    {
        $sql = "SELECT *  FROM files WHERE `file_key`=:file_key";
        $statment = $this->db->prepare($sql);
        $statment->bindValue(':file_key', $key);
        $statment->execute();
        $result = $statment->fetch(\PDO::FETCH_ASSOC);
        
        if (!$result) {
            return false;
        }
        
        return $this->selectFile($result);
    }
}