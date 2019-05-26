<?php
include '../includes/db.php';
include_once '../includes/custom-functions.php';

$obj = new ConnectDb();
$cFunc = new CustomFunctions();



	$action = $cFunc->xss_clean($_POST['action']);
	
	switch($action){
		case "insert_user":
			
			$user_name = $cFunc->xss_clean($_POST['user_name']);
			$user_email = filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL);
			$user_password = $cFunc->getSaltedHash($_POST['user_password']);
			$user_level = intval($_POST['user_level']);
			$domain_id = intval($_POST['domain_id']);
			$created_date = date("Y-m-d H:i:s");
			$created_ip = $cFunc->get_client_ip();
			
			$usr_stmt = $obj->insert_records('op2mro9899_admin_useres',array('user_name' => $user_name, 'user_email' => $user_email, 'user_password' => $user_password, 'user_level' => $user_level, 'domain_id' => $domain_id, 'created_date' => $created_date, 'created_ip' => $created_ip));
			if($usr_stmt){
				echo json_encode($obj->get_row_by_id('op2mro9899_admin_useres', 'id', $usr_stmt));
				//echo $res = json_encode($obj->get_site_name_byID($usr_stmt), true);
			}else{
				echo json_encode(array('msg' => 'Something went wrong.', 'status' => false));
			}
			
		break;
		
		case "delete":
			$del_id = intval($_POST['del_id']);
			$del_stmt =  $obj->deleteRow('op2mro9899_admin_useres', 'id', $del_id);
			if($del_stmt){
				echo json_encode(array('msg' => 'Delete successfully', 'status' => true));
			}else{
				echo json_encode(array('msg' => 'Something went wrong please try after sometime.', 'status' => false));
			}
		break;
		
		case "status":
			$rid = $_POST['rid'];
			$status_type = $_POST['status_type'];
			
			$data = array('user_status' => $status_type);
			$q = $obj->update_row('op2mro9899_admin_useres', $data,"WHERE id = '$rid'");
			if($q){
				echo $reponce = json_encode($obj->get_row_by_id('op2mro9899_admin_useres', 'id', $rid));
			}else{
				echo json_encode(array('msg' => 'Something went wrong please try after some time.', 'status' => false));
			}
		break;
		
		default:
		break;
	}
	
?>
