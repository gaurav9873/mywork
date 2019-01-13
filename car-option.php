<?php 
include("includes/conn.php");

$years=$_REQUEST['y'];
 $makes=$_REQUEST['m'];
 $models=$_REQUEST['md'];
 
 
 //echo print_r ($_POST);
 //if(isset($_POST['Continue']))
 //{
 if($_POST['task']=="signupcaroption"){
			
		$err = false;
			$_SESSION['regmsg'] = ""; 
 
 
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

	header("location:thankyou.php");

 }


/*if(!isset($_SESSION['nextstep']) ||  $_SESSION['nextstep']=="")
{
	header("location:index.php");
	exit;
}

if(!isset($_SESSION['task']) ||  $_SESSION['task']!="successmakes")
{
	header("location:makemodels.php");
	exit;
}
*/
 $sql="select * from brand where year=$years and  b_company=$makes and  model=$models";
$reses= mysql_query($sql);
while ($row=mysql_fetch_array($reses))
		{	  
		 $porduct= $row['image'];
  
 
 #$IMAGE.='<a href="#"><img src="carimage/'.$porduct.'" alt="" width="598" height="207" border="0" style="padding-top:52px;padding-left:33px;"></a>';
  }
//$row= mysql_fetch_assoc($reses);

// $iid=$row["b_id"];
 // echo  $images=$row["image"];
 
 $sqls="select * from trim_manage";
$rese= mysql_query($sqls);


 
  
		

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Car Oye!</title>
<link rel="stylesheet" href="css/style.css" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
function chkcaroption(){
	var chlen = $('input[type=checkbox]:checked').length;
	if(chlen > 0){
		return true ;
	} else {
		alert("select at least one color");
	 	return false ;
	}
}
</script>
</head>
<body>
<div id="wrapper">
  <!--help_wfix-->
  <div id="help_wfix"> <a href="#"><img src="images/help.png" alt=""  /></a></div>
  <!--help_wfix-->
  <div id="header_main">
    <?php include("header-small.php");?>
  </div>
  
  <div id="container" class="back2">
    <div class="container_inner backnoun">
      <div class="cont-shadow back2">
        <div id="body_cont_in">
        <div class="payment_gateway_inner">
      <div class="gateway_conten">
      <div class="gateway_conten_left"></div>
      <div class="gateway_conten_center">
      <h1>Cool car! Tell us more...
<span><a href="makemodels.php"><FONT color="#FFFFFF">Click here to change cars</FONT></a></span> </h1>
      </div>
      <div class="gateway_conten_right">
      </div>
      <div class="clear">
      
      </div>
      </div> 
             <div class="rate_review_inner_cont">
     <div class="gateway_conten1">
	 
	 <form method="post" action="" onsubmit="return chkcaroption();">
	<input type="hidden" name="task" value="signupcaroption" />
 <div class="car_bg"><img src="admin/carimage/<?php echo $porduct; ?>" align="middle" width="660"  height="315" /> </div> 
    <div class="choose_single-trim">
	<?php if(isset($_SESSION['errmsg']) && $_SESSION['errmsg']!="") { ?>
		<div id="errormsg" style="color:#FF0000; font-size:11px; line-height:20px;"><?php echo $_SESSION['errmsg'] ; echo $_SESSION['errmsg']="";?></div>
		<?php } ?>
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
<div class="trim_name_trans"><img src="images/upper-dwon.jpg" alt="" style="padding-top:2px;"/></div>
<div class="trim_name1">Trim Name</div>
<div class="trim_name_trans"><img src="images/upper-dwon.jpg" alt="" style="padding-top:2px;"/></div>
<div class="mSRP">MSRP</div>
<div class="trim_name_trans"><img src="images/upper-dwon.jpg" alt="" style="padding-top:2px;"/></div>
<div class="mSRP">Invoice</div>
<div class="trim_name_trans"><img src="images/upper-dwon.jpg" alt="" style="padding-top:2px;"/></div>
<div class="mSRP">MLG City</div>
<div class="trim_name_trans"><img src="images/upper-dwon.jpg" alt="" style="padding-top:2px;"/></div>
<div class="mSRP">MLG Hwy</div>
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
<div class="qustion-image"><img src="images/qustion_im.jpg" alt="" /></div>
<div class="willing-to_buy">Click colors you'd be<a href="#"> willing to buy.</a></div>
<div class="clear"></div>
</div>
<div class="your_color_cont">
<div class="your_color_cont_left">
<div class="color_chick-botton">
  <label>
  <input type="checkbox" name="prefcolor" id="checkbox" class="chkboxx"  value="Black" />
  </label>
</div>
<div class="black_cont">Black </div>
<div class="chosse_image_cont"><img src="images/col.jpg" alt="" /></div>
</div>
<div class="your_color_cont_left">
<div class="color_chick-botton">
  <label>
  <input type="checkbox" name="prefcolor" id="checkbox" class="chkboxx"  value="Blue"/>
  </label>
</div>
<div class="black_cont"> Blue </div>
<div class="chosse_image_cont"><img src="images/blow-col.jpg" alt="" width="42" height="43" /></div>
</div>
<div class="your_color_cont_left">
<div class="color_chick-botton">
  <label>
  <input type="checkbox" name="prefcolor" id="checkbox" class="chkboxx"  value="Brown"/>
  </label>
</div>
<div class="black_cont_brown">Brown </div>
<div class="chosse_image_cont"><img src="images/Brown.jpg" alt="" width="42" height="43" /></div>
</div>
<div class="your_color_cont_left">
<div class="color_chick-botton">
  <label>
  <input type="checkbox" name="prefcolor" id="checkbox" class="chkboxx"  value="Gray"/>
  </label>
</div>
<div class="black_cont">Gray </div>
<div class="chosse_image_cont"><img src="images/gray.jpg" alt="" width="42" height="43" /></div>
</div>
<div class="your_color_cont_left">
<div class="color_chick-botton">
  <label>
  <input type="checkbox" name="prefcolor" id="checkbox" class="chkboxx" value="Red"/>
  </label>
</div>
<div class="black_cont"> Red </div>
<div class="chosse_image_cont"><img src="images/-Red-.jpg" alt="" width="42" height="43" /></div>
</div>
<div class="your_color_cont_left">
<div class="color_chick-botton">
  <label>
  <input type="checkbox" name="prefcolor" id="checkbox" class="chkboxx"  value="Silver"/>
  </label>
</div>
<div class="black_cont">Silver </div>
<div class="chosse_image_cont"><img src="images/Silver-].jpg" width="42" height="43" /></div>
</div>
<div class="your_color_cont_left">
<div class="color_chick-botton">
  <label>
  <input type="checkbox" name="prefcolor" id="checkbox"  class="chkboxx" value="White"/>
  </label>
</div>
<div class="black_cont">White </div>
<div class="chosse_image_cont"><img src="images/White.jpg" alt="" width="42" height="43" /></div>
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
<div class="qustion-image"><img src="images/qustion_im.jpg" alt="" /></div>
<div class="willing-to_buy">You can notify the dealers of any special preferences you have here</div>
<div class="clear"></div>
</div>
<div class="click_colors">
<div class="helf_form">
  <label>
  <textarea name="specialpref" class="helf_for" id="specialpref"></textarea>
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
<div class="qustion-image"><img src="images/qustion_im.jpg" alt="" /></div>
<div class="willing-to_buy">Special order vehicles take up to 90 days to arrive but can be configured with exact options/colors/trims.</div>
<div class="clear"></div>
</div>
<div class="frontrak_cont">
<div class="trim_name_text_RADIO">
<input name="ordervehicle" type="radio" class="radio_form" value="no"  checked="checked"/></div>
<div class="door_SEARCH-TEXT">No, I'd only like offers on in-stock vehicles which are immediately available<br />
</div>
<div class="clear"></div>
</div>
<div class="frontrak_cont">
<div class="trim_name_text_RADIO">
<input name="ordervehicle" type="radio" class="radio_form" value="yes" /></div>
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
<input name="tradein" type="radio" class="radio_form" value="no" checked="checked" /></div>
<div class="door_SEARCH-TEXT">No, I'm not trading in a car.</div>
<div class="clear"></div>
</div>
<div class="frontrak_cont">
<div class="trim_name_text_RADIO">
<input name="tradein" type="radio" class="radio_form" value="yes" /></div>
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
<input name="caroption" type="radio" class="radio_form" value="buy" checked="checked" /></div>
<div class="door_SEARCH-TEXT">I want to buy the car</div>
<div class="clear"></div>
</div>
<div class="frontrak_cont">
<div class="trim_name_text_RADIO">
<input name="caroption" type="radio" class="radio_form" value="lease" /></div>
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
<input name="paymenttype" type="radio" class="radio_form" value="cash"  checked="checked"/></div>
<div class="door_SEARCH-TEXT">I already have financing or will pay cash</div>
<div class="clear"></div>
</div>
<div class="frontrak_cont">
<div class="trim_name_text_RADIO">
<input name="paymenttype" type="radio" class="radio_form" value="dealer" /></div>
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
<div class="qustion-image"><img src="images/qustion_im.jpg" alt="" /></div>
<div class="willing-to_buy"><strong>Why do we need this? </strong>Just to work with the dealerships closest to you. We won't ever give them your personal info.</div>
<div class="clear"></div>
</div>
<div class="zip_Code_box">
<div class="willing-to_buy" style="padding-top:5px; padding-left:10px;">Zip Code</div>
<div class="zip_Code_box_cont">
<input name="zipcode" type="text"  class="zip_Code_form" onblur="checkzipcode(this.value);"/>
</div><span id="pzipcode" style="margin-left:10px; color:red;"></span>
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
<div class="qustion-image"><img src="images/qustion_im.jpg" alt="" /></div>
<div class="willing-to_buy">Enter zip code first to see incentives.</div>
<div class="clear"></div>
</div>
<div class="continue_button"><input type="image"  src="images/continue.jpg" /></div>
</div>
<div class="clear"></div>
</form>
 </div>     
     </div> <div class="gateway_fotter"></div>  

             </div>
        <div class="clear"></div>
        </div>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
    </div>
  </div>
    <!--footer start here-->
  <?php include("footer.php");?>
  <!--footer end here-->
<script type="text/javascript">

function checkzipcode(pval){
	
	$.post("checkzipcode.php", {zipcode : pval },function(data){
		$("#pzipcode").html(data);
	});	
}  
  
</script>
</div>
</body>
</html>
