<?php
include_once '../include/class-library.php';

include_once '../smtpmail/library.php';
include_once '../smtpmail/classes/class.phpmailer.php';

$obj = new CustomFunctions();
if($obj->is_ajax()){
	$json = json_decode(file_get_contents("php://input"));
	$user_name = $obj->cleanData($json->user_name);
	$user_email = filter_var($json->user_email, FILTER_SANITIZE_EMAIL);
	$user_phone = $json->user_phone;
	$user_message = $obj->cleanData($json->user_message);
	
	$bodymsg .= 'Name: '.$user_name.''.'<br><br>';
	$bodymsg .= 'Email: '.$user_email.''.'<br><br>';
	$bodymsg .= 'Phone: '.$user_phone.''.'<br><br>';
	$bodymsg .= 'Comment: '.$user_message.''.'<br><br>';
	$bodymsg .= 'Thank you, <br> Nicola Florist';
	
	$email = 'info@fleurdelisflorist.co.uk';
	$mail = new PHPMailer(); 
	$mail->IsSMTP(); 
	//$mail->SMTPSecure = "tls";
	$mail->Host = SMTP_HOST; 
	$mail->Port = SMTP_PORT; 
	$mail->SMTPAuth = true; 
	$mail->Username = SMTP_UNAME; 
	$mail->Password = SMTP_PWORD; 
	//$mail->AddReplyTo("noreply", "Fleur De Lis"); //reply-to address
	$mail->SetFrom("info@fleurdelisflorist.co.uk", "Nicola Florist"); //From address of the mail
	// put your while loop here like below,
	$mail->Subject = "Nicola Florist Contact us enquiry"; //Subject of your mail
	$mail->AddAddress($email); //To address who will receive this email
	
    //$mail->AddBCC($email2, "Fleur De Lis");
	$mail->MsgHTML($bodymsg); //Put your body of the message you can place html code here
	$send = $mail->Send(); //Send the mails
	
	if($send){
		echo json_encode(array('status' => 'true', 'msg' => 'mail send successfully'));
	}else{
		echo json_encode(array('status' => 'false', 'msg' => 'something went wrong'));
	}
	
}
?>
