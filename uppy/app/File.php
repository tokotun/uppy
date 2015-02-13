<?php

class File
{
    public $name = '';
    public $size = '';
    public $tmpName = '';
    public $key = '';
    public $dateLoad = '';
    protected $error = true;
    public $errorSize = '';
    public $errorUpload = '';

    public function __construct()
    {   

        if (isset($_FILES['file'])) {
            $this->name   = trim($_FILES['file']['name']);
            $this->size   = $_FILES['file']['size']; 
            $this->error  = $_FILES['file']['error'];
            $this->tmpName= $_FILES['file']['tmp_name'];
            $this->key    = $this->generateKey();
            $this->dateLoad = date("Y-m-d H:i:s");
            $this->setError();
        }
    }

    public function getError()
    {
        return $this->error;
    }

    protected function setError()
    {
        $this->error = false;
        
        if (($this->size <= 0) or ($this->size > GW_MAXFILESIZE)) {
            $this->errorSize = 'The file no greater than ' . (GW_MAXFILESIZE / 1024) . ' KB in size.';
            $this->error = true;
        }

        if ($_FILES['file']['error'] <> 0) {
            $this->errorUpload = 'Sorry, there was a problem uploading your file.';
            $this->error = true;
        }
    }   

    protected function generateKey()
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $numChars = strlen($chars);
        $key = '';
        for ($i = 0; $i < 10; $i++) {
            $key .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $key;
    }

    public function setFile($f)
    {
        $this->name     = $f['name'];
        $this->key      = $f['key'];
        $this->dateLoad = $f['dateLoad'];
        $this->size     = $f['size'];
        $this->error = false;
    }
}