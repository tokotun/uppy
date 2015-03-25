<?php
error_reporting(-1);
require 'vendor/autoload.php';

require 'uppy/app/functions.php';

$twigView = new \Slim\Views\Twig();

// Slim
$app = new \Slim\Slim(array(
	'dirHost' => __DIR__,
	'uploadPath' => 'uppy\\container\\', //путь к папке с хранимыми файлами
	'maxFileSize' => 33554432,      // 32 MB
	'hostName' => 'http://localhost/uppy',  
	'dbHost' => 'localhost', //имя базы данных
	'dbUser' => 'root',      //имя пользователя базы данных
	'dbPassword' => 'root',  //пароль к базе данных
	'dbName' => 'uppy',      //имя базы данных
    'view' => $twigView,
    'templates.path' => 'uppy\\templates\\'
    
));

$app->container->singleton('fileMapper', function() use ($app){
	$dbc = 'mysql:host=' . $app->config('dbHost') . ';dbname=' . $app->config('dbName');
    $pdo = new PDO($dbc, $app->config('dbUser'), $app->config('dbPassword'));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return new \Uppy\FileMapper($pdo);
});

$app->container->singleton('commentsMapper', function() use ($app){
    $dbc = 'mysql:host=' . $app->config('dbHost') . ';dbname=' . $app->config('dbName');
    $pdo = new PDO($dbc, $app->config('dbUser'), $app->config('dbPassword'));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return new \Uppy\CommentsMapper($pdo);
});

$view = $app->view();
$view->parserOptions = array(
    'debug' => true,
    'cache' => dirname(__FILE__) . '/tmp/cache',
    'auto_reload' => true
);

$view->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
);

$app->get('/hello/:name', function ($name) {
    echo "Hello, $name";
});

$app->get('/', function () use ($app){
    $app->render('upload.html.twig', array( 'hostName' => $app->config('hostName')));
});

$app->get('/upload', function () use ($app){
	$app->render('upload.html.twig', array( 'hostName' => $app->config('hostName')));
});

$app->post('/upload',function () use ($app){
		
    $fileMapper = $app->fileMapper;
    $maxFileSize = $app->config('maxFileSize');
    $errorLoad = \Uppy\ErrorLoad::validateLoadFile($maxFileSize);
	$file = \Uppy\Uploader::createFile($errorLoad);
    
    if (isset($_POST['submit'])) {
        if ($errorLoad->getError() == false)
        {
            $file->id = $fileMapper->lastId();

            $fileName = $file->getNameForSave();

            $target = __DIR__ . '/' . $app->config('uploadPath') .  $fileName;
            
            if (rename($file->tmpName, $target)) {
                $fileMapper->saveFile($file);

                
                if (getimagesize($app->config('uploadPath') . $fileName)){
                    
					\Uppy\Uploader::resizeImage($fileName, $app->config('uploadPath'));
				}
            }
            header("Location: $file->key");
            die();
        }
    }
    $app->render('upload.html.twig', array( 
    	'hostName' => $app->config('hostName'), 
    	'errorSize' => $errorLoad->errorSize,
    	'errorUpload' => $errorLoad->errorUpload)
    );
});

$app->get('/main', function () use ($app){
    $fileMapper = $app->fileMapper;
    $files = $fileMapper->getFiles();
    $app->render('main.html.twig', array('files' => $files, 'hostName' => $app->config('hostName')));
});

$app->get('/:key', function ($key) use ($app){
	$isImage = false;

	$fileMapper = $app->fileMapper;
    $commentsMapper = $app->commentsMapper;
	$file = $fileMapper->loadFile($key);
    $comments = $commentsMapper->loadComments($file->id);

    //-------------------------------------------------------
    //Извлечение медаданных с помощью getID3()
    $filePath = 'uppy/container/' . $fileName = $file->getNameForSave(); 

    $au = new \Uppy\MediaInfo();
    $mediaInfo = $au->getMediaInfo($filePath);

    //-------------------------------------------------------
    $app->render('download.html.twig', 
        array('file' => $file, 
            'comments' => $comments, 
            'hostName' => $app->config('hostName'), 
            'mediaInfo' => $mediaInfo)
    );
});

$app->get('/download/:key/:name', function ($key) use ($app){

	$fileMapper = $app->fileMapper;
	$file = $fileMapper->loadFile($key);
	fileForceDownload($file, $app->config('uploadPath'));
});

$app->run();
