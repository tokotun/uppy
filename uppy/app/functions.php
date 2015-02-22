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

	function resizeImage($filename, $newWidth = 140, $newHeight = 140)
	{
		// Тип содержимого
		
		list($width, $height) = getimagesize($filename);

		// Загрузка
		$thumb = imagecreatetruecolor($newWidth, $newHeight);
		$source = imagecreatefromjpeg($filename);
		header('Content-Type: image/jpeg');
		// Масштабирование
		imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
		//imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
		// Вывод
		imagejpeg($thumb);
		
		//return $thumb;
	}