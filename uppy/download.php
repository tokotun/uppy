<?php
	require 'uppy/app/bootstrap.php';

	$file = new File;
	$f = $fileMapper->loadFile($key);
	
	$file->setFile($f);
// Twig
$loader = new Twig_Loader_Filesystem('uppy/x.twig'); 
$twig = new Twig_Environment($loader, array(
    'cache' => 'vendor/twig/cache', 
    'auto_reload' => true
)); 
$template = $twig->loadTemplate('download.html');
echo $template->render( array('file' => $file) );
