<?php
require_once 'vendor/autoload.php';

// Slim
$app = new \Slim\Slim();

$app->get('/hello/:name', function ($name) {
    echo "Hello, $name";
});

$app->get('/', function () {
    require "uppy/upload.php";
});

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

$app->run();
