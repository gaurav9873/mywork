<?php 
	include("includes/conn.php");
	
		
	if($_POST['task']=='forgot'){
		
		$email = $_POST['emailaddress'] ;
		$sqlres =  mysql_query("select email , fullname , password from  users where email='".$email."'  and  user_type='1' ") ;
		$numuser =  mysql_num_rows($sqlres);
		$_SESSION['errmsg']="";
		
		if($numuser > 0){
	
			$data = mysql_fetch_assoc($sqlres);
			$to      = $data['email'];
			$subject = 'lost passoword for caroye.com buyer account';
			$message = "Hello " . $_SESSION['regfullname'] . "<br/>";
			$message .= "your lost passoword for caroye.com buyer account is below <br/>";
			$message .= "your firstname : ".$data['fullname']. "<br/>";
			$message .= "your password : ".$data['password']." <br/>";
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			
			// Additional headers
			$headers .= "To: <$to>" . "\r\n";
			$headers .= "From: <prakash@w3csolutions.com>" . "\r\n";
			
			mail($to, $subject, $message, $headers);
			$_SESSION['errmsg'] = "password has been sent to registered email address" ;
		}else {
			$_SESSION['errmsg'] = "Email address does not exist" ;	
		}
		header("location:forgotpassword.php");
		exit;
	}	
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Car Oye!</title>
<link rel="stylesheet" href="css/style.css" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>

<script type="text/javascript">
function chkreg(){
		var emailadd = $("input[name=emailaddress]").val();
		var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/; 
		$("#errormsg").html("");
		$("#errormsg").fadeIn();
		
		
		if(emailadd=="" || emailadd=='Email Address'){
			
			$("#errormsg").html("Enter email address");
			 $("input[name=emailaddress]").focus();
			return false;
		}
		if(emailadd!=""){
			
			var emailvalid = emailPattern.test(emailadd);
			if(!emailvalid){
			
				$("#errormsg").html("Enter valid email address"); 
				$("input[name=emailaddress]").focus();
				return false ;
				
			}
		}	
}
</script>
</head>
<body>
<div id="wrapper">
  <!--help_wfix-->
  <div id="help_wfix"> <a href="#"><img src="images/help.png" alt=""  /></a></div>
  <!--help_wfix-->
  <div id="header_main">
    <?php include("header.php");?>
  </div>
  <div id="nav_main">
    <?php include("nav_menu.php"); ?>
  </div>
  <div id="container" class="back2">
    <div class="container_inner backnoun">
      <div class="cont-shadow back2">
        <div id="body_cont_in">
        <div class="top-tlt">
       		<h1>Reset your passworddcdddfdfdf</h1>
        </div>
        </div>
	<div id="sing_up_cont_in">
		<form method="post"   onsubmit="return chkreg();">
		<input type="hidden" name="task" value="forgot" />
		<div id="sing_up_cont_in_inner" style="background-image:url(images/resetpassword-bg.png)">
		<div class="get_started_cont">
		<div class="get_started_cont_text">
		<h1>&nbsp;</h1>
		</div>
		
		<div id="errormsg" style="color:#FF0000; font-size:11px; line-height:20px; display:none;"></div>
		<?php if(isset($_SESSION['errmsg']) && $_SESSION['errmsg']!="") { ?>
		<div id="errormsg" style="color:#FF0000; font-size:11px; line-height:20px;"><?php echo $_SESSION['errmsg'] ; echo $_SESSION['errmsg']="";?></div>
		<?php } ?>
		
		<div class="get_started_cont_form">
		<input type="text" class="caroye-form-input-plain" style="width:300px;"  name="emailaddress" value="Email Address" onblur="if(this.value=='')this.value='Email Address'" onfocus="if(this.value=='Email Address')this.value=''" />
		</div>
		
		
		<div><input type="image" src="images/resetpassword.jpg" /></div>
			
		</div>
		</div>
		</form>
	 </div>       
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
    </div>
  </div>
  
    <!--footer start here-->
  <?php include("footer.php");?>
  <!--footer end here-->
</div>
</body>
</html>
