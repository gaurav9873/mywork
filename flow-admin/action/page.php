<?php
include_once '../includes/db.php';
include_once '../includes/custom-functions.php';

$obj = new ConnectDb();
$cFunc = new CustomFunctions();

if($cFunc->is_ajax()){
	
	$action = $_POST['action'];
	
	switch($action){
		case "status":
			
			 $pid = intval($_POST['pid']);
			 $status = intval($_POST['status']);
			 
			 $data = array('status' => $status);
			 $update_stmt = $obj->update_row('op2mro9899_pages', $data,"WHERE id = '$pid'");
			 if($update_stmt){
				 echo json_encode(array('msg' => 'Status update successfully', 'status' => true));
			 }else{
				 echo json_encode(array('msg' => 'Something went wrong', 'status' => false));
			 }
			
		break;
		
		case "delete":
			
			$delID = intval($_POST['delID']); 
			$del_stmt =  $obj->deleteRow('op2mro9899_pages', 'id', $delID);
			$delr_stmt =  $obj->deleteRow('op2mro9899_pages_relation', 'page_id', $delID);
			if($delr_stmt){
				echo $req = json_encode(array('msg' => 'Delete successfully', 'status' => true));
			}else{
				echo json_encode(array('msg' => 'something went wrong', 'status' => false));
			}
		
		break;
		
		
		case "home_status":
			
				$pageID = intval($_POST['pids']);
				$statuss = intval($_POST['statuss']);
				
				$data_args = array('show_home_page' => $statuss);
				$stmt = $obj->update_row('op2mro9899_pages', $data_args,"WHERE id = '$pageID'");
				if($stmt){
					echo json_encode(array('msg' => 'Status update successfully', 'status' => true));
				}else{
					echo json_encode(array('msg' => 'Something went wrong', 'status' => false));
				}
			
		break;
		
	}
	
   
	
}




?>
