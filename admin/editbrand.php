<?php
	require('includes/application_top.php');
	 
	 if($_SESSION['admin_id']=="")
	 {
		header("location:login.php");
	}
	
	

	if($_POST['task']=="update")
	{
	
	$upload=$_FILES[upload][name];
		$upload_tmp=$_FILES[upload][tmp_name];
		$uploadsize=$_FILES[upload][size];
		$uploadtype=$_FILES[upload][type];	
		
		
			$years=$_POST['years'];
			$brandname = $_POST['brandname'] ;
			$companyname = $_POST['companyname'] ;
			$models= $_POST['models'] ;
			$performance = $_POST['performance'] ;
			$saftey = $_POST['saftey'] ;
			$comfort = $_POST['comfort'] ;
			$status = $_POST['status'] ;
			
			$details = $_POST['details'];
			$brandid=$_POST['brandid'];
			
			$colour=$_POST['colour'];
			$accessories=$_POST['accessories'];
			$hcarimgname=$_POST['hcarimg'];
			
			/*$sqlupate  = "update users set fullname='".$fullname."', status='".$status."' , user_type='".$usertype."', status='".$status."' , yourtitle='".$title."' where id ='".$userid."' "; */
			
			$sqlupdate  = "update brand set year='".$years."', b_name='".$brandname."', b_company='".$companyname."', model='".$models."', performance='".$performance."', saftey='".$saftey."', comfort='".$comfort."', status='".$status."' ,colour='".$colour."' ,accessories='".$accessories."' , b_details='".$details."'  where b_id ='".$brandid."'"; 
						mysql_query($sqlupdate);
						
			if(!empty($upload_tmp))
			{
			$delimg="carimage".'/'.$hcarimgname;
			
			unlink($delimg);
			$getrandno=rand(0000,1234);
			$upload1=$getrandno.$upload;
			
			$sqlupdateimage = "update brand set image='".$upload1."'  where b_id ='".$brandid."'"; 
						mysql_query($sqlupdateimage);
						move_uploaded_file($_FILES[upload][tmp_name],"carimage/".$upload1);
			
			}
			
			/*header("location:editusers.php?id=$userid");
			exit;*/
		$_SESSION['usrmsg'] = "Brand Updated Successully";
	
	header("location:brand.php?msg=Brand Has Been Edited Successully");
	exit;
	
	}
	 
	$sql = "SELECT * FROM brand WHERE `b_id` = '".$_REQUEST['id'] ."'";
	$req = tep_db_query($sql);
	$res = tep_db_fetch_array($req);
 
 
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
<script type="text/javascript" src="../js/jquery.js"></script>


 <script type="text/javascript" src="lightbox/js/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="lightbox/js/jquery.cycle.all.min.js"></script>
    <script type="text/javascript" src="lightbox/js/jquery.lightbox-0.5.min.js"></script>
    <script type="text/javascript" src="lightbox/js/example.js"></script>
    <link rel="stylesheet" type="text/css" href="lightbox/css/example.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="lightbox/css/jquery.lightbox-0.5.css" media="screen" />



<script type="text/javascript">

function getmakes(myear){
	$.post("getmakes.php", {myear : myear },function(data){
		$("#companyname").removeAttr("disabled");
		$("#companyname").html(data);
	});	
}

function getmodels(makval){
	var makval = $("#companyname").val();
	var yersval = $("#years").val();
	$.post("getmodels.php", {makval : makval , yersval : yersval },function(data){
		if(data.length > 0){
			$("#models").removeAttr("disabled");
			$("#submitimg").removeAttr("disabled");
			$("#submitimg").removeClass("disabled");
			$("#models").html(data);
		}else{
			$("#models").html('');
			$("#models").attr("disabled", true);
			$("#submitimg").attr("disabled", true);
			$("#submitimg").addClass("disabled");
		}
	});	
}
</script>
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
                          <td align="left" valign="middle" class="middle_bg_blu"><h4>Edit Car Details</h4></td>
                          <td width="31" align="right" valign="top"><img src="images/right_rouud_blu.jpg" alt="" width="31" height="35" /></td>
                        </tr>
                      </table></td>
                    </tr>
                      <tr>
                      <td height="12" align="center" ><font color="#00CC00"> <? //echo $msg ;?></font></td>
                    </tr>
                    <tr>
                      <td align="center" valign="top">
                      <form name="form1"  method="post" enctype="multipart/form-data">
					  <input type="hidden" name="brandid" value="<?php echo $res['b_id']; ?>" />
					  <input type="hidden" name="task" value="update" />					
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                       
  <tr>
    <td align="left" valign="top" class="page-tile"><strong>Year</strong></td>
    <td align="left" valign="top" class="tr-color">  <select name="years" id="years" class="input-type" onchange="getmakes(this.value);" >
	<option value="<?php echo $res['year']; ?>"><?php echo $res['year']; ?></option>
	<option value="">-------------</option>
    <?php 
		$resy = mysql_query("select * from year order by year desc");
		while($rowy = mysql_fetch_assoc($resy)){
	?>
    <option value="<?php echo $rowy['year'];?>"><?php echo $rowy['year'];?></option>
	<?php } ?>
    </select></td>
  </tr>
  <?php
  $getcomid=$res['b_company'];
  $sqlcom = "SELECT make_model_text FROM makes_model WHERE `id` = '".$getcomid ."'";
	$reqcom = tep_db_query($sqlcom);
	$rescom = tep_db_fetch_array($reqcom);
  
  ?>
  <tr>
    <td align="left" valign="top" class="page-tile"><strong>Manufacture</strong></td>
    <td align="left" valign="top" class="tr-color"><div class="choose_form_box">
  <label>
  <select name="companyname" id="companyname" class="input-type" onchange="getmodels(this.value);">
  <option value="<?php echo $res['b_company']; ?>"><?php echo $rescom['make_model_text']; ?></option>
    <option value="">------------</option>
 </select>
  </label>
</div></td>
  </tr>
  
   <?php
  $getcomid=$res['b_company'];
  $getmodid=$res['model'];
  
  $sqlmod = "SELECT make_model_text FROM makes_model WHERE `id` = '".$getmodid ."' and  `parent_id` = '".$getcomid ."'";
	$reqmod = tep_db_query($sqlmod);
	$resmod = tep_db_fetch_array($reqmod);
  
  ?>
  
  
  
  <tr>
    <td align="left" valign="top" class="page-tile"><strong>Models</strong></td>
    <td align="left" valign="top" class="tr-color"><div class="choose_form_box">
  <label>
  <select name="models" id="models" class="input-type" >
   <option value="<?php echo $res['model']; ?>"><?php echo $resmod['make_model_text']; ?></option>
    <option value="">-------------</option>
  </select>
  </label>
</div></td>
  </tr>
  
  <tr>
    <td align="left" valign="top" class="tr-color page-tile"><strong>Brand Name </strong></td>
    <td align="left" valign="top" class="tr-color"><input name="brandname" type="text" value="<?php echo $res['b_name']; ?>" class="input-type"/>;</td>
  </tr>
  <tr>
    <td align="left" valign="top" class="page-tile tr-color"><strong>Performance</strong></td>
    <td align="left" valign="top" class="tr-color"><input name="performance" type="text" value="<?php echo $res['performance']; ?>" class="input-type"/></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="page-tile tr-color"><strong>Saftey</strong></td>
    <td align="left" valign="top" class="tr-color"><input name="saftey" type="text" value="<?php echo $res['saftey']; ?>" class="input-type"/></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="page-tile tr-color"><strong>Comfort</strong></td>
    <td align="left" valign="top" class="tr-color"><input name="comfort" type="text" value="<?php echo $res['comfort']; ?>." class="input-type"/></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="tr-color page-tile"><strong>Status</strong></td>
    <td align="left" valign="top" class="tr-color">
	<select name="status" style="border:1px solid #C3D5EA; margin-left:10px;">
		<option value="1" <?php if($res['status']==1) { echo "selected"; } ?>>Active</option>
		<option value="0" <?php if($res['status']==0) { echo "selected"; } ?>>Inactive</option>
	</select></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="tr-color page-tile"><strong>Colour</strong></td>
    <td align="left" valign="top" class="tr-color"><input name="colour" type="text" value="<?php echo $res['colour']; ?>." class="input-type"/></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="tr-color page-tile"><strong>Accessories</strong></td>
    <td align="left" valign="top" class="tr-color"><input name="accessories" type="text" value="<?php echo $res['accessories']; ?>." class="input-type"/></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="tr-color page-tile">&nbsp;</td>
    <td align="left" valign="top" class="tr-color"><div></div></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="tr-color page-tile"><strong>Image</strong></td>
    <td align="left" valign="top" class="tr-color"><div class="album"><a href="carimage/<?php echo $res['image']; ?>"><img src="carimage/<?php echo $res['image']; ?>"/></a> 
	
	</div>  <input name="upload" id="upload" type="file" class="input-type" /><input name="hcarimg" type="hidden" value="<?php echo $res['image']; ?>" /></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="page-tile tr-color"><strong>Details </strong></td>
    <td align="left" valign="top" class="tr-color">
	<textarea name="details"  id="details" cols="2" rows="10" class="input-type"><?php echo $res['b_details']; ?></textarea></td>
  </tr>
  <tr>
   <td align="left" valign="top" class="page-tile tr-color">&nbsp;</td>
   <td align="left" valign="top" class="tr-color">&nbsp;</td>
  </tr>
  
 
  
   <tr>
    <td align="left" valign="middle" class="page-tile">&nbsp;</td>
    <td align="left" valign="middle">
    <input type="submit" name="update" value="Update Brand" class="inputBtn" />    </td>
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
			  <script type="text/javascript">
			//<![CDATA[

				CKEDITOR.replace( 'details',
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
