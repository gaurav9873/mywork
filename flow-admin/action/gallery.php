<?php
include_once '../includes/db.php';
include_once '../includes/custom-functions.php';

$obj = new ConnectDb();
$customObj = new CustomFunctions();

if($customObj->is_ajax()){
	
    $action = $customObj->xss_clean($_POST['action']);
    
    switch($action){
		case "delete_gallery":
			
			$gallery_id = $customObj->DecryptClientId($_POST['gallery_id']);
			$get_rows = $obj->get_row_by_id('op2mro9899_gallery_images', 'gid', intval($gallery_id));
			$get_row = array_filter($get_rows);
			if(!empty($get_row)){
				foreach($get_row as $row_val){
					$full_path = str_replace('./', '/', $row_val['full_path']);
					$medium_path = str_replace('./','/',$row_val['medium_path']);
					$thumbnail_path = str_replace('./', '/', $row_val['thumbnail_path']);
					
					$del_fimg = unlink('/var/www/html/new_demo/html/florist-admin/'.$full_path.'');
					$del_mimg = unlink('/var/www/html/new_demo/html/florist-admin/'.$medium_path.'');
					$del_timg = unlink('/var/www/html/new_demo/html/florist-admin/'.$thumbnail_path.'');
				}
				
				$del_img_stmt = $obj->deleteRow('op2mro9899_gallery_images', 'gid', intval($gallery_id));
			}
			$del_stmt = $obj->deleteRow('op2mro9899_galleries', 'id', intval($gallery_id));
			if($del_stmt){
				echo json_encode(array('msg' => 'successfully deleted', 'status' => true));
			}else{
				echo json_encode(array('msg' => 'somrthing went wrong', 'status' => false));
			}
			
		break;
		
		case "delete_image":
          
          $image_id = $customObj->DecryptClientId($_POST['image_id']);
          $img_row = $obj->get_row_by_id('op2mro9899_gallery_images', 'id', intval($image_id));
          
          $full_img = str_replace('./', '/', $img_row[0]['full_path']);
          $mid_img = str_replace('./', '/', $img_row[0]['medium_path']);
          $th_img = str_replace('./', '/', $img_row[0]['thumbnail_path']);
          
		 $unlink_full_img = unlink('/var/www/html/new_demo/html/florist-admin/'.$full_img.'');
		 $unlink_mid_img = unlink('/var/www/html/new_demo/html/florist-admin/'.$mid_img.'');
		 $unlink_thu_img = unlink('/var/www/html/new_demo/html/florist-admin/'.$th_img.'');
		 
		 $delete_stmt = $obj->deleteRow('op2mro9899_gallery_images', 'id', intval($image_id));
         if($delete_stmt){
			 echo json_encode(array('msg' => 'delete successfully', 'status' => true));
		 }else{
			 echo json_encode(array('msg' => 'something went wrong', 'status' => false));
		 }
		break;
	}
	
}
?>
