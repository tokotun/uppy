<?php
	function createFile(\Uppy\ErrorLoad $errorLoad)
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
		imagejpeg($thumb, 'uppy\\container\\thumbs\\' . $filename);
	}

	function fileForceDownload(\Uppy\file $file, $dirHost) 
    {
        $path = $dirHost . '\\uppy\\container\\' . $file->key;
        if (file_exists($path)) {
            // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
            // если этого не сделать файл будет читаться в память полностью!
            if (ob_get_level()) {
                ob_end_clean();
            }
            // заставляем браузер показать окно сохранения файла
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $file->name);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . $file->size);
            // читаем файл и отправляем его пользователю
            readfile($path);
            exit;
        }
    }