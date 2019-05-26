<?php
include_once '../include/class-library.php';

$obj = new CustomFunctions();
session_start();

if($obj->is_ajax()){
	
	$action = $_REQUEST['action'];
	
	
	switch($action){
		case "update_qty":
			
			$pqty = intval($_POST['pqty']);
			$proKey = $_POST['proKey'];
			$proType = ucfirst($_POST['proType']);
			$_SESSION['cart']['products'][$proType][$proKey]['quantity'] = $pqty;
		break;
		
		case "remove_item":
			$item_key = $_POST['item_id'];
			$ptype = ucfirst($_POST['ptype']);
			$pkey = $_POST['pkey'];
			unset($_SESSION['cart']['products'][$ptype][$pkey]);
		break;
		
		case "remove_gift":
			$gift_id = intval($_POST['gift_id']);
			foreach($_SESSION['cart']['gift'] as $key => $val){
				
				if($val == $gift_id){
					unset($_SESSION['cart']['gift'][$key]);
				}
			}
		break;
		
		case "giftqty":
		
			$gift_quantity = intval($_POST['gift_quantity']);
			$giftID = intval($_POST['giftID']);
			foreach($_SESSION['cart']['gift'] as $k => $v){
				if($v == $giftID){
					unset($_SESSION['cart']['gift'][$k]);
				}
			}
			
			for($i=1; $i<=$gift_quantity; $i++){
				array_push($_SESSION['cart']['gift'], $giftID);
			}
					
		break;
		
		case "add_gift":
			$giftKey = $obj->DecryptClientId($_POST['giftKey']);
			array_push($_SESSION['cart']['gift'], $giftKey);
		break;
	}

}
?>
