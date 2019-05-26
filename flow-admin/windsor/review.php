<?php include_once 'header.php'; 

if(empty($_SESSION['key'])){
	header("location:login.php");
}

if(empty($_SESSION['cart']['products'])){
	header("location:index.php");
}


$msg = '';
//Check Coupon
if ( isset( $_POST['submit'] ) && $_POST['submit'] == "Add" ){
	$c_code = $_POST['c_code'];
	$_SESSION['c_code'] = $c_code;
	//header("location:review.php");
}


$couponCodes = isset($_SESSION['c_code']) ? $_SESSION['c_code'] : '';
$c_cat_id = '';
$diffrence = '';
$c_product_code = '';
if($couponCodes!=''){
	$coupon_api = API_PATH.'check-coupon/'.$couponCodes.'';
	$coupon_data = $obj->getCurl($coupon_api);
	if(!empty($coupon_data->coupon_data[0])){
		$cdata = $coupon_data->coupon_data[0];
		$c_cat_id = isset($cdata->cat_id) ? $cdata->cat_id : '';
		$c_discount_offer = $cdata->discount_offer;
		$c_min_order = $cdata->min_order;
		$c_product_code = $cdata->product_code;
		$valid_from = $cdata->valid_from;
		$valid_upto = $cdata->valid_upto;
		$total_coupons = $cdata->total_coupons;
		$coupon_code = $cdata->coupon_code;
		//$_SESSION['discount_offer'] = $c_discount_offer;
	}else{
		$msg = "<font style='color:#ff6347;'>Not a valid coupon</font>";
		$_SESSION['discount_offer'] = 0;
		unset($_SESSION['c_code']);
	}
}

$discount_offer = isset($_SESSION['discount_offer']) ? $_SESSION['discount_offer'] : '0';


//User Billing Address
$key = $_SESSION['key'];
$billing_address = API_PATH.'user-data/'.$key.'';
$data = $obj->getCurl($billing_address);
$res = $data->user_record;


//All Gifts
$array_val = array_count_values($_SESSION['cart']['gift']);
ksort($array_val);


//All Products
$product_array = array();
foreach($_SESSION['cart']['products'] as $size=>$valarr){
	foreach($valarr as $data){
		$product_attributes = array('size'=> $size,'product_id'=> $data['product_id'],'quantity'=> $data['quantity']);
		array_push($product_array,$product_attributes);
	}	
}

$post_codes = $_SESSION['post_code'];

$inner_pcode = substr($post_codes,-3);

//check shipment price
$delpcode = strtoupper($_SESSION['post_code']);
$pcode = str_replace(' ', '', $delpcode);
$post_code_api = API_PATH.'check-postcode/'.trim($pcode).'';
$chk_pcode = $obj->getCurl($post_code_api);

foreach($chk_pcode->shipping_cost as $pkey => $pval){
	$inner_post_code = $pval->inner_post_code;
	if($inner_post_code == $inner_pcode){
		$location_name = $pval->location_name;
		$delivery_charges = $pval->delivery_charges;
	}else{
		$location_name = $pval->location_name;
		$delivery_charges = $pval->delivery_charges;
	}
	
}
//$shipping_cost = isset($chk_pcode->shipping_cost[0]) ? $chk_pcode->shipping_cost[0] : '';
$delivery_charges = isset($delivery_charges) ? $delivery_charges : '0';
$_SESSION['delivery_charges'] = $delivery_charges;


$args = array('post_code' => $_SESSION['post_code'], 'user_prefix' => $_SESSION['user_prefix'], 'user_fname' => $_SESSION['user_fname'], 'user_lname' => $_SESSION['user_lname'],
		'user_mobile' => $_SESSION['user_mobile'], 'user_telephone' => $_SESSION['user_telephone'], 'user_fax' => $_SESSION['user_fax'], 'delivery_email' => $_SESSION['delivery_email'],
		'user_city' => $_SESSION['user_city'], 'primary_address' => $_SESSION['primary_address'], 'secondary_address' => $_SESSION['secondary_address'],
		'user_county' => $_SESSION['user_county'], 'country' => $_SESSION['country'], 'delivery_date' => $_SESSION['delivery_date'],
		'user_card_msg' => $_SESSION['user_card_msg'], 'user_notes' => $_SESSION['user_notes'], 'user_id' => $_SESSION['user_id'],
		'email' => $_SESSION['email'], 'first_name' => $_SESSION['first_name'], 'last_name' => $_SESSION['last_name'], 'key' => $_SESSION['key']
);


//Paypal Submit
if ( isset( $_POST['submit'] ) && $_POST['submit'] == "paySubmit" ){
	
	$post_data = array('product' => $product_array, 'gift' => $array_val, 'delivery_address' => $args, 'delivery_charges' => $_SESSION['delivery_charges'], 
						'discount_offer' => $discount_offer);
	$order_url = API_PATH.'product-order';
	$req = $obj->httpPost($order_url, $post_data);
	$_SESSION['orderID'] = $req->order[0]->order_id;
	$urls = "www.sandbox.paypal.com";
	$baseUrl = "http://54.191.172.136:82";
	$ramount = $_SESSION['grand_total'];


	echo "<form id=\"paypal_form\" action=\"https://".$urls."/cgi-bin/webscr\" method=\"post\" name=\"frm\">
		<input type=\"hidden\" name=\"cmd\" value=\"_xclick\">
		<input type=\"hidden\" name=\"business\" value=\"gaurav.mysql@gmail.com\">
		<input type=\"hidden\" name=\"item_name\" value=\"Flowers\">
		<input type=\"hidden\" name=\"amount\" value=".$ramount.">
		<input type=\"hidden\" name=\"notify_url\" value=\"$baseUrl/florist-windsor/payment.php\" />
		<input type=\"hidden\" name=\"return\" value=\"$baseUrl/florist-windsor/success.php\" />
		<input type=\"hidden\" name=\"item_number\" value=". $req->order[0]->order_id.">
		<input type=\"hidden\" name=\"currency_code\" value=\"GBP\">
		<input type=\"hidden\" name=\"rm\" value=\"2\" />
	</form><script type='text/javascript'>document.getElementById('paypal_form').submit(); </script>";
}
?>
<script type='text/javascript'>
$(document).ready(function(){
	$("#coupons").on('submit', function(evt){
		var c_code = $(".c_code").val();
		if(c_code==''){
			$(".c_code").css({"border-color": "#ff6347", "border-width":"2px", "border-style":"solid"});
			return false;
		}
	});
});
</script>

<section>
  <div class="container-fluid">     
    <div class="row">
    <div class="col-lg-3"></div>
      <div class="col-lg-6">
        <div class="reviewDetail">
          <div class="flowerRevDetail">
            <section>
              <ul>

                <li class="TotalPrice"><strong>Product</strong> 
					<?php
						$product_price = array();
						$gift_price = array();
						$discount_price = array();
						$panme = array();
						foreach($product_array as $key => $val){
							
							$product_size = strtolower($val['size']);
							$pid = $obj->DecryptClientId($val['product_id']);
							$qty = $val['quantity'];
								
														
							//Product Api
							$product_api = API_PATH.'product_cart/'.$pid.'';
							$post_product = $obj->getCurl($product_api);  
							//print_r($post_product);	
							$productPid = $post_product->pid;
							
							$product_name = $post_product->product_name;
							$regular_price = $post_product->regular_price;
							$large_price = $post_product->large_price;
							$disscount_price = $post_product->disscount_price;
							$product_code = $post_product->product_code;
							$img_id = $post_product->img_id;
							$thumbnail_path = $post_product->thumbnail_path;
							
							//Check Coupon Exist
							$product_cat_id = explode(',', $post_product->cat_id);
							if(in_array($c_cat_id, $product_cat_id)){
								$cprice = (($disscount_price <> '') ? $disscount_price : $regular_price);
								$final_prices = (($product_size == 'large') ? $cprice+$large_price : $cprice);
								$quantity_prices = $final_prices*$qty;
								$pro_name = $post_product->product_name;
								array_push($discount_price, $quantity_prices);
								array_push($panme, $pro_name);
								
							}elseif($product_code == $c_product_code){
								$cprice = (($disscount_price <> '') ? $disscount_price : $regular_price);
								$final_prices = (($product_size == 'large') ? $cprice+$large_price : $cprice);
								$quantity_prices = $final_prices*$qty;
								$pro_name = $post_product->product_name;
								array_push($discount_price, $quantity_prices);
								array_push($panme, $pro_name);
							}else{}

							$price = (($disscount_price <> '') ? $disscount_price : $regular_price);
							$final_price = (($product_size == 'large') ? $price+$large_price : $price);
							$quantity_price = $final_price*$qty;
							array_push($product_price, $quantity_price);
											
							echo '<span>'.$product_name.'</span>&nbsp; &nbsp;<span class="qunt">(1 X '.$qty.')</span><br />';
						}
						
						//Calculate Discount Price
						$discount_sum = array_sum($discount_price);
						if(!empty($discount_price)){
							if($c_min_order <= $discount_sum){
								$discount = isset($c_discount_offer)? $c_discount_offer : '0';
								$discounted_total = $discount_sum - ($discount_sum * ($discount/100));
								$diffrence = $discount_sum - $discounted_total;
								$_SESSION['discount_offer'] = $discount;
								if($discounted_total!='0'){
									$msg = "<font style='color:#228B22;'>Coupon has been applied on:<strong>".$obj->implodeArrayKeys($panme)."</strong><br />
											Discount availed is £".$diffrence."</font>";								 
								}
							}else{
								unset($_SESSION['c_code']);
								$_SESSION['discount_offer'] = 0;
								$msg = "<font style='color:#ff6347;'>The minimum order of these products <strong>".$obj->implodeArrayKeys($panme)."</strong> 
										should be ".$c_min_order." to apply the coupon</font>";
							}
					}
						
					?>
				</li>
                <li class="TotalPrice"><strong>Gift: </strong> 
					<?php
						//All Gifts
						foreach($array_val as $keys => $value){
						
							$gift_url = API_PATH.'gifts/'.$keys.'';
							$json = $obj->getCurl($gift_url);
							$gifts = $json->post_gift;
							
							
							foreach($gifts as $k => $gift_val){
								$gift_name = $gift_val->gift_name;
								$gift_regular_price = $gift_val->regular_price;
								$gift_disccount_price = $gift_val->disccount_price;
								$short_note = $gift_val->short_note;
								$description = $gift_val->description;
								$gift_thumb = $gift_val->thumbnail_path;
								$total_qty = $value*$gift_regular_price;
								array_push($gift_price, $total_qty);
								echo '<span>'.$gift_name.'</span>&nbsp; &nbsp;<span class="qunt">(1 X '.$value.')</span><br />';
							}
						}
						
						$product_cart = array_sum($product_price);
						$gift_cart = array_sum($gift_price);
						$total_price = $product_cart+$gift_cart;
					?>
				</li>
                <li class="TotalPrice"><strong>Delivery Date <span><?php echo str_replace('-', '/', $_SESSION['delivery_date']); ?></span></strong></li>
                <li class="TotalPrice"><strong>Sub. Total <span>£<?php echo $total_price; ?></span></strong></li>
                <?php 
					if($delivery_charges <> ''){
						echo '<li class="TotalPrice"><strong>Delivery Charge <span>£'.$delivery_charges.'</span></strong></li>';
					}
					
					if(isset($discounted_total)){
                ?>
                <li class="TotalPrice"><strong>Discount <span>£<?php echo $c_discount_offer; ?></span></strong></li>
                <?php } ?>
                <li class="TotalPrice">
					<strong>Grand Total <span>£<?php $grand_totals = $total_price + $delivery_charges; 
						echo $grand_total = $grand_totals - $diffrence;
						$_SESSION['grand_total'] = $grand_total; ?></span>
					</strong></li>
					
				
				<li class="TotalPrice">
					<strong>Coupon Code</strong>
					<span>
						<form name="apply_coupon" id="coupons"  action="" method="post">
							<input type="text" name="c_code" class="c_code" id="coupon">
							<button type="submit" name="submit" value="Add" class="btn btn-col">Add Coupon</button>
						</form>
						<div class="coupon-err-msg"><?php echo $msg; ?></div>
					</span>
				</li>
				
              </ul>
              
            </section>
          </div>
        </div>
      </div>
    <div class="col-lg-3"></div>
    </div>
    
    <div class="row">
      <div class="col-lg-6 ftSize">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered table-responsive">
          <tr bgcolor="#f8f8f8">
            <th scope="col" colspan="2">Customer's Details (Account Holder, Customer ID = <?php //echo $res[0]->customer_id; ?>)</th>
          </tr>
          <tr>
            <td width="30%"><strong>Name</strong> </td>
            <td width="70%"><?php echo $res[0]->user_first_name;; ?></td>
          </tr>
          <tr>
            <td><strong>Last Name</strong></td>
            <td><?php echo $res[0]->user_last_name; ?></td>
          </tr>
          <tr>
            <td><strong>Address Line 1</strong></td>
            <td><?php echo $res[0]->primary_address; ?></td>
          </tr>
          <tr>
            <td><strong>Address Line 2</strong></td>
            <td><?php echo $res[0]->secondary_address; ?></td>
          </tr>
          <tr>
            <td><strong>Town / City</strong></td>
            <td><?php echo $res[0]->user_city; ?></td>
          </tr>
          <tr>
            <td><strong>County</strong></td>
            <td><?php echo $res[0]->user_county; ?></td>
          </tr>
          <tr>
            <td><strong>Country</strong></td>
            <td><?php echo $res[0]->user_country; ?></td>
          </tr>
          <tr>
            <td><strong>Post Code</strong></td>
            <td><?php echo $res[0]->user_postcode; ?></td>
          </tr>
          <tr>
            <td><strong>Telephone Number</strong></td>
            <td><?php echo $res[0]->user_phone; ?></td>
          </tr>
          
          <tr>
            <td><strong>Email Address</strong></td>
            <td><?php echo $res[0]->user_emailid; ?></td>
          </tr>
        </table>

      </div>
      <div class="col-lg-6 ftSize">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered table-responsive">
          <tr bgcolor="#f8f8f8">
            <th scope="col" colspan="2">Delivery Details &nbsp;&nbsp;&nbsp;<a href="checkout-login.php">Edit Delivery Details</a></th>
          </tr>
          <tr>
            <td width="30%"><strong>Name</strong> </td>
            <td width="70%"><?php echo $_SESSION['user_fname']; ?></td>
          </tr>
          <tr>
            <td><strong>Last Name</strong></td>
            <td><?php echo $_SESSION['user_lname']; ?></td>
          </tr>
          <tr>
            <td><strong>Address Line 1</strong></td>
            <td><?php echo $_SESSION['primary_address']; ?></td>
          </tr>
          <tr>
            <td><strong>Address Line 2</strong></td>
            <td><?php echo $_SESSION['secondary_address']; ?></td>
          </tr>
          <tr>
            <td><strong>Town / City</strong></td>
            <td><?php echo $_SESSION['user_city']; ?></td>
          </tr>
          <tr>
            <td><strong>County</strong></td>
            <td><?php echo $_SESSION['user_county']; ?></td>
          </tr>
         
          <tr>
            <td><strong>Post Code</strong></td>
            <td><?php echo $_SESSION['post_code']; ?></td>
          </tr>
          <tr>
            <td><strong>Telephone Number</strong></td>
            <td><?php echo $_SESSION['user_telephone']; ?></td>
          </tr>
          <tr>
            <td><strong>Mobile Number</strong></td>
            <td><?php echo $_SESSION['user_mobile']; ?></td>
          </tr>
          
          <tr>
            <td><strong>Email Address</strong></td>
            <td><?php echo $_SESSION['delivery_email']; ?></td>
          </tr>
        </table>

      </div>
    </div>
    
    
    <div class="row">
    <div class="col-lg-12">
		<form name="paymentfrm" id="paymentfrm" action="" method="post">
			<ul>
			  <li>
				<label>
				  <input type="radio" checked="checked" name="paypal" value="paypal" />
				  Pay Via PayPal</label>
			  </li>
			</ul>
			<button type="submit" name="submit" value="paySubmit" class="btn btn-col">Make Payment</button>
        </form>
    </div>
    </div>
    
  </div>
</section>

<?php include_once 'footer.php'; ?> 
