<?php
    require 'uppy/app/bootstrap.php';
    $file = new File;
    
    if (isset($_POST['submit'])) {
     
        if (!$file->getError())
        {
            $target = __DIR__ . GW_UPLOADPATH . $file->key;
            
            if (rename($file->tmpName, $target)) {
                $fileMapper->saveFile($file);
            }
        }
        // Try to delete the temporary file
        @unlink($_FILES['file']['tmp_name']);

        /*header("Location: index.php");
        die();*/
    }

// Twig
$loader = new Twig_Loader_Filesystem('uppy/x.twig'); 
$twig = new Twig_Environment($loader, array(
    'cache' => 'vendor/twig/cache', 
    'auto_reload' => true
)); 
$template = $twig->loadTemplate('upload.html');
echo $template->render( array('errorSize' => $file->errorSize, 'errorUpload' => $file->errorUpload));
