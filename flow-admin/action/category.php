<?php

include_once '../includes/db.php';
include_once '../includes/custom-functions.php';

$obj = new ConnectDb();
$cFunc = new CustomFunctions();

if($cFunc->is_ajax()){
	$action = $cFunc->xss_clean($_POST['action']);
	
	switch($action){
		case "chkCatName":
			$cat_name = $cFunc->xss_clean($_POST['cat_name']);
			
			$chk_stmt = $obj->check_exist_value('op2mro9899_category', 'category_name', $cat_name);
			if($chk_stmt > 0){
				echo json_encode(array('msg' => 'Category already exist.', 'status' => false));
			}else{
				echo json_encode(array('msg' => 'Category Added', 'status' => false));
			}
			
		break;
		
		case "delete_image":
			
			
			$imgPath = $cFunc->xss_clean(str_replace('./', '/', $_POST['img']));
			$siteID = intval($_POST['siteID']);
			$catID = intval($_POST['catID']);
			
			$full_img = str_replace('/thumbnail/', '/full/', $imgPath);
			$med_img = str_replace('/thumbnail/', '/medium/', $imgPath);
			
			$del_img = unlink('/var/www/html/new_demo/html/florist-admin/'.$imgPath.'');
			$del_med_imgs = unlink('/var/www/html/new_demo/html/florist-admin/'.$med_img.'');
			$del_full_imgss = unlink('/var/www/html/new_demo/html/florist-admin/'.$full_img.'');

			$del_img = $obj->deleteImage($siteID, $catID);
			if($del_img){
				echo json_encode(array('msg' => 'Image delete successfully', 'status' => true));
			}else{
				echo json_encode(array('msg' => 'Something went wrong', 'status' => false));
			}
			
		break;
		
		case "active_status":
			$active_val = intval($_POST['active_val']);
			$datacat_id = $cFunc->DecryptClientId($_POST['datacat_id']);
			$data = array('active' => $active_val);
			$q = $obj->update_row('op2mro9899_category', $data,"WHERE cat_id = '$datacat_id'");	
			if($q){
				echo json_encode(array('msg' => 'updated successfully', 'status' => 'true'));
			}else{
				echo json_encode(array('msg' => 'something went wrong please try again later', 'status' => 'false'));
			}
		break;
		
		case "active_menu":
			
			$data_catid = intval($_POST['data_catid']);
			$menu_id = $_POST['menu_id'];
			$menu_args = array('menu' => $menu_id);
			$upStmt = $obj->update_row('op2mro9899_category', $menu_args,"WHERE cat_id = '$data_catid'");
			if($upStmt){
				echo json_encode(array('status' => 'true', 'msg' => 'successfully updated'));
			}else{
				echo json_encode(array('status' => 'false', 'msg' => 'something went wrong'));
			}
		break;
		
		
		case "sort_category":
			
			$data = json_decode($_POST['data']);
			$counter = 1;
			foreach($data as $key=>$val){
				$dataArr = array('cat_order' => $counter);
				$update_stmt =  $obj->update_row('op2mro9899_category', $dataArr,"WHERE cat_id = '$val'");
				$counter++;
			}
			echo "saved";
			
		break;
		
		default:
		break;
	}
    
}
    
?>
