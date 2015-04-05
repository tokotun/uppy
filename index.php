<?php
error_reporting(-1);
mb_internal_encoding("utf-8");
require 'vendor/autoload.php';

require 'uppy/app/functions.php';

$twigView = new \Slim\Views\Twig();

// Slim
$app = new \Slim\Slim(array(
    'dirHost' => __DIR__,
    'uploadPath' => 'uppy/container/', //путь к папке с хранимыми файлами
    'maxFileSize' => 33554432,      // 32 MB . Максимальный размер принимаемых файлов.
    'hostName' => 'http://localhost/uppy',  
    'dbHost' => 'localhost', //имя базы данных
    'dbUser' => 'root',      //имя пользователя базы данных
    'dbPassword' => 'root',  //пароль к базе данных
    'dbName' => 'uppy',      //имя базы данных
    'view' => $twigView,
    'templates.path' => 'uppy/templates/'
    
));
$app->container->singleton('pdo', function() use ($app){
    $dbc = 'mysql:host=' . $app->config('dbHost') . ';dbname=' . $app->config('dbName');
    $options = array( PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' ); 
    $pdo = new PDO($dbc, $app->config('dbUser'), $app->config('dbPassword'), $options);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
});

$app->container->singleton('fileMapper', function() use ($app){
    return new \Uppy\FileMapper($app->pdo); // $app->pdo вызывается через синглтон
});

$app->container->singleton('commentsMapper', function() use ($app){
    return new \Uppy\CommentsMapper($app->pdo); // $app->pdo вызывается через синглтон
});

$app->container->singleton('getID3', function() use ($app){
    $getID3 = new \getID3;
    $getID3->option_md5_data        = true;
    $getID3->option_md5_data_source = true;
    $getID3->encoding               = 'UTF-8';
    return $getID3;
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

            $fileName = $file->getFileNameInOS();
            

            $target = __DIR__ . '/' . $app->config('uploadPath') .  $fileName;
            
            if (move_uploaded_file($file->tmpName, $target)) {
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
    $app->render('main.html.twig', array(
        'files' => $files, 
        'hostName' => $app->config('hostName'))
    );
});

$app->get('/:key', function ($key) use ($app){

    $fileMapper = $app->fileMapper;
    $commentsMapper = $app->commentsMapper;
    $file = $fileMapper->loadFile($key);
    $comments = $commentsMapper->loadComments($file->id);

    //-------------------------------------------------------
    //Извлечение метаданных с помощью getID3()
    $filePath = 'uppy/container/' . $fileName = $file->getFileNameInOS(); 
    $getID3 = $app->getID3;
    $mediaInfo = new \Uppy\MediaInfo();
    $mediaInfo->info = $getID3->analyze($filePath);
    
    //-------------------------------------------------------
    $app->render('download.html.twig', 
        array('file' => $file, 
            'comments' => $comments, 
            'hostName' => $app->config('hostName'), 
            'mediaInfo' => $mediaInfo)
    );
});

$app->post('/:key', function ($key) use ($app){
    $fileMapper = $app->fileMapper;
    $commentsMapper = $app->commentsMapper;
    $newComment = \Uppy\Uploader::createComment();

    //если создан коментарий, то продолжаем заносить его в базу
    if ($newComment){
        $newComment->fileId = $fileMapper->getId($key);
        $idParentComment = \Uppy\Uploader::getIdParentComment();

        $newComment->getNewPathComment($commentsMapper, $idParentComment);
        
        $commentsMapper->saveComment($newComment);
    }

    header("Location: $key");
    die();
});

$app->get('/download/:key/:name', function ($key) use ($app){

    $fileMapper = $app->fileMapper;
    $file = $fileMapper->loadFile($key);

    fileForceDownload($file, $app->config('uploadPath'));
});

$app->run();
