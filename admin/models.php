<?php
require('includes/application_top.php');

if($_SESSION['admin_id']==""){
		header("location:login.php");
}

	
	
	
if(isset($_REQUEST['action'])&& $_REQUEST["action"]=="delete" && $_REQUEST['id']!=""){
  
  	$id = $_REQUEST['id'];
  	mysql_query("delete from  makes_model where id='".$id."'");
	
	
	
	header("location:models.php?msg=Data Has Been Deleted Successully");
	exit;
 
 }
 
 /*if(isset($_REQUEST['action']) && $_REQUEST["action"]=="active" && $_REQUEST['id']!=""){
  
  	$id = $_REQUEST['id'];
	$updatestatus = 1 ;
  	mysql_query("update users set status='".$updatestatus."' where id='".$id."'");
	
	
	
	header("location:brand.php?msg=Brand Has Been Deleted Successully");
	exit;
 
 }
 
  if(isset($_REQUEST['action']) && $_REQUEST["action"]=="inactive" && $_REQUEST['id']!=""){
  
  	$id = $_REQUEST['id'];
	$updatestatus =0 ;
  	mysql_query("update users set status='".$updatestatus."' where id='".$id."'");
	
	$_SESSION['usrmsg'] = "user deleted successully";
	
	header("location:users.php");
	exit;
 
 }*/
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Caroye.com :: Admin</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="ddaccordion.js"></script>
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
                          <td align="left" valign="middle" class="middle_bg_blu"><h4> Model Management</h4></td>
                          <td width="31" align="right" valign="top"><img src="images/right_rouud_blu.jpg" alt="" width="31" height="35" /></td>
                        </tr>
                      </table></td>
                    </tr>
                     <tr>
                      <td height="12"></td>
                    </tr>
                     <tr>
                      <td height="12" align="right"><?php if($_REQUEST[msg]!='') { ?><div align="center"><?php echo $_REQUEST[msg];?></div><?php } ?><input type="button" value="Add" class="inputBtn2" onclick="window.location='add_models.php'" /></td>
                    </tr>
                    <tr>
                      <td align="center" valign="top"><table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
						<td height="30" class="border_row_left"><strong>Sr.No</strong></td>
                          <td height="30" class="border_row_left"><strong>Year</strong></td>
                          <td height="30" class="border_row_left"><strong>Manufacture Name</strong></td>
						  <td height="30" class="border_row_left"><strong>Model name</strong></td>
						  
                          <td height="30" class="border_row_right"><strong>Edit / Delete</strong></td>
                        </tr>
                        <?php
							$sql = "SELECT * FROM makes_model where parent_id!='0' order by id desc ";
							$req = tep_db_query($sql);
							$sn=1;
							while($res = tep_db_fetch_array($req))
							{
							
							$ustatus  = $res["status"];
							$usertype = $res["user_type"];
	
						?>
                        <tr>
						 <td height="30" class="border_row_left"><?php echo $sn; ?></td>
                          <td height="30" class="border_row_left"><?php echo $res["year"]; ?></td>
						  <td height="30" class="border_row_left"><?php $pid=$res["parent_id"]; $selp_name=mysql_query("select make_model_text from makes_model where id='$pid'");$row_pname=mysql_fetch_array($selp_name); echo $row_pname['make_model_text']; ?></td>
						  <td height="30" class="border_row_left"><?php echo $res["make_model_text"]; ?></td>
						  
                          <td height="30" class="border_row_right"><a href="edit_models.php?id=<?php echo $res["id"]; ?>">Edit </a>/ <a href="?action=delete&id=<?php echo $res["id"]; ?>" onclick="return confirm('Are you sure want to delete this users');">Delete</a></td>
                        </tr>
<?php
	$sn++ ;}
?>   
                      </table>
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
