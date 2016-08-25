<?php
    session_start();
    require_once __DIR__.'/config.php';
    require_once __DIR__.'/functions.php';
    $db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=UTF8', DB_USER, DB_PASS);