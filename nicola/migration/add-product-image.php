<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$DB_host = "localhost";
$DB_user = "root";
$DB_pass = "admin@pcs";
$DB_name = "floweradmin";

try
{
	$DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}",$DB_user,$DB_pass);
	$DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	echo $e->getMessage();
}

//$sql = "SELECT * FROM jss_products as js INNER JOIN op2mro9899_products pi ON js.code=pi.product_code";
$report_stmt = $DB_con->prepare("SELECT a.code, a.name, a.thumbnail, a.mainimage, a.price1, a.templateFile, b.pid, b.product_code, b.product_name FROM jss_products a INNER JOIN op2mro9899_products b
									ON a.code=b.product_code WHERE a.templateFile = 'product.html' AND a.code IS NOT NULL AND a.code <> ''");
$report_stmt->execute();
$report = $report_stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($report as $val){
	
	$code = $val['code'];
	$name = $val['name'];
	$thumbnail = $val['thumbnail'];
	$mainimage = $val['mainimage'];
	$price1 = $val['price1'];
	$templateFile = $val['templateFile'];
	$pid = $val['pid'];
	$product_code = $val['product_code'];
	$product_name = $val['product_name'];
	
	$thumb_image = basename($thumbnail);
	$main_image = basename($mainimage);
	$full_image = basename($mainimage);
	
    $tpath = './uploads/products/2017/08/thumbnail/'.$thumb_image.'';
	$mpath = './uploads/products/2017/08/medium/'.$main_image.'';
	$fpath = './uploads/products/2017/08/full/'.$full_image.'';
	
	
	$sql = "INSERT INTO op2mro9899_products_image(full_path, medium_path, thumbnail_path, pid) 
							VALUES (:fpath, :mpath, :tpath, :pid)";                      
							
							
	$stmt = $DB_con->prepare($sql);
	$stmt->bindParam(':fpath', $fpath);       
	$stmt->bindParam(':mpath', $mpath); 
	$stmt->bindParam(':tpath', $tpath);
	$stmt->bindParam(':pid', $pid); 
	$stmt->execute();
	$insert_id = $DB_con->lastInsertId().'<br />';
	
	
}
?>
