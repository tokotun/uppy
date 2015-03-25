<?php
    function fileForceDownload(\Uppy\file $file, $uploadPath) {
    	$path = $uploadPath . $file->getNameForSave();
    	$path_to_somefile = $file->id . '_' . $file->name;

	  	if (file_exists($path)) {
	  	    header("X-SendFile:  $path_to_somefile");
		  	header("Content-Type: application/octet-stream");
		  	header("Content-Disposition: attachment;");
		   	exit;
  		}
  	}