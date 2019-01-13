<?php
/*
  $Id: customers.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

if(isset($_POST['submit'])){
 $parentId=$_REQUEST['parent_page_id'];
 $page_name=$_POST['page_name'];
 $page_title=$_POST['page_title'];
 $page_meta_keyword=$_POST['page_meta_keyword'];
 $position=$_POST['position'];
 $query = tep_db_query("insert into website_pages values('', '', '".(int)$parentId."','$page_name','$page_title','$page_meta_keyword','','0','N', '')");
	if($query)
	{
		header('location:website_pages.php?msg=ok');
		exit;
	} 
 $pid=mysql_insert_id();
 }   
  
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Caroye.com :: Admin</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="includes/general.js"></script>
<SCRIPT LANGUAGE="JavaScript">
			function submitChk()
			{
			if(document.page.page_name.value=="")
			{
			alert("Please Enter Page name !");
			document.page.page_name.focus();
			return(false);
			}
			 if(document.page.page_title.value=="")
			{
			alert("Please Enter Page Title !");
			document.page.page_title.focus();
			return(false);
			}
			if(document.page.page_meta_keyword.value=="")
			{
			alert("Please Enter Meta keyword !");
			document.page.page_meta_keyword.focus();
			return(false);
			}
			
			}

</SCRIPT></head>
<body>
<?php
require('includes/header.php');
?>
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>

  <form name="page"  method="post" action="" onSubmit="return submitChk()">
 	<TR>
		<TD height="22">&nbsp;</TD>
		<TD height="22"><b>ADD PAGE</b></TD>
	</TR>
	<TR>
		<TD align="right" width="20%">&nbsp;</TD><TD>&nbsp;</TD>		
	</TR>
	<TR>
		<TD align="right" width="20%" height="22" valign="top" class="smallText"><b>Page Name:&nbsp;&nbsp;</b></TD>
		<TD width=""><textarea name="page_name" cols="50" rows="4"></textarea>
		&nbsp;</TD>
	</TR>
	<TR>
		<TD align="right" width="20%"  height="22" valign="top"><b>Page Title:</b>&nbsp;&nbsp;</TD>
		<TD width="80%"><textarea name="page_title" cols="50" rows="4"></textarea></TD>
		
	</TR>
	<TR>
		<TD align="right" width="20%"  height="22" valign="top"><b>Page Meta Keyword:</b>&nbsp;&nbsp;</TD>
		<TD width="80%"><textarea rows="4" name="page_meta_keyword" cols="50"></textarea></TD>
		
	</TR>
	<input name="position" type="hidden" value="">
	<TR>
		<TD align="right" width="20%">&nbsp;</TD><TD>&nbsp;</TD>		
	</TR>
		<input type="hidden" name="parent_page_id" value="<? echo $_REQUEST['parent_page_id']; ?>">
 		<input type="Hidden" name="page_id" value="<? echo $pid; ?>">
	<TR>
		<TD width="20%">&nbsp;</TD>
		<TD align="" width="80%" valign="bottom" height="22"><input type="Submit" name="submit" value="Add Page"></TD>
			
	</TR>
	</form>
							

</TABLE>			
<?php
require('includes/footer.php');
?>
</body>
</html>
