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
       <h1>The more, the merrier!</h1>
        </div>
        <div class="left_navi_cont">
        
			<?php include("left_menu.php");?>	
		
        </div>
        
        
        <div class="why_right_cont">
        <div class="why_top6_text"><h1>Group Car Buying</h1>
        </div>
        <div class="why_twitter_cont"><a href="#"><img src="images/facebook_like.jpg" alt="" border="0" /></a>
        
          <a href="#"><img src="images/t-like.gif" alt="" border="0" /></a></div>
          <div class="about_caroye_cont">
          <h3>Comming soon</h3>
 
          </div>
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
