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


$report_stmt = $DB_con->prepare("SELECT * FROM `op2mro9899_customers_billing_address`  WHERE site_id = 3 GROUP BY customer_id");
$report_stmt->execute();
$report = $report_stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($report);
$count = 1;
foreach($report as $val){ 
		
	/*$id = $val['id'];
	$cust_id = $val['customer_id'];
	$daddress = '1';
	
	$sql = "UPDATE op2mro9899_customers_billing_address SET default_address = :daddress WHERE id = :id";
	$stmt = $DB_con->prepare($sql);                                  
	$stmt->bindParam(':daddress', $daddress);       
	$stmt->bindParam(':id', $id);   
	$stmt->execute(); 
  
   echo $count.'==='.$id.'<br />';*/
		
$count++; }
?>
