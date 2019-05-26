<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
$path = realpath(dirname(__FILE__));
include_once ''.$path.'/db.php';
include_once ''.$path.'/custom-functions.php';
include_once ''.$path.'/paginator.class.php';
include_once ''.$path.'/define.php';
include_once ''.$path.'/class-login.php';
include_once ''.$path.'/jwt_helper.php';
include_once ''.$path.'/class-reports.php';
?>
