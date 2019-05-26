<?php
include 'db.php';

function unique_salt() {
    return substr(sha1(mt_rand()), 0, 22);
}

$report_stmt = $DB_con->prepare("SELECT * FROM `theflowercorner2_custdatabase` WHERE TRUE");
$report_stmt->execute();
$report = $report_stmt->fetchAll(PDO::FETCH_ASSOC);

$count = 1;
foreach ($report as $val) {

    $ID = $val['ID'];
    $Cust_Name = $val['Cust_Name'];
    $Cust_Email = $val['Cust_Email'];
    $password_txt = $val['Cust_PSW'];
    $BillToName = $val['BillToName'];
    $BillToStreet = $val['BillToStreet'];
    $BillToStreet2 = $val['BillToStreet2'];
    $BillToCity = $val['BillToCity'];
    $BillToState = $val['BillToState'];
    $BillToCountry = $val['BillToCountry'];
    $BillToZip = $val['BillToZip'];
    $BillphoneNum = $val['BillphoneNum'];
    $BillEmail = $val['BillEmail'];
    $shipToName = $val['shipToName'];
    $shipToStreet = $val['shipToStreet'];
    $shipToStreet2 = $val['shipToStreet2'];
    $shipToCity = $val['shipToCity'];
    $shipToState = $val['shipToState'];
    $shipToCountryCode = $val['shipToCountryCode'];
    $shipToZip = $val['shipToZip'];
    $phoneNum = $val['phoneNum'];
    $Dealer = $val['Dealer'];

    $user_password = hash('sha256', $password_txt);
    //$enc_password = hash('sha256', $Cust_PSW);

    $rand = sprintf("%06d",rand(1,77));
    $rand_num = rand(2,9);
    $customerID = $rand + $rand_num;
    $cust_id = $customerID.$ID;

    $site_id = 2;
    $default_address = 1;
    $unique_salt = $ID.unique_salt().$site_id;

    $lname = explode(' ', $Cust_Name);
    $fname = $lname[0];
    $lnames = isset($lname[1]) ? $lname[1] : '&nbsp;';

    $reg_date = date('Y-m-d');
    $created_date  = date('Y-m-d H:i:s');


    $sql = "INSERT INTO op2mro9899_customers_login( 
                            user_email,
                            user_password,
                            password_txt,
                            user_first_name, 
                            user_last_name, 
                            phone_number, 
                            customer_id, 
                            unique_key, 
                            site_id, 
                            reg_date, 
                            created_date)
							VALUES (:Cust_Email,
							        :user_password,
							        :password_txt,
							        :fname, 
							        :lnames, 
							        :phoneNum, 
							        :cust_id, 
							        :unique_salt, 
							        :site_id, :reg_date, :created_date)";


    $stmt = $DB_con->prepare($sql);

    $stmt->bindParam(':Cust_Email', $Cust_Email);
    $stmt->bindParam(':user_password', $user_password);
    $stmt->bindParam(':password_txt', $password_txt);
    $stmt->bindParam(':fname', $fname);
    $stmt->bindParam(':lnames', $lnames);
    $stmt->bindParam(':phoneNum', $phoneNum);
    $stmt->bindParam(':cust_id', $cust_id);
    $stmt->bindParam('unique_salt', $unique_salt);
    $stmt->bindParam(':site_id', $site_id);
    $stmt->bindParam(':reg_date', $reg_date);
    $stmt->bindParam(':created_date', $created_date);
    $stmt->execute();
    //$DB_con->commit();
    //UserID
    $insertID = $DB_con->lastInsertId();


    $sql1 = "INSERT INTO op2mro9899_customers_billing_address(
								user_first_name, user_last_name, user_postcode, primary_address,  secondary_address, user_city, user_county,
								user_country, user_pcode, user_phone, user_emailid, user_id, customer_id, site_id, default_address, reg_date, created_date )
							VALUES (
								:fname, :lnames, :BillToZip, :BillToStreet, :BillToStreet2, :BillToCity, :BillToState, :BillToCountry, :BillToZip, :phoneNum,
								:Cust_Email, :insertID, :cust_id, :site_id, :default_address, :reg_date, :created_date)";


	$stmts = $DB_con->prepare($sql1);
	$stmts->bindParam(':fname', $fname);
	$stmts->bindParam(':lnames', $lnames);
	$stmts->bindParam(':BillToZip', $BillToZip);
	$stmts->bindParam(':BillToStreet', $BillToStreet);
	$stmts->bindParam(':BillToStreet2', $BillToStreet2);
	$stmts->bindParam(':BillToCity', $BillToCity);
	$stmts->bindParam(':BillToState', $BillToState);
	$stmts->bindParam(':BillToCountry', $BillToCountry);
	$stmts->bindParam(':phoneNum', $phoneNum);
	$stmts->bindParam(':Cust_Email', $Cust_Email);
	$stmts->bindParam(':insertID', $insertID);
	$stmts->bindParam(':cust_id', $cust_id);
	$stmts->bindParam(':site_id', $site_id);
	$stmts->bindParam(':default_address', $default_address);
	$stmts->bindParam(':reg_date', $reg_date);
	$stmts->bindParam(':created_date', $created_date);
	$stmts->execute();
    //$DB_con->commit();
    //UserID
    $billID = $DB_con->lastInsertId();

    echo $count.'<br />';


$count++; }


