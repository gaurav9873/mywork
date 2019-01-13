<?php
require('includes/application_top.php');
if($_REQUEST['action']=="delete")
{
    $uploaddir = '../images/map/';
	  $id=$_REQUEST['id'];
	mysql_query("update map set image='' where id='".$_REQUEST['id']."'"); 
	echo "<script> window.location.href='map_update.php?msg=deleted&id='+'".$id."';</script>";
	exit;
}
if(isset($_REQUEST['update'])){

	if($_FILES['image']['name']!=''){
		$uploaddir = '../images/map/' . $_FILES['image']['name'];
		move_uploaded_file($_FILES['image']['tmp_name'], $uploaddir);
		$small_image_str = ", image = '". $_FILES['image']['name'] ."'";
	}
	
  $sql = "/* Update map */
   INSERT INTO
     map
   SET
			`id`    				= '". $_REQUEST['id'] ."'
		, `title`  				= '". $_REQUEST['title'] ."'
		, `descriptions`	= '". $_REQUEST['descriptions'] ."'
		". $small_image_str ."
      ON DUPLICATE KEY UPDATE
			`title`  				= '". $_REQUEST['title'] ."'
			, `descriptions`	= '". $_REQUEST['descriptions'] ."'
		    ". $small_image_str ."
          ;";
	if(tep_db_query($sql)){
		if($_REQUEST['id'] == ''){
			$_REQUEST['id'] = tep_db_insert_id();
		}
		$msg = 'Successfully Saved';
	}else{
		$msg = 'Not Saved';
	}
	/*header("location:services_update.php?id=".$_REQUEST['id']."&msg=" . $msg);
	exit; */

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dr. Robert Simons</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">

function fn_del(id)
{
  var conf;
  if(confirm("Are you sure to delete this ?"))
  {
	  window.location.href='map_update.php?action=delete&id='+id;
  }
}

</script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="ddaccordion.js"></script>
<!-- TinyMCE -->
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		
		
		/*theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "template,pagebreak",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
*/
		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>

</head>
<body>
<?php
require('includes/header.php');
$sql = "SELECT * FROM map WHERE `id` = '".$_REQUEST['id'] ."'";
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
                          <td align="left" valign="middle" class="middle_bg_blu"><h4>Map</h4></td>
                          <td width="31" align="right" valign="top"><img src="images/right_rouud_blu.jpg" alt="" width="31" height="35" /></td>
                        </tr>
                      </table></td>
                    </tr>
                      <tr>
                      <td height="12" align="center" ><font color="#00CC00"> <? echo $msg ;?></font></td>
                    </tr>
                    <tr>
                      <td align="center" valign="top">
                      <form name="software" action="map_update.php" method="post" enctype="multipart/form-data">
											<input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>" />
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                       
  <tr>
   <td align="left" valign="top" class="page-tile"><strong>image</strong></td>
     <td align="left" valign="top" class="logo-padding"><input name="image" type="file" />
       <?php
if($res['image'] != '') echo "<br />" . $res['image'];
	?></td>
  </tr>
 
  <tr>
    <td width="20%" align="left" valign="top" class="page-tile tr-color"><strong>title</strong></td>
    <td width="80%" align="left" valign="top"  class="tr-color"><input name="title" type="text" value="<?php echo $res['title']; ?>" class="input-type"/></td>
  </tr>
  <tr>
   <td align="left" valign="top" class="page-tile tr-color"><strong>Map Path </strong></td>
   <td align="left" valign="top" class="tr-color"><textarea name="descriptions" cols="" rows="" class="textarea3"><?php echo $res['descriptions']; ?></textarea></td>
  </tr>
  
 
  
   <tr>
    <td align="left" valign="middle" class="page-tile">&nbsp;</td>
    <td align="left" valign="middle">
    <input type="submit" name="update" value="Update" class="inputBtn" />
    <input type="button" value="Back" class="inputBtn" onclick="document.location.href='map.php';" />    </td>
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
