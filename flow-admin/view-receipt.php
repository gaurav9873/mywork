<?php require_once 'includes/init.php'; session_start();?>
<!DOCTYPE html>
<html>
<head>
<title>Print Invoice</title>
<link href="print/midday_receipt.css" rel="stylesheet">
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="print/printPreview.js"></script>
<script type="text/javascript">
        //~ $(function(){
            //~ $("#btnPrint").printPreview({
                //~ obj2print:'#wrapper',
                //~ width:'810'
            //~ });
        //~ });
     
     $(document).ready(function(){
		 $("#btnPrint").on('click', function(){
			 var print_id = $(this).data('print-id');
			 var payement_id = $(this).data('payement-id');
			 
			 if(print_id!=''){
				 $.ajax({
					 url:'action/print.php',
					 type:'post',
					 data:{action:'print_action', pid:print_id, payemtID:payement_id},
					 cache:false,
					 beforeSend:function(){
					 },
					 complete:function(){
					 },
					 success:function(resp){
						var data = JSON.parse(resp);
						if(data.msg=='true'){
							window.print();
						}
					 }
				 });
			 }
			 //window.print();
		 });
	 });
        
    </script>
</head>
<body>
<?php
	$obj =  new ConnectDb();
	$custom_obj = new CustomFunctions();
	
	$domain = (($_SESSION['shop_id'] == 3) ? NICOLA : FLEURDELIS);
	//$referer = $_SERVER["HTTP_REFERER"];
	$redirect = (($_REQUEST['type'] == 'allorder') ? 'product-orders.php' : 'upcoming-orders.php');
	
	$order_id = $custom_obj->DecryptClientId($_REQUEST['order_id']);
	$shop_id = $_SESSION['shop_id'];
	
	$delivery_address = $obj->get_row_by_id('op2mro9899_delivery_address', 'order_id', $order_id);
	$post_address = $delivery_address[0];
	$user_id = $post_address['user_id'];
	$customer_id = $post_address['customer_id'];
	$user_key = $post_address['user_key'];
	$card_message = $post_address['card_message'];
	$florist_instruction = $post_address['florist_instruction'];
	
	//Billing Address
	$user_billing_address = $obj->user_billing_address($user_id, $shop_id);
	$billing_address = json_decode($user_billing_address);
	if(empty($billing_address)){
		$cust_address = $obj->user_billing_address_custID($customer_id, $shop_id);
		$billing_address = json_decode($cust_address);
	}
	$post_bill = $billing_address[0];
	
	//Total Orders
	$invoice_order_detail = $obj->invoice_order_detail($order_id);
	$jShop = json_decode($invoice_order_detail);
	

	//Payment Detail
	
	$payement_detail = $obj->user_payment_detail($order_id);
	$post_payement_detail = json_decode($payement_detail); //print_r($post_payement_detail);
	$payment_id = $post_payement_detail[0]->payment_id;
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

<div id="wrapper">
  <table class="heading" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="2">
		<a href="javascript:void(0);" type="button" class="btn genrate-pdf" onclick="window.history.back();">Back</a>
        <a id="btnPrint" class="btn right genrate-pdf" data-print-id="<?php echo $order_id; ?>" data-payement-id="<?php echo $payment_id; ?>">Print</a>
        <p style="text-align:center; font-weight:bold;">Order Number : <?php echo $order_id; ?></p></td>
    </tr>
    <tr>
      <td width="50%" align="left" valign="top"><table width="95%" border="0" cellspacing="10" cellpadding="10" class="borderBx">
          <tr>
            <th> <h1 class="heading">Billing Address</h1>
              <p>Name: <?php echo $post_bill->user_first_name; ?> <br />
                Last Name: <?php echo $post_bill->user_last_name; ?> <br />
                Address Line 1: <?php echo $post_bill->primary_address; ?> <br />
                Address Line 2: <?php echo $post_bill->secondary_address; ?> <br />
                Town / City: <?php echo $post_bill->user_first_name; ?> <br />
                County: <?php echo $post_bill->user_county; ?> <br />
                Country: <?php echo $post_bill->user_country; ?> <br />
                Post Code: <?php echo $post_bill->user_postcode; ?> <br />
                Telephone Number: <?php echo $post_bill->user_phone; ?> <br />
                Email Address: <?php echo $post_bill->user_emailid; ?></p>
            </th>
          </tr>
        </table></td>
      <td width="50%" align="right" valign="top"><table width="100%" border="0" cellspacing="10" cellpadding="10" class="borderBx">
          <tr>
            <th> <h1 class="heading">Delivery Address</h1>
              <p>Name: <?php echo $post_address['user_name']; ?><br />
                Last Name: <?php echo $post_address['user_lname']; ?><br />
                Address Line 1: <?php echo $post_address['primary_address']; ?><br />
                Address Line 2: <?php echo $post_address['secondary_address']; ?><br />
                Town / City: <?php echo $post_address['city']; ?><br />
                County: <?php echo $post_address['county']; ?><br />
                Country: <?php echo $post_address['county']; ?><br />
                Post Code: <?php echo $post_address['post_code']; ?><br />
                Telephone Number: <?php echo $post_address['telephone_number']; ?><br />
                <!--Mobile Number: <?php //echo $post_address['mobile_number']; ?><br />--> 
                Email Address: <?php echo $post_address['email_address']; ?><br />
                <!--Delivery Date: <?php // echo $post_address['delivery_date']; ?>--></p>
            </th>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td colspan="2" valign="top" align="right" style="padding:0;"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="topBg">
          <tr>
            <td style="padding:10px; border:none;" width="33.33%">Order Date : <?php echo date("d-M-Y", strtotime($post_address['ordered_date'])); ?></td>
            <td style="padding:10px; border:none;" align="center" width="33.33%">Delivery Date : <?php echo date("d-M-Y", strtotime($post_address['delivery_date'])); ?></td>
            <td style="padding:10px; border:none;" align="right" width="33.33%">Currency : <?php echo $mc_currency; ?></td>
          </tr>
        </table></td>
    </tr>
  </table>
  <div id="content">
    <div id="invoice_body">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr class="bgBlue">
          <td style="width:10%;"><b>Sl. No.</b></td>
          <td style="width:35%;"><b>Product</b></td>
          <td style="width:15%;"><b>Quantity</b></td>
          <td style="width:15%; padding:0;"><b>Product Rate</b></td>
          <td style="width:15%; padding:0;"><b>Gift Rate</b></td>
          <td style="width:10%;"><b>Total</b></td>
        </tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:0;">
        <?php
				$count = 1;
				foreach($jShop as $value){
				
					$product_name = trim($value->product_name);
					$product_prices = $value->product_price;
					$product_qty_prices = $value->product_qty_price;
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
					$pdetailid = (($product_id == 0) ? $obj->get_ptoduct_id($product_name) : $product_id);
					
					//Large price
					$large_price = $obj->get_row_by_id('op2mro9899_products', 'pid', $product_id);
					$p_large_price = isset($large_price[0]['large_price']) ? $large_price[0]['large_price'] : '';
					$pprice = (($product_qty_prices == '') ? $product_prices : $product_qty_prices);
					
					$product_price = (($product_size == 'Large') ? $product_prices+$p_large_price : $product_prices);
					$product_qty_price = (($product_size == 'Large') ? $product_price*$product_qty : $product_qty_prices);
					
					//$pidss = $obj->get_ptoduct_id($product_name);
					if($flag == 'product'){
            ?>
        <tr>
          <td style="width:10%;"><?php echo $count; ?></td>
          <td style="width:35%; text-align:left; padding-left:10px;"><a target="_blank" href="<?php echo $domain; ?>?product_id=<?php echo $custom_obj->EncryptClientId($pdetailid); ?>"><?php echo $product_name; ?></a><br />
            Size : <?php echo $product_size; ?></td>
          <td class="mono" style="width:15%;"><?php echo $product_qty; ?></td>
          <td style="width:15%;" class="mono">£<?php echo $product_price; ?></td>
          <td style="width:15%;" class="mono"><?php //echo $product_price; ?></td>
          <td style="width:10%;" class="mono">£<?php echo $product_qty_price; ?></td>
        </tr>
        <?php } if($flag == 'gift'){ ?>
        <tr>
          <td style="width:10%;"><?php echo $count; ?></td>
          <td style="width:35%; text-align:left; padding-left:10px;"><?php echo $gift_name; ?><br /></td>
          <td class="mono" style="width:15%;"><?php echo $gift_quantity; ?></td>
          <td style="width:15%;" class="mono"></td>
          <td style="width:15%;" class="mono">£<?php echo $gift_price; ?></td>
          <td style="width:10%;" class="mono">£<?php echo $gift_qty_price; ?></td>
        </tr>
        <?php } ?>
        <?php $count++; } ?>
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
      </table>
    </div>
    <div id="invoice_total"> Total Amount :
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:5px 0 0;">
        <tr>
          <td style="text-align:left; padding-left:10px;"><?php echo $custom_obj->convert_number_to_words($mc_gross); ?>&nbsp;only </td>
          <td style="width:15%;"><?php echo $mc_currency; ?></td>
          <td style="width:15%;" class="mono">£<?php echo $mc_gross; ?></td>
        </tr>
      </table>
    </div>
    <div class="cardMessage"> Card Message : <?php echo $card_message; ?></div>
    <div class="cardMessage"> Note to Florist : <?php echo $florist_instruction; ?></div>
  </div>
</div>
</body>
</html>
