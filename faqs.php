<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Car Oye!</title>
<link rel="stylesheet" href="css/style.css" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
<script type="text/javascript">
	$(
 		function moveDown(){
		$('.login a').click(function (){
   			//if($('.mon-bot-txt:visible')){$('.mon-bot-txt').toggle()}else{return};
			if($('#login-menu:visible')){$('#login-menu').slideToggle()}else{return};
  			return false;
		});
	}
	);
</script>
</head>
<body>
<div id="wrapper">
  <!--help_wfix-->
  <div id="help_wfix"> <a href="#"><img src="images/help.png" alt=""  /></a></div>
  <!--help_wfix-->
  <div id="header_main">
    <?php include("header.php");?>
  </div>
  <div  id="nav_main">
    <?php include("nav_menu.php"); ?>
  </div>
  <div id="container" class="back2">
    <div class="container_inner backnoun">
      <div class="cont-shadow back2">
        <div id="body_cont_in">
        <div class="top-tlt">
       <h1>Top Reasons why people love using CarOye!</h1>
        </div>
        <div class="left_navi_cont">
        		<?php include("left_menu.php");?>			 
        </div>
        
        
        <div class="why_right_cont">
        <div class="why_top6_text"><h1>Frequently Asked Questions</h1>
        </div>
        <div class="why_twitter_cont"><a href="#"><img src="images/facebook_like.jpg" alt="" border="0" /></a>
        
          <a href="#"><img src="images/t-like.gif" alt="" border="0" /></a></div>
          
          <div class="about_caroye_cont">
          
          <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>
          <h1>About CarOye!</h1>
          <div class="what_caroye"><a href="#">What is CarOye!?</a></div>
           <div class="what_caroye"><a href="#">Where did the name CarOye! come from?
           </a>
             <p>Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae.</p>
           </div>
           
       <div class="what_caroye">
         <a href="#">Who are the founders and who else is behind CarOye!?</a></div>
       
       <div class="what_caroye"><a href="#">How did you come up with the idea? Why did you start CarOye!?</a></div>
       
       
       <div class="what_caroye"><a href="#">Who are your competitors?</a></div>
       
       <div class="what_caroye"><a href="#">How many cars have you sold?</a></div>
       <div class="what_caroye"><a href="#">Is CarOye! a broker?</a></div>
       
           
          
          </div>
          </div>
        <div class="check_out_cont">
          <div class="what_next_text">...what to read next...</div>
  <div class="real_customer_button"><span><a href="pricing.php">Plans and pricing</a></span></div>
  </div>
        
        
        </div>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
    </div>
  </div>
    <!--footer start here-->
  <?php include("footer.php");?>
  <!--footer end here-->
</div>
</body>
</html>
