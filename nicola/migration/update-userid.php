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

	
$report_stmt = $DB_con->prepare("SELECT * FROM `op2mro9899_customers_login`  WHERE site_id = 3");
$report_stmt->execute();
$report = $report_stmt->fetchAll(PDO::FETCH_ASSOC);

$count = 1;
foreach($report as $val){ 
		
	//print_r($val);
		
	$cust_id = $val['id'];
	$customer_id = $val['customer_id'];
	$reg_date = $val['reg_date'];
	$created_date = $val['created_date'];
	

	$sql = "UPDATE op2mro9899_customers_billing_address SET user_id = :cust_id, reg_date = :reg_date, created_date = :created_date WHERE customer_id = :customer_id";
	$stmt = $DB_con->prepare($sql);                                  
	$stmt->bindParam(':cust_id', $cust_id);       
	$stmt->bindParam(':reg_date', $reg_date);       
	$stmt->bindParam(':created_date', $created_date);       
	$stmt->bindParam(':customer_id', $customer_id);   
	$stmt->execute(); 

	echo $count.'==='.$cust_id.'<br />';
		
$count++; }
?>
