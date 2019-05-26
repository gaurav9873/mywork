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


/*$report_stmt = $DB_con->prepare("SELECT js.customerID, js.password, js.accTypeID, js.rID, js.title, js.forename, js.surname, js.wishlistID, js.address1, js.address2, js.town, js.county, js.country, js.postcode,
		js.telephone, js.fax, js.email, js.company, js.companyDepartment, js.date, js.newsletter, js.taxExempt, js.productHistory, js.sectionHistory,
		jsc.deliveryName, jsc.deliveryAddress1 as paddress, jsc.deliveryAddress2 as saddress, jsc.deliveryTown, jsc.deliveryCounty, jsc.deliveryCountry, jsc.deliveryPostcode,
		jsc.deliveryTelephone, jsc.deliveryEmail, jsc.deliveryCompany, jsc.deliveryCompanyDepartment FROM `jss_customers` js 
		INNER JOIN `jss_customers_addresses` jsc ON js.customerID = jsc.customerID GROUP BY js.customerID");*/

$report_stmt = $DB_con->prepare("SELECT * FROM `jss_customers`  WHERE TRUE");
$report_stmt->execute();
$report = $report_stmt->fetchAll(PDO::FETCH_ASSOC);

$date = strtotime($report[0]['date']); 
$new_date = date('Y-m-d H:i:s', $date);

foreach($report as $val){ 
		
		
		print_r($val);
		
	/*$Cust_ID = $val['Cust_ID'];
    $Title = $val['Title'];
    $FirstName = $val['FirstName'];
    $LastName = $val['LastName'];
    $Address1 = $val['Address1'];
    $Address2 = $val['Address2'];
    $City = $val['City'];
    $State = $val['State'];
    $County = $val['County'];
    $Country = $val['Country'];
    $Postcode = $val['Postcode'];
    $Phone = $val['Phone'];
    $MobileNo = $val['MobileNo'];
    $Fax = $val['Fax'];
    $EmailID = $val['EmailID'];
    $Password = $val['Password'];
    $RegDate = $val['RegDate'];
    $Status = $val['Status'];
    
    
    $reg_date = explode(' ', $RegDate);
    $reg_dates = $reg_date[0];*/
    
		
	/*$rand = sprintf("%06d",rand(1,999999));
	$rand_num = rand(100000,999999);
	$customerID = $rand + $rand_num;
	$cust_id = $customerID.$Cust_ID;*/
	
	$site_id = 3;
	$default_address = 1;
	
	
	/*$sql = "INSERT INTO op2mro9899_customers_login(	
								id, 
								user_email, 
								user_first_name, 
								user_last_name, 
								phone_number,
								customer_id,
								site_id,
								reg_date,
								created_date ) 
							VALUES (
							:Cust_ID, 
							:EmailID, 
							:FirstName, 
							:LastName,
							:Phone,
							:cust_id,
							:site_id,
							:reg_dates,
							:RegDate
							)";                      
							
							
	$stmt = $DB_con->prepare($sql);
	$stmt->bindParam(':Cust_ID', $Cust_ID);       
	$stmt->bindParam(':EmailID', $EmailID); 
	$stmt->bindParam(':FirstName', $FirstName);
	$stmt->bindParam(':LastName', $LastName); 
	$stmt->bindParam(':Phone', $Phone);
	$stmt->bindParam(':cust_id', $cust_id);  
	$stmt->bindParam(':site_id', $site_id);
	$stmt->bindParam(':reg_dates', $reg_dates);
	$stmt->bindParam(':RegDate', $RegDate);
	$stmt->execute();*/
	
	
	
	/*$sql1 = "INSERT INTO op2mro9899_customers_billing_address(	
								user_prefix, 
								user_first_name, 
								user_last_name, 
								user_postcode, 
								primary_address,
								secondary_address,
								user_city,
								user_county,
								user_country,
								user_phone,
								user_emailid,
								user_id,
								customer_id,
								site_id,
								default_address,
								reg_date,
								created_date ) 
							VALUES (
								:Title, 
								:FirstName, 
								:LastName, 
								:Postcode,
								:Address1,
								:Address2,
								:City,
								:County,
								:Country,
								:Phone,
								:EmailID,
								:Cust_ID,
								:cust_id,
								:site_id,
								:default_address,
								:reg_dates,
								:RegDate
							)";    
	
	
	$stmts = $DB_con->prepare($sql1);
	$stmts->bindParam(':Title', $Title); 
	$stmts->bindParam(':FirstName', $FirstName);
	$stmts->bindParam(':LastName', $LastName);
	$stmts->bindParam(':Postcode', $Postcode);
	$stmts->bindParam(':Address1', $Address1); 
	$stmts->bindParam(':Address2', $Address2); 
	$stmts->bindParam(':City', $City); 
	$stmts->bindParam(':County', $County); 
	$stmts->bindParam(':Country', $Country); 
	$stmts->bindParam(':Phone', $Phone);
	$stmts->bindParam(':EmailID', $EmailID); 
	$stmts->bindParam(':cust_id', $cust_id); 
	$stmts->bindParam(':Cust_ID', $Cust_ID); 
	$stmts->bindParam(':site_id', $site_id); 
	$stmts->bindParam(':default_address', $default_address); 
	$stmts->bindParam(':reg_dates', $reg_dates); 
	$stmts->bindParam(':RegDate', $RegDate); 
	$stmts->execute();*/
		
}
?>
