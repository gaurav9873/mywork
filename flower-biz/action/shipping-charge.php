<?php
include_once '../include/init.php';
$dbobj = new ConnectDb();

if($obj->is_ajax()){
	$post_action = $_POST['action'];
    switch($post_action){
		case "qtspcode":
			$post_codes = strtoupper($_POST['post_codes']); 
			$pcode = trim($post_codes);
 			$site_id = SITE_ID;
			$shipping_cahrge = $dbobj->get_shipping_charge($pcode, $site_id);
			if(empty($shipping_cahrge)){
				echo json_encode(array('status' => 'false', 'msg' => 'Location not serviceable please check post code.')); die;
			}else{
				$location_name = $shipping_cahrge[0]->location_name;
				$outer_post_code = $shipping_cahrge[0]->outer_post_code;
				$inner_post_code = $shipping_cahrge[0]->inner_post_code;
				$delivery_charges = $shipping_cahrge[0]->delivery_charges;
				echo json_encode(array('status' => 'true', 'delivery_charges' => $delivery_charges, 'location_name' => $location_name)); die;
			}
		break;
	}
	
}
