<? 
require('includes/application_top.php');
checklogin();
require("fckeditor/fckeditor.php");

function tep_draw_fckeditor($name, $width, $height, $text) {

	$oFCKeditor = new FCKeditor($name);
	$oFCKeditor -> Width  = $width;
	$oFCKeditor -> Height = $height;
	$oFCKeditor -> BasePath	= 'fckeditor/';	
	$oFCKeditor -> Value = $text;

    $field = $oFCKeditor->Create($name);
    return $field;
}
//$page_id=$_REQUEST['page_id'];
if(isset($_POST['submit'])){
  //print_r($_POST);
  	//$update_query = "update website_pages set page_body = '". $_POST['page_body'] ."' where page_id = " . $_POST['page_id'] . "";
	$update_query = "update website_pages set page_title = '". $_POST['page_title'] ."', page_meta_keyword = '". $_POST['page_meta_keyword'] ."', page_body = '".$_POST['page_body'] ."' where page_id = " . $_POST['page_id'] . "";
 	mysql_query($update_query) or die(mysql_error());
	$success_message = "Successfully Saved";
  }
  $select_website_pages_query = "select * from website_pages ";
  $select_website_pages_rec = tep_db_query($select_website_pages_query);
  $select_page_fld = '<select name="page_id" onChange="this.form.submit();">'."\n";
  while($select_website_pages_result = tep_db_fetch_array($select_website_pages_rec)){
  	if(isset($_POST['page_id'])){
		if($_POST['page_id'] == $select_website_pages_result['page_id']){
  			$select_page_fld .= '<option value="'. $select_website_pages_result['page_id'] .'" selected>'. $select_website_pages_result['page_title'] .'</option>'."\n";
		}else{
			$select_page_fld .= '<option value="'. $select_website_pages_result['page_id'] .'">'. $select_website_pages_result['page_title'] .'</option>'."\n";
		}
	}else{
  		$select_page_fld .= '<option value="'. $select_website_pages_result['page_id'] .'">'. $select_website_pages_result['page_title'] .'</option>'."\n";
	}
  }
  $select_page_fld .= '</select>';
  
  if(isset($_POST['page_id'])){
  	$page_page_body_query = "select * from website_pages where page_id = '" . $_POST['page_id'] . "'";
  }else{
  	$page_page_body_query = "select * from website_pages where page_id = '16'";
  }
  $page_page_body_query_rec = tep_db_query($page_page_body_query);
  $page_page_body_query_result = tep_db_fetch_array($page_page_body_query_rec);
  ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE>Admin Area</TITLE>
<META http-equiv=Content-Type content="text/html; charset=iso-8859-1">
<LINK href="images/style.css" type=text/css rel=stylesheet>
<SCRIPT src="images/main.js" type="text/javascript"></SCRIPT>
<SCRIPT language="JavaScript" src="images/innovaeditor.js"></SCRIPT>
<script language="JavaScript" type="text/javascript" src="wysiwyg/wysiwyg.js">
</script>
<META content="MSHTML 6.00.2900.2180" name=GENERATOR></HEAD>
<BODY vLink=#003399 leftMargin=0 topMargin=0>
<!-- header start -->
<? include("includes/header.php")?>	
<!-- header end -->	
<table width="90%" style="border-collapse:collapse" border="1" bordercolor="#d7d7d7" cellspacing="0" cellpadding="5">
	<tr>
		<td bgcolor="#d7d7d7" class="smalltext"  align="">
			<form action="<? echo $PHP_SELF; ?>" method="post">
			<b>Select the page:&nbsp;&nbsp;</b><? echo $select_page_fld; ?>&nbsp;&nbsp;
			<input type="Button" name="btnadd" value="Add Page" class="buttn" onClick="document.location.href='add_pages.php'">			&nbsp;&nbsp;<!--<input type="Button" name="btnedit" value="Edit Page" class="buttn" onClick="document.location.href='edit_pages.php?page_id=<?=$page_page_body_query_result['page_id'];?>'">-->
			
			<input type="hidden" name="osCAdminID" value="<?=$GLOBALS['osCAdminID']?>">
			</form>
		</td>
	</tr>
	<tr>
		<td class="smalltext">
		<?
			if(isset($success_message)){
				echo '<center><b><font color="#008000">'. $success_message .'</font></b></center>';
			}
		?>
		 <form action="<? echo $PHP_SELF; ?>" method="post">
			<table border="0" width="100%">
			  
			  <tr>
				<td width="20%" valign="top" align="right" class="smalltext"><b>Page Title:</b>&nbsp;&nbsp;</td>
				<td width="80%"><input type="Text" name="page_title" value="<?=$page_page_body_query_result['page_title']?>"></td>
			  </tr>
			  <tr>
				<td width="20%" valign="top" align="right" class="smalltext"><b>Page Meta Keyword:</b>&nbsp;&nbsp;</td>
				<td width="80%"><textarea rows="4" name="page_meta_keyword" cols="40"><?=$page_page_body_query_result['page_meta_keyword']?></textarea></td>
			  </tr>
				<tr>
				<td width="20%" valign="top" align="right" class="smalltext"><b>Page Body:</b>&nbsp;&nbsp;</td>
				<td width="80%">
<?php
	$name="page_body";//like name of text field
	$width=550;
	$height=400;
	$text=$page_page_body_query_result['page_body'];//like value of text field
	tep_draw_fckeditor($name, $width, $height, $text);
?>
	
				
				
				</TD>
				
			 </table>
			<?
			if(isset($_POST['page_id'])){
			?>
				<input type="Hidden" name="page_id" value="<? echo $_POST['page_id']; ?>">
			<?
			}else{
			?>
				<input type="Hidden" name="page_id" value="16">
			<?
			}
			?>
			<tr>
				<td width="100%" valign="top" align="center" colspan="2">
			<input type="Submit" name="submit" value="Submit"></td>
			</tr>
			</form>						
	</tr>
</table>
		
<!-- header start -->
<? include("includes/footer.php")?>	
<!-- header end -->	
</BODY>
</HTML>