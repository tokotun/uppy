<?php
namespace Uppy\\;
Class ErrorLoad
{
    public $error = true;
    public $errorSize = '';
    public $errorUpload = '';

    public function getError()
    {
        return $this->error;
    }

    public function setError($error)
    {
        if ($error == 0) {
            $this->error = false;
        } else {
            $this->error = true;
        }
    }

    public function errorSize($fileSize, $maxFileSize)
    {
        if (($fileSize <= 0) or ($fileSize > $maxFileSize)) {
            $this->errorSize = 'The file no greater than ' . ($maxFileSize / 1024) . ' KB in size.';
            $this->error = true;
        }
    }

    public function errorUpload()
    {   
        if ($this->error == true) {
            $this->errorUpload = 'Sorry, there was a problem uploading your file.';
        }
    }
}