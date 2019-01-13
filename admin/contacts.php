<?php
require('includes/application_top.php');
$id1=$_GET['id'];
if(isset($_REQUEST['action'])&& $_REQUEST["action"]=="delete"){
  $deletequery=mysql_query("delete from  contacts where id='$id1'") ;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dr. Robert Simons :: Admin</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="ddaccordion.js"></script>
<script type="text/javascript">

<!--
function fn_del(id)
{
  var conf;
  if(confirm("Are you sure to delete this ?"))
  {
	  window.location.href='contacts.php?action=delete&id='+id;
  }
}
//-->
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
                          <td align="left" valign="middle" class="middle_bg_blu"><h4>Contacts</h4></td>
                          <td width="31" align="right" valign="top"><img src="images/right_rouud_blu.jpg" alt="" width="31" height="35" /></td>
                        </tr>
                      </table></td>
                    </tr>
                     <tr>
                      <td height="12"></td>
                    </tr>
                    <!-- <tr>
                      <td height="12" align="right"><input type="button" value="Add" class="inputBtn2" onclick="window.location ='contacts_update.php'" /></td>
                    </tr>-->
                    <tr>
                      <td align="center" valign="top"><table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="35%" height="30" class="border_row_left"><strong>Phone </strong></td>
                          <td width="30%" height="30" class="border_row_left"><strong>Email </strong></td>
                          <td width="23%" height="30" class="border_row_left"><strong>Delete </strong></td>
                          <td width="35%" height="30" class="border_row_right"><strong> Edit </strong></td>
                        </tr>
                        
                       <?php
	$sql = "SELECT * FROM contacts";
	$req = tep_db_query($sql);
	while($res = tep_db_fetch_array($req)){
?>
                        <tr>
                          <td height="30" class="border_row_left"><?php echo $res['phone']; ?></td>                          
                          <td height="30" class="border_row_left"><?php echo $res['email']; ?></td>
                          <td height="30" class="border_row_left"><span><img src="images/delet-b.png" alt="" width="56" height="19" border="0" onclick="fn_del('<?php echo $res['id']; ?>')" /></span></td>
                          <td height="30" class="border_row_right"><a href="contacts_update.php?id=<?php echo $res['id']; ?>"><span><img src="images/edit_img.jpg" alt="" width="56" height="19" border="0" /></span></a></td>
                        </tr>
<?php
	}
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
