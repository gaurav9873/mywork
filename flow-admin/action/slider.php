<?php
include_once '../includes/db.php';
include_once '../includes/custom-functions.php';

$obj = new ConnectDb();
$customObj = new CustomFunctions();

if($customObj->is_ajax()){ 
	
	$action = $_POST['action'];
	
	switch($action){
		
		case "delete_image":
			
			$img_id = $customObj->DecryptClientId($_POST['img_id']);
			$get_img = $obj->get_row_by_id('op2mro9899_slider', 'id', intval($img_id));
			
			$full_img = str_replace('./', '/', $get_img[0]['full_path']);
			$medium_img = str_replace('./', '/', $get_img[0]['medium_path']);
			$thumbnail_img = str_replace('./', '/', $get_img[0]['thumbnail_path']);
			
			$del_fullImg = unlink('/var/www/html/new_demo/html/florist-admin/'.$full_img.'');
			$del_mediumImg = unlink('/var/www/html/new_demo/html/florist-admin/'.$medium_img.'');
			$del_thumbImg = unlink('/var/www/html/new_demo/html/florist-admin/'.$thumbnail_img.'');
			
			$row_arr = array('full_path' => '', 'medium_path' => '', 'thumbnail_path' => '');
	        $update_stmt = $obj->update_row('op2mro9899_slider', $row_arr,"WHERE id = '$img_id'");
	        if($update_stmt){
				echo json_encode(array('msg' => 'successfully deleted', 'status' => true));
			}else{
				echo json_encode(array('msg' => 'something went wrong', 'status' => false));
			}
			
		break;
	}
	
}
?>
