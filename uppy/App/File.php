<?php
class File
{
    public $name = '';
    public $size = '';
    public $tmpName = '';
    public $key = '';
    public $dateLoad = '';

    public function generateKey()
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $numChars = strlen($chars);
        $key = '';
        for ($i = 0; $i < 10; $i++) {
            $key .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $key;
    }

    function fileForceDownload($dirHost) {
        $path = $dirHost . '\\' . $this->key;
        if (file_exists($path)) {
            // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
            // если этого не сделать файл будет читаться в память полностью!
            if (ob_get_level()) {
                ob_end_clean();
            }
            // заставляем браузер показать окно сохранения файла
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $this->name);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($this->size));
            // читаем файл и отправляем его пользователю
            readfile($path);
            exit;
        }
    }
}