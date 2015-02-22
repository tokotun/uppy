<?php
$loader = require 'vendor/autoload.php';
$loader->add('app\\FileMapper\\', __DIR__);

require 'uppy/app/functions.php';

$twigView = new \Slim\Views\Twig();

// Slim
$app = new \Slim\Slim(array(
	'dirHost' => __DIR__,
	'uploadPath' => 'uppy/container/', //путь к папке с хранимыми файлами
	'maxFileSize' => 1048576,      // 1024 KB
	'hostName' => 'http://localhost/uppy',  
	'dbHost' => 'localhost', //имя базы данных
	'dbUser' => 'root',      //имя пользователя базы данных
	'dbPassword' => 'root',  //пароль к базе данных
	'dbName' => 'uppy',      //имя базы данных
    'view' => $twigView,
    'templates.path' => 'uppy/templates/'
    
));

$app->container->singleton('fileMapper', function() use ($app){
	$dbc = 'mysql:host=' . $app->config('dbHost') . ';dbname=' . $app->config('dbName');
    $pdo = new PDO($dbc, $app->config('dbUser'), $app->config('dbPassword'));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $fileMapper = new \Uppy\FileMapper($pdo);
});

$view = $app->view();
$view->parserOptions = array(
    'debug' => true,
    'cache' => dirname(__FILE__) . '/vendor/twig/cache',
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
    $errorLoad = getErrorLoad($maxFileSize);
	$file = createFile($errorLoad);
    
    if (isset($_POST['submit'])) {
        if ($errorLoad->getError() == false)
        {
            $target = __DIR__ . '/' . $app->config('uploadPath') . $file->key;
            if (rename($file->tmpName, $target)) {
                $fileMapper->saveFile($file);
            }
        }

        header("Location: $file->key");
        die();
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
	$fileMapper = $app->fileMapper;
	$file = $fileMapper->loadFile($key);

	if (getimagesize($app->config('uploadPath') . '/' . $file->key)){
		resizeImage($file->key, $app->config('uploadPath'));
	}
    $app->render('download.html.twig', array('file' => $file, 'hostName' => $app->config('hostName')) );
});

$app->get('/download/:key', function ($key) use ($app){
	$fileMapper = $app->fileMapper;
	$file = $fileMapper->loadFile($key);
	print_r($file); echo '<br>';
	print_r($app->config('dirHost')); echo '<br>';
	$file->fileForceDownload($app->config('dirHost'));
	header("Location: $file->key");
    die();

});

$app->run();
