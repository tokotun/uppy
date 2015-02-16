<?php
require_once 'vendor/autoload.php';

$twigView = new \Slim\Extras\Views\Twig();

// Slim
$app = new \Slim\Slim(array(
    'view' => $twigView,
    'templates.path' => 'uppy/templates/'
));


$app->get('/hello/:name', function ($name) {
    echo "Hello, $name";
});

$app->get('/', function () use ($app){

    echo $app->render('upload.html.twig');

});

/*
$app->get('/upload', function () {
    require "uppy/upload.php";
});

$app->post('/upload',function () {
    require "uppy/upload.php";
});

$app->get('/main', function () {
    require "uppy/main.php";
});

$app->get('/:key', function ($key) {
    require "uppy/download.php";
});
*/
$app->run();
