<?php 
include("includes/conn.php");

if($_POST['task']=='dealersignup')
{
	$fullname = trim($_POST['fullname']) ;
	$email = trim($_POST["email"]);
	$dealershipname = trim($_POST['dealershipname']) ;
	$password = $_POST["password"] ;
	
	$mobileno = trim($_POST['mobileno']) ;
	$landlineno = trim($_POST['landlineno']) ;
	$yourtitle = trim($_POST['yourtitle']) ;
 
	$err = false ; 
	$_SESSION['errmsg'] = "";
	
	if($dealershipname=="" ){
		$err = true ;
		$_SESSION['errmsg'] .= "Enter your dealership name. <br/>";
	}
	if($fullname==""){
		$err = true ;
		$_SESSION['errmsg'] .= "Enter your fullname. <br/>";
	}
	
	if($email==""){
		$err = true ;
		$_SESSION['errmsg'] .= "Enter your email address. <br/>";
	}
	elseif($email!=""){
		if(!eregi("^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$", $email)) {
			$err = true ;
			$_SESSION['errmsg'] .= "Invalid email address. <br/>";
		}
	}
	
	if($landlineno=="" ||   strlen($landlineno) > 11){
		$err = true ;
		$_SESSION['errmsg'] .= "Enter valid landline phone no. <br/>";
	}
	//!is_integer($mobileno) ||
	if($mobileno=="" ||  strlen($mobileno) > 11){
		$err = true ;
		$_SESSION['errmsg'] .= "Enter valid mobile no. <br/>";
	}
	
	if($password==""){
		$err = true ;
		$_SESSION['errmsg'] .= "Enter your password. <br/>";
	}
	
	if($err){
		$_SESSION['postvar'] = $_POST ;
		header("location:dealer-signup.php");
		exit;
	}else{
		
		$sqlres =  mysql_query("select email from  users where email='".$email."'  and  user_type='2' ") ;
		
		if(mysql_num_rows($sqlres) > 0){
			
			$err = true ;
			$_SESSION['postvar'] = $_POST ;
			$_SESSION['errmsg'] .= "email address already exist. <br/>";
		
			header("location:dealer-signup.php");
			exit;
		}else{
			
			$sqlquery = "insert into users set fullname='".$fullname."' , user_type='2' , email='".$email."' , password='".$password."' , dealershipname='".$dealershipname."' , mobileno='".$mobileno."' , landlineno='".$landlineno."' , yourtitle='".$yourtitle."' , date='".date('Y-m-d H:i:s')."'" ;
			
			mysql_query($sqlquery);
			
			$to      = $email;
			$subject = 'registration to caroye.com for dealer account';
			$message = "Hello " . $fullname . "<br/>";
			$message .= "your registration successfullly at caroye.com <br/>";
			$message .= "your firstname : ".$fullname. "<br/>";
			$message .= "your password : ".$password." <br/>";
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			
			// Additional headers
			$headers .= "To: <$to>" . "\r\n";
			$headers .= "From: <prakash@w3csolutions.com>" . "\r\n";
			
			mail($to, $subject, $message, $headers);
		
			session_unset();
			header("location:thankyou.php");
			exit;
		}
	}
}

?>