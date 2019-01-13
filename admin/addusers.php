<?php
require('includes/application_top.php');

if($_SESSION['admin_id']==""){
		header("location:login.php");
}

	if($_POST['task']=="adduser"){
			
			$err = false ;
			$_SESSION['regmsg'] = ""; 
			
			$userid = $_POST['userid'] ;
			
			$fullname = $_POST['fullname'] ;
			$email = $_POST['email'] ;
			$status = $_POST['status'] ;
			$usertype = $_POST['usertype'] ;
			$title = $_POST['title'] ;
			
			
		
			if($fullname==""){
				$err = true ;
				$_SESSION['regmsg'] .= "enter fullname.<br />";
			}
			if($email==""){
				$err = true ;
				$_SESSION['regmsg'] .= "enter email.<br />";
			}
			
			$sqlres =  mysql_query("select email from  users where email='".$email."'  and  user_type='".$usertype."' ") ;
			if(mysql_num_rows($sqlres) > 0){
				$err = true ;
				$_SESSION['regmsg'] .= "email address already exist.";
			 
			}
			if($err){
				//header("location:addusers.php");
			} else {
			 	$sqlq  = "insert into users set fullname='".$fullname."', email='".$email."', status='".$status."' , user_type='".$usertype."', yourtitle='".$title."'"; 
				mysql_query($sqlq);
				$_SESSION['regmsg'] = "new user added successfully.";
				header("location:addusers.php");
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
                          <td align="left" valign="middle" class="middle_bg_blu"><h4>Add New User</h4></td>
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
                      <form name="adduser"  method="post" onsubmit="chkreg();" >
						 <input type="hidden" name="task" value="adduser" />						
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                       
  <tr>
   <td align="left" valign="top" class="page-tile"><strong>Fullname</strong></td>
     <td align="left" valign="top" class="tr-color"> 
       <input name="fullname" type="text" value="<?php $_POST['fullname']; ?>" class="input-type"/>
      </td>
  </tr>
 
  <tr>
    <td width="20%" align="left" valign="top" class="page-tile tr-color"><strong>Email</strong></td>
    <td width="80%" align="left" valign="top"  class="tr-color"><input name="email" type="text" value="<?php $_POST['email']; ?>" class="input-type"/></td>
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
    <td align="left" valign="top" class="page-tile tr-color"><strong>User Type </strong></td>
    <td align="left" valign="top" class="tr-color">
	<select name="usertype" style="border:1px solid #C3D5EA; margin-left:10px;">
		<option value="1">Buyer</option>
		<option value="2">Dealer</option>
	</select></td>
  </tr>
  <tr>
   <td align="left" valign="top" class="page-tile tr-color"><strong>Title </strong></td>
   <td align="left" valign="top" class="tr-color"><input type="text" name="title"  class="input-type"/></td>
  </tr>
  
 
  
   <tr>
    <td align="left" valign="middle" class="page-tile">&nbsp;</td>
    <td align="left" valign="middle">
    <input type="submit" name="update" value="Add New User" class="inputBtn" />
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
