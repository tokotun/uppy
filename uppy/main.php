<?php
	require 'uppy/app/bootstrap.php';

	$files = $fileMapper->getFiles();

// Twig
$loader = new Twig_Loader_Filesystem('uppy/x.twig'); 
$twig = new Twig_Environment($loader, array(
    'cache' => 'vendor/twig/cache', 
    'auto_reload' => true
)); 
$template = $twig->loadTemplate('main.html');
echo $template->render( array('files' => $files ) );
