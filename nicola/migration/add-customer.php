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


$report_stmt = $DB_con->prepare("SELECT * FROM `jss_customers`  WHERE TRUE");
$report_stmt->execute();
$report = $report_stmt->fetchAll(PDO::FETCH_ASSOC);

$count = 1;
foreach($report as $val){ 
		
		
		$customerID = $val['customerID'];
		$password = $val['password'];
		$accTypeID = $val['accTypeID'];
		$rID = $val['rID'];
		$title = $val['title'];
		$forename = $val['forename'];
		$surname = $val['surname'];
		$wishlistID = $val['wishlistID'];
		$address1 = $val['address1'];
		$address2 = $val['address2'];
		$town = $val['town'];
		$county = $val['county'];
		$country = $val['country'];
		$postcode = $val['postcode'];
		$telephone = $val['telephone'];
		$fax = $val['fax'];
		$email = $val['email'];
		$company = $val['company'];
		$companyDepartment = $val['companyDepartment'];
		$date = $val['date'];
		$newsletter = $val['newsletter'];
		$taxExempt = $val['taxExempt'];
		$productHistory = $val['productHistory'];
		$sectionHistory = $val['sectionHistory'];
		
		
		$reg_date = strtotime($date); 
		$regDate = date('Y-m-d', $reg_date);
		
		$created_date = strtotime($date); 
		$createdDate = date('Y-m-d H:i:s', $created_date);
	
		$site_id = 3;
		$default_address = 1;
	
	
	$sql = "INSERT INTO op2mro9899_customers_login(	
								user_email, 
								user_first_name, 
								user_last_name, 
								phone_number,
								customer_id,
								site_id,
								reg_date,
								created_date ) 
							VALUES (
							:email, 
							:forename, 
							:surname,
							:telephone,
							:customerID,
							:site_id,
							:regDate,
							:createdDate
							)";           
							
							
	$stmt = $DB_con->prepare($sql);
	$stmt->bindParam(':email', $email); 
	$stmt->bindParam(':forename', $forename);
	$stmt->bindParam(':surname', $surname); 
	$stmt->bindParam(':telephone', $telephone);
	$stmt->bindParam(':customerID', $customerID);  
	$stmt->bindParam(':site_id', $site_id);
	$stmt->bindParam(':regDate', $regDate);
	$stmt->bindParam(':createdDate', $createdDate);
	$stmt->execute();
	
	echo $count.'==='.$customerID.'</br />';

		
$count++; }
?>
