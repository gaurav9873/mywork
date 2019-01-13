<?php
	require('includes/application_top.php');
	 
	 if($_SESSION['admin_id']==""){
		header("location:login.php");
	}

	if($_POST['task']=="update"){
			
			$userid = $_POST['userid'] ;
			
			$fullname = $_POST['fullname'] ;
			$status = $_POST['status'] ;
			$usertype = $_POST['usertype'] ;
			$title = $_POST['title'] ;
			
			$sqlupate  = "update users set fullname='".$fullname."', status='".$status."' , user_type='".$usertype."', status='".$status."' , yourtitle='".$title."' where id ='".$userid."' "; 
			mysql_query($sqlupate);
			
			header("location:editusers.php?id=$userid");
			exit;
	
	}
	 
	$sql = "SELECT * FROM users WHERE `id` = '".$_REQUEST['id'] ."'";
	$req = tep_db_query($sql);
	$res = tep_db_fetch_array($req);
 
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Caroye.com :: Admin</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php require('includes/header.php'); ?>
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
                          <td align="left" valign="middle" class="middle_bg_blu"><h4>Edit User</h4></td>
                          <td width="31" align="right" valign="top"><img src="images/right_rouud_blu.jpg" alt="" width="31" height="35" /></td>
                        </tr>
                      </table></td>
                    </tr>
                      <tr>
                      <td height="12" align="center" ><font color="#00CC00"> <? //echo $msg ;?></font></td>
                    </tr>
                    <tr>
                      <td align="center" valign="top">
                      <form name="adduser"  method="post" >
					  <input type="hidden" name="userid" value="<?php echo $res['id']; ?>" />
					  <input type="hidden" name="task" value="update" />					
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                       
  <tr>
   <td align="left" valign="top" class="page-tile"><strong>Fullname</strong></td>
     <td align="left" valign="top" class="tr-color"> 
       <input name="fullname" type="text" value="<?php echo $res['fullname']; ?>" class="input-type"/>
      </td>
  </tr>
 
  <tr>
    <td width="20%" align="left" valign="top" class="page-tile tr-color"><strong>Email</strong></td>
    <td width="80%" align="left" valign="top"  class="tr-color"><input name="email" type="text" value="<?php echo $res['email']; ?>" class="input-type" readonly=""/></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="tr-color page-tile"><strong>Status</strong></td>
    <td align="left" valign="top" class="tr-color">
	<select name="status" style="border:1px solid #C3D5EA; margin-left:10px;">
		<option value="1" <?php if($res['status']==1) { echo "selected"; } ?>>Active</option>
		<option value="0" <?php if($res['status']==0) { echo "selected"; } ?>>Inactive</option>
	</select>
	</td>
  </tr>
  <tr>
    <td align="left" valign="top" class="page-tile tr-color"><strong>User Type </strong></td>
    <td align="left" valign="top" class="tr-color">
	<select name="usertype" style="border:1px solid #C3D5EA; margin-left:10px;">
		<option value="1" <?php if($res['user_type']==1) { echo "selected"; } ?>>Buyer</option>
		<option value="2" <?php if($res['user_type']==2) { echo "selected"; } ?>>Dealer</option>
	</select></td>
  </tr>
  <tr>
   <td align="left" valign="top" class="page-tile tr-color"><strong>Title </strong></td>
   <td align="left" valign="top" class="tr-color"><input type="text" name="title" value="<?php echo $res['yourtitle'] ;?>"  class="input-type"/></td>
  </tr>
  
 
  
   <tr>
    <td align="left" valign="middle" class="page-tile">&nbsp;</td>
    <td align="left" valign="middle">
    <input type="submit" name="update" value="Update User" class="inputBtn" />
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
