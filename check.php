<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<!--<link rel="stylesheet" href="css/style.css" type="text/css" />-->
<script type="text/javascript" src="js/jquery.js"></script>
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
<table cellpadding="0" cellspacing="0" border="0" align="center" width="50%">
<tr><td>sfsf</td></tr>
<tr><td>
<tr><td><form method="post" action=""  onsubmit="return chkreg();">
	<input type="hidden" name="task" value="signup" />

		<div class="get_started_cont">
		<div class="get_started_cont_text">
		<h1>Get started here!</h1>
		</div>
		
		<div id="errormsg" style="color:#FF0000; font-size:11px; line-height:20px; display:none;"></div>
		<?php if(isset($_SESSION['errmsg']) && $_SESSION['errmsg']!="") { ?>
		<div id="errormsg" style="color:#FF0000; font-size:11px; line-height:20px;"><?php echo $_SESSION['errmsg'] ; echo $_SESSION['errmsg']="";?></div>
		<?php } ?>
		<div class="get_started_cont_form">
		<input type="text" class="sing_up_form" name="fullname" value="Your Name" onblur="if(this.value=='')this.value='Your Name'" onfocus="if(this.value=='Your Name')this.value=''" />
		</div>
		<div class="get_started_cont_form">
		<input type="text" class="sing_up_form"  name="emailaddress" value="Email Address" onblur="if(this.value=='')this.value='Email Address'" onfocus="if(this.value=='Email Address')this.value=''" />
		</div>
		<div class="get_started_cont_form">
		<input type="password" class="sing_up_form" name="cpassword" value="Password" onblur="if(this.value=='')this.value='Password'" onfocus="if(this.value=='Password')this.value=''"/>
		</div>
		<div class="get_started_cont_form">
		<input type="password"  name="confpassword" class="sing_up_form" value="Password" onblur="if(this.value=='')this.value='Password'" onfocus="if(this.value=='Password')this.value=''" />
		</div>
		<div><input type="submit" value="Sign up now" /></div>
		<div class="by_signing">
		
		
		</div>
		
		</div>
		 </form></td></tr>
</table>
</body>
</html>
