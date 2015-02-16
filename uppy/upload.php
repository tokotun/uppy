<?php
    require 'uppy/app/bootstrap.php';
    if (isset($_FILES['file'])) {
        $f = array(
            'name'   => trim($_FILES['file']['name']),
            'size'   => $_FILES['file']['size'], 
            'tmpName'=> $_FILES['file']['tmp_name'],
            'error'=> $_FILES['file']['error'],
        );
    }

    $file = new File($f);
    
    if (isset($_POST['submit'])) {
     
        if (!$file->getError())
        {
            $target = __DIR__ . $uploadPath . $file->key;
            
            if (rename($file->tmpName, $target)) {
                $fileMapper->saveFile($file);
            }
        }


        /*header("Location: index.php");
        die();*/
    }

// Twig
$loader = new Twig_Loader_Filesystem('uppy/templates'); 
$twig = new Twig_Environment($loader, array(
    'cache' => 'vendor/twig/cache', 
    'auto_reload' => true
)); 
$template = $twig->loadTemplate('upload.html.twig');
echo $template->render( array('errorSize' => $file->errorSize, 'errorUpload' => $file->errorUpload,
    'hostName' => $hostName));
