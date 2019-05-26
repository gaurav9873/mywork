<?php
ob_start();
ob_clean();
date_default_timezone_set('Europe/London');
$path = realpath(dirname(__FILE__));
include_once ''.$path.'/class-library.php';
include_once ''.$path.'/define.php';
$obj = new CustomFunctions();
?>
