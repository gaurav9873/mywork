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



$sql = "SELECT a.*, b.ProductTitle, b.ProductCode as pcode, b.Quantity as qty, b.StandardPrice, b.LuxuryPrice
		FROM tbl_OrderMaster AS a INNER JOIN tbl_OrderDetail AS b ON a.OrderID=b.OrderID";
$report_stmt = $DB_con->prepare($sql);
$report_stmt->execute();
$report = $report_stmt->fetchAll(PDO::FETCH_OBJ);
foreach($report as $vals){
	
	
	$OrderID = $vals->OrderID;
    $InvoiceNum = $vals->InvoiceNum;
    $TotalQtyOrdered = $vals->TotalQtyOrdered;
    $OrderDate = $vals->OrderDate;
    $exp = explode(" ", $OrderDate);
    $order_date = $exp[0];
    $DeliveryDate = $vals->DeliveryDate;
    $OrderTotalAmount = $vals->OrderTotalAmount;
    $SaleTax = $vals->SaleTax;
    $DeliveryCost = $vals->DeliveryCost;
    $DiscountAmount = $vals->DiscountAmount;
    $AmountPayable = $vals->AmountPayable;
    $PaymentStatus = $vals->PaymentStatus;
    $chk_status = (($PaymentStatus == 1) ? 'Completed': 'Pending');
    $GiftCertificateCode = $vals->GiftCertificateCode;
    $PromoCode = $vals->PromoCode;
    $Cust_ID = $vals->Cust_ID;
    $Bill_FirstName = $vals->Bill_FirstName;
    $Bill_LastName = $vals->Bill_LastName;
    $Bill_EmailID = $vals->Bill_EmailID;
    $Bill_Address1 = $vals->Bill_Address1;
    $Bill_Address2 = $vals->Bill_Address2;
    $Bill_City = $vals->Bill_City;
    $Bill_County = $vals->Bill_County;
    $Bill_Country = $vals->Bill_Country;
    $Bill_Postcode = $vals->Bill_Postcode;
    $Bill_Phone = $vals->Bill_Phone;
    $Bill_MobileNo = $vals->Bill_MobileNo;
    $Bill_Fax = $vals->Bill_Fax;
    $Ship_FirstName = $vals->Ship_FirstName;
    $Ship_LastName = $vals->Ship_LastName;
    $Ship_Address1 = $vals->Ship_Address1;
    $Ship_Address2 = $vals->Ship_Address2;
    $Ship_City = $vals->Ship_City;
    $Ship_County = $vals->Ship_County;
    $Ship_Country = $vals->Ship_Country;
    $Ship_Postcode = $vals->Ship_Postcode;
    $Ship_Phone = $vals->Ship_Phone;
    $Ship_MobileNo = $vals->Ship_MobileNo;
    $Ship_Fax = $vals->Ship_Fax;
    $Ship_EmailID = $vals->Ship_EmailID;
    $CardMsg = $vals->CardMsg;
    $InstructionMsg = $vals->InstructionMsg;
    $OrderStatusMsg = $vals->OrderStatusMsg;
    $OrderStatus = $vals->OrderStatus;
    $order_status = (($OrderStatus == 1) ? 'dispatch' : 'due');
    $TransId = $vals->TransId;
    $CardType = $vals->CardType;
    $ProductTitle = $vals->ProductTitle;
    $pcode = $vals->pcode;
    $qty = $vals->qty;
    $StandardPrice = $vals->StandardPrice;
    $LuxuryPrice = $vals->LuxuryPrice;
	$item_name = 'Flowers';
	$mc_currency = 'GBP';
	$site_id = 1;
	$payment_type = 'instant';
	
	$sql = "INSERT INTO op2mro9899_payments(	
								item_name, 
								item_number, 
								payment_status,
								order_status,
								mc_gross,
								mc_currency,
								txn_id,
								receiver_email,
								payer_email,
								payment_type,
								order_id,
								user_id,	 
								delivery_charges,
								discount_offer,
								site_id,
								ordered_date,
								created_date
							) 
							VALUES (
							:item_name, 
							:OrderID, 
							:chk_status, 
							:order_status,
							:AmountPayable,
							:mc_currency,
							:TransId,
							:Bill_EmailID,
							:Bill_EmailID,
							:payment_type,
							:OrderID,
							:Cust_ID,
							:DeliveryCost,
							:DiscountAmount,
							:site_id,
							:order_date,
							:OrderDate
							)";
	
	
	$stmt = $DB_con->prepare($sql);
	$stmt->bindParam(':item_name', $item_name);
	$stmt->bindParam(':OrderID', $OrderID);
	$stmt->bindParam(':chk_status', $chk_status);
	$stmt->bindParam(':order_status', $order_status);
	$stmt->bindParam(':AmountPayable', $AmountPayable);
	$stmt->bindParam(':mc_currency', $mc_currency);
	$stmt->bindParam(':TransId', $TransId);
	$stmt->bindParam(':Bill_EmailID', $Bill_EmailID);
	$stmt->bindParam(':Bill_EmailID', $Bill_EmailID);
	$stmt->bindParam(':payment_type', $payment_type);
	$stmt->bindParam(':OrderID', $OrderID);
	$stmt->bindParam(':Cust_ID', $Cust_ID);
	$stmt->bindParam(':DeliveryCost', $DeliveryCost);
	$stmt->bindParam(':DiscountAmount', $DiscountAmount);
	$stmt->bindParam(':site_id', $site_id);
	$stmt->bindParam(':order_date', $order_date);
	$stmt->bindParam(':OrderDate', $OrderDate);
	$stmt->execute();
	
}

?>
