<?php
include_once '../includes/db.php';
include_once '../includes/custom-functions.php';

$obj = new ConnectDb();
$customObj = new CustomFunctions();

if($customObj->is_ajax()){

	   $action = $customObj->xss_clean($_POST['action']);
	   
	   switch($action){
		   
		   case "delete":
				$delete_id = $customObj->DecryptClientId($_POST['did']);
				$del_stmt =  $obj->deleteRow('op2mro9899_coupons', 'id', intval($delete_id));
				if($del_stmt){
					echo json_encode(array('msg' => 'delete successfully', 'status' => true));
				}else{
					echo json_encode(array('msg' => 'something went wrong', 'status' => false));
				}
		   break;
		   
	   }
}
?>
