<?php 
	include("includes/conn.php");
	
	if(!isset($_SESSION['buyer']) || $_SESSION['buyer']==""){
		header("location:index.php");
		exit; 
	}
?>
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
    <?php include("header-small.php"); ?>
  </div>
  
  <div id="container" class="back2">
    <div class="container_inner backnoun">
      <div class="cont-shadow back2">
        <div id="body_cont_in">
        <div class="payment_gateway_inner">
      <div class="gateway_conten">
      <div class="gateway_conten_left"></div>
      <div class="gateway_conten_center">
      <h1>Compare Offers</h1>
      </div>
      <div class="gateway_conten_right"></div>
      <div class="clear"></div>
      </div> 
       <div class="payment_gateway_inner_cont">
       <div class="gateway_conten1">

      <div class="view-0ffer-detail">
      <div class="disply-car-box">
      <div class="img-box"><img src="images/compare-car-non.jpg" alt="" width="218" height="102" /></div>
      
  <div class="view-0ffer_button"><span><a href="#">Add A Cart</a></span></div>
  
  
  </div>

      <div class="disply-car-box">
      <div class="img-box"><img src="images/compare-car-non.jpg" alt="" width="218" height="102" /></div>
      
  <div class="view-0ffer_button"><span><a href="#">Add A Cart</a></span></div>
  
  
  </div>
  <div class="disply-car-box">
      <div class="img-box"><img src="images/compare-car-non.jpg" alt="" width="218" height="102" /></div>
      
  <div class="view-0ffer_button"><span><a href="#">Add A Cart</a></span></div>
  
  
  </div>
  <div class="disply-car-box" style="margin-left:0px;">
      <div class="img-box"><img src="images/compare-car-non.jpg" alt="" width="218" height="102" /></div>
      
  <div class="view-0ffer_button"><span><a href="#">Add A Cart</a></span></div>
  
  
  </div>
    
      </div> 
      <div class="campare-style-text"><img src="images/compare-cars.png" alt=""/></div>
      <div class="detail-table">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div class="table-title-bg">Pricing Information</div></td>
  </tr>
  <tr>
    <td><div class="table-title-sub">Sticker Price</div></td>
  </tr>
  <tr>
    <td><div class="table-cont-td"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%">...</td>
  </tr>
</table>
</div></td>
  </tr>
   <tr>
    <td><div class="table-title-sub">Sale Price (from the dealer)</div></td>
  </tr>
  <tr>
    <td><div class="table-cont-td"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%">...</td>
  </tr>
</table>
</div></td>
  </tr>
   <tr>
    <td><div class="table-title-sub">Savings</div></td>
  </tr>
  <tr>
    <td><div class="table-cont-td" style="border-bottom:none;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%">...</td>
  </tr>
</table>
</div></td>
  </tr>
 
</table>

      
      </div>
      <div class="detail-table">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div class="table-title-bg">Standard Equipment & Specs</div></td>
  </tr>
  <tr>
    <td><div class="table-title-sub">Trim Style</div></td>
  </tr>
  <tr>
    <td><div class="table-cont-td"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%">...</td>
  </tr>
</table>
</div></td>
  </tr>
   <tr>
    <td><div class="table-title-sub">Exterior Color</div></td>
  </tr>
  <tr>
    <td><div class="table-cont-td"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%">...</td>
  </tr>
</table>
</div></td>
  </tr>
   <tr>
    <td><div class="table-title-sub">Interior Color</div></td>
  </tr>
  <tr>
    <td><div class="table-cont-td"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%">...</td>
  </tr>
</table>
</div></td>
  </tr>
  
   <tr>
    <td><div class="table-title-sub">Fuel Economy</div></td>
  </tr>
  <tr>
    <td><div class="table-cont-td"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%">...</td>
  </tr>
</table>
</div></td>
  </tr>
   <tr>
    <td><div class="table-title-sub">Transmission</div></td>
  </tr>
  <tr>
    <td><div class="table-cont-td"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%">...</td>
  </tr>
</table>
</div></td>
  </tr>
   <tr>
    <td><div class="table-title-sub">VIN</div></td>
  </tr>
  <tr>
    <td><div class="table-cont-td"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%">...</td>
  </tr>
</table>
</div></td>
  </tr>
   <tr>
    <td><div class="table-title-sub">Mileage</div></td>
  </tr>
  <tr>
    <td><div class="table-cont-td" style="border-bottom:none;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="25%" class="table-td-left-bord"> ...</td>
     <td width="25%" class="table-td-left-bord"> ...</td>
     <td width="25%" class="table-td-left-bord"> ...</td>
     <td width="25%">...</td>
  </tr>
</table>
</div></td>
  </tr>
 
</table>

      
      </div>
      
      <div class="detail-table">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div class="table-title-bg">Included Options</div></td>
  </tr>
  <tr>
    <td><div class="table-title-sub">Option 1 out of 3</div></td>
  </tr>
  <tr>
    <td><div class="table-cont-td"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">... </td>
     <td width="25%" class="table-td-left-bord">... </td>
     <td width="25%">...</td>
  </tr>
</table>
</div></td>
  </tr>
   <tr>
    <td><div class="table-title-sub">Option 2 out of 3</div></td>
  </tr>
  <tr>
    <td><div class="table-cont-td"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%">...</td>
  </tr>
</table>
</div></td>
  </tr>
   <tr>
    <td><div class="table-title-sub">Option 2 out of 3</div></td>
  </tr>
  <tr>
    <td><div class="table-cont-td"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%">...</td>
  </tr>
</table>
</div></td>
  </tr>
  <tr>
    <td><div class="table-title-sub">Option 3 out of 3</div></td>
  </tr>
  <tr>
    <td><div class="table-cont-td" style="border-bottom:none;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">... </td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%">...</td>
  </tr>
</table>
</div></td>
  </tr>
 
</table>

      
      </div>
      
      <div class="detail-table">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div class="table-title-bg">Rebates & Incentives</div></td>
  </tr>
  <tr>
    <td><div class="table-title-sub">Sticker Price</div></td>
  </tr>
  <tr>
    <td><div class="table-cont-td" style="border-bottom:none;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%" class="table-td-left-bord">...</td>
     <td width="25%">...</td>
  </tr>
</table>
</div></td>
  </tr>
  
   
 
</table>

      
      </div>
      
       </div>
       </div>
     <div class="gateway_fotter"></div>  
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
</div>
</body>
</html>
