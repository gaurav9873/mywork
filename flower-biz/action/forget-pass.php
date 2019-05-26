<?php
$path = realpath(dirname(__FILE__));

include_once '../include/init.php';

$dbobj = new ConnectDb();
$cobj = new CustomFunctions();
$user = new Login();
if($cobj->is_ajax()){
	
	$json = json_decode(file_get_contents("php://input"));
	$forget_email = filter_var($json->forget_email, FILTER_SANITIZE_EMAIL);
	
	$chk_exist_email = $user->chk_exist_email($forget_email, SITE_ID);
	if(!empty($chk_exist_email)){
		
		//$pass = $cobj->randomPassword();
		$pass = $chk_exist_email[0]->password_txt;
		$pasword = hash('sha256', $pass);
		$uids = $chk_exist_email[0]->id;
		$usalt = $cobj->unique_salt();
		$unique_salt = $uids.$usalt;
		$msg  = 'Please use this password to login: '.trim($pass).'<br /><br /> From <br /> Nicola Florist';
		$subj = 'Reset your Password - Flower Corner';
		$to   = $forget_email;
		$from = 'info@theflowercorner.biz';
		$name = 'Flower Corner';
		$cobj->password_smtpmailer($to,$from, $name ,$subj, $msg);
		//$cust_args = array('user_password' => $pasword, 'unique_key' => $unique_salt, 'pwd_status' => 1);
		$cust_args = array('unique_key' => $unique_salt, 'pwd_status' => 1);
		$condition = array('id' => $uids);
		$update_stmt = $dbobj->update_record('op2mro9899_customers_login', $cust_args, $condition);
		if($update_stmt){
			echo json_encode(array('status' => 'true', 'msg' => 'Please use the password sent on your email id to login'));
		}else{
			echo json_encode(array('status' => 'false', 'msg' => 'Sorry, something went wrong please try again later'));
		}
	}else{
		echo json_encode(array('status' => 'false', 'msg' => 'Email not found in our database.'));
	}

}

?>
