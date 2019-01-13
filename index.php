<?php 
	include("includes/conn.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Car Oye!</title>
<link rel="stylesheet" href="css/style.css" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>

</head>
<body>
<div id="wrapper">
  <!--help_wfix-->
  <div id="help_wfix"> <a href="#"><img src="images/help.png" alt=""  /></a></div>
  <!--help_wfix-->
  <div id="header_main">
    <?php include("header.php");?>
  </div>
  <div id="flash_banner">
    <div class="flash_inner">
      <div class="flash_wfix">
        <div class="flashArea">
          <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="FlashID" width="1002" height="358" id="FlashID">
            <param name="movie" value="banner_v4.swf" />
            <param name="quality" value="high" />
            <param name="wmode" value="transparent" />
            <param name="swfversion" value="6.0.65.0" />
            <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
            <param name="expressinstall" value="Scripts/expressInstall.swf" />
            <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
            <!--[if !IE]>-->
        	  <object type="application/x-shockwave-flash" data="banner_v4.swf" width="1002" height="358">
        	    <!--<![endif]-->
            <param name="quality" value="high" />
            <param name="wmode" value="transparent" />
            <param name="swfversion" value="6.0.65.0" />
            <param name="expressinstall" value="Scripts/expressInstall.swf" />
            <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
            <div>
              <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
              <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" /></a></p>
            </div>
            <!--[if !IE]>-->
      	    </object>
        	  <!--<![endif]-->
          </object>
        </div>
      </div>
    </div>
  </div>
  <div  id="nav_main">
    <?php include("nav_menu.php"); ?>
  </div>
  <div id="container">
    <div class="container_inner">
      <div class="cont-shadow">
        <div class="container_wfix">
          <div class="top-n"><img src="images/body_top-n.png" alt=""  /></div>
          <div class="mid-n">
            <div class="offered_date">
              <ul>
                <li><span>123456</span>
                    <h1>Savings offered till date</h1>
                </li>
                <li><span>123456</span>
                    <h1> Happy CarOye! customers</h1>
                </li>
                <li><span>123456</span>
                    <h1> Litres of fuel saved till date</h1>
                </li>
                <li><span>123456</span>
                    <h1> Time in hours saved till date</h1>
                </li>
              </ul>
            </div>
            <img src="images/spacer.gif" alt="" width="100%" height="2"  />
            <!--start of lft-->
            <div id="lft">
              <div class="dis_wrp" style="background-image:none;">
                <div class="img_div"><a href="#"><img src="images/thum_img2.jpg" alt=""  /></a></div>
                <div class="txt_div">
                  <h1>See How CarOye Works</h1>
                  <p>Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p>
                </div>
              </div>
              <div class="dis_wrp">
                <div class="img_div"><a href="#"><img src="images/thum_img3.jpg" alt=""  /></a></div>
                <div class="txt_div">
                  <h1>Help Me Decide the Car</h1>
                  <p>Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p>
                </div>
              </div>
              <div class="dis_wrp">
                <div class="img_div"><a href="#"><img src="images/thum_img4.jpg" alt=""  /></a></div>
                <div class="txt_div">
                  <h1>Car Buying Tips</h1>
                  <p>Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p>
                </div>
                <div class="followUs2">
                <ul>
                
               
                  <li><a href="#"><img src="images/blog-bying-tip.png" alt="" width="32" height="32"  /></a></li>
                    <li>Follow our blog</li>
                </ul>
              </div>
              </div>
              <div class="dis_wrp">
                <div class="img_div"><a href="#"><img src="images/group-car-buying.png" alt="" width="251" height="121"  /></a></div>
                <div class="txt_div">
                  <h1>Car Buying Tips</h1>
                  <p>Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p>
                </div>
              </div>
              <div class="dis_wrp">
                <div class="img_div"><a href="#"><img src="images/for-corporates.png" alt="" width="251" height="121"  /></a></div>
                <div class="txt_div">
                  <h1>Car Buying Tips</h1>
                  <p>Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p>
                </div>
              </div>
            </div>
            <!--end of lft-->
            <!--start of rgt-->
            <div id="rgt">
            <div class="happy-c-customers">
            <h1>Happy CarOye! customers</h1>
            <div class="testimonials"> <img src="images/thum_img1.jpg" alt="" width="281" height="154"  />
            
            <div class="text"><p>Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. </p></div>
            
            </div>
             <div class="socal_like"><a href="#"><img src="images/f-like.gif" alt=""  /></a><a href="#"><img src="images/t-like.gif" alt=""  /></a> </div>
           
            
            </div>
              <div class="brimg"></div>
              <div class="wall-Street">
                <h1>In the News</h1>
                <img src="images/abc-news.jpg" alt=""  /><img src="images/the-post-news.jpg" alt=""  /><img src="images/cnn-news.jpg" alt=""  /> </div>
              <div class="brimg"></div>
              <div class="guarantee"> <img src="images/gurarantee.png" alt="" width="134" height="107"  />
                  <p>If you ever feel like Groupon let you down, give us a call and we'll return your purchase — simple as that. If you ever feel like Groupon let you down, give us a</p>
              </div>
              <div class="brimg"></div>
              <div class="latest-facebook">
                <h1>Latest on Facebook</h1>
                <div class="fhold"> <img src="images/facebook.jpg" alt=""  /></div>
              </div>
              <div class="followUs">
                <ul>
                  <li>Follow Us:</li>
                  <li><a href="#"><img src="images/socal-icon-f.gif" alt=""  /></a></li>
                  <li><a href="#"><img src="images/socal-icon-t.gif" alt=""  /></a></li>
                  <li><a href="#"><img src="images/socal-icon-in.gif" alt=""  /></a></li>
                  <li><a href="#"><img src="images/blog.png" alt="" width="20" height="20"  /></a></li>
                </ul>
              </div>
            </div>
            <!--end of rgt-->
            <div class="clear"></div>
          </div>
          <div class="bot-n"><img src="images/body_bot-n.png" alt=""  /></div>
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
