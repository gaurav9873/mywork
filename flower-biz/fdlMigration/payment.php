<?php
include 'db.php';

$sqlStmt = "SELECT * FROM  theflowercorner2_shippingaddress as t3 INNER JOIN theflowercorner2_shoppingcart as t4 ON t3.cart_ID = t4.SESSIONKEY";
$dtatStmt = $DB_con->prepare($sqlStmt);
$dtatStmt->execute();
$record = $dtatStmt->fetchAll(PDO::FETCH_OBJ);

$count = 1;
foreach ($record as $val) {

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

    $delivery_date = date('Y-m-d', strtotime($DELDATE));
    $item_name = 'Flowers';
    $pstatus = 'Pending';
    $print_status = '1';
    $mc_currency = 'GBP';
    $paymentName = 'SHP';
    $tttt = getUid($Cust_Email);
    if (!empty($tttt)) {



        $uid = $tttt[0]->id;
        $customer_id = $tttt[0]->customer_id;

        $sql = "INSERT INTO op2mro9899_payments(
								item_name, item_number, payment_status, order_status, print_status, mc_gross, mc_currency, txn_id, receiver_email, payer_email, payment_date,
								payment_type, order_id, user_id, delivery_charges, discount_offer, site_id, ordered_date, created_date) 
							VALUES (
							:item_name, :cart_ID, :pstatus, :ShippingStatus, :print_status, :Gtotalx, :mc_currency, :cart_ID, :Cust_Email, :Cust_Email,
							:regDate, :paymentName, :cart_ID, :uid, :Shippingx, :DiscountValue, :site_id, :regDate, :LastUpdate)";


        $stmt = $DB_con->prepare($sql);
        $stmt->bindParam(':item_name', $item_name);
        $stmt->bindParam(':cart_ID', $cart_ID);
        $stmt->bindParam(':pstatus', $pstatus);
        $stmt->bindParam(':ShippingStatus', $ShippingStatus);
        $stmt->bindParam(':print_status', $print_status);
        $stmt->bindParam(':Gtotalx', $Gtotalx);
        $stmt->bindParam(':mc_currency', $mc_currency);
        $stmt->bindParam(':cart_ID', $cart_ID);
        $stmt->bindParam(':Cust_Email', $Cust_Email);
        $stmt->bindParam(':Cust_Email', $Cust_Email);
        $stmt->bindParam('regDate', $regDate);
        $stmt->bindParam(':paymentName', $paymentName);
        $stmt->bindParam(':cart_ID', $cart_ID);
        $stmt->bindParam(':uid', $uid);
        $stmt->bindParam(':Shippingx', $Shippingx);
        $stmt->bindParam(':DiscountValue', $DiscountValue);
        $stmt->bindParam(':site_id', $site_id);
        $stmt->bindParam(':regDate', $regDate);
        $stmt->bindParam(':LastUpdate', $LastUpdate);
        $stmt->execute();
        //$stmt->debugDumpParams();
        echo $count . '===' . $cart_ID . '</br />';


    }

    $count++;
}