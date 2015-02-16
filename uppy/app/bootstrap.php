<?php
    require 'uppy/app/connectvars.php';
    require_once 'uppy/app/autoloader.php';
    spl_autoload_register('autoloader');

    $dbc = 'mysql:host=' . $dbHost . ';dbname=' . $dbName;
    $pdo = new PDO($dbc, $dbUser, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $fileMapper = new FileMapper($pdo);
    