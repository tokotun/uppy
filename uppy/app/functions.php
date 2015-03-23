<?php
    function fileForceDownload(\Uppy\file $file, $uploadPath) {
    	$path = $uploadPath . $file->key;
	  	if (file_exists($path)) {
	  	    header('X-SendFile: ' . realpath($path));
		  	header('Content-Type: application/octet-stream');
		  	header('Content-Disposition: attachment; filename=' . $file->name);
		   	exit;
  		}
  	}