<?php

class FileMapper
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function saveFile(File $file)
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
        $sql = "SELECT `name`, `key` FROM files";
        $statment = $this->db->prepare($sql);
        $statment->execute();   
        $result = $statment->fetchAll(PDO::FETCH_ASSOC); 
        return $result;   
    }

/**
* 
*/
    public function loadFile($key)
    {   
        $sql = "SELECT * FROM files WHERE `key`=:key";
        $statment = $this->db->prepare($sql);
        $statment->bindValue(':key', $key);
        $statment->execute();   
        $result = $statment->fetch(PDO::FETCH_ASSOC); 
        return $result;   
    }

}
