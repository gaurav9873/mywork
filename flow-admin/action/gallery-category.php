<?php
include_once '../includes/db.php';
include_once '../includes/custom-functions.php';

$obj = new ConnectDb();
$customObj = new CustomFunctions();


if($customObj->is_ajax()){ 
	$action = $_POST['action'];
	switch($action){
		case "delete":
			$cat_id = $customObj->DecryptClientId($_POST['cat_id']);
			$get_row = $obj->get_row_by_id('op2mro9899_gallery_category', 'id', intval($cat_id));
			
			$full_path = str_replace('./', '/', $get_row[0]['full_path']);
			$medium_path = str_replace('./', '/', $get_row[0]['medium_path']);
			$thumbnail_path = str_replace('./', '/', $get_row[0]['thumbnail_path']);
			
			$del_fimg = unlink('/var/www/html/new_demo/html/florist-admin/'.$full_path.'');
			$del_mimg = unlink('/var/www/html/new_demo/html/florist-admin/'.$medium_path.'');
			$del_timg = unlink('/var/www/html/new_demo/html/florist-admin/'.$thumbnail_path.'');
			
			$del_relation = $obj->deleteRow('op2mro9899_gallery_category', 'id', $cat_id);
		    $del_stmt = $obj->deleteRow('op2mro9899_gallery_category_relation', 'gallery_cat_id', $cat_id);
			if($del_stmt){
				echo json_encode(array('msg' => 'successfully deleted', 'status' => true));
			}else{
				echo json_encode(array('msg' => 'something went wrong', 'status' => false));
			}	
		break;
		
		case "delete_image":
			$img_id = $customObj->DecryptClientId($_POST['img_id']);
			$get_img = $obj->get_row_by_id('op2mro9899_gallery_category', 'id', intval($img_id));
			
			$full_img = str_replace('./', '/', $get_img[0]['full_path']);
			$medium_img = str_replace('./', '/', $get_img[0]['medium_path']);
			$thumbnail_img = str_replace('./', '/', $get_img[0]['thumbnail_path']);
			
			$del_fullImg = unlink('/var/www/html/new_demo/html/florist-admin/'.$full_img.'');
			$del_mediumImg = unlink('/var/www/html/new_demo/html/florist-admin/'.$medium_img.'');
			$del_thumbImg = unlink('/var/www/html/new_demo/html/florist-admin/'.$thumbnail_img.'');
			
			$row_arr = array('full_path' => '', 'medium_path' => '', 'thumbnail_path' => '');
	        $update_stmt = $obj->update_row('op2mro9899_gallery_category', $row_arr,"WHERE id = '$img_id'");
	        if($update_stmt){
				echo json_encode(array('msg' => 'successfully deleted', 'status' => true));
			}else{
				echo json_encode(array('msg' => 'something went wrong', 'status' => false));
			}
			
		break;
	}
}
?>




