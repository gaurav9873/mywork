<?php
date_default_timezone_set('Europe/London');
require_once 'db.php';
$dbObj = new ConnectDb();



if($dbObj->is_ajax()){
	if (isset($_POST["action"]) && !empty($_POST["action"])) { //Checks if action value exists
		
		$action = $dbObj->xss_clean($_POST['action']);

		switch($action){
			case 'domain_data':
				$domain_name = $dbObj->xss_clean($_POST['value']);
				$created_date = date("Y-m-d H:i:s");
				$created_ip = $dbObj->get_client_ip();
				
				$domainStmt = $dbObj->insert_records('op2mro9899_add_domain',array('domain_name' => $domain_name, 'created_date' => $created_date, 'created_ip' => $created_ip));
				if($domainStmt){
					echo $res = json_encode($dbObj->get_row_by_id('op2mro9899_add_domain', 'site_id', $domainStmt));
				} else {
					echo json_encode(array("message"=>'Something went wrong',"status"=>false,"data"=>array()));
				}
			break;
			
			case 'qt5gduser':
				
				$user_name = $dbObj->xss_clean($_POST['user_name']);
				$user_email = filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL);
				$user_password = $dbObj->getSaltedHash($_POST['user_password']);
				$user_level = intval($_POST['user_level']);
				$domain_id = intval($_POST['domain_id']);
				$created_date = date("Y-m-d H:i:s");
				$created_ip = $dbObj->get_client_ip();
				
				$usrStmt = $dbObj->insert_records('op2mro9899_admin_useres',array('user_name' => $user_name, 'user_email' => $user_email, 'user_password' => $user_password, 'user_level' => $user_level, 'domain_id' => $domain_id, 'created_date' => $created_date, 'created_ip' => $created_ip));
				if($usrStmt){
					echo $res = json_encode($dbObj->get_row_by_id('op2mro9899_admin_useres', 'id', $usrStmt));
				}else{
					echo json_encode(array("message" => 'Something went wrong', "status" => false));
				}
				
			break;
			
		 default:
			echo '';
			break;
		}
	}
}


?>
