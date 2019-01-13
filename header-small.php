<div class="header_inner">
      <div class="hadder_payment_gateway">
        <div class="gateway_logo_lp"><a href="<?php echo SITEPATH; ?>"><img src="images/gateway_logo.png" alt="" width="148" height="36" /></a></div>
       <?php if(isset($_SESSION['buyer'])){?>
	   <div class="gateway_logo_rp">
			  <div class="your_cars"><a href="#">Your cars</a></div>
			  <div class="space6"></div>
			   <div class="compare_offers"><a href="compare.php">Compare offers</a></div>
			   <div class="space6"></div>
			   <div class="dealerd "><a href="#">Rate the dealerd </a></div>
			   <div class="space6"></div>
				<div class="account "><a href="buyeraccount.php">Your account</a></div>
				<div class="clear"></div>
        </div>
		<?php } ?>
      </div>
    </div>