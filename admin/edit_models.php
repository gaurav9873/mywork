<?php
	require('includes/application_top.php');
	 
	 if($_SESSION['admin_id']==""){
		header("location:login.php");
	}

	if($_POST['task']=="update"){
			
			$userid = $_POST['userid'] ;
			
			$year = $_POST['year'];
			$manu_name = $_POST['manu_name'] ;
			$model_name=$_POST['model_name'];
			
									
		$sqlupate  = "update makes_model set year='$year',make_model_text='$model_name',parent_id='$manu_name' where id='$userid'"; 
			mysql_query($sqlupate);
			
	header("location:models.php?msg=Data Has Been Edited Successully");
	exit;
		}
	 
	$sql = "SELECT * FROM makes_model WHERE `id` = '".$_REQUEST['id'] ."'";
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
                          <td align="left" valign="middle" class="middle_bg_blu"><h4>Edit Model </h4></td>
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
   <td width="20%" align="left" valign="top" class="page-tile"><strong>Year</strong></td>
     <td width="80%" align="left" valign="top" class="tr-color">
       <select name="year" class="input-type">
	   <option value="<?php echo $res['year']; ?>"><?php $myear=$res['year']; echo $myear; ?></option>
	   <?php 
	   $selyear=mysql_query("select * from year  where year!='$myear' order by year desc");
	   while($rowyear=mysql_fetch_array($selyear))
	   {	   
	   ?>
	    <option value="<?php echo $rowyear['year']; ?>"><?php echo $rowyear['year']; ?></option>
		<?php } ?>
       </select>     </td>
  </tr>
 
  <tr>
    <td align="left" valign="top" class="tr-color page-tile"><strong>Manufacture Name </strong></td>
    <td align="left" valign="top"  class="tr-color"><select name="manu_name" class="input-type">
	<?php $pid=$res["parent_id"]; $selp_name=mysql_query("select * from makes_model where id='$pid'");$row_pname=mysql_fetch_array($selp_name); echo $row_pname['make_model_text']; ?>
	
	   <option value="<?php echo $res['parent_id']; ?>"><?php echo $row_pname['make_model_text'] ?></option>
	   <option value="">------------</option>
	   <?php 
	   $selmanu=mysql_query("select * from makes_model  where parent_id='0' order by id desc");
	   while($rowmanu=mysql_fetch_array($selmanu))
	   {	   
	   ?>
	    <option value="<?php echo $rowmanu['id']; ?>"><?php echo $rowmanu['make_model_text']; ?></option>
		<?php } ?>
	   
       </select></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="page-tile tr-color"><strong>Name</strong></td>
    <td align="left" valign="top"  class="tr-color"><input name="model_name" type="text" value="<?php echo $res['make_model_text']; ?>" class="input-type"/></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="tr-color page-tile">&nbsp;</td>
    <td align="left" valign="top" class="tr-color">&nbsp;</td>
  </tr>
  
  
 
  
   <tr>
    <td align="left" valign="middle" class="page-tile">&nbsp;</td>
    <td align="left" valign="middle">
    <input type="submit" name="update" value="Update Model" class="inputBtn" />    </td>
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
