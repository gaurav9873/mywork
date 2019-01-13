<?php
require('includes/application_top.php');

if($_SESSION['admin_id']==""){
		header("location:login.php");
}

 
$id=$_REQUEST[id];

if($_REQUEST['st']=="0")
	{
	$sqlup=mysql_query("update brand set status='1' where b_id='$id'");
	#header("location:brand.php");
	}
	else if($_REQUEST['st']=="1")
	{
	
	echo  $sqlup="update brand set status='0' where b_id='$id'";
	 $reup=mysql_query($sqlup);
		#header("location:brand.php");
	}
	
header("location:brand.php");

?>