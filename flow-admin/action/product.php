<?php
include_once '../includes/db.php';
include_once '../includes/custom-functions.php';

$obj = new ConnectDb();
$cFunc = new CustomFunctions();

if($cFunc->is_ajax()){
	
	$action = $cFunc->xss_clean($_POST['action']);
   
    
    switch($action){
		case 'delete_image' :
			$imgID = intval($_POST['img_id']);
			$p_img = $obj->get_row_by_id('op2mro9899_products_image', 'img_id', $imgID);


			$full_path = str_replace('./', '/', $p_img[0]['full_path']);
			$medium_path = str_replace('./', '/', $p_img[0]['medium_path']);
			$thumbnail_path = str_replace('./', '/', $p_img[0]['thumbnail_path']);
			
			$del_timg = unlink('/var/www/html/new_demo/html/florist-admin/'.$thumbnail_path.'');
			$del_mimg = unlink('/var/www/html/new_demo/html/florist-admin/'.$medium_path.'');
			$del_fimg = unlink('/var/www/html/new_demo/html/florist-admin/'.$full_path.'');
        
			$del_stmt = $obj->deleteRow('op2mro9899_products_image', 'img_id', $imgID);
			if($del_stmt){
				echo json_encode(array('msg' => 'Image deleted successfully', 'status' => true));
			}else{
				echo json_encode(array('msg' => 'Something went wrong', 'status' => false));
			}
			
		break;
	}
	
}
?>
