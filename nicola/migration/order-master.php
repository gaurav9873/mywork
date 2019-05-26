<?php 
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$DB_host = "localhost";
$DB_user = "root";
$DB_pass = "admin@pcs";
$DB_name = "flower_admin";

try
{
	$DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}",$DB_user,$DB_pass);
	$DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	echo $e->getMessage();
}


$report_stmt = $DB_con->prepare("SELECT * FROM `tbl_OrderMaster` WHERE TRUE");
$report_stmt->execute();
$report = $report_stmt->fetchAll(PDO::FETCH_OBJ);
foreach($report as $value){
	
	
	
	$OrderID = $value->OrderID;
    $InvoiceNum = $value->InvoiceNum;
    $TotalQtyOrdered = $value->TotalQtyOrdered;
    $OrderDate = $value->OrderDate;
    $exp = explode(" ", $OrderDate);
    $order_date = $exp[0];
    $DeliveryDate = $value->DeliveryDate;
    $ddate = explode(" ", $DeliveryDate);
    $del_date = $ddate[0];
    $OrderTotalAmount = $value->OrderTotalAmount;
    $SaleTax = $value->SaleTax;
    $DeliveryCost = $value->DeliveryCost;
    $DiscountAmount = $value->DiscountAmount;
    $AmountPayable = $value->AmountPayable;
    $PaymentStatus = $value->PaymentStatus;
    $GiftCertificateCode = $value->GiftCertificateCode;
    $PromoCode = $value->PromoCode;
    
    $Cust_ID = $value->Cust_ID;
    $Bill_FirstName = $value->Bill_FirstName;
    $Bill_LastName = $value->Bill_LastName;
    $Bill_EmailID = $value->Bill_EmailID;
    $Bill_Address1 = $value->Bill_Address1;
    $Bill_Address2 = $value->Bill_Address2;
    $Bill_City = $value->Bill_City;
    $Bill_County = $value->Bill_County;
    $Bill_Country = $value->Bill_Country;
    $Bill_Postcode = $value->Bill_Postcode;
    $Bill_Phone = $value->Bill_Phone;
    $Bill_MobileNo = $value->Bill_MobileNo;
    $Bill_Fax = $value->Bill_Fax;
    
    
    $Ship_FirstName = $value->Ship_FirstName;
    $Ship_LastName = $value->Ship_LastName;
    $Ship_Address1 = $value->Ship_Address1;
    $Ship_Address2 = $value->Ship_Address2;
    $Ship_City = $value->Ship_City;
    $Ship_County = $value->Ship_County;
    $Ship_Country = $value->Ship_Country;
    $Ship_Postcode = $value->Ship_Postcode;
    $Ship_Phone = $value->Ship_Phone;
    $Ship_MobileNo = $value->Ship_MobileNo;
    $Ship_Fax = $value->Ship_Fax;
    $Ship_EmailID = $value->Ship_EmailID;
    $CardMsg = $value->CardMsg;
    $InstructionMsg = $value->InstructionMsg;
    $OrderStatusMsg = $value->OrderStatusMsg;
    $OrderStatus = $value->OrderStatus;
    $TransId = $value->TransId;
    $CardType = $value->CardType;
	$site_id = 1;
	
	$sql = "INSERT INTO op2mro9899_delivery_address(	
								post_code, 
								user_name, 
								user_lname, 
								mobile_number,
								telephone_number,
								fax_number,
								email_address,
								city,
								primary_address,
								secondary_address,
								county,
								country,
								delivery_date,
								card_message,
								florist_instruction,
								order_id,
								user_id,
								ordered_date,
								site_id,
								created_date	 
							) 
							VALUES (
							:Ship_Postcode, 
							:Ship_FirstName, 
							:Ship_LastName, 
							:Ship_MobileNo,
							:Ship_Phone,
							:Ship_Fax,
							:Ship_EmailID,
							:Ship_City,
							:Ship_Address1,
							:Ship_Address2,
							:Ship_County,
							:Ship_Country,
							:del_date,
							:CardMsg,
							:InstructionMsg,
							:OrderID,
							:Cust_ID,
							:order_date,
							:site_id,
							:OrderDate
							)";
	
	
	
	$stmt = $DB_con->prepare($sql);
	$stmt->bindParam(':Ship_Postcode', $Ship_Postcode);       
	$stmt->bindParam(':Ship_FirstName', $Ship_FirstName); 
	$stmt->bindParam(':Ship_LastName', $Ship_LastName);
	$stmt->bindParam(':Ship_MobileNo', $Ship_MobileNo); 
	$stmt->bindParam(':Ship_Phone', $Ship_Phone);
	$stmt->bindParam(':Ship_Fax', $Ship_Fax);  
	$stmt->bindParam(':Ship_EmailID', $Ship_EmailID);
	$stmt->bindParam(':Ship_City', $Ship_City);
	$stmt->bindParam(':Ship_Address1', $Ship_Address1);
	$stmt->bindParam(':Ship_Address2', $Ship_Address2);
	$stmt->bindParam(':Ship_County', $Ship_County);
	$stmt->bindParam(':Ship_Country', $Ship_Country);
	$stmt->bindParam(':del_date', $del_date);
	$stmt->bindParam(':CardMsg', $CardMsg);
	$stmt->bindParam(':InstructionMsg', $InstructionMsg);
	$stmt->bindParam(':OrderID', $OrderID);
	$stmt->bindParam(':Cust_ID', $Cust_ID);
	$stmt->bindParam(':order_date', $order_date);
	$stmt->bindParam(':site_id', $site_id);
	$stmt->bindParam(':OrderDate', $OrderDate);
	$stmt->execute();
	
	
	
}

?>
