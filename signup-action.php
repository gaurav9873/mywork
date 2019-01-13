<?php 
include("includes/conn.php");

if($_POST['task']=='signup')
{
	$fullname = trim($_POST['fullname']) ;
	$email = trim($_POST["emailaddress"]);
	
	$cpassword = $_POST["cpassword"] ;
	
	$err = false ; 
	$_SESSION['errmsg'] = "";
	
	if($fullname=="" || $fullname=='Your Name'){
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
	
	if($cpassword==""){
		$err = true ;
		$_SESSION['errmsg'] .= "Enter your password <br/>";
	}
	
	if($err){
		header("location:sign-up.php");
		exit;
	}else{
		
		$sqlres =  mysql_query("select email from  users where email='".$email."'  and  user_type='1' ") ;
		
		if(mysql_num_rows($sqlres) > 0){
			$err = true ;
			$_SESSION['errmsg'] .= "email address already exist. <br/>";
		
			header("location:sign-up.php");
			exit;
		}else{
			$sqlquery = "insert into users set fullname='".$fullname."' , email='".$email."' , password='".$cpassword."' , date='".date('Y-m-d H:i:s')."'" ;
			mysql_query($sqlquery);
			$insertid = mysql_insert_id();
			
			$_SESSION['nextstep'] = $insertid ;
			$_SESSION['task'] = "successsignup" ;	
			$_SESSION['regemail'] = $email ;
			$_SESSION['regfullname'] = $fullname ;	
			$_SESSION['regpass'] = $password ;	
			header("location:makemodels.php");
			exit;
		}
	}
}

if(isset($_SESSION['nextstep']) && $_SESSION['nextstep']!="" && $_POST['task'] == 'signupmakes' && $_SESSION['task']=='successsignup'){

	$years = $_POST['years'];
	$makes = trim($_POST['makes']);
	$models = trim($_POST['models']);
	
	$err = false ; 
	$_SESSION['errmsg'] = "";
	
	if($makes==""){
		$err = true ; 
		$_SESSION['errmsg'] .= "makes can not  be blank <br/> ";
	}
	
	if($models==""){
		$err = true ; 
		$_SESSION['errmsg'] .= "models can not  be blank <br/> ";
	}
	
	if($err){
		header("location:makemodels.php");
		exit;
		
	}else{
		$sqlquery = "update users set makeyear='".$years."' , makes='".$makes."' , models='".$models."'  where id = '".$_SESSION['nextstep']."' " ;
		mysql_query($sqlquery);	
		$_SESSION['task'] = "successmakes" ;
		header("location:car-option.php");
		exit;	
	}
}

if(isset($_SESSION['nextstep']) && $_SESSION['nextstep']!="" && $_POST['task'] == 'signupcaroption' && $_SESSION['task']=='successmakes'){
	
	$trimname = $_POST['trimname'];
	$prefcolor = implode(',',$_POST['prefcolor']);
	$specialpref = trim($_POST['specialpref']);
	$ordervehicle = trim($_POST['ordervehicle']);
	$tradein = trim($_POST['tradein']);
	$caroption = trim($_POST['caroption']);
	$paymenttype = trim($_POST['paymenttype']);
	$zipcode = trim($_POST['zipcode']);
	
	$err = false ; 
	$_SESSION['errmsg'] = "";
	
	if($trimname==""){
		$err = true ; 
		$_SESSION['errmsg'] .= "select at least one trim <br/> ";
	}
	if($prefcolor==""){
		$err = true ; 
		$_SESSION['errmsg'] .= "select at least one color<br/> ";
	}
	if($specialpref==""){
		$err = true ; 
		$_SESSION['errmsg'] .= "special preference can not be blank <br/> ";
	}
	if($ordervehicle==""){
		$err = true ; 
		$_SESSION['errmsg'] .= "select at least one order vehicle <br/> ";
	}
	if($tradein==""){
		$err = true ; 
		$_SESSION['errmsg'] .= "select at least one trade type <br/> ";
	}	
	if($caroption==""){
		$err = true ; 
		$_SESSION['errmsg'] .= "select at least one car type <br/> ";
	}
	if($paymenttype==""){
		$err = true ; 
		$_SESSION['errmsg'] .= "select at least one payment type <br/> ";
	}
	if($zipcode==""){
		$err = true ; 
		$_SESSION['errmsg'] .= "zipcode can not be blank <br/>";
	}
	if($zipcode!=""){
	
		$resy = mysql_query("select * from zipcode  where zipcode='".$zipcode."'");
		$numzipcode =  mysql_num_rows($resy);
		
		if($numzipcode < 1 ){
			$err = true ; 
			$_SESSION['errmsg'] .= "zipcode does not exist <br/>";
		}
	}
	
	if($err){
		header("location:car-option.php");
		exit;
	}else{
		
		$sqlquery = "update users set trimname='".$trimname."' , prefcolor='".$prefcolor."' , specialpref='".$specialpref."' , ordervehicle='".$ordervehicle."' , tradetype='".$tradein."', cartype='".$caroption."' , paymenttype='".$paymenttype."' , zipcode='".$zipcode."' where id = '".$_SESSION['nextstep']."' " ;
		
				
		mysql_query($sqlquery);	
		
		$to      = $_SESSION['regemail'];
		$subject = 'registration to caroye.com for buyer account';
		$message = "Hello " . $_SESSION['regfullname'] . "<br/>";
		$message .= "your registration successfullly at caroye.com<br/>";
		$message .= "your firstname : ".$_SESSION['regfullname']. "<br/>";
		$message .= "your password : ".$_SESSION['regpass']." <br/>";
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		// Additional headers
		$headers .= "To: <$to>" . "\r\n";
		$headers .= "From: <prakash@w3csolutions.com>" . "\r\n";
		
		mail($to, $subject, $message, $headers);
					
		session_unset();
		session_destroy();
		
		header("location:thankyou.php");
		exit;	
	}
	
}
?>