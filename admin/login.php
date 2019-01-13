<?php 
require('../includes/conn.php');
if (isset($_POST['adminloginok']))
 {
     $username = $_POST["username"];
     $password=  $_POST["password"];

// Check if email exists
   
    if($username!="" &&  $password!="")
		{
			  $sql="select admin_id , username, password from admin where username='".mysql_escape_string($username)."' and password='".mysql_escape_string($password)."'";
				#$sql="select admin_id , username, password from admin where username=='$username' and password=='$password'";
				    $check_admin_query = mysql_query($sql) or die(mysql_error());
					$num_rows=mysql_num_rows($check_admin_query);
					$fetch_rows=@mysql_fetch_array($check_admin_query);
					$fetch_rows['admin_id'];
				if($num_rows > 0)
				{ 
						if($username==$fetch_rows['username'])
						{	
							if ($_GET['url']!=''){
								header ("location: $url");
								}
								else{
								$_SESSION['admin_id']=$fetch_rows['admin_id'];
								header("location:index.php");
								}
						 }
					
				 }else{
					 $error_msg="t";
				 }
	  
	  
		}else{ 
		 $msg_blank="t";
		 }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin Area</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
    <td class="heder_bg"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="68" align="left" valign="middle" class="header"><div class="logo-padding"><a href="index.html"><img src="images/caroye_logo.png" alt="Caroye" border="0" /></a></div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="31" align="right" valign="top" class="menu_bg">&nbsp;</td>
  </tr>
 <tr>
    <td class="body_bg"><p>&nbsp;</p>
<table width="400" border="0" align="center" cellpadding="0" cellspacing="0" class="login_bg">
        <tr>
          <td height="347" align="left" valign="top"> 
          <form name="login" action="" method="post">
          <input id=adminloginok type=hidden value=Login name=adminloginok>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
         
            <tr>
              <td height="83" align="left" valign="middle" ><span class="login">Login</span></td>
            </tr>
            <tr>
              <td height="47" align="left" valign="bottom"><p class="text_lable">User Name</p></td>
            </tr>
            <tr>
              <td height="30" align="left"><label>
                <input name="username" type="text" class="input_box" id="username" />
              </label></td>
            </tr>
            <tr>
              <td height="35" align="left" valign="bottom"><p class="text_lable">Password</p></td>
            </tr>
            <tr>
              <td height="33" align="left" valign="top"><label>
              <input name="password" type="password" class="input_box" id="password" />
              </label></td>
            </tr>
            <tr>
              <td height="33" align="left" valign="middle"><label class="text_lable3">
                <input type="checkbox" name="checkbox" id="checkbox" />
              Remember me</label></td>
            </tr>
            <tr>
              <td height="25" align="left" valign="bottom"><table width="100%" style="margin-left:30px;" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="text2">&nbsp;</td>
                  <td><label>
                 
                    <input type="submit" value="Login" name="submit" class="inputBtn" />
                  </label></td>
                </tr>
              </table></td>
            </tr>
             <? //if($error_msg){?><tr ><td colSpan=3>
		<font color="" style="padding-left:30px; color:#c00; font-size:12px; font-family:Arial, Helvetica, sans-serif;">Invalid username or password</font></td></tr><? #}?>
		<? #if($msg_blank){?><tr ><td colSpan=3>
		<font  style="padding-left:30px; color:#c00; font-size:12px; font-family:Arial, Helvetica, sans-serif;">Please Enter username and password</font></td></tr><?  # }?>
          </table>
          </form>
          </td>
        </tr>
      </table>
		     
    <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td height="60" align="center" class="footer_bg"><p>&copy; 2010 Caroye.com All Rights Reserved.</p></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>