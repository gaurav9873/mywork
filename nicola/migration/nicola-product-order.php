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


//$report_stmt = $DB_con->prepare("SELECT a.* FROM jss_orders_headers a INNER JOIN jss_orders_lines  b ON a.orderID=b.orderID WHERE a.customerID!=0");
$report_stmt = $DB_con->prepare("SELECT a.orderID, a.datetime, a.ip, a.customerID,a.e_delivery_date, b.lineID, b.orderID, b.productID, b.code, b.name,
								 b.qty, b.weight, b.price, b.nameNative, b.taxamount, b.isDigital, b.digitalFile, b.digitalReg, b.downloadID, b.ooprice,
								 b.ootaxamount, b.supplierID, b.suppliercode
								 FROM jss_orders_headers a 
								 INNER JOIN jss_orders_lines  b ON a.orderID=b.orderID WHERE a.customerID!=0 AND b.code REGEXP '[0-9]+'");
$report_stmt->execute();
$report = $report_stmt->fetchAll(PDO::FETCH_ASSOC);
$count = 1;
foreach($report as $val){
	
	$orderID = $val['orderID'];
    $datetime = $val['datetime'];
    $ip = $val['ip'];
    $customerID = $val['customerID'];
    $e_delivery_date = $val['e_delivery_date'];
    $lineID = $val['lineID'];
    $productID = $val['productID'];
    $code = $val['code'];
    $name = $val['name'];
    $qty = $val['qty'];
    $weight = $val['weight'];
    $price = $val['price'];
    $nameNative = $val['nameNative'];
    $taxamount = $val['taxamount'];
    $isDigital = $val['isDigital'];
    $digitalFile = $val['digitalFile'];
    $digitalReg = $val['digitalReg'];
    $downloadID = $val['downloadID'];
    $ooprice = $val['ooprice'];
    $ootaxamount = $val['ootaxamount'];
    $supplierID = $val['supplierID'];
    $suppliercode = $val['suppliercode'];
    
	$reg_date = strtotime($datetime); 
	$regDate = date('Y-m-d', $reg_date);
		
	$created_date = strtotime($datetime); 
	$createdDate = date('Y-m-d H:i:s', $created_date);
    
    $flag = 'product';
    $site_id = '3';
    
    $sql = "INSERT INTO op2mro9899_ordered_product(	
								product_name, 
								product_price, 
								product_qty,
								product_code,
								user_id,
								order_id,
								ordered_date,
								created_date,
								created_ip,
								flag,
								site_id
							) 
							VALUES (
							:name, 
							:price, 
							:qty, 
							:code,
							:customerID,
							:orderID,
							:regDate,
							:createdDate,
							:ip,
							:flag,
							:site_id
							)";
	
	
	
	$stmt = $DB_con->prepare($sql);
	$stmt->bindParam(':name', $name);       
	$stmt->bindParam(':price', $price); 
	$stmt->bindParam(':qty', $qty);
	$stmt->bindParam(':code', $code); 
	$stmt->bindParam(':customerID', $customerID);
	$stmt->bindParam(':orderID', $orderID);  
	$stmt->bindParam(':regDate', $regDate);  
	$stmt->bindParam(':createdDate', $createdDate);
	$stmt->bindParam(':ip', $ip);
	$stmt->bindParam(':flag', $flag);
	$stmt->bindParam(':site_id', $site_id);
	$stmt->execute();
	
	echo $count.'==='.$orderID.'</br />';
	
$count++; }
?>
