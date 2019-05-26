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


$report_stmt = $DB_con->prepare("SELECT * FROM `jss_products`  WHERE templateFile = 'product.html'");
$report_stmt->execute();
$report = $report_stmt->fetchAll(PDO::FETCH_ASSOC);


$count = 1;
foreach($report as $val){ 
	
	$productID = $val['productID'];
	$pcode = $val['code'];
	$pname = $val['name'];
	$shortdescription = strip_tags($val['shortdescription']);
	$description = strip_tags($val['description']);
	$price1 = round($val['price1'], 2);
	$site_id = 3;
	$sql = "INSERT INTO op2mro9899_products(	
			product_name, description, short_description, regular_price, product_code) 
			VALUES ( :pname, :description, :shortdescription, :price1, :pcode )";                      


	$stmt = $DB_con->prepare($sql);
	$stmt->bindParam(':pname', $pname);       
	$stmt->bindParam(':description', $description); 
	$stmt->bindParam(':shortdescription', $shortdescription);
	$stmt->bindParam(':price1', $price1); 
	$stmt->bindParam(':pcode', $pcode);
	$stmt->execute();
	$insert_id = $DB_con->lastInsertId();
	
	$sql_stmt = "INSERT INTO op2mro9899_product_site_relation(	
				 pid, site_id) 
				 VALUES ( :insert_id, :site_id)";   
  
	$stmts = $DB_con->prepare($sql_stmt);
	$stmts->bindParam(':insert_id', $insert_id);       
	$stmts->bindParam(':site_id', $site_id); 
	$stmts->execute();
  
	echo $count.'==='.$productID.'<br />';
		
$count++; }
?>
