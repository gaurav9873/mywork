<?php
	include("includes/conn.php");
	
	$zipcode = $_POST['zipcode'];
	$resy = mysql_query("select * from zipcode  where zipcode='".$zipcode."'");
	
	$numzipcode =  mysql_num_rows($resy);	
	
	if($numzipcode < 1 ){
		echo "Pin code not exist in our database";
	}	

?>