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

$sql = "SELECT  mo.*, ( SELECT  COUNT(*) FROM `jss_customers_addresses` mi WHERE mi.`customerID` = mo.`customerID` ) NG FROM `jss_customers_addresses` mo WHERE mo.customerID NOT IN(0)";
$report_stmt = $DB_con->prepare($sql);
$report_stmt->execute();
$report = $report_stmt->fetchAll(PDO::FETCH_ASSOC);

//print_r($report); die;
//$date = strtotime($report[0]['date']); 
//$new_date = date('Y-m-d H:i:s', $date);
//SELECT  mo.*, ( SELECT  COUNT(*) FROM `jss_customers_addresses` mi WHERE mi.`customerID` = mo.`customerID` ) FROM `jss_customers_addresses` mo WHERE mo.customerID NOT IN(0)
$check = array();
$count = 1;
foreach($report as $key=>$val){ 
		
	
	$addressID = $val['addressID'];
    $customerID = $val['customerID'];
    $deliveryName = $val['deliveryName'];
    $deliveryAddress1 = strip_tags($val['deliveryAddress1']);
    $deliveryAddress2 = strip_tags($val['deliveryAddress2']);
    $deliveryTown = $val['deliveryTown'];
    $deliveryCounty = $val['deliveryCounty'];
    $deliveryCountry = $val['deliveryCountry'];
    $deliveryPostcode = $val['deliveryPostcode'];
    $deliveryTelephone = $val['deliveryTelephone'];
    $deliveryEmail = $val['deliveryEmail'];
    $deliveryCompany = $val['deliveryCompany'];
    $deliveryCompanyDepartment = $val['deliveryCompanyDepartment'];
    $NG = $val['NG'];
	$site_id = 3;
	//$default_address = 1;
	
	$sql1 = "INSERT INTO op2mro9899_customers_billing_address(	
								user_first_name, 
								user_postcode, 
								primary_address,
								secondary_address,
								user_city,
								user_county,
								user_country,
								user_phone,
								user_emailid,
								customer_id,
								site_id
								 ) 
							VALUES (
								:deliveryName, 
								:deliveryPostcode,
								:deliveryAddress1,
								:deliveryAddress2,
								:deliveryTown,
								:deliveryCounty,
								:deliveryCountry,
								:deliveryTelephone,
								:deliveryEmail,
								:customerID,
								:site_id
							)";
	
	
	$stmts = $DB_con->prepare($sql1);

		$stmts->bindParam(':deliveryName', $deliveryName);
		$stmts->bindParam(':deliveryPostcode', $deliveryPostcode);
		$stmts->bindParam(':deliveryAddress1', $deliveryAddress1); 
		$stmts->bindParam(':deliveryAddress2', $deliveryAddress2); 
		$stmts->bindParam(':deliveryTown', $deliveryTown); 
		$stmts->bindParam(':deliveryCounty', $deliveryCounty); 
		$stmts->bindParam(':deliveryCountry', $deliveryCountry); 
		$stmts->bindParam(':deliveryTelephone', $deliveryTelephone);
		$stmts->bindParam(':deliveryEmail', $deliveryEmail); 
		$stmts->bindParam(':customerID', $customerID); 
		$stmts->bindParam(':site_id', $site_id); 
		$stmts->execute();
   	//echo $stmts->debugDumpParams();
	echo $count.''.$customerID.'<br />';
		
$count++; }
?>
