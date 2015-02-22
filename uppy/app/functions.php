<?php
	function getErrorLoad($maxFileSize)
	{
		$errorLoad = new \Uppy\ErrorLoad;
		
		$errorLoad->setError($_FILES['file']['error']);
		$errorLoad->errorSize($_FILES['file']['size'], $maxFileSize);
		$errorLoad->errorUpload();
		return $errorLoad;
	}

	function createFile($errorLoad)
	{
		$file = new \Uppy\File;
		if ($errorLoad->getError() == false) {
            $file->name = trim($_FILES['file']['name']);
            $file->size = $_FILES['file']['size'];
            $file->tmpName= $_FILES['file']['tmp_name'];
            $file->key = $file->generateKey();
            $file->dateLoad = date("Y-m-d H:i:s");
        }
        return $file;
	}

	function resizeImage($filename, $uploadPath, $maxSize = 200)
	{
		$path = $uploadPath . $filename;
		list($width, $height) = getimagesize($path);

		if (($width > 0) and ($height > 0)) {
			if ($width > $height) {
				$newWidth = $maxSize;
				$newHeight = $maxSize * ($height/$width);
			} else {
				$newHeight = $maxSize;
				$newWidth = $maxSize * ($width/$height);
			}
		}

		// Загрузка
		$thumb = imagecreatetruecolor($newWidth, $newHeight);
		$source = imagecreatefromjpeg($path);
		//header('Content-Type: image/jpeg');
		// Масштабирование
		//imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
		imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
		// Вывод
		imagejpeg($thumb, 'uppy\\container\\' . '_' . $filename);
	}