<?php
include_once '../includes/db.php';
include_once '../includes/custom-functions.php';

$obj = new ConnectDb();
$customObj = new CustomFunctions();

if($customObj->is_ajax()){
	
	$action = $_POST['action'];
	
	switch($action){
		
		case "change_status":
			
			$status_val = $_POST['status_val'];
			$oid = intval($_POST['oid']);
			
			$data = array('order_status' => $status_val);
			$q = $obj->update_row('op2mro9899_payments', $data,"WHERE payment_id = '$oid'");
			if($q){
				echo json_encode(array('status' => 'true', 'message' => 'update successfully'));
			}else{
				echo json_encode(array('status' => 'false', 'message' => 'something went wrong'));
			}
			
		break;
		
		
		case "cancel_order":
			
			$status_value = $_POST['status_value'];
			$cid = intval($_POST['cid']);
			
			$data = array('order_status' => $status_value);
			$q = $obj->update_row('op2mro9899_payments', $data,"WHERE payment_id = '$cid'");
			if($q){
				echo json_encode(array('status' => 'true', 'message' => 'update successfully'));
			}else{
				echo json_encode(array('status' => 'false', 'message' => 'something went wrong'));
			}
			
		break;
		
	}
	
	
}

?>
