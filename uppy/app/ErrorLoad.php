<?php
namespace Uppy;
Class ErrorLoad
{
    protected $error;
    protected $errorSize;

    public function __construct()
    {
        $this->error = true;
        $this->errorSize = '';
    }


    public function getError()
    {
        return $this->error;
    }

    public function setError($error)
    {
        if ($error == UPLOAD_ERR_OK) {
            $this->error = false;
        } else {
            $this->error = true;
        }
    }

    public function setErrorSize($fileSize, $maxFileSize)
    {
        if (($fileSize <= 0) or ($fileSize > $maxFileSize)) {
            $this->errorSize = 'The file no greater than ' . ($maxFileSize / 1024) . ' KB in size.';
            $this->error = true;
        }
    }

    public function getErrorSize()
    {
        
        return $this->errorSize;
    }
}