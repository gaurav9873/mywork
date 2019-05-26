<?php require_once 'includes/init.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Print Invoice</title>
<link href="http://fleurdelisflorist.co.uk/florist-admin/print/receipt_new.css" rel="stylesheet">
</head>
<body style="background:url(http://fleurdelisflorist.co.uk/florist-admin/images/FDL_letterhead.jpg) no-repeat; background-position:bottom center left; background-size:100% auto;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="10" style="border-bottom:2px solid #ccc;">
        <tr>
          <td width="60%" align="left" valign="top"><img src="images/logoFleur.png" width="" height=""></td>
          <td width="40%" align="right" valign="top" style="font:100% Raleway;"><p style="margin:0 0 15px;">28 King Street<br>
              Maidenhead SL6 1EF</p>
            <p style="margin:0 0 15px;"><strong style="color:#60bb46;">Telephone:</strong><br>
              01628 675566<br>
              01628 624903<br>
              01628 776336</p>
            <p style="margin:0 0 15px;"><strong style="color:#60bb46;">email:</strong> <a style="color:#000; text-decoration:none;" href="mailto:info@fleurdelisflorist.co.uk" target="_blank">info@fleurdelisflorist.co.uk</a><br>
              <a style="color:#000; text-decoration:none;" href="https://www.fleurdelisflorist.co.uk" target="_blank">www.fleurdelisflorist.co.uk</a></p></td>
        </tr>
      </table></td>
  </tr>
  <tr>
	  
	  <?php
		$obj =  new ConnectDb();
		$custom_obj = new CustomFunctions();
		
		$order_id = $custom_obj->DecryptClientId($_REQUEST['order_id']);
		
		$delivery_address = $obj->get_row_by_id('op2mro9899_delivery_address', 'order_id', $order_id);
		$post_address = $delivery_address[0];
		$user_id = $post_address['user_id'];
		$user_key = $post_address['user_key'];
		$card_message = $post_address['card_message'];
		$florist_instruction = $post_address['florist_instruction'];

		//Billing Address
		$user_billing_address = $obj->user_billing_address($user_id);
		$billing_address = json_decode($user_billing_address);
		$post_bill = $billing_address[0];

		//Total Orders
		$invoice_order_detail = $obj->invoice_order_detail($order_id);
		$jShop = json_decode($invoice_order_detail);

		//Payment Detail

		$payement_detail = $obj->user_payment_detail($order_id);
		$post_payement_detail = json_decode($payement_detail);
		$item_number = $post_payement_detail[0]->item_number;
		$payment_status = $post_payement_detail[0]->payment_status;
		$order_status = $post_payement_detail[0]->order_status;
		$mc_gross = $post_payement_detail[0]->mc_gross;
		$mc_currency = $post_payement_detail[0]->mc_currency;
		$txn_id = $post_payement_detail[0]->txn_id;
		$delivery_charges = $post_payement_detail[0]->delivery_charges;
		$discount_offer = $post_payement_detail[0]->discount_offer;
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  ?>
	  
    <td align="left" valign="top" style="border-bottom:2px solid #ccc; padding-bottom:100px;">
      <table class="heading" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody>
          <tr>
            <td width="50%" valign="top" align="left"><table class="borderBx" width="95%" cellspacing="10" cellpadding="10" border="0">
                <tbody>
                  <tr>
                    <th style="padding:15px 25px;"> <h1 class="heading">Billing Address</h1>
                      <p style="font-size:70%; line-height:55px;">Name: <?php echo $post_bill->user_first_name; ?> <br>
                        Last Name: <?php echo $post_bill->user_last_name; ?> <br>
                        Address Line 1: <?php echo $post_bill->primary_address; ?> <br>
                        Address Line 2: <?php echo $post_bill->secondary_address; ?> <br>
                        Town / City: <?php echo $post_bill->user_first_name; ?> <br>
                        County: <?php echo $post_bill->user_county; ?> <br>
                        Country: <?php echo $post_bill->user_country; ?> <br>
                        Post Code: <?php echo $post_bill->user_postcode; ?> <br>
                        Telephone Number: <?php echo $post_bill->user_phone; ?> <br>
                        Email Address: <?php echo $post_bill->user_emailid; ?></p>
                    </th>
                  </tr>
                </tbody>
              </table></td>
            <td width="50%" valign="top" align="right"><table class="borderBx" width="100%" cellspacing="10" cellpadding="10" border="0">
                <tbody>
                  <tr>
                    <th style="padding:15px 25px;"> <h1 class="heading">Delivery Address</h1>
                      <p style="font-size:70%; line-height:55px;">Name: <?php echo $post_address['user_name']; ?><br>
                        Last Name: <?php echo $post_address['user_lname']; ?><br>
                        Address Line 1: <?php echo $post_address['primary_address']; ?><br>
                        Address Line 2: <?php echo $post_address['secondary_address']; ?><br>
                        Town / City: <?php echo $post_address['city']; ?><br>
                        County: <?php echo $post_address['county']; ?><br>
                        Country: <?php echo $post_address['county']; ?><br>
                        Post Code: <?php echo $post_address['post_code']; ?><br>
                        Telephone Number: <?php echo $post_address['telephone_number']; ?><br>
                        Email Address: <?php echo $post_address['email_address']; ?><br>
                      </p>
                    </th>
                  </tr>
                </tbody>
              </table></td>
          </tr>
          <tr>
            <td colspan="2" style="padding:0;" valign="top" align="right"><div id="invoice_body">
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                  <tbody>
                    <tr style="background:#eee;">
                      <td style="width:33.33%;" align="left" valign="middle"><b style="color:#60bb46;">Order Date : <?php echo date("d-M-Y", strtotime($post_address['ordered_date'])); ?></b></td>
                      <td style="width:33.33%;" align="center" valign="middle"><b style="color:#60bb46;">Delivery Date : <?php echo date("d-M-Y", strtotime($post_address['delivery_date'])); ?></b></td>
                      <td style="width:33.33%;" align="right" valign="middle"><b style="color:#60bb46;">Currency : <?php echo $mc_currency; ?></b></td>
                    </tr>
                  </tbody>
                </table>
              </div></td>
          </tr>
        </tbody>
      </table>
      <div id="content">
        <div id="invoice_body">
          <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody>
              <tr style="background:#eee;">
                <td style="width:10%;"><b>Sl. No.</b></td>
                <td style="width:35%;"><b>Product</b></td>
                <td style="width:15%;"><b>Quantity</b></td>
                <td style="width:15%; padding:0;"><b>Product Rate</b></td>
                <td style="width:15%; padding:0;"><b>Gift Rate</b></td>
                <td style="width:10%;"><b>Total</b></td>
              </tr>
            </tbody>
          </table>
          <table style="margin:0;" width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody>
             
             
				<?php
				$count = 1;
				foreach($jShop as $value){  

				$product_name = $value->product_name;
				$product_price = $value->product_price;
				$product_qty_price = $value->product_qty_price;
				$product_qty = $value->product_qty;
				$product_id = $value->product_id;
				$product_size = $value->product_size;
				$product_code = $value->product_code;
				$gift_name = $value->gift_name;
				$gift_price = $value->gift_price;
				$gift_qty_price = $value->gift_qty_price;
				$gift_quantity = $value->gift_quantity;
				$gift_id = $value->gift_id;
				$flag = $value->flag;
				if($flag == 'product'){
				?>
             
              <tr>
                <td style="width:10%;"><?php echo $count; ?></td>
                <td style="width:35%; text-align:left; padding-left:10px;">
					<a target="_blank" href="#"><?php echo $product_name; ?></a><br>
                  Size : <?php echo $product_size; ?></td>
                <td class="mono" style="width:15%;"><?php echo $product_qty; ?></td>
                <td style="width:15%;" class="mono">£<?php echo $product_price; ?></td>
                <td style="width:15%;" class="mono">£<?php echo $product_price; ?></td>
                <td style="width:10%;" class="mono">£<?php echo $product_qty_price; ?></td>
              </tr>
              <?php } if($flag == 'gift'){ ?>
              <tr>
                <td style="width:10%;">1</td>
                <td style="width:35%; text-align:left; padding-left:10px;">
					<a target="_blank" href="#"><?php echo $gift_name; ?></a><br>
                  Size : </td>
                <td class="mono" style="width:15%;"><?php echo $gift_quantity; ?></td>
                <td style="width:15%;" class="mono">£<?php echo $gift_price; ?></td>
                <td style="width:15%;" class="mono">£<?php echo $gift_qty_price; ?></td>
                <td style="width:10%;" class="mono">&nbsp;</td>
              </tr>
              <?php } $count++;}?>
              <tr>
                <td colspan="4"></td>
                <td>Shipping :</td>
                <td class="mono">£<?php echo $delivery_charges; ?></td>
              </tr>
              
              <tr>
                <td colspan="4"></td>
                <td>Discount :</td>
                <td class="mono"><?php echo $discount_offer; ?></td>
              </tr>
              
              <tr>
                <td colspan="4"></td>
                <td>Total :</td>
                <td class="mono">£<?php echo $mc_gross; ?></td>
              </tr>
              
              
              
            </tbody>
            
            
          </table>
        </div>
        <div id="invoice_total"> Total Amount :
          <table style="margin:5px 0 0;" width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody>
              <tr>
                <td style="text-align:left; padding-left:10px;"><?php echo $custom_obj->convert_number_to_words($mc_gross); ?>&nbsp;only </td>
                <td style="width:15%;">GBP</td>
                <td style="width:15%;" class="mono">£<?php echo $mc_gross; ?></td>
              </tr>
            </tbody>
          </table>
        </div>
        <p style="font-size:70%; padding:15px 20px; border:1px dotted #ccc; line-height:55px;"> Card Message : <?php echo $card_message; ?></p>
        <p style="font-size:70%; margin-top:25px; padding:15px 20px; border:1px dotted #ccc;"> Note to Florist : <?php echo $florist_instruction; ?></p>
      </div>
    </td>
  </tr>
  <tr>
    <td align="left" valign="top" height=""><table width="100%" border="0" cellspacing="0" cellpadding="10" style="margin-top:135px;">
        <tr>
          <td width="60%" align="left" valign="top">&nbsp;</td>
          <td width="40%" align="right" valign="top" style="font:70% Raleway; line-height:55px;"><p style="margin:0 0 5px;">Siddhi Enterprises Ltd<br>
              Company Number - 08097082<br>
              VAT Number - 138587277</p></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
