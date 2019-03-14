<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Car Oye!</title>
<link rel="stylesheet" href="css/style.css" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
</head>
<body>
<div id="wrapper">
  <!--help_wfix-->
  <div id="help_wfix"> <a href="#"><img src="images/help.png" alt=""  /></a></div>
  <!--help_wfix-->
  <div id="header_main">
    <div class="header_inner">
      <div class="header_wfix">
        <div class="logo_lp"><a href="index.php"><img src="images/caroye_logo.png" alt="logo" width="280" height="77" /></a></div>
        <div class="logo_rp">
          <div class="link_top">
            <ul>
              <li class="margin0">Your City: <a href="#">Delhi NCR</a></li>
              <li>Other Cities: <a href="#">Mumbai</a><a href="#">Bangalore</a> </li>
              <li class="margin0"><a href="#"><img src="images/reter-a-friend_icon.png" alt=""  /></a></li>
            </ul>
          </div>
          <div id="socal_wfix">
            <div class="login"><a href="#">Login</a>
            <div id="login-menu">
		<form method="POST" action="" class="">
				<div style="margin: 0px; padding: 5px 0px;" class="caroye-form-field-last">
					<label for="login" class="caroye-form-label">Email</label>
					<span class="caroye-form-req">(required)</span><br>
					<input type="email" tabindex="1" autofocus="" required="" class="caroye-form-input-plain" style="width: 235px;" id="login" name="login" gtbfieldid="1">
					<div class="caroye-form-error"></div>
					<div class="clear"></div>
				</div>
				<div style="margin: 0px; padding: 5px 0px;" class="caroye-form-field-last">
					<label for="password" class="caroye-form-label">Password</label>
					<span class="caroye-form-req">(required)</span><br>
					<input type="password" tabindex="2" required="" class="caroye-form-input-plain" style="width: 235px;" id="password" name="password"><br>
					<div class="caroye-form-error"></div>
					<div class="clear"></div>
				</div>
				<div style="margin-top: 5px;" class="fb-form-field-last flotrgt">
					<input type="hidden" value="true" name="from_login_menu">
					<input type="image" value="Login" src="../html/images/login_btn.png">
				</div>
				<div style="margin: 5px 0px; padding-right: 2px;" class="fgot">
					<a href="#">Register!</a>
					<br>
					<a href="#">Forgot Password?</a>				</div>
                <div class="clear"></div>
		</form>
		<div id="connect-buttons">
			Or Connect with: <br>
			<a class="facebook-login" id="login-facebook-connect" href="#"><img alt="Facebook Connect" src="../html/images/facebook-connect.gif" class="facebook-login"></a> &nbsp;
			<a href="#"><img alt="Twitter Signin" style="height: 21px;" src="../html/images/twitter-connect.png"></a>		</div>
	</div>
            </div>
           <div class="signup"><a href="sign-up.php">Sign up</a></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div  id="nav_main">
   <?php include("nav_menu.php"); ?>
  </div>
  <div id="container" class="back2">
    <div class="container_inner backnoun">
      <div class="cont-shadow back2">
        <div id="body_cont_in">
        <div class="top-tlt">
       <h1>We’re in the news!</h1>
        </div>
        <div class="left_navi_cont">
			<?php include("left_menu.php");?>		
        </div>
        
        
        <div class="why_right_cont">
        <div class="why_top6_text"><h1>Recent Press Coverage</h1>
        </div>
        <div class="why_twitter_cont"><a href="#"><img src="images/facebook_like.jpg" alt="" border="0" /></a>
        
          <a href="#"><img src="images/t-like.gif" alt="" border="0" /></a></div>
          <div class="about_caroye_cont">
          
          <p>These fine publications think CarOye! is up to some cool stuff. Check it out!</p>
          <div class="media_vedio"><img src="images/vidio_media-covrage.jpg" alt="" width="468" height="281" /></div>
          <div class="founder_cont_media">
          <div class="founder_image_media"><img src="images/media-covrage_image.jpg" alt="" width="260" height="100" /></div>
          <div class="founder_media_text_cont">
          <h1>CarWoo Unveils a Better Way to Buy a Ca</h1>
          <p>&quot;the car-buying process is one of the few areas that remains</p>
          <p> untouched&quot; </p>
          <div class="read_full_article"><a href="#">
          Read full article
          </a></div> </div>
          </div>
          <div class="founder_cont_media">
          <div class="founder_image_media"><img src="images/media-covrage_image1.jpg" alt="" width="260" height="100" /></div>
          <div class="founder_media_text_cont">
          <h1>CarWoo: Haggling for a Great Car Price, Without <br />
            the Haggling Part</h1>
          <p>&quot;The process is anonymous, helping ward off aggressive salespeople&quot; </p>
          <div class="read_full_article"><a href="#">
          Read full article
          </a></div> </div>
          </div>
          <div class="founder_cont_media_fotter">
          <div class="founder_image_media"><img src="images/media-covrage_image2.jpg" alt="" width="260" height="100" /></div>
          <div class="founder_media_text_cont">
          <h1> CarWoo Takes the Cheap Suit Out Of Carczczczdsd Sales</h1>
          <p>&quot;CarOye...[connects] you with firm offers from dealers while preserving your privacy&quot; </p>
          <div class="read_full_article"><a href="#">
          Read full article
          </a></div> </div>
          </div>
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
