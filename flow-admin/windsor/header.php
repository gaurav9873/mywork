<?php session_start(); include_once 'include/init.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Send Online Flowers to UK England | Best Florist Windsor Berkshire Maidenhead Slough</title>


<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="css/font-awesome.css" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/responsive.css" />
<script src="javascripts/jquery.min.js"></script>

<script src="javascripts/bootstrap.js"></script>
<link href="css/lightslider.css" rel="stylesheet" type="text/css" />
<script src="javascripts/lightslider.js"></script>

<!-- slider code --> 
<!--[if IE 7]>
	<link href="css/style_ie7.css" rel="stylesheet" type="text/css" />
<![endif]-->

<link href="css/topBarPromoter.css" rel="stylesheet" type="text/css" />

<!-- must have -->
<link href="css/bannerscollection_zoominout.css" rel="stylesheet" type="text/css">

<script src="javascripts/jquery-ui.min.js"></script>
<script src="javascripts/jquery.ui.touch-punch.min.js" type="text/javascript"></script>
<script src="javascripts/bannerscollection_zoominout.js" type="text/javascript"></script>
<script src="javascripts/theme.js" type="text/javascript"></script>
<!-- must have -->

<script>	
	
	$(window).load(function(){
		$('.BannerBox').find('img').each(function(){
			var imgClass = (this.width/this.height > 1) ? 'wide' : 'tall';
			$(this).addClass(imgClass);
		});
	});
	
	jQuery(function() {
		jQuery('#bannerscollection_zoominout_opportune').bannerscollection_zoominout({
			skin: 'opportune',
			responsive:true,
			width100Proc:true,
			width:1920,
			height:600,
			circleRadius:8,
			circleLineWidth:4,
			circleColor:"#ffffff", //849ef3
			circleAlpha:50,
			behindCircleColor: "#000000",
			behindCircleAlpha: 20,
			thumbsWrapperMarginTop:25,
			scrollSlideDuration:0.8,
			scrollSlideEasing:'easeOutQuad',
			fadeSlides:true			
		});	
	});
	
	
	$(document).ready(function() {
		$("#content-slider").lightSlider({
			loop:true,
			keyPress:true,
			slideMargin:40,
			item:6
		});
		$('#image-gallery').lightSlider({
			gallery:true,
			item:1,
			thumbItem:5,
			slideMargin: 0,
			speed:500,
			auto:true,
			loop:true,
			onSliderLoad: function() {
				$('#image-gallery').removeClass('cS-hidden');
			}  
		});
		$('.shareLinks a').click(function(){
			$('.shareLinks ul').toggle();
		});
		
		$("#searchPin").click(function(){
			$(".ppSelect").addClass("addressSelect");
		});
		
		
		$(document).bind( "mouseup touchend", function(e){
			var container = $('.searchField');
			var dcontainer = $('.track');
			if(!dcontainer.has(e.target).length && !container.is(e.target) && container.has(e.target).length === 0){
				container.slideUp();
			}
		});
		
		
		
		$(".clickOnSearch").click(function(){		
			$('.searchField').show();
		});
		
		$("#searchProduct").on("click", function(){
			var search_string = $(".search-txt").val();
			if (search_string != undefined && search_string != null) {
				window.location = 'search.php?sq=' + search_string;
			}
		});
		

		//~ $('.nav').on('click', 'li', function(){
			//~ $('.nav li').removeClass('active');
			//~ $(this).addClass('active');
		//~ });
		
	});
	
</script>
<script src="javascripts/bootstrap.js"></script>
<link href="css/lightslider.css" rel="stylesheet" type="text/css" />
<link href="css/responsive-tabs.css" rel="stylesheet" type="text/css" />
 </head>
<body>
<header class="headerSection">
	<div class="container-fluid">
    	<div class="row topHeader">
             <div class="col-lg-5 col-sm-7 col-md-5"><span class="topMsg">Earliest Delivery Date -: Tuesday 15 November</span></div>
             <div class="col-lg-7 col-sm-5 col-md-7 Login">
                <ul>
					<?php
						$basename = basename($_SERVER['SCRIPT_NAME']);
						//~ $page_menu = API_PATH.'page-menu';
						//~ $page_name = $obj->getCurl($page_menu);
						//~ print_r($page_name);
						
						$cat_menu = API_PATH.'cat-menu';
						$jdata = $obj->getCurl($cat_menu);
					
					
						if(isset($_SESSION['key'])){
							echo '<li><a href="logout.php">Logout</a></li>';
						}else{
							echo '<li><a href="login.php">Login</a></li>';
						}
						if(isset($_SESSION['key'])){
							echo '<li><a href="my-account.php"><i class="fa fa-user"></i></a></li>';
						}
						echo '<li><a href="cart.php"><i class="fa fa-shopping-cart"></i> '.$obj->countProduct().'</a></li>';
						echo '<li class="clickOnSearch"><a href="javascript:void(0);"><i class="fa fa-search"></i></a>
						
									<div class="searchField">
										<div class="form-group track">
											<div class="input-group">
												<input name="searchBy" id="searchBy" class="form-control search-txt" placeholder="Search" type="text">
												<div class="input-group-addon" id="searchProduct"><a href="javascript:void(0);">Go</a></div>
											</div>
										</div>
									</div>
						
						</li>';
                    ?>
                </ul>	
             </div>
        </div>
        <div class="row">
        	<div class="col-lg-12">
            	<div class="fixed-header">
               		 <div class="col-lg-2 col-lg-2 col-md-2 col-sm-3"><div class="logo"><a href="index.php"><img src="images/logo.png" alt="Logo" class="img-responsive" /></a></div></div>
                     <div class="col-lg-5 col-md-4 col-sm-9">
                     	<h1 class="quote">Delivery <span>Throughout</span> The UK</h1>
                        <samp>Order Now</samp>
                     </div>
                     <div class="col-lg-5 col-md-6 col-sm-12">
                     	<ul class="callDetail">
                        	<li><i class="fa fa-phone-square"></i>+44 1628 675566</li>
                            <li><strong>(Mon - Sat :9am to 5.30pm)</strong></li>
                            <li><a href="advance-search.php"><i class="fa fa-search"></i>Product Search</a></li>
                        </ul>
                     </div>
                </div>
            </div>
        </div>
        <div class="row">               
            <nav class=" navbar navbar-inverse">
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>                        
                  </button>
                </div>
                <div class=" navi collapse navbar-collapse" id="myNavbar">
                  <ul class="nav navbar-nav">
					   <li <?php echo ($basename == "index.php") ? "class='active'" : ""; ?>><a href="index.php">Home</a></li>
					   <li <?php echo ($basename == "about-us.php") ? "class='active'" : ""; ?>><a href="about-us.php">About&nbsp;Us</a></li>                    
					   <li <?php echo ($basename == "contact-us.php") ? "class='active'" : ""; ?>><a href="contact-us.php">Contact&nbsp;Us</a></li>
					   <li <?php echo ($basename == "gallery.php") ? "class='active'" : ""; ?>><a href="gallery.php">Gallery</a></li>
						<?php
							$res = isset($_REQUEST['cat_id']) ? $_REQUEST['cat_id'] : '';
							foreach($jdata->cat_menu as $cat_val){
								$cat_ids = $obj->EncryptClientId($cat_val->cat_id);
								$chk_active = (($cat_ids == $res) ? 'active' : '');
								$category_name = $cat_val->category_name;
								 echo '<li class="'.$chk_active.'"><a href="cat-product.php?cat_id='.$cat_ids.'">'.$category_name.'</a></li>';
							}
						?>
                 </ul>      
                </div>
            </nav>
        </div>
    </div>
</header>
<div class="clearfix"></div>
