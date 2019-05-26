<?php
include_once '../include/init.php';

if($obj->is_ajax()){
	$action = $_REQUEST['action'];
	switch($action){
		case "get_category":
			$cid = $obj->DecryptClientId($_POST['cid']);
			$sub_category_api = API_PATH.'child-category/'.intval($cid).'';
			$sub_cat = $obj->getCurl($sub_category_api);
			//print_r(json_encode($sub_cat->child_categories));
			$options = '';
			foreach($sub_cat->child_categories as $child_cat){
				$options .= '<option value="'.$obj->EncryptClientId($child_cat->cat_id).'">'.$child_cat->category_name.'</option>';
			}
			echo json_encode($options);
		break;
		
		
		case "search_q":
			$post_data = file_get_contents("php://input");
			$post_con = json_decode($post_data);
			$user_keyword = $post_con->user_keyword;
		    $search_term = (($user_keyword) ? $user_keyword : "''");
		    $parent_category = $obj->DecryptClientId($post_con->parent_category);
		    $price = $post_con->price_range;
			if($parent_category == 0){
				$cat_string = $parent_category;
			}else{
				$sub_category = $obj->DecryptClientId($post_con->sub_category);
				$cat_string = $parent_category .','. $sub_category;
				
			}
			$api = API_PATH.'advance-search/'.$price.'/'.$cat_string.'/'.$search_term.'';
			$records = $obj->getCurl($api);
			print_r($records); die;
			//~ foreach($records->search_key as $post_val){
				//~ print_r($post_val);
			//~ }
		break;
		
	}
}
?>
