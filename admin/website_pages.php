<?php
/*
  $Id: customers.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

   if($_REQUEST['action'] == 'delete'){

	if (!in_array($_REQUEST['page_id'], $page_not_in)) {
		$delete_qry = "DELETE FROM website_pages WHERE page_id = '". $_REQUEST['page_id'] ."'";
		mysql_query($delete_qry);
		header('location:website_pages.php');
	}
}

//$page_id=$_REQUEST['page_id'];
if(isset($_POST['submit'])){
  //print_r($_POST);
  	//$update_query = "update website_pages set page_body = '". $_POST['page_body'] ."' where page_id = " . $_POST['page_id'] . "";
	$update_query = "update website_pages set page_title = '". $_POST['page_title'] ."', page_meta_keyword = '". $_POST['page_meta_keyword'] ."', page_body = '".mysql_escape_string($_POST['page_body']) ."' where page_id = " . $_POST['update_page_id'] . "";
 	mysql_query($update_query) or die(mysql_error());
	$success_message = "Successfully Saved";
}
  
  
 ////////////////PARENT/////////// 
   
  function make_dropdown($CID,$selected='',$opt_str='',$addthis=''){
  global $opt_str,$add_str;
  $select_website_pages_query = "select * from website_pages where parent_page_id='$CID' order by page_id ASC";
  $select_website_pages_rec = tep_db_query($select_website_pages_query);
  
  //$select_page_fld_parent .= '<option value="0" selected>Please Select a Page</option>'."\n";
  $j=0;
  
  while($select_website_pages_result = tep_db_fetch_array($select_website_pages_rec)){
 if($select_website_pages_result['parent_page_id']==0){
  $add_str='';
  }else{
 $add_str=$addthis."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
 }
 if($selected==$select_website_pages_result['page_id']){
 $selstr='selected="selected"';
 }else{
 $selstr='';
 }
  $opt_str=$opt_str.'<option value="'.$select_website_pages_result['page_id'].'" '.$selstr.'>'. $add_str.$select_website_pages_result['page_title'] .'</option>';

  make_dropdown($select_website_pages_result['page_id'],$selected,$opt_str,$add_str);
  }
  return $opt_str;
  }
  $select_page_fld_parent = '<select name="parent_page_id" onChange="this.form.submit();"><option value="0">ROOT</option>'."\n";
  $select_page_fld_parent .= make_dropdown('0',$_REQUEST['parent_page_id']);
  $select_page_fld_parent .= '</select>';
  
////////////////PARENT///////////   
  
  
  if($_REQUEST['parent_page_id']=="")
  	$pPageId=0;
 else
 	$pPageId=$_POST['parent_page_id'];	
  
   
  
  
 
	 if(($_REQUEST['page_id'] == "") ){
  		$showingPageId = $pPageId;
	 }
	 elseif(($_REQUEST['page_id'] != "") ){
  		 $showingPageId = $_REQUEST['page_id'];
	 }
	 else{
 		$showingPageId = $_POST['page_id'];
	 }
 	 
  
 
  $page_page_body_query = "select * from website_pages where page_id = '" . $showingPageId . "'"; 
  $page_page_body_query_rec = tep_db_query($page_page_body_query);
  $page_page_body_query_result = tep_db_fetch_array($page_page_body_query_rec);
  
  $del_check_id = $page_page_body_query_result['parent_page_id'];
	
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Caroye.com :: Admin</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<script src="ckeditor/_sample/sample.js" type="text/javascript"></script>
<link href="ckeditor/_sample/sample.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php
require('includes/header.php');
?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
     
<!-- body_text //-->
    <td width="100%" valign="top">
<table width="90%" style="border-collapse:collapse" border="1" bordercolor="#d7d7d7" cellspacing="0" cellpadding="5">
	<tr>
		<td class="smalltext"  align="">
			<form action="<? echo $PHP_SELF; ?>" method="post">
			
			<b>Select Parent page:&nbsp;&nbsp;</b><? echo $select_page_fld_parent; ?>&nbsp;&nbsp;
			<input type="Button" name="btnadd2" value=" ADD PAGE " class="buttn" onClick="document.location.href='add_pages.php?parent_page_id=<?=$pPageId?>'" />
			 
			</form>
<?php

	 
?>
			<!--<input type="Button" name="btnadd" value=" DELETE PAGE " class="buttn" onClick="document.location.href='website_pages.php?page_id=<?php //echo $_REQUEST['parent_page_id']?>&parent_page_id=0&action=delete'">-->
<?php
	 
?>		</td>
	</tr>
	<tr>
		<td class="smalltext">
		<?php
			if(isset($success_message)){
				echo '<center><b><font color="#008000">'. $success_message .'</font></b></center>';
			}
		?>
		 <form action="<?php echo $PHP_SELF; ?>" method="post">
			<table border="0" width="100%">
			  
			  <tr>
				<td width="20%" valign="top" align="right" class="smalltext"><b>Page&nbsp;Title:</b>&nbsp;&nbsp;</td>
				<td width="80%"><input type="Text" name="page_title" value="<?php echo $page_page_body_query_result['page_title']; ?>" size="50"></td>
			  </tr>
			  <tr>
				<td width="20%" valign="top" align="right" class="smalltext"><b>Page&nbsp;Meta&nbsp;Keyword:</b>&nbsp;&nbsp;</td>
				<td width="80%"><textarea rows="4" name="page_meta_keyword" cols="138" style="overflow:auto;"><?php echo $page_page_body_query_result['page_meta_keyword']; ?></textarea></td>
			  </tr>
				<tr>
				<td width="20%" valign="top" align="right" class="smalltext"><b>Page Body:</b>&nbsp;&nbsp;</td>
				<td width="80%">
                <textarea cols="80" id="page_body" name="page_body" rows="10"><?php $pbody=stripslashes($page_page_body_query_result['page_body']); echo $pbody;?> </textarea>
			 		</TD>
			 </table>			
				<input type="hidden" name="update_page_id" value="<?php echo $showingPageId; ?>">
				<input type="hidden" name="page_id" value="<?php echo $_POST['page_id']; ?>">
				<input type="hidden" name="parent_page_id" value="<?php echo$_POST['parent_page_id']; ?>">			
			<tr>
				<td width="100%" valign="top" align="center" colspan="2">
			<input type="Submit" name="submit" value="Submit"></td>
			</tr>
		</form>	</tr>
</table>
	</td>
<!-- body_text_eof //-->
  </tr>
</table>
<script type="text/javascript">
			//<![CDATA[

				CKEDITOR.replace( 'page_body',
					{
						/*
						 * Style sheet for the contents
						 */
						contentsCss : 'body {color:#000; background-color#:FFF;}',

						/*
						 * Simple HTML5 doctype
						 */
						docType : '<!DOCTYPE HTML>',

						/*
						 * Core styles.
						 */
						coreStyles_bold	: { element : 'b' },
						coreStyles_italic	: { element : 'i' },
						coreStyles_underline	: { element : 'u'},
						coreStyles_strike	: { element : 'strike' },

						/*
						 * Font face
						 */
						// Define the way font elements will be applied to the document. The "font"
						// element will be used.
						font_style :
						{
								element		: 'font',
								attributes		: { 'face' : '#(family)' }
						},

						/*
						 * Font sizes.
						 */
						fontSize_sizes : 'xx-small/1;x-small/2;small/3;medium/4;large/5;x-large/6;xx-large/7',
						fontSize_style :
							{
								element		: 'font',
								attributes	: { 'size' : '#(size)' }
							} ,

						/*
						 * Font colors.
						 */
						colorButton_enableMore : true,

						colorButton_foreStyle :
							{
								element : 'font',
								attributes : { 'color' : '#(color)' },
								overrides	: [ { element : 'span', attributes : { 'class' : /^FontColor(?:1|2|3)$/ } } ]
							},

						colorButton_backStyle :
							{
								element : 'font',
								styles	: { 'background-color' : '#(color)' }
							},

						/*
						 * Styles combo.
						 */
						stylesSet :
								[
									{ name : 'Computer Code', element : 'code' },
									{ name : 'Keyboard Phrase', element : 'kbd' },
									{ name : 'Sample Text', element : 'samp' },
									{ name : 'Variable', element : 'var' },

									{ name : 'Deleted Text', element : 'del' },
									{ name : 'Inserted Text', element : 'ins' },

									{ name : 'Cited Work', element : 'cite' },
									{ name : 'Inline Quotation', element : 'q' }
								],

						on : { 'instanceReady' : configureHtmlOutput }
					});

/*
 * Adjust the behavior of the dataProcessor to avoid styles
 * and make it look like FCKeditor HTML output.
 */
function configureHtmlOutput( ev )
{
	var editor = ev.editor,
		dataProcessor = editor.dataProcessor,
		htmlFilter = dataProcessor && dataProcessor.htmlFilter;

	// Out self closing tags the HTML4 way, like <br>.
	dataProcessor.writer.selfClosingEnd = '>';

	// Make output formatting behave similar to FCKeditor
	var dtd = CKEDITOR.dtd;
	for ( var e in CKEDITOR.tools.extend( {}, dtd.$nonBodyContent, dtd.$block, dtd.$listItem, dtd.$tableContent ) )
	{
		dataProcessor.writer.setRules( e,
			{
				indent : true,
				breakBeforeOpen : true,
				breakAfterOpen : false,
				breakBeforeClose : !dtd[ e ][ '#' ],
				breakAfterClose : true
			});
	}

	// Output properties as attributes, not styles.
	htmlFilter.addRules(
		{
			elements :
			{
				$ : function( element )
				{
					// Output dimensions of images as width and height
					if ( element.name == 'img' )
					{
						var style = element.attributes.style;

						if ( style )
						{
							// Get the width from the style.
							var match = /(?:^|\s)width\s*:\s*(\d+)px/i.exec( style ),
								width = match && match[1];

							// Get the height from the style.
							match = /(?:^|\s)height\s*:\s*(\d+)px/i.exec( style );
							var height = match && match[1];

							if ( width )
							{
								element.attributes.style = element.attributes.style.replace( /(?:^|\s)width\s*:\s*(\d+)px;?/i , '' );
								element.attributes.width = width;
							}

							if ( height )
							{
								element.attributes.style = element.attributes.style.replace( /(?:^|\s)height\s*:\s*(\d+)px;?/i , '' );
								element.attributes.height = height;
							}
						}
					}

					// Output alignment of paragraphs using align
					if ( element.name == 'p' )
					{
						style = element.attributes.style;

						if ( style )
						{
							// Get the align from the style.
							match = /(?:^|\s)text-align\s*:\s*(\w*);/i.exec( style );
							var align = match && match[1];

							if ( align )
							{
								element.attributes.style = element.attributes.style.replace( /(?:^|\s)text-align\s*:\s*(\w*);?/i , '' );
								element.attributes.align = align;
							}
						}
					}

					if ( !element.attributes.style )
						delete element.attributes.style;

					return element;
				}
			},

			attributes :
				{
					style : function( value, element )
					{
						// Return #RGB for background and border colors
						return convertRGBToHex( value );
					}
				}
		} );
}


/**
* Convert a CSS rgb(R, G, B) color back to #RRGGBB format.
* @param Css style string (can include more than one color
* @return Converted css style.
*/
function convertRGBToHex( cssStyle )
{
	return cssStyle.replace( /(?:rgb\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\))/gi, function( match, red, green, blue )
		{
			red = parseInt( red, 10 ).toString( 16 );
			green = parseInt( green, 10 ).toString( 16 );
			blue = parseInt( blue, 10 ).toString( 16 );
			var color = [red, green, blue] ;

			// Add padding zeros if the hex value is less than 0x10.
			for ( var i = 0 ; i < color.length ; i++ )
				color[i] = String( '0' + color[i] ).slice( -2 ) ;

			return '#' + color.join( '' ) ;
		 });
}
			//]]>
			</script>
<?php
require('includes/footer.php');
?>
</body>
</html>

