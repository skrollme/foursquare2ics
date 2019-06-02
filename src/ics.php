<?php
    error_reporting(E_ALL);
    require __DIR__. '/../etc/config.php';
    require_once __DIR__.'/../vendor/autoload.php';

    // MYSQL
    $db = new mysqli(MYSQL_HOST,MYSQL_USER, MYSQL_PASS,MYSQL_DB);
    if ($db->connect_errno) {
        die('Error connecting to MySQL: ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
    }

