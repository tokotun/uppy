<?php
error_reporting(-1);
mb_internal_encoding("utf-8");
require 'vendor/autoload.php';

require 'uppy/app/functions.php';
require 'uppy/app/config.php';

$twigView = new \Slim\Views\Twig();

// Slim
$app = new \Slim\Slim(array(
    'dirHost' => __DIR__,
    'uploadPath' => $uploadPath, //путь к папке с хранимыми файлами
    'maxFileSize' => $maxFileSize,      // 8 MB . Максимальный размер принимаемых файлов.
    'hostName' => $hostName,  
    'dbHost' => $dbHost, //имя базы данных
    'dbUser' => $dbUser,      //имя пользователя базы данных
    'dbPassword' => $dbPassword,  //пароль к базе данных
    'dbName' => $dbName,      //имя базы данных
    'view' => $twigView,
    'templates.path' => $templatesPath
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

$app->container->singleton('uploader', function() use ($app){
    return new \Uppy\Uploader($app->config('uploadPath'));
});

$app->container->singleton('utilHelper', function() use ($app){
    return new \Uppy\UtilHelper($app->config('dirHost'), $app->config('hostName'), $app->config('uploadPath'));
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
//В каждом шаблоне будет находится имя хоста
$view->setData( 
        array( 'hostName' => $app->config('hostName') )
);

$app->notFound(function () use ($app) {
    $app->render('notFoundFile.html.twig', array() );
});

$app->get('/hello/:name', function ($name) {
    echo "Hello, $name";
});

$app->get('/', function () use ($app){
    $app->render('upload.html.twig', array());
});

$app->get('/upload', function () use ($app){
    $app->render('upload.html.twig', array());
});

$app->post('/upload',function () use ($app){
    $uploader = $app->uploader;
    $fileMapper = $app->fileMapper;
    $utilHelper = $app->utilHelper;
    $maxFileSize = $app->config('maxFileSize');
    $dirHost = $app->config('dirHost');
    $errorLoad = $uploader->validateLoadFile($maxFileSize);

    if (isset($_POST['submit']) and ($errorLoad->getError() == false)) {

        $getID3 = $app->getID3;   
        $file = $uploader->createFile($getID3);
    
        if ($uploader->saveFile($file, $fileMapper, $utilHelper)){
            $app->response->redirect("file/$file->key", 301);
        } else {
            $errorLoad->setErrorMoveFile();
        }
    }
    $app->render('upload.html.twig', array( 
       'errorLoad' => $errorLoad )
    );
});

$app->get('/main', function () use ($app){
    $utilHelper = $app->utilHelper;
    $fileMapper = $app->fileMapper;
    $listFiles = $fileMapper->getFiles();
    $app->render('main.html.twig', array(
        'listFiles' => $listFiles,
        'utilHelper' => $utilHelper)
    );
});

$app->get('/file/:key', function ($key) use ($app){
    $fileMapper = $app->fileMapper;
    $commentsMapper = $app->commentsMapper;
    $utilHelper = $app->utilHelper;
    $file = $fileMapper->loadFile($key);
    if ($file) {
        $comments = $commentsMapper->loadComments($file->id);
        $app->render('download.html.twig', array(
            'file' => $file, 
            'comments' => $comments,
            'utilHelper' => $utilHelper)
        );
    } else {
        $app->notFound();
    }
});

$app->post('/file/:key', function ($key) use ($app){
    $uploader = $app->uploader;
    $fileMapper = $app->fileMapper;
    $commentsMapper = $app->commentsMapper;

    
    if ($app->request->post('comment') <> '')  {
            $newComment = new \Uppy\Comment;
            $newComment->message= $app->request->post('comment');
            $newComment->dateLoad = time();
    } else {
        $newComment = false;
    }

    //если создан коментарий, то продолжаем заносить его в базу
    if ($newComment){
        $newComment->fileId = $fileMapper->getId($key);
        $idParentComment = $uploader->getIdParentComment();

        $newComment->getNewPathComment($commentsMapper, $idParentComment);

        $commentsMapper->saveComment($newComment);
        $app->response->redirect("$key", 301);
    }
    $app->response->redirect("$key", 301);
});

$app->get('/download/file/:key/:name', function ($key) use ($app){

    $fileMapper = $app->fileMapper;
    $file = $fileMapper->loadFile($key);

    fileForceDownload($file, $app->config('uploadPath'));
});


$app->run();
