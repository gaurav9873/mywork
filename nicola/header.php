<?php ob_start(); session_start(); include_once 'include/init.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Nicola Florist - buy and send flowers, bouquets and arrangements online for reliable delivery in Basingstoke and surrounding areas</title>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="css/font-awesome.css" />
<link rel="stylesheet" href="css/superslides.css">
<link rel="stylesheet" type="text/css" href="css/animate.css" />
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
<script src="javascripts/jquery.superslides.js" type="text/javascript" charset="utf-8"></script>
<script src="javascripts/wow.min.js"></script>
<script src="javascripts/theme.js" type="text/javascript"></script>
<script src="javascripts/custom-functions.js" type="text/javascript"></script>
<!-- must have -->

<link type="text/css" rel="stylesheet" href="lightbox/simplelightbox.css">
<script type="text/javascript" src="lightbox/simple-lightbox.js"></script>
<script>
$(document).ready(function() {
	var gallery = $('body .pgallery a').simpleLightbox();

});
</script>

<link href="css/lightslider.css" rel="stylesheet" type="text/css" />
<link href="css/responsive-tabs.css" rel="stylesheet" type="text/css" />
</head>
<body id="scroll">
<header class="headerSection">
  <div class="container-fluid">
    <div class="row topHeader">
      <div class="col-lg-5 col-sm-7 col-md-5"><span class="topMsg">Earliest Delivery Date -: <?php echo $obj->delivery_date_time(); ?></span></div>
      <div class="col-lg-7 col-sm-5 col-md-7 Login">
        <ul>
			<?php
			$basename = basename($_SERVER['SCRIPT_NAME']);
			$info = pathinfo($basename);
			$name = $info['filename'];
			$ext  = $info['extension'];
			$cat_menu = API_PATH.'cat-menu/'.SITE_ID.'';
			$jdata = $obj->getCurl($cat_menu);
			if(isset($_SESSION['key'])){
				echo '<li><a href="logout" title="Logout">Logout</a></li>';
			}else{
				echo '<li><a href="login" title="Login">Login</a></li>';
			}
			if(isset($_SESSION['key'])){
				echo '<li><a href="my-account" title="My account"><i class="fa fa-user"></i></a></li>';
			}
			echo '<li><a href="cart" title="Shopping cart"><i class="fa fa-shopping-cart"></i> '.$obj->countProduct().'</a></li>';
			?>
        </ul>
        
        <ul class="callDetail mt">
          <li><strong>(Mon - Sat :9am to 5.30pm)</strong></li> 
        </ul>
        
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="fixed-header">
          <div class="col-lg-2 col-lg-2 col-md-2 col-sm-2 col-xs-6">
            <div class="logo"><a href="<?php echo SITE_URL; ?>"><img src="images/logo.png" alt="Logo" class="img-responsive" /></a></div>
          </div>
          <div class="col-lg-5 col-md-4 col-sm-5">
            <h1 class="quote">Delivery <span>Throughout</span> The UK</h1></div>
          <div class="col-lg-5 col-md-6 col-sm-5 col-xs-6">
            <ul class="callDetail">
              <!--<li><i class="fa fa-phone-square"></i>+44 08003165184</li>-->
              <li><i class="fa fa-phone-square"></i>+44 1256354771</li>
              <li></li>
              <li><a href="advance-search" title="Product search"><i class="fa fa-search"></i>Product Search</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <nav class=" navbar navbar-inverse">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        </div>
        <div class=" navi collapse navbar-collapse" id="myNavbar">
          <ul class="nav navbar-nav">
            <li <?php echo ($name == "index") ? "class='active'" : ""; ?>><a href="<?php echo SITE_URL; ?>">Home</a></li>
			<?php
			$res = isset($_REQUEST['category_id']) ? $_REQUEST['category_id'] : '';
			foreach($jdata->cat_menu as $cat_val){ 
				$cat_ids = $obj->EncryptClientId($cat_val->cat_id);
				$chk_active = (($cat_ids == $res) ? 'active' : '');
				$category_name = $cat_val->category_name;
				
				$child_cat_menu = API_PATH.'child-cat-menu/'.$cat_val->cat_id.'';
				$jqdata = $obj->getCurl($child_cat_menu);
				$count_child_category = count($jqdata->child_categories);
				$product_page = (($count_child_category == 0) ? 'category-products' : 'categories');
				
				$count_child_category = API_PATH.'child-category/'.$cat_val->cat_id.'';
				$get_curl = $obj->getCurl($count_child_category);
				$count_cat = count($get_curl->child_categories);
				$product_pages = (($count_cat == 0) ? 'category-products' : 'categories');
			?>
            <li class="<?php echo $chk_active; ?>"><a href="<?php echo $product_pages; ?>?category_id=<?php echo $cat_ids; ?>"><?php echo $category_name; ?></a>
			<?php if(!empty($jqdata->child_categories)){?> 
			 <span class="arrowso"></span>
              <ul class="dropdown-menu">
				<?php 
				foreach($jqdata->child_categories as $childval){
					$child_cat_id = $obj->EncryptClientId($childval->cat_id);
					$child_category_name = htmlspecialchars($childval->category_name);
				?>
                <li><a href="cat-product?cat_id=<?php echo $child_cat_id; ?>"><?php echo $child_category_name; ?></a></li>
                <?php } ?>
              </ul>
              <?php } ?>
            </li>
            <?php } ?>
            <li <?php echo ($name == "about-us") ? "class='active'" : ""; ?>><a href="about-us">About&nbsp;Us</a></li>
            <li <?php echo ($name == "gallery") ? "class='active'" : ""; ?>><a href="gallery">Gallery</a></li>
            <li <?php echo ($name == "contact-us") ? "class='active'" : ""; ?>><a href="contact-us">Contact&nbsp;Us</a></li>
          </ul>
        </div>
      </nav>
    </div>
  </div>
</header>
<div class="clearfix"></div>
