<?php
    function fileForceDownload(\Uppy\file $file, $uploadPath) {
    	$path = $uploadPath . $file->id . '_' . $file->name;
    	$path_to_somefile = realpath($path);
	  	if (file_exists($path)) {
	  	    header("X-SendFile:  $path_to_somefile");
		  	header("Content-Type: application/octet-stream");
		  	header("Content-Disposition: attachment; filename*=\"utf8'ru-ru'$file->name\"");
		   	exit;
  		}
  	}