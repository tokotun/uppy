<?php
    function fileForceDownload(\Uppy\file $file, $uploadPath) {
        $path = $uploadPath . $file->getFileNameInOS();
        $pathToSomefile = $file->getSavedName();

        if (file_exists($path)) {
            header("X-SendFile:  $pathToSomefile");
            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment;");
            exit;
        }
    }