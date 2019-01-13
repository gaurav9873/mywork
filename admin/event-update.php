<?php
require('includes/application_top.php');
if(isset($_REQUEST['update'])){

	if($_FILES['image']['name']!=''){
		$uploaddir = '../images/events/' . $_FILES['image']['name'];
		move_uploaded_file($_FILES['image']['tmp_name'], $uploaddir);
		$small_image_str = ", image = '". $_FILES['image']['name'] ."'";
	}
	
  $sql = "/* Update events */
   INSERT INTO
     events
   SET
			`id`    				= '". $_REQUEST['id'] ."'
		, `date`  				= '". $_REQUEST['date'] ."'
		, `title`  				= '". $_REQUEST['title'] ."'
		, `descriptions`	= '". $_REQUEST['descriptions'] ."'
		". $small_image_str ."
       ON DUPLICATE KEY UPDATE
	        `date`  				= '". $_REQUEST['date'] ."'
			,`title`  				= '". $_REQUEST['title'] ."'
			, `descriptions`	= '". $_REQUEST['descriptions'] ."'
		    ". $small_image_str ."
     ;";
	if(tep_db_query($sql)){
		if($_REQUEST['id'] == ''){
			$_REQUEST['id'] = tep_db_insert_id();
		}
		$msg = '1';
	}else{
		$msg = '0';
	}
	header("location:event-update.php?id=".$_REQUEST['id']."&msg=" . $msg);
	exit;

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>kwickut</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="datePicker.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="ddaccordion.js"></script>
<script type="text/javascript" src="./js/date.js"></script>
<script type="text/javascript" src="./js/jquery.datePicker.js"></script>
<script type="text/javascript">
$(document).ready(function (){
 Date.firstDayOfWeek = 1;
 Date.format = 'yyyy-mm-dd';
 $('.date_pick').datePicker();
 $('.date_pick').datePicker({startDate:'1996-01-01'});
 $('.date_pick').attr("readonly", "true");
});
</script>
</head>
<body>
<?php
require('includes/header.php');
$sql = "SELECT * FROM events WHERE `id` = '".$_REQUEST['id'] ."'";
	$req = tep_db_query($sql);
	$res = tep_db_fetch_array($req);
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
                          <td align="left" valign="middle" class="middle_bg_blu"><h4>Events</h4></td>
                          <td width="31" align="right" valign="top"><img src="images/right_rouud_blu.jpg" alt="" width="31" height="35" /></td>
                        </tr>
                      </table></td>
                    </tr>
                     <tr>
                      <td height="12"></td>
                    </tr>
                    <tr>
                      <td align="center" valign="top">
                      
                       <form name="events" action="event-update.php" method="post" enctype="multipart/form-data">
											<input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>" />
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                       
  <tr>
   <td align="left" valign="top" class="page-tile"><strong>image</strong></td>
     <td align="left" valign="top" class="logo-padding"><input name="image" type="file" />
     <?php
if($res['image'] != '') echo "<br />" . $res['image'];
	?>
     </td>
  </tr>
 
  <tr>
    <td width="20%" align="left" valign="top" class="page-tile  tr-color"><strong>date and time</strong></td>
    <td width="80%" align="left" valign="top" class="tr-color"><input type="text" name="date" class="input-type date_pick" value="<?php echo $res['date']; ?>"/><!--<input type="text"  class="input-type" value="Date"/>
    &nbsp;<input type="text"  class="input-type" value="Time"/>--></td>
  </tr>
  <tr>
    <td width="20%" align="left" valign="top" class="page-tile"><strong>title</strong></td>
    <td width="80%" align="left" valign="top"><input name="title" type="text" value="<?php echo $res['title']; ?>" class="input-type"/></td>
  </tr>
  <tr>
   <td align="left" valign="top" class="page-tile tr-color"><strong>Description </strong></td>
   <td align="left" valign="top" class="tr-color"><textarea name="descriptions" cols="" rows="" class="textarea3"><?php echo $res['descriptions']; ?></textarea></td>
  </tr>
  
 
  
   <tr>
    <td align="left" valign="middle" class="page-tile">&nbsp;</td>
    <td align="left" valign="middle">
    	<input type="submit" name="update" value="Update" class="inputBtn" />
        <input type="button" value="Back" class="inputBtn" onclick="document.location.href='event.php';" />
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
