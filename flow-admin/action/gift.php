<?php
include_once '../includes/db.php';
include_once '../includes/custom-functions.php';

$obj = new ConnectDb();
$cFunc = new CustomFunctions();


$action = $_POST['action'];

switch($action){
	case "updateOrder":
	
	
		$data = json_decode($_POST['data']);
		
		$counter = 1;
		foreach($data as $key=>$val){
			$dataArr = array('gift_order' => $counter);
			$update_stmt =  $obj->update_row('op2mro9899_gifts', $dataArr,"WHERE id = '$val'");
			$counter++;
		}
		echo 'saved';
		
	break;
	
	case "deleteGift":
		$gift_id = intval($_POST['gift_id']);
		$row = $obj->get_row_by_id('op2mro9899_gifts', 'id', $gift_id);
		$full_path = str_replace('./', '/', $row[0]['full_path']);
		$medium_path = str_replace('./', '/', $row[0]['medium_path']);
		$thumbnail_path = str_replace('./', '/', $row[0]['thumbnail_path']);

		$del_timg = unlink('/var/www/html/new_demo/html/florist-admin/'.$thumbnail_path.'');
		$del_mimg = unlink('/var/www/html/new_demo/html/florist-admin/'.$medium_path.'');
		$del_fimg = unlink('/var/www/html/new_demo/html/florist-admin/'.$full_path.'');
		
		$del_relation = $obj->deleteRow('op2mro9899_gifts', 'id', $gift_id);
		$del_stmt = $obj->deleteRow('op2mro9899_gifts', 'id', $gift_id);
		if($del_stmt){
			echo json_encode(array('msg' => 'Successfully deleted', 'status' => true));
		}else{
			echo json_encode(array('msg' => 'Something went wrong', 'status' => false));
		}
		
		
	break;
	
	
	case "deleteImage":
		$imgID = intval($_POST['imgID']);
		$rowid = $obj->get_row_by_id('op2mro9899_gifts', 'id', $imgID);
		
		$fullPath = str_replace('./', '/', $rowid[0]['full_path']);
		$mediumPath = str_replace('./', '/', $rowid[0]['medium_path']);
		$thumbnailPath = str_replace('./', '/', $rowid[0]['thumbnail_path']);
		
		$delFimg = unlink('/var/www/html/new_demo/html/florist-admin/'.$fullPath.'');
		$delMimg = unlink('/var/www/html/new_demo/html/florist-admin/'.$mediumPath.'');
		$delTimg = unlink('/var/www/html/new_demo/html/florist-admin/'.$thumbnailPath.'');
		
		$arr = array('full_path' => '', 'medium_path' => '', 'thumbnail_path' => '');
		$update_stmt = $obj->update_row('op2mro9899_gifts', $arr,"WHERE id = '$imgID'");
		if($update_stmt){
			echo json_encode(array('msg' => 'Successfully deleted', 'status' => true));
		}else{
			echo json_encode(array('msg' => 'Something went wrong', 'status' => false));
		}
		
	break;
	
	
}



?>
