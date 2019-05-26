<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ob_start();
ob_clean();
date_default_timezone_set('Europe/London');
$path = realpath(dirname(__FILE__));
include_once ''.$path.'/jwt_helper.php';
include_once ''.$path.'/class-library.php';
include_once ''.$path.'/define.php';
include_once ''.$path.'/dbinfo.php';
include_once ''.$path.'/class-login.php';
$obj = new CustomFunctions();
$modelObj = new ConnectDb();
$loginObj = new Login();