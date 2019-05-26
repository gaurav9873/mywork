<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$DB_host = "localhost";
$DB_user = "root";
$DB_pass = "admin@pcs";
$DB_name = "flower";

try
{
	$DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}",$DB_user,$DB_pass);
	$DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	echo $e->getMessage();
}



/*$full_path = './uploads/products/2017/03/full/l3-image'.$pid.'.jpg';
	$medium_path = './uploads/products/2017/03/medium/l3-image'.$pid.'.jpg';
	$thumbnail_path = './uploads/products/2017/03/thumbnail/sl3-image'.$pid.'.jpg';
	
		
	$sql = "INSERT INTO `op2mro9899_products_image`(`full_path`, `medium_path`, `thumbnail_path`, `pid`) 
			VALUES (:full_path, :medium_path, :thumbnail_path, :pid)";
			
	$stmt = $DB_con->prepare($sql);
	$stmt->bindParam(':full_path', $full_path);       
	$stmt->bindParam(':medium_path', $medium_path); 
	$stmt->bindParam(':thumbnail_path', $thumbnail_path);
	$stmt->bindParam(':pid', $pid); 
	$stmt->execute();*/

//$report_stmt = $DB_con->prepare("SELECT * FROM `op2mro9899_products` WHERE `pid` >18 ORDER BY `pid` ASC LIMIT 1000");

$report_stmt = $DB_con->prepare("SELECT * FROM `tbl_product` WHERE TRUE");
$report_stmt->execute();
$report = $report_stmt->fetchAll(PDO::FETCH_OBJ);


foreach($report as $post_val){
	
	$ProductId = $post_val->ProductId;
	$Description = strip_tags($post_val->Description);
	$SpecialMessage = strip_tags($post_val->SpecialMessage);
	
	
	
	$sql = "UPDATE op2mro9899_products SET description = :Description, 
			short_description = :SpecialMessage
			WHERE pid = :ProductId";
	$stmt = $DB_con->prepare($sql);                                  
	$stmt->bindParam(':Description', $Description, PDO::PARAM_STR);       
	$stmt->bindParam(':SpecialMessage', $SpecialMessage, PDO::PARAM_STR);
	$stmt->bindParam(':ProductId', $ProductId, PDO::PARAM_INT);   
	$stmt->execute(); 
	
	
	
	
}

?>
