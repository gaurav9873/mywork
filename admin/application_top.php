<?php
session_start();
error_reporting(0);
  require('includes/configure.php');
   require('functions/database.php');
  tep_db_connect() or die('Unable to connect to database server!');
  /*if($_SESSION['admin_id']=="" && basename($PHP_SELF) !== 'login.php'){
		header("location:login.php");
	}*/
?>