<?php 
	include("includes/conn.php");
	
	if(!isset($_SESSION['buyer']) || $_SESSION['buyer']==""){
		header("location:index.php");
		exit; 
	}
	
	$resu =  mysql_query("select fullname , email ,landlineno from users where id ='".$_SESSION['buyer']."'");
	$userdata = mysql_fetch_assoc($resu);
	
	if($_POST['task'] == 'updateinfo'){
		
		$fullname = trim($_POST['fullname']);
		$phoneno = trim($_POST['phoneno']);
		
		$password =     $_POST['password'];
		$confpassword = $_POST['confpassword'];
		
		mysql_query("update users set fullname='".$fullname."' , landlineno='".$phoneno."' where id ='".$_SESSION['buyer']."'");
		
		if($password!="" && $password == $confpassword){
			 
			 mysql_query("update users set password='".$password."' where id ='".$_SESSION['buyer']."' ");   		
		}
		
		header("location:buyeraccount.php");
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
</head>
<body>
<div id="wrapper">
  <!--help_wfix-->
  <div id="help_wfix"> <a href="#"><img src="images/help.png" alt=""  /></a></div>
  <!--help_wfix-->
  <div id="header_main">
    <?php include("header-small.php"); ?>
  </div>
  
  <div id="container" class="back2">
    <div class="container_inner backnoun">
      <div class="cont-shadow back2">
        <div id="body_cont_in">
        <div class="payment_gateway_inner">
      <div class="gateway_conten">
      <div class="gateway_conten_left"></div>
      <div class="gateway_conten_center">
      <h1>Your Account </h1>
    
      </div>
      
      <div class="gateway_conten_right"></div>
      
      <div class="clear"></div>
      </div> 
<div class="payment_gateway_inner_cont">
<form method="post" >
<input type="hidden" name="task" value="updateinfo" />
<div class="gateway_conten1">
<div class="edit_Your_Account">
<h1>Edit Your Account</h1>
</div>
       <div class="your_account">
 <div class="contact_information">
<h1>Contact Information</h1> 
 </div>
 <div class="your_account_form_cont">
 <div class="your_accoun_text">Full Name</div>
 <div class="your_accoun_form_box">
   <input   type="text" name="fullname" class="form_your_account" value="<?php echo $userdata['fullname'];?>"/>
 </div>
 <div class="clear"></div>
 </div>
 <div class="your_account_form_cont">
 <div class="your_accoun_text">Email Address</div>
 <div class="your_accoun_form_box">
   <input   type="text" name="email" class="form_your_account"  value="<?php echo $userdata['email'];?>" />
 </div>
 <div class="clear"></div>
 </div>
 <div class="your_account_form_cont">
 <div class="your_accoun_text">Phone Number</div>
 <div class="your_accoun_form_box">
   <input  type="text" name="phoneno" class="form_your_account"  value="<?php echo $userdata['landlineno'];?>" />

 </div>
 <div class="clear"></div>
 </div>
 </div>   
 
 <div class="your_account">
 <div class="contact_information">
<h1>Change Password</h1> 
 </div>
 <div class="your_account_form_cont_password">
 <div class="your_accoun_text">New Password</div>
 <div class="your_accoun_form_box">
   <input  type="password" name="password" class="form_your_account" />
 </div>
 <div class="clear"></div>
 </div>
 <div class="note"><strong>Note: </strong>leave blank if you do not want to change password
 
</div>
 <div class="your_account_form_cont">
 <div class="your_accoun_text">Confirm Password</div>
 <div class="your_accoun_form_box">
   <input type="password"  name="confpassword" class="form_your_account" />
 </div>
 <div class="clear"></div>
 </div>
 </div>
 <div class="your_account">
 <div class="contact_information">
<h1>Email Preferences</h1> 
 </div>
 <div class="your_account_form_cont">
 <div class="update_text">Price Update It’s ok to email me price update on the car I selected</div>
 <div class="clear"></div>
 </div>
 </div>
 <div class="all_changes">
<div class="changes_button_gateway "><input  type="image" src="images/acount-update.jpg" alt="account info" /></div>
 </div>
     </div>
	 </form>
     </div>
        <div class="clear"></div>
         <div class="gateway_fotter"></div>
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
</div>
</body>
</html>
