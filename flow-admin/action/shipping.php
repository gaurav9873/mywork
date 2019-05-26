<?php
include_once '../includes/db.php';
include_once '../includes/custom-functions.php';

$obj = new ConnectDb();
$cFunc = new CustomFunctions();

if($cFunc->is_ajax()){
	
	$action = $cFunc->xss_clean($_POST['action']);
	
	switch($action){
		case "delete_shipping":
			
			$ship_id = $cFunc->DecryptClientId($_POST['ship_id']);
			$id = intval($ship_id);
			$del_stmt =  $obj->deleteRow('op2mro9899_shipping', 'id', $id);
			if($del_stmt){
				echo json_encode(array('msg' => 'Delete successfully', 'status' => true));
			}else{
				echo json_encode(array('msg' => 'Something went wrong', 'status' => false));
			}
			
		break;
	}
	
}


?>
