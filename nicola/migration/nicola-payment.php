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


//$report_stmt = $DB_con->prepare("SELECT * FROM jss_orders_headers WHERE customerID!=0");
$report_stmt = $DB_con->prepare("SELECT a.orderID, a.datetime, a.ip, a.customerID, a.title, a.forename, a.surname, a.address1, a.address2, a.town, a.county,a.country, a.postcode,
								 a.telephone, a.fax, a.email, a.company, a.deliveryCompany, a.deliveryName, a.deliveryAddress1, a.deliveryAddress2, a.deliveryTown, a.deliveryCounty,
								 a.deliveryCountry, a.deliveryPostcode, a.deliveryTelephone, a.ccName, a.ccNumber, a.ccExpiryDate, a.ccType, a.ccStartDate, a.ccIssue, a.ccCVV, a.currencyID,
								 a.goodsTotal, a.shippingTotal, a.taxTotal, a.discountTotal, a.giftCertTotal, a.status, a.shippingMethod, a.paymentID, a.paymentName, a.paymentDate,
								 a.authInfo, a.terms, a.shippingID, a.randID, a.orderPrinted, a.orderNotes, a.paymentNameNative, a.shippingMethodNative, a.languageID, a.giftCertOrder,
								 a.referURL, a.accTypeID, a.affiliateID, a.offerCode, a.e_delivery_date, b.orderID, b.lineID, b.extraFieldID, b.extraFieldName, b.extraFieldTitle,
								 b.exvalID, b.content, b.contentNative FROM jss_orders_headers a  
								 INNER JOIN jss_orders_extrafields b ON a.orderID=b.orderID WHERE a.customerID!=0");
$report_stmt->execute();
$report = $report_stmt->fetchAll(PDO::FETCH_ASSOC);

//print_r($report); die;
$count = 1;
foreach($report as $val){
	
	
	$orderID = $val['orderID'];
    $datetime = $val['datetime'];
    $ip = $val['ip'];
    $customerID = $val['customerID'];
    $title = $val['title'];
    $forename = $val['forename'];
    $surname = $val['surname'];
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
    $deliveryCompany = $val['deliveryCompany'];
    $deliveryName = $val['deliveryName'];
    $deliveryAddress1 = $val['deliveryAddress1'];
    $deliveryAddress2 = $val['deliveryAddress2'];
    $deliveryTown = $val['deliveryTown'];
    $deliveryCounty = $val['deliveryCounty'];
    $deliveryCountry = $val['deliveryCountry'];
    $deliveryPostcode = $val['deliveryPostcode'];
    $deliveryTelephone = $val['deliveryTelephone'];
    $ccName = $val['ccName'];
    $ccNumber = $val['ccNumber'];
    $ccExpiryDate = $val['ccExpiryDate'];
    $ccType = $val['ccType'];
    $ccStartDate = $val['ccStartDate'];
    $ccIssue = $val['ccIssue'];
    $ccCVV = $val['ccCVV'];
    $currencyID = $val['currencyID'];
    $goodsTotal = $val['goodsTotal'];
    $shippingTotal = $val['shippingTotal'];
    $taxTotal = $val['taxTotal'];
    $discountTotal = $val['discountTotal'];
    $giftCertTotal = $val['giftCertTotal'];
    $status = $val['status'];
    $shippingMethod = $val['shippingMethod'];
    $paymentID = $val['paymentID'];
    $paymentName = $val['paymentName'];
    $paymentDate = $val['paymentDate'];
    $authInfo = $val['authInfo'];
    $terms = $val['terms'];
    $shippingID = $val['shippingID'];
    $randID = $val['randID'];
    $orderPrinted = $val['orderPrinted'];
    $orderNotes = $val['orderNotes'];
    $paymentNameNative = $val['paymentNameNative'];
    $shippingMethodNative = $val['shippingMethodNative'];
    $languageID = $val['languageID'];
    $giftCertOrder = $val['giftCertOrder'];
    $referURL = $val['referURL'];
    $accTypeID = $val['accTypeID'];
    $affiliateID = $val['affiliateID'];
    $offerCode = $val['offerCode'];
    $e_delivery_date = $val['e_delivery_date'];
    
    $card_message = $val['content'];
	
    $payement_data = explode('&', $authInfo);
    $pdata = explode('=', $authInfo);
    $transaction_number = str_replace('&Status','', $pdata[2]).'<br />';
    $pstatus = 'Paid';
    
	$reg_date = strtotime($datetime); 
	$regDate = date('Y-m-d', $reg_date);
		
	$created_date = strtotime($datetime); 
	$createdDate = date('Y-m-d H:i:s', $created_date);
    
    $date = str_replace('/', '-', $e_delivery_date);
    $delivery_date = date('Y-m-d', strtotime($date));
   
    $flag = 'product';
    $site_id = '3';
    
    $item_name = 'Flowers';
    $order_status = 'dispatch';
    $print_status = '0';
    $mc_currency = 'GBP';
    $sql = "INSERT INTO op2mro9899_payments(	
								item_name, 
								item_number,
								payment_status,
								order_status,
								print_status,
								mc_gross,
								mc_currency,
								txn_id,
								receiver_email,
								payer_email,
								payment_date,
								payment_type,
								order_id,
								user_id,
								delivery_charges,
								discount_offer,
								site_id,
								ordered_date,
								created_date,
								created_ip
							) 
							VALUES (
							:item_name, 
							:orderID,
							:pstatus, 
							:order_status,
							:print_status,
							:goodsTotal,
							:mc_currency,
							:transaction_number,
							:email,
							:email,
							:regDate,
							:paymentName,
							:orderID,
							:customerID,
							:taxTotal,
							:discountTotal,
							:site_id,
							:regDate,
							:createdDate,
							:ip
							)";
	
	
	
	$stmt = $DB_con->prepare($sql);
	$stmt->bindParam(':item_name', $item_name);       
	$stmt->bindParam(':orderID', $orderID); 
	$stmt->bindParam(':pstatus', $pstatus);
	$stmt->bindParam(':order_status', $order_status); 
	$stmt->bindParam(':print_status', $print_status);
	$stmt->bindParam(':goodsTotal', $goodsTotal);  
	$stmt->bindParam(':mc_currency', $mc_currency);  
	$stmt->bindParam(':transaction_number', $transaction_number);
	$stmt->bindParam(':email', $email);
	$stmt->bindParam(':email', $email);
	$stmt->bindParam(':paymentName', $paymentName);
	$stmt->bindParam(':orderID', $orderID);
	$stmt->bindParam(':customerID', $customerID);
	$stmt->bindParam(':taxTotal', $taxTotal);
	$stmt->bindParam(':discountTotal', $discountTotal);
	$stmt->bindParam(':site_id', $site_id);
	$stmt->bindParam(':regDate', $regDate);
	$stmt->bindParam(':createdDate', $createdDate);
	$stmt->bindParam(':ip', $ip);
	$stmt->execute();
	$stmt->debugDumpParams();
	echo $count.'==='.$orderID.'</br />';
	
$count++; }
?>
