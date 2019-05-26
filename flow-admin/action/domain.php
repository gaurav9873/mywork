<?php
include '../includes/db.php';
include_once '../includes/custom-functions.php';

$dbObj = new ConnectDb();
$cFunc = new CustomFunctions();

$action = $_POST['action'];
if($cFunc->is_ajax()){
	if (isset($_POST["action"]) && !empty($_POST["action"])) {
		switch($action){
			case "insert":
			
				$domain_name = $cFunc->xss_clean($_POST['value']);
				$created_date = date("Y-m-d H:i:s");
				$created_ip = $cFunc->get_client_ip();
				$ins_stmt = $dbObj->insert_records('op2mro9899_add_domain',array('domain_name' => $domain_name, 'created_date' => $created_date, 'created_ip' => $created_ip));
				if($ins_stmt){
					echo $res = json_encode($dbObj->get_row_by_id('op2mro9899_add_domain', 'site_id', $ins_stmt));
				}else{
					echo json_encode(array("message"=>'Something went wrong',"status"=>false,"data"=>array()));
				}
			break;
			
			case "Delete":
				$del_id = intval($_POST['del_id']);
				$del_stmt =  $dbObj->deleteRow('op2mro9899_add_domain', 'site_id', $del_id);
				if($del_stmt){
					echo $req = json_encode(array('msg' => 'Delete successfully', 'status' => true));
				}else{
					echo json_encode(array('msg' => 'Something went wrong', 'status' => 'false'));
				}
			break;
			
			case 'update':
				$site_id = intval($_POST['site_id']);
				$site_name = $cFunc->xss_clean($_POST['site_name']);
				$data = array('domain_name' => $site_name);
				
				$q = $dbObj->update_row('op2mro9899_add_domain', $data,"WHERE site_id = '$site_id'");
				if($q){
					echo json_encode(array('msg' => 'Update complete', 'status' => true));
				}else{
					echo json_encode(array('msg' => 'Something went wrong', 'status' => false));
				}
				
			break;
			
			default:
				
			break;
		}
	}
}

?>
