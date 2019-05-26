<?php
include_once '../include/class-library.php';
$obj = new CustomFunctions();
session_start();
if($obj->is_ajax()){
	$json = json_decode(file_get_contents("php://input"));

	
	$_SESSION['post_code'] = $json->post_code;
	$_SEESION['delivery_address'] = $json->delivery_address;
	$_SESSION['user_prefix'] = $json->user_prefix;
	$_SESSION['user_fname'] = $json->user_fname;
	$_SESSION['user_lname'] = $json->user_lname;
	$_SESSION['user_mobile'] = $json->user_mobile;
	$_SESSION['user_telephone'] = $json->user_telephone;
	$_SESSION['user_fax'] = $json->user_fax;
	$_SESSION['delivery_email'] = $json->delivery_email;
	$_SESSION['user_city'] = $json->user_city;
	$_SESSION['primary_address'] = $json->user_address1;
	$_SESSION['secondary_address'] = $json->user_address2;
	$_SESSION['user_county'] = $json->user_county;
	$_SESSION['country'] = 'United Kingdom';
	$_SESSION['delivery_date'] = $json->delivery_date;
	$_SESSION['user_card_msg'] = $json->user_card_msg;
	$_SESSION['user_notes'] = $json->user_notes;

	 
	if($json->action == 'regfrm'){
		echo json_encode(array('status' => true, 'message' => 'redirect register page'));
	}elseif($json->action == 'saveAddress'){
		echo json_encode(array('status' => true, 'message' => 'redirect myaccount page'));
	}else{
		echo json_encode(array('status' => true, 'message' => 'redirect myaccount page'));
	}
}

?>
