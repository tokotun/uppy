<?php
namespace Uppy;
class FileMapper
{
    protected $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }


    public function getLastId()
    {
        $sql = "SELECT MAX(`id`) FROM `files`";
        $statment = $this->db->prepare($sql);
        $statment->execute();
        $result = $statment->fetchColumn();
        return ($result + 1);
    }

    public function saveFile(\Uppy\File $file, $jsonID3)
    {

        $sql = "INSERT INTO files VALUES (NULL, :name, :file_key, FROM_UNIXTIME(:dateLoad), :size)";
        $statment = $this->db->prepare($sql);
        $statment->bindValue(':name', $file->name);
        $statment->bindValue(':file_key', $file->key);
        $statment->bindValue(':dateLoad', $file->dateLoad);
        $statment->bindValue(':size', $file->size);
        $statment->execute();

        $lastId = $this->db->lastInsertId();

        $sql = "INSERT INTO file_info VALUES (NULL, :file_id, :ID3)";
        $statment = $this->db->prepare($sql);
        $statment->bindValue(':file_id', $lastId);
        $statment->bindValue(':ID3', $jsonID3);
        $statment->execute();
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

    public function getFiles()
    {
        $sql = "SELECT `name`, `size`, `file_key` FROM files ORDER BY dateLoad DESC LIMIT 100";
        $statment = $this->db->prepare($sql);
        $statment->execute();
        $result = $statment->fetchAll(\PDO::FETCH_ASSOC);

        $files = array();
        foreach ($result as $r) {
            $file = new \Uppy\File;
            $file->name = $r['name'];
            $file->size = $r['size'];
            $file->key = $r['file_key'];
            $files[] = $file;
        }
            
        return $files;
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
        $file = new \Uppy\File;
        $file->id = $result['id'];
        $file->name = $result['name'];
        $file->size = $result['size'];
        $file->key = $result['file_key'];
        $file->dateLoad = strtotime($result['dateLoad']);
        return $file;
    }
}