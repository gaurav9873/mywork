<?php
include_once '../include/class-library.php';
$obj = new CustomFunctions();

if($obj->is_ajax()){
	
	$action = $_POST['action'];
   	switch($action){
		
		case "checkEmail":
			$eid = filter_var($_POST['eid'], FILTER_VALIDATE_EMAIL);
			if($eid){
				$url = "http://54.191.172.136:82/florist-admin/flowers/api/emailexist/$eid";			
				$req = $obj->httpGet($url);
				print_r($req);
			}
		break;
	}
	
	

}

?>
