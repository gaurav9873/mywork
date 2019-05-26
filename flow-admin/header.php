<?php
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();
require_once 'includes/init.php';

$page_name = basename($_SERVER["SCRIPT_FILENAME"]);
if($page_name!='login.php'){
	if(isset($_SESSION['user_level']) == ''){
		header("location:login.php");
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- The styles -->
    <link id="bs-css" href="css/bootstrap-cerulean.min.css" rel="stylesheet">

    <link href="css/charisma-app.css" rel="stylesheet">
    <link href='bower_components/fullcalendar/dist/fullcalendar.css' rel='stylesheet'>
    <link href='bower_components/fullcalendar/dist/fullcalendar.print.css' rel='stylesheet' media='print'>
    <link href='bower_components/chosen/chosen.min.css' rel='stylesheet'>
    <link href='bower_components/colorbox/example3/colorbox.css' rel='stylesheet'>
    <link href='bower_components/responsive-tables/responsive-tables.css' rel='stylesheet'>
    <link href='bower_components/bootstrap-tour/build/css/bootstrap-tour.min.css' rel='stylesheet'>
    <link href='css/jquery.noty.css' rel='stylesheet'>
    <link href='css/noty_theme_default.css' rel='stylesheet'>
    <link href='css/elfinder.min.css' rel='stylesheet'>
    <link href='css/elfinder.theme.css' rel='stylesheet'>
    <link href='css/jquery.iphone.toggle.css' rel='stylesheet'>
    <link href='css/uploadify.css' rel='stylesheet'>
    <link href='css/animate.min.css' rel='stylesheet'>
    <!--<link href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css" rel='stylesheet'>-->

    <!-- jQuery -->
    <script src="bower_components/jquery/jquery.min.js"></script>
    
    <script src="js/application/include.js"></script>
    <!-- The fav icon -->
    <link rel="shortcut icon" href="img/favicon.ico">

</head>

<body>
<?php
$page_name = basename($_SERVER["SCRIPT_FILENAME"]);
if($page_name != 'login.php'){
?>
<!-- topbar starts -->
<?php include_once 'top-bar.php';?>
<!-- topbar ends -->

<div class="ch-container">
    <div class="row">
	<!-- left menu starts -->
<?php include 'left-menu.php'; ?>
<div id="content" class="col-lg-10 col-sm-10">
<?php } ?>            
            

