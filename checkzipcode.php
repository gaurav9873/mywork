<?php
	include("includes/conn.php");
	//sdsdsdsdsd//
	$zipcode = $_POST['zipcode'];
	$zipcodess = $_POST['ssss'];
	$zipcodesss = $_POST['eeee'];
	$resy = mysql_query("select * from zipcode  where zipcode='".$zipcode."'");
	
	$numzipcode =  mysql_num_rows($resy);	
	
	if($numzipcode < 1 ){
		echo "Pin code not exist in our database";
		echo "Pin code not exist in our sdsdsdsdsdatabase";
		echo "Pin code not exist in our database";
	}

?>