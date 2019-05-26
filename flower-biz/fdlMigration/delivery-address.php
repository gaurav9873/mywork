<?php
include 'db.php';

//SELECT `BillEmail`, COUNT(`BillEmail`) FROM `theflowercorner2_shippingaddress` GROUP BY `BillEmail` HAVING COUNT(`BillEmail`) > 1

//DELETE p1
//FROM op2mro9899_customers_login p1, op2mro9899_customers_login p2
//WHERE p1.customer_id > p2.customer_id
//print_r($data);


//Order Stmt

$sqlStmt = "SELECT * FROM  theflowercorner2_shippingaddress as t3 INNER JOIN theflowercorner2_shoppingcart as t4 ON t3.cart_ID = t4.SESSIONKEY";
$dtatStmt = $DB_con->prepare($sqlStmt);
$dtatStmt->execute();
$record = $dtatStmt->fetchAll(PDO::FETCH_OBJ);

$i = 1;
foreach ($record as $val) {

    //print_r($val);

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
    $ddd = date('Y-m-d', strtotime($DeliveryBooking));

    $delivery_date = date('Y-m-d', strtotime($DELDATE));

    $names = explode(' ', $shipToName);
    //$tttt = userID($Cust_Email);
    $tttt = getUid($Cust_Email);
    if (!empty($tttt)) {
        $uid = $tttt[0]->id;
        $customer_id = $tttt[0]->customer_id;

        $sql = "INSERT INTO op2mro9899_delivery_address(
								post_code, user_name, telephone_number, fax_number, email_address, city, primary_address, secondary_address, county,
								country, user_pcode, delivery_date, card_message, order_id, user_id, customer_id, ordered_date, site_id, created_date) 
							VALUES (
							:shipToZip, :shipToName, :phoneNum, :phoneNum, :Cust_Email, :shipToCity, :shipToStreet, :shipToStreet2,
							:shipToState, :shipToCountryCode, :shipToZip, :delivery_date, :CustMsg, :cart_ID, :uid, :customer_id, :ddd, :site_id, :LastUpdate)";


        $stmt = $DB_con->prepare($sql);
        $stmt->bindParam(':shipToZip', $shipToZip);
        $stmt->bindParam(':shipToName', $shipToName);
        $stmt->bindParam(':phoneNum', $phoneNum);
        $stmt->bindParam(':phoneNum', $phoneNum);
        $stmt->bindParam(':Cust_Email', $Cust_Email);
        $stmt->bindParam(':shipToCity', $shipToCity);
        $stmt->bindParam(':shipToStreet', $shipToStreet);
        $stmt->bindParam(':shipToStreet2', $shipToStreet2);
        $stmt->bindParam(':shipToState', $shipToState);
        $stmt->bindParam(':shipToCountryCode', $shipToCountryCode);
        $stmt->bindParam(':shipToZip', $shipToZip);
        $stmt->bindParam(':delivery_date', $delivery_date);
        $stmt->bindParam(':CustMsg', $CustMsg);
        $stmt->bindParam(':cart_ID', $cart_ID);
        $stmt->bindParam(':uid', $uid);
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->bindParam(':ddd', $ddd);
        $stmt->bindParam(':site_id', $site_id);
        $stmt->bindParam(':LastUpdate', $LastUpdate);
        $stmt->execute();
        $insertID = $DB_con->lastInsertId();
        //$stmt->debugDumpParams();
        echo $i . '===' . $cart_ID . '==' . $insertID . '<br/>';

    }


    $i++;
}
