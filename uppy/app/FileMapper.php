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
        $sql = "INSERT INTO files VALUES (NULL, :name, :key, :dateLoad, :size)";
        $statment = $this->db->prepare($sql);
        $statment->bindValue(':name', $file->name);
        $statment->bindValue(':key', $file->key);
        $statment->bindValue(':dateLoad', $file->dateLoad);
        $statment->bindValue(':size', $file->size);
        $statment->execute();
    }

    public function getFiles()
    {
        $sql = "SELECT `name`, `size`, `key` FROM files ORDER BY dateLoad DESC LIMIT 100";
        $statment = $this->db->prepare($sql);
        $statment->execute();
        $result = $statment->fetchAll(\PDO::FETCH_ASSOC);

        $files = array();
        foreach ($result as $r) {
            $file = new \Uppy\File;
            $file->name = $r['name'];
            $file->size = $r['size'];
            $file->key = $r['key'];
            $files[] = $file;
        }
            
        return $files;
    }


    public function loadFile($key)
    {
        $sql = "SELECT * FROM files WHERE `key`=:key";
        $statment = $this->db->prepare($sql);
        $statment->bindValue(':key', $key);
        $statment->execute();
        $result = $statment->fetch(\PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        }

        $file = new \Uppy\File;
        $file->name = $result['name'];
        $file->size = $result['size'];
        $file->key = $result['key'];
        $file->dateLoad = $result['dateLoad'];
        return $file;
    }
}