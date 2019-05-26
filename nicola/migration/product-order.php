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
    $TransId = $vals->TransId;
    $CardType = $vals->CardType;
    $ProductTitle = $vals->ProductTitle;
    $pcode = $vals->pcode;
    $qty = $vals->qty;
    $StandardPrice = $vals->StandardPrice;
    $LuxuryPrice = $vals->LuxuryPrice;
	$flag = 'product';
	$site_id = 1;
	
	$sql = "INSERT INTO op2mro9899_ordered_product(	
								product_name, 
								product_qty_price, 
								product_qty,
								product_code,
								user_id,
								order_id,
								ordered_date,
								created_date,
								flag,
								site_id	 
							) 
							VALUES (
							:ProductTitle, 
							:OrderTotalAmount, 
							:TotalQtyOrdered, 
							:pcode,
							:Cust_ID,
							:OrderID,
							:order_date,
							:OrderDate,
							:flag,
							:site_id
							)";
	
	
	$stmt = $DB_con->prepare($sql);
	$stmt->bindParam(':ProductTitle', $ProductTitle);
	$stmt->bindParam(':OrderTotalAmount', $OrderTotalAmount);
	$stmt->bindParam(':TotalQtyOrdered', $TotalQtyOrdered);
	$stmt->bindParam(':pcode', $pcode);
	$stmt->bindParam(':Cust_ID', $Cust_ID);
	$stmt->bindParam(':OrderID', $OrderID);
	$stmt->bindParam(':order_date', $order_date);
	$stmt->bindParam(':OrderDate', $OrderDate);
	$stmt->bindParam(':flag', $flag);
	$stmt->bindParam(':site_id', $site_id);
	$stmt->execute();
	
}

?>
