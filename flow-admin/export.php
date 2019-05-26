<?php
//include_once '../includes/db.php';
//include_once '../includes/custom-functions.php';

require_once 'includes/init.php';

$obj = new ConnectDb();
$custom_obj = new CustomFunctions();

// set headers to force download on csv format
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=members.csv');

$sdate = isset($_REQUEST['sdate']) ? $_REQUEST['sdate'] : '';
$edate = isset($_REQUEST['edate']) ? $_REQUEST['edate'] : '';

$skey = isset($_REQUEST['skey']) ? $_REQUEST['skey'] : '';

$output = "Number, Email, FirstName, LastName, CustomerID, PostCode, HouseNumber, PrimaryAddress, SecondaryAddress, City, County, Country, Phone, RegistredDate\n";



if($sdate!='' && $edate!=''){
	$csv_list = $obj->export_csv_byDate_range($sdate, $edate);
}else if($skey!=''){
	$csv_list = $obj->search_customers($skey);
}else{
	$csv_list = $obj->export_customer_csv();
}

$count = 1;
foreach ($csv_list as $rs) {
  
  $prefix = $rs['user_prefix'];
  $user_emailid = $rs['user_emailid'];
  $fname = $rs['user_first_name'];
  $lname = $rs['user_last_name'];
  $cust_id = $rs['customer_id'];
  $reg_date = $rs['reg_date'];
  $user_postcode = $rs['user_postcode'];
  $user_house_number = $rs['user_house_number'];
  $primary_address = $rs['primary_address'];
  $secondary_address = $rs['secondary_address'];
  $user_city = $rs['user_city'];
  $user_county = $rs['user_county'];
  $user_country = $rs['user_country'];
  $user_phone = $rs['user_phone'];
  
  $output .= $count.",".$user_emailid.", ".$fname.", ".$lname.", ".$cust_id.", ".$user_postcode.", ".$user_house_number.", ".$primary_address.", ".$secondary_address.", ".$user_city.", ".$user_county.", ".$user_country.", ".$user_phone.", ".$reg_date."\n";
$count++;}
echo $output;
//header("location:customer-list.php");
exit;
?>
