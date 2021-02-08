<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL & ~E_NOTICE);

session_start(); 

require_once "app/config.php";

require_once "app/init.php";

$app = new App;