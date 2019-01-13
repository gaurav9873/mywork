<?php
require('includes/application_top.php');

if($_SESSION['admin_id']==""){
		header("location:login.php");
}

	if($_POST['task']=="adddealer"){
			
			$err = false ;
			$_SESSION['regmsg'] = ""; 
			
		
			
			$dname = $_POST['dname'] ;
			$add = $_POST['add'] ;
			$email = $_POST['emailaddress'] ;
			$contact = $_POST['contact'] ;
			$company_name = $_POST['company_name'] ;
			$brandname = $_POST['brandname'] ;
			$status = $_POST['status'] ;
			
			$details = $_POST['details'];
			
			
		
			if($dname==""){
				$err = true ;
				$_SESSION['regmsg'] .= "Enter Dealer Name in the text  box.<br />";
			}
			if($email=="")
			{
				$err = true ;
				$_SESSION['regmsg'] .= "Enter Email in the text box.<br />";
			}
			
			/*$sqlres =  mysql_query("select email from  users where email='".$email."'  and  user_type='".$usertype."' ") ;
			if(mysql_num_rows($sqlres) > 0)
			{
				$err = true ;
				$_SESSION['regmsg'] .= "email address already exist.";
			 
			}*/
			if($err)
			{
				//header("location:addusers.php");
			} else {
			
		echo	$sqlq="INSERT INTO `dealer`(`name`,`address`,`email`,`contactno`,`d_company`,`d_brand`,`status`)VALUES('$dname','$add','$email','$contact','$company_name','$brandname','$status')";
			  #	$sqlq  = "insert into dealer set name='".$dname."', add='".$add."', email='".$email."', contactno='".$contact."', d_company='".$company_name."', d_brand='".$brandname."', status='".$status."'"; 
				mysql_query($sqlq);
				#$_SESSION['regmsg'] = "new dealer  added successfully.";
				header("location:dealer.php?msg=Dealer Has Been Added Successully");
	exit;
			}
	
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Caroye.com :: Admin</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript">
 
	function chkreg(){
		//alert($("input[name=cpassword]").val())
		var fullname = $("input[name=fullname]").val();
		var emailadd = $("input[name=emailaddress]").val();
		var cpassword = $("input[name=cpassword]").val();
		var confpassword = $("input[name=confpassword]").val();
		var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/; 
		$("#errormsg").html("");
		$("#errormsg").fadeIn();
		
		if(fullname=="" || fullname=='Your Name'){
		   $("#errormsg").html("Enter your Fullname");
		   $("input[name=fullname]").focus();
			return false;
		}
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
		
		if(cpassword=="" || cpassword=='Password'){
			$("#errormsg").html("Enter Password"); 
			 $("input[name=cpassword]").focus();
			return false;
		}
		
		if(confpassword=="" || confpassword=='Password'){
			$("input[name=confpassword]").focus();
			$("#errormsg").html("Enter conform Password"); 
			return false;
		}
		
		if(cpassword != confpassword){
			$("#errormsg").html("Password and conform password should be match");
			$("input[name=confpassword]").val('');
			$("input[name=confpassword]").focus();
			return false;
		}
	}
</script>
</head>
<body>
<?php
	require('includes/header.php');
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="22" valign="top" class="right_top_l_bg"><img src="images/right_top_l.gif" alt="" width="22" height="236" /></td>
                  <td align="left" valign="top" class="right_top_top_bg"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td height="19"></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="24" align="left" valign="top"><img src="images/left_rouud_blu.jpg" alt="" width="24" height="35" /></td>
                          <td align="left" valign="middle" class="middle_bg_blu"><h4>Add New Dealer</h4></td>
                          <td width="31" align="right" valign="top"><img src="images/right_rouud_blu.jpg" alt="" width="31" height="35" /></td>
                        </tr>
                      </table></td>
                    </tr>
                      <tr>
                      <td height="12"  
					  <div style="color:red; margin-left:300px;"><?php echo $_SESSION['regmsg'] ; $_SESSION['regmsg']=""; ?></div></td>
                    </tr>
                    <tr>
                      <td align="center" valign="top">
                      <form name="adddealer"  method="post" onsubmit="return chkreg();" >
						 <input type="hidden" name="task" value="adddealer" />						
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
					  
		 <tr>
   <td align="left" valign="top" class="page-tile"><div id="errormsg" style="color:#FF0000; font-size:11px; line-height:20px; display:none;"></div>
		<?php if(isset($_SESSION['errmsg']) && $_SESSION['errmsg']!="") { ?>
		<div id="errormsg" style="color:#FF0000; font-size:11px; line-height:20px;"><?php echo $_SESSION['errmsg'] ; echo $_SESSION['errmsg']="";?></div>
		<?php } ?></td>
     <td align="left" valign="top" class="tr-color"> 
      
      </td>
  </tr>
  
  
                       
  <tr>
   <td align="left" valign="top" class="page-tile"><strong>Dealer Name</strong></td>
     <td align="left" valign="top" class="tr-color"> 
       <input name="dname" type="text" value="" class="input-type"/>
      </td>
  </tr>
 
  <tr>
    <td width="20%" align="left" valign="top" class="page-tile tr-color"><strong>Address</strong></td>
    <td width="80%" align="left" valign="top"  class="tr-color"><input name="add" type="text" value="" class="input-type"/></td>
  </tr>
  
   <tr>
    <td width="20%" align="left" valign="top" class="page-tile tr-color"><strong>Email</strong></td>
    <td width="80%" align="left" valign="top"  class="tr-color"<div class="get_started_cont_form">
		<input type="text" class="input-type"  name="emailaddress" value="Email Address" onblur="if(this.value=='')this.value='Email Address'" onfocus="if(this.value=='Email Address')this.value=''" />
		</div></td>
  </tr>
  
  
  
   <tr>
    <td width="20%" align="left" valign="top" class="page-tile tr-color"><strong>Contact No</strong></td>
    <td width="80%" align="left" valign="top"  class="tr-color"><input name="contact" type="text" class="input-type"/></td>
  </tr>
  
  
  
   <tr>
    <td width="20%" align="left" valign="top" class="page-tile tr-color"><strong>Company Name</strong></td>
    <td width="80%" align="left" valign="top"  class="tr-color"><input name="company_name" type="text"  class="input-type"/></td>
  </tr>
  
  
   <tr>
    <td width="20%" align="left" valign="top" class="page-tile tr-color"><strong>Brand Name</strong></td>
    <td width="80%" align="left" valign="top"  class="tr-color"><input name="brandname" type="text"  class="input-type"/></td>
  </tr>
  
  <tr>
    <td align="left" valign="top" class="tr-color page-tile"><strong>Status</strong></td>
    <td align="left" valign="top" class="tr-color">
	<select name="status" style="border:1px solid #C3D5EA; margin-left:10px;">
		<option value="1">Active</option>
		<option value="0">Inactive</option>
	</select>
	</td>
  </tr>
 
  
  
 
  
   <tr>
    <td align="left" valign="middle" class="page-tile">&nbsp;</td>
    <td align="left" valign="middle">
    <input type="submit" name="update" value="Add New Dealer" class="inputBtn" />
    </td>
   </tr>
</table>
</form>
</td>
                    </tr>
                    
                  </table></td>
                  <td width="22" valign="top" class="right_top_r_bg"><img src="images/right_top_r.gif" alt="" width="22" height="236" /></td>
                </tr>
                <tr>
                  <td height="42" valign="top"><img src="images/right_bot_l.gif" alt="" width="22" height="42" /></td>
                  <td valign="top" class="right_bot_bot_bg">&nbsp;</td>
                  <td valign="top"><img src="images/right_bot_r.gif" alt="" width="22" height="43" /></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              </table>
<?php
require('includes/footer.php');
?>
</body>
</html>
