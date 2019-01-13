<?php
	require('includes/application_top.php');
	 
	 if($_SESSION['admin_id']==""){
		header("location:login.php");
	}

	if($_POST['task']=="update"){
			
			$userid = $_POST['userid'] ;
			
			$dname = $_POST['dname'] ;
			$add = $_POST['add'] ;
			$email = $_POST['email'] ;
			$contact = $_POST['contact'] ;
			$company_name = $_POST['company_name'] ;
			$brandname = $_POST['brandname'] ;
			$status = $_POST['status'] ;
			
			$details = $_POST['details'];
			
			
		echo	$sqlupate  = "update dealer set name='$dname',address='$add',email='$email',contactno='$contact',d_company='$company_name',d_brand='$brandname',status='$status' where id='$userid'"; 
			mysql_query($sqlupate);
			
	header("location:dealer.php?msg=Dealer Has Been Edited Successully");
	exit;
		}
	 
	$sql = "SELECT * FROM dealer WHERE `id` = '".$_REQUEST['id'] ."'";
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
                          <td align="left" valign="middle" class="middle_bg_blu"><h4>Edit Dealer </h4></td>
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
   <td align="left" valign="top" class="page-tile"><strong>name</strong></td>
     <td align="left" valign="top" class="tr-color"> 
       <input name="dname" type="text" value="<?php echo $res['name']; ?>" class="input-type"/></td>
  </tr>
 
  <tr>
    <td align="left" valign="top" class="page-tile tr-color"><strong>add</strong></td>
    <td align="left" valign="top"  class="tr-color"><input name="add" type="text" value="<?php echo $res['address']; ?>" class="input-type"/></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="page-tile tr-color"><strong>email</strong></td>
    <td align="left" valign="top"  class="tr-color"><input name="email" type="text" value="<?php echo $res['email']; ?>" class="input-type"/></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="page-tile tr-color"><strong>contactno</strong></td>
    <td align="left" valign="top"  class="tr-color"><input name="contact" type="text" value="<?php echo $res['contactno']; ?>" class="input-type"/></td>
  </tr>
  <tr>
    <td width="20%" align="left" valign="top" class="page-tile tr-color"><strong>company_Name</strong></td>
    <td width="80%" align="left" valign="top"  class="tr-color"><input name="company_name" type="text" value="<?php echo $res['d_company']; ?>" class="input-type" readonly=""/></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="page-tile tr-color"><strong>brand_Name</strong></td>
    <td align="left" valign="top" class="tr-color"><input name="brandname" type="text" value="<?php echo $res['d_brand']; ?>" class="input-type"/></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="tr-color page-tile"><strong>Status</strong></td>
    <td align="left" valign="top" class="tr-color">
	<select name="status" style="border:1px solid #C3D5EA; margin-left:10px;">
		<option value="1" <?php if($res['status']==1) { echo "selected"; } ?>>Active</option>
		<option value="0" <?php if($res['status']==0) { echo "selected"; } ?>>Inactive</option>
	</select>	</td>
  </tr>
  
  
 
  
   <tr>
    <td align="left" valign="middle" class="page-tile">&nbsp;</td>
    <td align="left" valign="middle">
    <input type="submit" name="update" value="Update Dealer" class="inputBtn" />    </td>
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
