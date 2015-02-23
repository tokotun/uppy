<?php
namespace Uppy;
Class Uploader
{

	public static function createFile(\Uppy\ErrorLoad $errorLoad)
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

	public static function resizeImage($filename, $uploadPath, $maxSize = 200)
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
}