<?php
include 'db.php';

$sqlStmt = "SELECT * FROM  theflowercorner2_shippingaddress as t3 INNER JOIN theflowercorner2_shoppingcart as t4 ON t3.cart_ID = t4.SESSIONKEY";
$dtatStmt = $DB_con->prepare($sqlStmt);
$dtatStmt->execute();
$record = $dtatStmt->fetchAll(PDO::FETCH_OBJ);


$count = 1;
foreach ($record as $val){

    $ID = $val->ID;
    $BillToName = $val->BillToName;
    $BillToStreet = $val->BillToStreet;
    $BillToStreet2 = $val->BillToStreet2;
    $BillToCity = $val->BillToCity;
    $BillToState = $val->BillToState;
    $BillToCountry = $val->BillToCountry;
    $BillToZip = $val->BillToZip;
    $BillphoneNum = $val->BillphoneNum;
    $BillEmail = $val->BillEmail;
    $shipToName = $val->shipToName;
    $shipToStreet = $val->shipToStreet;
    $shipToStreet2 = $val->shipToStreet2;
    $shipToCity = $val->shipToCity;
    $shipToState = $val->shipToState;
    $shipToCountryCode = $val->shipToCountryCode;
    $shipToZip = $val->shipToZip;
    $phoneNum = $val->phoneNum;
    $cart_ID = $val->cart_ID;
    $Cust_Email = $val->Cust_Email;
    $PaymentStatus = $val->PaymentStatus;
    $Stotalx = $val->Stotalx;
    $Shippingx = $val->Shippingx;
    $Gtotalx = $val->Gtotalx;
    $DiscountValue = $val->DiscountValue;
    $ShippingStatus = $val->ShippingStatus;
    $OrderBooking = $val->OrderBooking;
    $DeliveryBooking = $val->DeliveryBooking;
    $DELDATE = $val->DELDATE;
    $DispatchDate = $val->DispatchDate;
    $DispatchDetails = $val->DispatchDetails;
    $transactionId = $val->transactionId;
    $DiscountCode = $val->DiscountCode;
    $TaxRule = $val->TaxRule;
    $Tax = $val->Tax;
    $CustMsg = $val->CustMsg;
    $SESSIONKEY = $val->SESSIONKEY;
    $ReferenceN = $val->ReferenceN;
    $ImageURL1 = $val->ImageURL1;
    $Name = $val->Name;
    $Variant = $val->Variant;
    $ShortDesc = $val->ShortDesc;
    $Rate = $val->Rate;
    $Qty = $val->Qty;
    $Weight = $val->Weight;
    $Color = $val->Color;
    $Size = $val->Size;
    $Size2 = $val->Size2;
    $PaymentTime = $val->PaymentTime;
    $LastUpdate = $val->LastUpdate;
    $eventdate = $val->eventdate;
    $comment = $val->comment;
    $site_id = 2;
    //Booking Date
    $regDate = date('Y-m-d', strtotime($DeliveryBooking));
    $flag = 'product';

    $delivery_date = date('Y-m-d', strtotime($DELDATE));
    $item_name = 'Flowers';
    $pstatus = 'due';
    $print_status = '1';
    $mc_currency = 'GBP';
    $paymentName = 'SHP';
    $tttt = getUid($Cust_Email);


    if(!empty($tttt)){

        $uid = $tttt[0]->id;
        $customer_id = $tttt[0]->customer_id;
        $unique_key = $tttt[0]->unique_key;

        $sql = "INSERT INTO op2mro9899_ordered_product(
								product_name, 
								product_price, 
								product_qty_price, 
								product_qty, 
								product_size, 
								product_code, 
								user_id, 
								user_key,
								order_id, 
								ordered_date, 
								created_date, 
								flag, 
								site_id
							) 
							VALUES (
							:Name, 
							:Rate, 
							:Stotalx,
							:Qty, 
							:Size, 
							:ReferenceN, 
							:uid, 
							:unique_key, 
							:cart_ID, 
							:regDate,  
							:regDate, 
							:flag,
							:site_id
							)";



        $stmt = $DB_con->prepare($sql);
        $stmt->bindParam(':Name', $Name);
        $stmt->bindParam(':Rate', $Rate);
        $stmt->bindParam(':Stotalx', $Stotalx);
        $stmt->bindParam(':Qty', $Qty);
        $stmt->bindParam(':Size', $Size);
        $stmt->bindParam(':ReferenceN', $ReferenceN);
        $stmt->bindParam(':uid', $uid);
        $stmt->bindParam(':unique_key', $unique_key);
        $stmt->bindParam(':cart_ID', $cart_ID);
        $stmt->bindParam(':regDate', $regDate);
        $stmt->bindParam(':regDate', $regDate);
        $stmt->bindParam(':flag', $flag);
        $stmt->bindParam(':site_id', $site_id);
        $stmt->execute();

        echo $count.'==='.$cart_ID.'</br />';


    }

$count++;}