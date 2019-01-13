<?php
require('includes/application_top.php');

if($_SESSION['admin_id']==""){
		header("location:login.php");
}


//echo $getrandno;

	if($_POST['task']=="addbrand"){
			
		$err = false ;
			$_SESSION['regmsg'] = ""; 
		//$upload=$_FILES[upload][name];
		//$upload_tmp=$_FILES[upload][tmp_name];
		//$uploadsize=$_FILES[upload][size];
		//$uploadtype=$_FILES[upload][type];
		
			
		
	 $trims=$_POST['trimname'];
  $colors=$_POST['prefcolor'];
 $specialprif=$_POST['specialpref'];
 $orders=$_POST['ordervehicle'];
 $tradein=$_POST['tradein'];
 $caroption=$_POST['caroption'];
 $payments=$_POST['paymenttype'];
 $zipcodes=$_POST['zipcode'];
 

 
 
$inse="INSERT INTO purchase_cardetail (trimname , prefcolor ,specialpref ,
ordervehicle ,
tradein ,
caroption ,
paymenttype ,
zipcode
)
VALUES ('$trims', '$colors', '$specialprif', '$orders', '$tradein', '$caroption', '$payments', '$zipcodes'
)";

mysql_query($inse);

	header("location:buyer_cardetail.php?msg=Data Has Been Added Successully");

   exit;
	}
			
		/*
			if($brandname==""){
				$err = true ;
				$_SESSION['regmsg'] .= "Enter Brand Name in the text  box.<br />";
			}
			if($companyname==""){
				$err = true ;
				$_SESSION['regmsg'] .= "Enter Company Name.<br />";
			}*/
			
			/*$sqlres =  mysql_query("select email from  users where email='".$email."'  and  user_type='".$usertype."' ") ;
			if(mysql_num_rows($sqlres) > 0)
			{
				$err = true ;
				$_SESSION['regmsg'] .= "email address already exist.";
			 
			}*/
			/*if($err)
			{
				//header("location:addusers.php");
			}
			 else
			 {*/
			
		
	
			
			/*$getrandno=rand(0000,1234);
			$upload1=$getrandno.$upload;
			
			 	$sqlq  = "insert into brand set b_name='".$brandname."',year='".$years."', b_company='".$companyname."', model='".$model."', performance='".$performance."', saftey='".$saftey."', comfort='".$comfort."', status='".$status."', colour='".$colour."' , accessories='".$accessories."',image='".$upload1."', b_details='".$details."'"; 
				
				mysql_query($sqlq);
				move_uploaded_file($_FILES[upload][tmp_name],"carimage/".$upload1);
				$_SESSION['regmsg'] = "new brand  added successfully.";
				header("location:brand.php?msg=Brand Has Been Added Successully");
	exit;*/
		
	
$sqls="select * from trim_manage";
$rese= mysql_query($sqls);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Caroye.com :: Admin</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="style2.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
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
                          <td align="left" valign="middle" class="middle_bg_blu"><h4>Add New Trim</h4></td>
                          <td width="31" align="right" valign="top"><img src="images/right_rouud_blu.jpg" alt="" width="31" height="35" /></td>
                        </tr>
                      </table></td>
                    </tr>
                      <tr>
                      <td height="12">  
					  <div style="color:red; margin-left:300px;"><?php // echo $_SESSION['regmsg'] ; $_SESSION['regmsg']=""; ?></div></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">
                      <form name="addbrand"  method="post" onsubmit="chkreg();" >
						 <input type="hidden" name="task" value="addbrand" />						
                    
<div class="choose_single-trim" style="width:940px;">
	    <div class="choose_single_choose">
 <div class="choose_left"></div>   
  <div class="choose_center">
  <h1>Choose a single trim</h1>
  </div>  
   <div class="choose_right"></div> 
   <div class="clear"></div> 
    </div>
<div class="trim_name">
<div class="trim_name_cont">
<!--<div class="trim_name_text">
<input name="Trim_name" type="radio" value="" class="radio_form" />
</div>-->
<div class="trim_name_trans"><img style="padding-top:2px;" alt="" src="images/upper-dwon.jpg"></div>
<div class="trim_name1">Trim Name</div>
<div class="trim_name_trans"><img style="padding-top:2px;" alt="" src="images/upper-dwon.jpg"></div>
<div class="mSRP">MSRP</div>
<div class="trim_name_trans"><img style="padding-top:2px;" alt="" src="images/upper-dwon.jpg"></div>
<div class="mSRP">Invoice</div>
<div class="trim_name_trans"><img style="padding-top:2px;" alt="" src="images/upper-dwon.jpg"></div>
<div class="mSRP">MPG City</div>
<div class="trim_name_trans"><img style="padding-top:2px;" alt="" src="images/upper-dwon.jpg"></div>
<div class="mSRP">MPG Hwy</div>
<div class="clear"></div>
</div>
</div> 
<div class="frontrak_cont">

<?php 
while ($res=mysql_fetch_array($rese))
		{	
		?>

<div class="trim_name_text_RADIO">
<input name="trimname" type="radio" class="radio_form" value="4 Door Sedan CVT FrontTrak 2.0T Premium" checked="checked" /></div>

<div class="door_SEARCH-TEXT"><?php echo $res["trim_name"]; ?> <span> <?php echo $res["msrp"]; ?> </span> <?php echo $res["invoice"]; ?>  <span style="margin-left:50px;"> <?php echo $res["mpg_city"]; ?> </span> <?php echo $res["mpg_hwy"]; ?>  </div>


<div class="clear"></div><br />


<?php  } ?>







</div> 
<!--<div class="frontrak_cont_doller">
<div class="frontrak_cont1">
<div class="trim_name_text_RADIO">
<input name="trimname" type="radio" class="radio_form" value="4 Door Sedan CVT FrontTrak 2.0T Premium" /></div>
<div class="door_SEARCH-TEXT">4 Door Sedan CVT FrontTrak 2.0T Premium <span> $32,300 </span>	$30,040 <span style="margin-left:50px;"> $30,040 </span> $30,040 </div>
<div class="clear"></div>
</div>
</div>
 <div class="frontrak_cont">
<div class="trim_name_text_RADIO">
<input name="trimname" type="radio" class="radio_form" value="4 Door Sedan CVT FrontTrak 2.0T Premium" /></div>
<div class="door_SEARCH-TEXT">4 Door Sedan CVT FrontTrak 2.0T Premium <span> $32,300 </span>$30,040  <span style="margin-left:50px;"> $30,040 </span> $30,040</div>
<div class="clear"></div>
</div> 
<div class="frontrak_cont_doller">
<div class="frontrak_cont1">
<div class="trim_name_text_RADIO">
<input name="trimname" type="radio" class="radio_form" value="4 Door Sedan CVT FrontTrak 2.0T Premium" /></div>
<div class="door_SEARCH-TEXT">4 Door Sedan CVT FrontTrak 2.0T Premium<span>      $32,300 </span>	$30,040  <span style="margin-left:50px;"> $30,040 </span> $30,040 </div>
<div class="clear"></div>
</div>
</div>
<div class="frontrak_cont">
<div class="trim_name_text_RADIO">
<input name="trimname" type="radio" class="radio_form" value="4 Door Sedan CVT FrontTrak 2.0T Premium" /></div>
<div class="door_SEARCH-TEXT">4 Door Sedan CVT FrontTrak 2.0T Premium  <span>      $32,300 </span>	$30,040 <span style="margin-left:50px;"> $30,040 </span> $30,040 </div>
<div class="clear"></div>
</div>-->

<div class="preferred_colors">
 <div class="choose_left"></div>   
  <div class="choose_center_pract">
  <h1>Choose your preferred colors</h1>
  </div>  
   <div class="choose_right"></div>
<div class="clear"></div>
</div>
<div class="click_colors">
<div class="qustion-image"><img alt="" src="images/qustion_im.jpg"></div>
<div class="willing-to_buy">Click colors you'd be<a href="#"> willing to buy.</a></div>
<div class="clear"></div>
</div>
<div class="your_color_cont">
<div class="your_color_cont_left">
<div class="color_chick-botton">
  <label>
  <input type="checkbox" value="Black" class="chkboxx" id="checkbox" name="prefcolor">
  </label>
</div>
<div class="black_cont">Black </div>
<div class="chosse_image_cont"><img alt="" src="images/col.jpg"></div>
</div>
<div class="your_color_cont_left">
<div class="color_chick-botton">
  <label>
  <input type="checkbox" value="Blue" class="chkboxx" id="checkbox" name="prefcolor">
  </label>
</div>
<div class="black_cont"> Blue </div>
<div class="chosse_image_cont"><img height="43" width="42" alt="" src="images/blow-col.jpg"></div>
</div>
<div class="your_color_cont_left">
<div class="color_chick-botton">
  <label>
  <input type="checkbox" value="Brown" class="chkboxx" id="checkbox" name="prefcolor">
  </label>
</div>
<div class="black_cont_brown">Brown </div>
<div class="chosse_image_cont"><img height="43" width="42" alt="" src="images/Brown.jpg"></div>
</div>
<div class="your_color_cont_left">
<div class="color_chick-botton">
  <label>
  <input type="checkbox" value="Gray" class="chkboxx" id="checkbox" name="prefcolor">
  </label>
</div>
<div class="black_cont">Gray </div>
<div class="chosse_image_cont"><img height="43" width="42" alt="" src="images/gray.jpg"></div>
</div>
<div class="your_color_cont_left">
<div class="color_chick-botton">
  <label>
  <input type="checkbox" value="Red" class="chkboxx" id="checkbox" name="prefcolor">
  </label>
</div>
<div class="black_cont"> Red </div>
<div class="chosse_image_cont"><img height="43" width="42" alt="" src="images/-Red-.jpg"></div>
</div>
<div class="your_color_cont_left">
<div class="color_chick-botton">
  <label>
  <input type="checkbox" value="Silver" class="chkboxx" id="checkbox" name="prefcolor">
  </label>
</div>
<div class="black_cont">Silver </div>
<div class="chosse_image_cont"><img height="43" width="42" src="images/Silver-].jpg"></div>
</div>
<div class="your_color_cont_left">
<div class="color_chick-botton">
  <label>
  <input type="checkbox" value="White" class="chkboxx" id="checkbox" name="prefcolor">
  </label>
</div>
<div class="black_cont">White </div>
<div class="chosse_image_cont"><img height="43" width="42" alt="" src="images/White.jpg"></div>
</div>
<div class="clear"></div>
</div>
<div class="preferred_colors">
 <div class="choose_left"></div>   
  <div class="choose_center_practect ">
  <h1>Tell us about any specific options or packages you'd like</h1>
  </div>  
   <div class="choose_right"></div>
<div class="clear"></div>
</div>
<div class="click_colors">
<div class="qustion-image"><img alt="" src="images/qustion_im.jpg"></div>
<div class="willing-to_buy">You can notify the dealers of any special preferences you have here</div>
<div class="clear"></div>
</div>
<div class="click_colors">
<div class="helf_form">
  <label>
  <textarea id="specialpref" class="helf_for" name="specialpref"></textarea>
  </label>
</div>
</div>
<div class="preferred_colors">
 <div class="choose_left"></div>   
  <div class="choose_center_practect ">
  <h1>Would you be willing to wait for a special order vehicle?</h1>
  </div>  
   <div class="choose_right"></div>
<div class="clear"></div>
</div>
<div class="click_colors">
<div class="qustion-image"><img alt="" src="images/qustion_im.jpg"></div>
<div class="willing-to_buy">Special order vehicles take up to 90 days to arrive but can be configured with exact options/colors/trims.</div>
<div class="clear"></div>
</div>
<div class="frontrak_cont">
<div class="trim_name_text_RADIO">
<input type="radio" checked="checked" value="no" class="radio_form" name="ordervehicle"></div>
<div class="door_SEARCH-TEXT">No, I'd only like offers on in-stock vehicles which are immediately available<br>
</div>
<div class="clear"></div>
</div>
<div class="frontrak_cont">
<div class="trim_name_text_RADIO">
<input type="radio" value="yes" class="radio_form" name="ordervehicle"></div>
<div class="door_SEARCH-TEXT">Yes, I'd be willing to wait several months for a special order vehicle </div>
<div class="clear"></div>
</div>
<div class="preferred_colors">
 <div class="choose_left"></div>   
  <div class="choose_center_pract">
  <h1>Do you have a trade-in?</h1>
  </div>  
   <div class="choose_right"></div>
<div class="clear"></div>
</div>
<div class="frontrak_cont">
<div class="trim_name_text_RADIO">
<input type="radio" checked="checked" value="no" class="radio_form" name="tradein"></div>
<div class="door_SEARCH-TEXT">No, I'm not trading in a car.</div>
<div class="clear"></div>
</div>
<div class="frontrak_cont">
<div class="trim_name_text_RADIO">
<input type="radio" value="yes" class="radio_form" name="tradein"></div>
<div class="door_SEARCH-TEXT">Yes, I want to trade in a car.</div>
<div class="clear"></div>
</div>
<div class="preferred_colors">
 <div class="choose_left"></div>   
  <div class="choose_center_leasing">
  <h1>Are you buying or leasing your new car?</h1>
  </div>  
   <div class="choose_right"></div>
<div class="clear"></div>
</div>
<div class="frontrak_cont">
<div class="trim_name_text_RADIO">
<input type="radio" checked="checked" value="buy" class="radio_form" name="caroption"></div>
<div class="door_SEARCH-TEXT">I want to buy the car</div>
<div class="clear"></div>
</div>
<div class="frontrak_cont">
<div class="trim_name_text_RADIO">
<input type="radio" value="lease" class="radio_form" name="caroption"></div>
<div class="door_SEARCH-TEXT">I want to lease the car</div>
<div class="clear"></div>
</div>
<div class="preferred_colors">
 <div class="choose_left"></div>   
  <div class="choose_center_pract">
  <h1>How would you like to pay?</h1>
  </div>  
   <div class="choose_right"></div>
<div class="clear"></div>
</div>
<div class="frontrak_cont">
<div class="trim_name_text_RADIO">
<input type="radio" checked="checked" value="cash" class="radio_form" name="paymenttype"></div>
<div class="door_SEARCH-TEXT">I already have financing or will pay cash</div>
<div class="clear"></div>
</div>
<div class="frontrak_cont">
<div class="trim_name_text_RADIO">
<input type="radio" value="dealer" class="radio_form" name="paymenttype"></div>
<div class="door_SEARCH-TEXT">I want to finance through the dealer.</div>
<div class="clear"></div>
</div>
<div class="preferred_colors">
 <div class="choose_left"></div>   
  <div class="choose_center_pract">
  <h1>Enter your zip code</h1>
  </div>  
   <div class="choose_right"></div>
<div class="clear"></div>
</div>
<div class="click_colors">
<div class="qustion-image"><img alt="" src="images/qustion_im.jpg"></div>
<div class="willing-to_buy"><strong>Why do we need this? </strong>Just to work with the dealerships closest to you. We won't ever give them your personal info.</div>
<div class="clear"></div>
</div>
<div class="zip_Code_box">
<div style="padding-top:5px; padding-left:10px;" class="willing-to_buy">Zip Code</div>
<div class="zip_Code_box_cont">
<input type="text" onblur="checkzipcode(this.value);" class="zip_Code_form" name="zipcode">
</div><span style="margin-left:10px; color:red;" id="pzipcode"></span>
<div class="clear"></div>
</div>
<div class="preferred_colors">
 <div class="choose_left"></div>   
  <div class="choose_center_rebates">
  <h1>Do you qualify for any rebates or incentives?</h1>
  </div>  
   <div class="choose_right"></div>
<div class="clear"></div>
</div>
<div class="click_bottom">
<div class="qustion-image"><img alt="" src="images/qustion_im.jpg"></div>
<div class="willing-to_buy">Enter zip code first to see incentives.</div>
<div class="clear"></div>
</div>
<div class="continue_button"><input type="image" src="images/continue.jpg"></div>
</div>


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
