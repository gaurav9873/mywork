<?php include_once 'header.php';

$dbobj = new ConnectDb();
$newobj = new CustomFunctions();
$root_url = SITE_URL;


$current_date = date("Y-m-d");
$tmp_address = $dbobj->check_ordered_process($_SESSION['user_id'], SITE_ID, $current_date);
$delivery_address = json_decode($tmp_address->delivery_address);
$orderID = $tmp_address->order_id;
$tmpID = $tmp_address->id;
$delivery_date = $tmp_address->id;


$hol_date = $dbobj->match_holiday_date($delivery_address->delivery_date);
if($hol_date){
    $chk_hol_date = $hol_date[0]->holiday_date;
}


//Holiday Charges
$holiday_charges = $dbobj->holiday_charges($delivery_address->delivery_date);

if (empty($_SESSION['cart']['products'])) {
    header("location:index.php");
} else if (empty($_SESSION['key'])) {
    header("location:login");
} else if ($obj->countProduct() == 0) {
    header("location:my-account");
} else {
}

$msg = '';
//Check Coupon
if (isset($_POST['c_code'])) {
    $c_code = $_POST['c_code'];
    $_SESSION['c_code'] = $c_code;
}


$c_cat_id = '';
$diffrence = '';
$c_product_code = '';
$c_min_order = '';
$c_discount_offer = '';
$couponCodes = isset($_SESSION['c_code']) ? $_SESSION['c_code'] : '';
if ($couponCodes != '') {
    $coupon_api = API_PATH . 'check-coupon/' . $couponCodes . '/' . SITE_ID . '';
    $coupon_data = $newobj->getCurl($coupon_api);
    if (!empty($coupon_data->coupon_data[0])) {
        $cdata = $coupon_data->coupon_data[0];
        $c_cat_id = isset($cdata->cat_id) ? $cdata->cat_id : '';
        $c_discount_offer = $cdata->discount_offer;
        $c_min_order = $cdata->min_order;
        $c_product_code = $cdata->product_code;
        $valid_from = $cdata->valid_from;
        $valid_upto = $cdata->valid_upto;
        $total_coupons = $cdata->total_coupons;
        $coupon_code = $cdata->coupon_code;

    } else {
        $msg = "<font style='color:#ff6347;'>Coupon code is invalid.</font>";
        $_SESSION['discount_offer'] = 0;
        unset($_SESSION['c_code']);
    }
}

$discount_offer = isset($_SESSION['discount_offer']) ? $_SESSION['discount_offer'] : '5555';


//User Billing Address
$key = $_SESSION['key'];

$billingAddress = $loginObj->get_default_address($key, SITE_ID);
//$billing_address = API_PATH . 'user-data/' . $key . '';
//$data = $newobj->getCurl($billing_address);
//$res = $data->user_record;
$res = json_decode($billingAddress);

$_SESSION['user_billing_email_id'] = $res->user_emailid;
$_SESSION['default_email'] = $res->user_emailid;

//All Gifts
$array_val = array_count_values($_SESSION['cart']['gift']);
ksort($array_val);

//All Products
$product_array = array();
foreach ($_SESSION['cart']['products'] as $size => $valarr) {
    foreach ($valarr as $data) {
        $product_attributes = array('size' => $size, 'product_id' => $data['product_id'], 'quantity' => $data['quantity']);
        array_push($product_array, $product_attributes);
    }
}

//$post_codes = $_SESSION['user_pcode'];
$post_codes = $delivery_address->user_pcode;
$inner_pcode = substr($post_codes, -3);
$inpcode = strtoupper($inner_pcode);


//check shipment price
$delpcode = strtoupper($post_codes);
$pcode = str_replace(' ', '', $delpcode);
$post_code_api = API_PATH . 'check-postcode/' . trim($pcode) . '/' . SITE_ID . '';
$chk_pcode = $newobj->getCurl($post_code_api);
$len = count($chk_pcode->shipping_cost);
$current_dates = date('Y-m-d');

//Shipping charge
$shp_charges = $dbobj->get_shipping_charge($post_codes, intval(SITE_ID));
if (empty($shp_charges)) {
    $shp_msg = 'Location not serviceable please check post code.';
} else {
    if (empty($holiday_charges)) {
        $delivery_charges = $shp_charges->delivery_charges;
    } else {
        $delivery_charges = $holiday_charges->special_charges;
    }
}

$delivery_charges = isset($delivery_charges) ? $delivery_charges : '0';
$_SESSION['delivery_charges'] = $delivery_charges;

$delivery_email = isset($_SESSION['delivery_email']) ? $_SESSION['delivery_email'] : ' ';
$first_last_name = $res->user_first_name . ' ' . $res->user_last_name;

//Paypal Submit
if (isset($_POST['submit']) && $_POST['submit'] == "paySubmit") {

    $payement_type = $_POST['paypal'];
    $urls = "www.sandbox.paypal.com";
    //$urls = "www.paypal.com";
    //$baseUrl = "https://www.nicolaflorist.co.uk";
    $baseUrl = "http://52.39.230.23/nicola-admin";
    $ramount = $_SESSION['grand_total'];
    //$ramount = '1';


    //Update temp table
    $post_data = array('product' => json_encode($product_array), 'gift' => json_encode($array_val),
        'delivery_charges' => json_encode($_SESSION['delivery_charges']), 'discount' => json_encode($discount_offer),
        'submit_process' => '1', 'coupon_code' => $couponCodes);
    $condition = array('id' => $tmpID);
    $update_stmt = $dbobj->updateRow('op2mro9899_tmp_order', $post_data, $condition);

    if ($update_stmt['status'] == true) {

        //delivery address
        $usr_del_address = array('post_code' => $delivery_address->post_code,
            'user_prefix' => $delivery_address->user_prefix,
            'user_name' => $delivery_address->user_fname,
            'user_lname' => $delivery_address->user_lname,
            'mobile_number' => $delivery_address->user_mobile,
            'telephone_number' => $delivery_address->user_mobile,
            'fax_number' => $delivery_address->user_mobile,
            'email_address' => $delivery_address->delivery_email,
            'city' => $delivery_address->user_city,
            'primary_address' => $delivery_address->primary_address,
            'secondary_address' => $delivery_address->secondary_address,
            'country' => $delivery_address->country,
            'user_pcode' => $delivery_address->user_pcode,
            'delivery_date' => $delivery_address->delivery_date,
            'card_message' => $delivery_address->user_card_msg,
            'florist_instruction' => $delivery_address->user_notes,
            'order_id' => $orderID,
            'user_id' => $_SESSION['user_id'],
            'customer_id' => $_SESSION['customer_id'],
            'user_key' => $_SESSION['key'],
            'ordered_date' => date("Y-m-d"),
            'site_id' => SITE_ID,
            'created_ip' => $obj->getUserIP(),
            'created_date' => date("Y-m-d H:i:s"));


        $insStmt = $dbobj->insertRecords('op2mro9899_delivery_address', $usr_del_address);
        if ($insStmt) {
            $chk_oid = $dbobj->checkOrderID($orderID);
            $ord_product = $chk_oid[0]->product;
            $ord_gift = $chk_oid[0]->gift;
            $ord_delivery_address = $chk_oid[0]->delivery_address;
            $ord_orderID = $chk_oid[0]->order_id;
            $ord_delivery_charges = $chk_oid[0]->delivery_charges;
            $ord_discount = $chk_oid[0]->discount;
            $ord_siteID = $chk_oid[0]->site_id;

            if ($ord_orderID == $orderID) {

                $product_records = json_decode($ord_product);
                foreach ($product_records as $pkey => $pval) {

                    $product_size = $pval->size;
                    $product_ids = $newobj->DecryptClientId($pval->product_id);
                    $product_qty = $pval->quantity;

                    //Product detail
                    $product_detail_byid = $dbobj->product_detail_byid($product_ids);

                    //Product Price
                    $product_price_bysiteid = $dbobj->product_detail_by_siteid($product_ids, SITE_ID);

                    $prt_price = $product_price_bysiteid[0]->price;

                    $product_name = $product_detail_byid[0]->product_name;
                    $regular_price = $product_detail_byid[0]->regular_price;
                    $large_price = $product_detail_byid[0]->large_price;
                    $disscount_price = $product_detail_byid[0]->disscount_price;
                    $product_code = $product_detail_byid[0]->product_code;

                    $chk_reg_price_val = (($prt_price == '0.00') ? $regular_price : (($prt_price == '') ? $regular_price : $prt_price));
                    $price = (($disscount_price <> '') ? $disscount_price : $chk_reg_price_val);
                    $final_price = (($product_size == 'large') ? $price + $large_price : $price);
                    $quantity_price = $final_price * $product_qty;

                    //Product details
                    $pro_array_args = array('product_name' => $product_name, 'product_qty' => $product_qty, 'product_price' => $final_price, 'product_qty_price' => $quantity_price,
                        'product_id' => $product_ids, 'product_size' => $product_size, 'product_code' => $product_code, 'user_id' => $_SESSION['user_id'], 'user_key' => $_SESSION['key'],
                        'order_id' => $orderID, 'ordered_date' => date("Y-m-d"), 'created_date' => date("Y-m-d H:i:s"), 'created_ip' => $newobj->getUserIP(), 'flag' => 'product', 'site_id' => SITE_ID);

                    $order_stmt = $dbobj->insertRecords('op2mro9899_ordered_product', $pro_array_args);

                }

                //Gift Detail
                $gft_detail = json_decode($ord_gift);
                if (!empty($gft_detail)) {
                    foreach ($gft_detail as $k => $gftvals) {
                        $gift_qty = $gftvals;
                        $gift_item_byid = $dbobj->gift_item_byid($k);

                        $gft_id = $gift_item_byid[0]->id;
                        $gift_cat_id = $gift_item_byid[0]->gift_cat_id;
                        $gifts_name = $gift_item_byid[0]->gifts_name;
                        $gifts_price = $gift_item_byid[0]->gifts_price;
                        $total_qty = $gift_qty * $gifts_price;

                        $gift_args = array('gift_name' => $gifts_name, 'gift_price' => $gifts_price, 'gift_qty_price' => $total_qty, 'gift_quantity' => $gift_qty,
                            'gift_id' => $gft_id, 'gift_code' => 'GIFT', 'user_id' => $_SESSION['user_id'], 'user_key' => $_SESSION['key'], 'order_id' => $ord_orderID,
                            'ordered_date' => date("Y-m-d"), 'created_date' => date("Y-m-d H:i:s"), 'created_ip' => $newobj->getUserIP(), 'flag' => 'gift', 'site_id' => SITE_ID);

                        $gift_order_stmt = $dbobj->insertRecords('op2mro9899_ordered_product', $gift_args);
                    }
                }

                //$payment_type = (($_POST['paypal'] == 'shp') ? 'shp' : 'paypal');
                $payer_id = (($_POST['paypal'] == 'shp') ? '&nbsp;' : 'GP8NP5BVATYML');
                $offer = (($discount_offer == '5555') ? '0' : $discount_offer);
                //Payment Data
                $payment_args = array('item_name' => 'Flowers',
                    'item_number' => $orderID,
                    'payment_status' => 'Pending',
                    'mc_gross' => $_SESSION['grand_total'],
                    'mc_currency' => 'GBP',
                    'txn_id' => $orderID,
                    'receiver_email' => $_SESSION['email'],
                    'payer_email' => $_SESSION['email'],
                    'order_id' => $orderID,
                    'payment_date' => date("Y-m-d"),
                    'payer_id' => $payer_id,
                    'payment_type' => $payement_type,
                    'user_id' => $_SESSION['user_id'],
                    'user_key' => $_SESSION['key'],
                    'delivery_charges' => $_SESSION['delivery_charges'],
                    'discount_offer' => $offer,
                    'site_id' => SITE_ID,
                    'ordered_date' => date("Y-m-d"),
                    'created_date' => date("Y-m-d H:i:s"),
                    'created_ip' => $newobj->getUserIP());

                $ord_payement_detail = $dbobj->insertRecords('op2mro9899_payments', $payment_args);

            }


            if ($payement_type == 'shp') {

                echo '<form action="https://test.secure-server-hosting.com/secutran/secuitems.php" method="post" name="basketform" id="basketform">
					<input type="hidden" name="shreference" value="SH209673" />
					<input type="hidden" name="checkcode" value="612791" />
					<input type="hidden" name="filename" value="SH209673/payment3.html" />
					<input type="hidden" name="itemcode" value="' . $orderID . '" />
					<input type="hidden" name="itemskew" value="' . $orderID . '" />
					<input type="hidden" name="itemdesc" value="Nicola Flower" />
					<input type="hidden" name="itemquan" value="' . $newobj->countProduct() . '" />
					<input type="hidden" name="itemtota" value="' . $newobj->countProduct() . '" />
					<input type="hidden" name="itempric" value="' . $_SESSION['grand_total'] . '" />
					<input type="hidden" name="cardholdersname" value="' . htmlspecialchars($first_last_name) . '" />
					<input type="hidden" name="cardholdersemail" value="' . $res->user_emailid . '" />
					<input type="hidden" name="address" value="' . $res->primary_address . '" />
					<input type="hidden" name="postcode" value="' . $res->user_postcode . '" />
					<input type="hidden" name="telephone" value="' . $res->user_phone . '" />				
					<input type="hidden" name="transactionamount" value="' . $_SESSION['grand_total'] . '" />
					<input type="hidden" name="cardholdercity" value="' . $res->user_city . '" />
					<input type="hidden" name="deliveryname" value="' . $delivery_address->user_fname . '"/>
					<input type="hidden" name="deliveryAddr1" value="' . $delivery_address->primary_address . '" />
					<input type="hidden" name="deliveryCity" value="' . $delivery_address->user_city . '" />
					<input type="hidden" name="deliveryPostcode" value="' . $delivery_address->user_pcode . '" />
					<input type="hidden" name="transactioncurrency" value="GBP" />
					<input type="hidden" name="secuitems" value="' . $orderID . '|sku1|Nicola Item|' . $_SESSION['grand_total'] . '|' . $newobj->countProduct() . '|' . $_SESSION['grand_total'] . ']" />
					<input type="hidden" name="callbackurl" value="http://52.39.230.23/nicola-admin/shp-success.php" />
					<input type="hidden" name="callbackdata" value="pname|flower|price|' . $_SESSION['grand_total'] . '|orderid|' . $orderID . '" />
					<input type="hidden" name="merchanturl" value="http://52.39.230.23/nicola-admin/review.php?orderid=' . $orderID . '">
				</form><div class="loader1" style="display: block;"><div class="overlay"><div class="loader"></div><h1>Please wait while we redirect...</h1></div></div>
				<script type="text/javascript">document.getElementById("basketform").submit(); </script>';

            }


            if ($payement_type == 'paypal') {
                echo "<form id=\"paypal_form\" action=\"https://" . $urls . "/cgi-bin/webscr\" method=\"post\" name=\"frm\">
				<input type=\"hidden\" name=\"cmd\" value=\"_xclick\">
				<input type=\"hidden\" name=\"business\" value=\"orders@nicolaflorist.co.uk\">
				<input type=\"hidden\" name=\"item_name\" value=\"Flowers\">
				<input type=\"hidden\" name=\"amount\" value=\"$ramount\">
				<input type=\"hidden\" name=\"notify_url\" value=\"$baseUrl/payment.php\" />
				<input type=\"hidden\" name=\"return\" value=\"$baseUrl/success.php\" />
				<input type=\"hidden\" name=\"item_number\" value=" . $orderID . ">
				<input type=\"hidden\" name=\"currency_code\" value=\"GBP\">
				<input type=\"hidden\" name=\"rm\" value=\"2\" />
				</form>
				<div class=\"loader1\" style=\"display: block;\"><div class=\"overlay\"><div class=\"loader\"></div><h1>Please wait while we redirect...</h1></div></div>
				<script type='text/javascript'>document.getElementById('paypal_form').submit(); </script>";

                unset($_SESSION["cart"]["products"]);
                unset($_SESSION["cart"]["gift"]);
                unset($_SESSION['c_code']);
            }



        }
    }
}
?>
<script type='text/javascript'>
    $(document).ready(function () {
        $("#coupons").on('submit', function (evt) {
            var c_code = $(".c_code").val();
            if (c_code == '') {
                $(".c_code").css({"border-color": "#ff6347", "border-width": "2px", "border-style": "solid"});
                return false;
            }
        });

        $("#paymentfrm").on("submit", function (e) {
            var isChecked = $("input[name=paypal]:checked").length;
            if (isChecked > 0) {
                $("#paymentfrm").submit();
            } else {
                e.preventDefault();
                alert("Please check payement type");
                return false;
            }
        });
    });
</script>

<section class="responsPnone">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4">
                <table width="100%" border="0" cellpadding="5" cellspacing="10">
                    <tr class="ttHaed ttc">
                        <th scope="col"> Product Detail</th>
                    </tr>
                </table>
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
                                    foreach ($product_array as $key => $val) {

                                        $product_size = strtolower($val['size']);
                                        $pid = $newobj->DecryptClientId($val['product_id']);
                                        $qty = $val['quantity'];


                                        //Product Api
                                        $product_api = API_PATH . 'product_cart/' . $pid . '';
                                        $post_product = $newobj->getCurl($product_api);
                                        //print_r($post_product);
                                        $productPid = $post_product->pid;


                                        $product_cart_price_bysiteid = API_PATH . 'product_cart_price_bysiteid/' . $pid . '/' . SITE_ID . '';
                                        $post_pdct = $newobj->getCurl($product_cart_price_bysiteid);
                                        $pdt_price = $post_pdct->product_cart_price->price;


                                        $product_name = $post_product->product_name;
                                        $regular_price = $post_product->regular_price;
                                        $large_price = $post_product->large_price;
                                        $disscount_price = $post_product->disscount_price;
                                        $product_code = $post_product->product_code;
                                        $img_id = $post_product->img_id;
                                        $thumbnail_path = $post_product->thumbnail_path;

                                        //Check Coupon Exist
                                        $product_cat_id = explode(',', $post_product->cat_id);
                                        if (in_array($c_cat_id, $product_cat_id)) {

                                            $cprice = (($disscount_price <> '') ? $disscount_price : $pdt_price);
                                            $final_prices = (($product_size == 'large') ? $cprice + $large_price : $cprice);
                                            $quantity_prices = $final_prices * $qty;
                                            $pro_name = $post_product->product_name;
                                            array_push($discount_price, $quantity_prices);
                                            array_push($panme, $pro_name);

                                        } elseif ($product_code == $c_product_code) {
                                            $cprice = (($disscount_price <> '') ? $disscount_price : $pdt_price);
                                            $final_prices = (($product_size == 'large') ? $cprice + $large_price : $cprice);
                                            $quantity_prices = $final_prices * $qty;
                                            $pro_name = $post_product->product_name;
                                            array_push($discount_price, $quantity_prices);
                                            array_push($panme, $pro_name);
                                        } else if ($c_cat_id == '0') {
                                            $cprice = (($disscount_price <> '') ? $disscount_price : $pdt_price);
                                            $final_prices = (($product_size == 'large') ? $cprice + $large_price : $cprice);
                                            $quantity_prices = $final_prices * $qty;
                                            $pro_name = $post_product->product_name;
                                            array_push($discount_price, $quantity_prices);
                                            array_push($panme, $pro_name);
                                        } else {
                                        }

                                        $chk_reg_price = (($pdt_price == '0.00') ? $regular_price : (($pdt_price == '') ? $regular_price : $pdt_price));
                                        $price = (($disscount_price <> '') ? $disscount_price : $chk_reg_price);
                                        $final_price = (($product_size == 'large') ? $price + $large_price : $price);
                                        $quantity_price = $final_price * $qty;
                                        array_push($product_price, $quantity_price);

                                        echo '<span>' . $product_name . '(' . ucfirst($product_size) . '-' . $qty . ')</span><br />';
                                    }

                                    //Calculate Discount Price
                                    $discount_sum = array_sum($discount_price);
                                    if (!empty($discount_price)) {
                                        if ($c_min_order <= $discount_sum) {
                                            $discount = isset($c_discount_offer) ? $c_discount_offer : '';
                                            $discounted_total = $discount_sum - ($discount_sum * ($discount / 100));
                                            $diffrence = $discount_sum - $discounted_total;
                                            $_SESSION['discount_offer'] = $discount;
                                            if ($discounted_total != 0) {
                                                $msg = "<font style='color:#228B22;'>Coupon has been applied on:<b>" . $newobj->implodeArrayKeys($panme) . "</b><br />
											Discount availed is £" . $diffrence . "</font>";
                                            }
                                        } else {
                                            unset($_SESSION['c_code']);
                                            $_SESSION['discount_offer'] = 0;
                                            $msg = "<font style='color:#ff6347;'>The minimum order of these products <strong>" . $newobj->implodeArrayKeys($panme) . "</strong> 
										should be " . $c_min_order . " to apply the coupon</font>";
                                        }
                                    }

                                    ?>
                                </li>

                                <li class="TotalPrice"><strong>Gift: </strong>
                                    <?php
                                    if (empty($array_val)) {
                                        echo '<span>N/A</span>';
                                    } else {
                                        //All Gifts
                                        foreach ($array_val as $keys => $value) {

                                            $gft_url = API_PATH . 'gift-attribs-prices/' . $keys . '';
                                            $gft_json = $newobj->getCurl($gft_url);
                                            $gft_vals = $gft_json->prices[0];

                                            $gftids = $gft_vals->id;
                                            $gift_cat_id = $gft_vals->gift_cat_id;
                                            $gifts_name = $gft_vals->gifts_name;
                                            $gifts_price = $gft_vals->gifts_price;
                                            $total_qty = $value * $gifts_price;
                                            array_push($gift_price, $total_qty);

                                            echo '<span>' . $gifts_name . '</span><span class="qunt">(' . $value . ')</span><br />';
                                        }
                                    }
                                    $product_cart = array_sum($product_price);
                                    $gift_cart = array_sum($gift_price);
                                    $total_price = $product_cart + $gift_cart;

                                    ?>
                                </li>

                                <li class="TotalPrice"><strong>Delivery Date
                                        <span><?php echo str_replace('-', '/', $delivery_address->delivery_date); ?></span></strong>
                                </li>
                                <li class="TotalPrice"><strong>Sub. Total
                                        <span>£<?php echo $total_price; ?></span></strong></li>
                                <?php
                                if ($delivery_charges <> '') {
                                    echo '<li class="TotalPrice"><strong>Delivery Charge <span>£' . $delivery_charges . '</span></strong></li>';
                                }

                                if (isset($discounted_total)) {
                                    ?>
                                    <li class="TotalPrice"><strong>Discount(%)
                                            <span>£<?php echo $c_discount_offer; ?></span></strong></li>
                                <?php } ?>
                                <li class="TotalPrice"><strong>Grand Total
                                        <span>£<?php $grand_totals = $total_price + $delivery_charges;
                                            $grand_total = $grand_totals - $diffrence;
                                            echo round($grand_total, 2);
                                            $_SESSION['grand_total'] = $grand_total; ?>
                  </span> </strong></li>
                                <li class="TotalPrice"><strong>Coupon Code</strong> <span>
                  <form name="apply_coupon" id="coupons" action="" method="post">
                    <input type="text" name="c_code" class="c_code" id="coupon" placeholder="Coupon Code">
                    <div class="clearfix"></div>
                    <button type="submit" name="submit" value="Add" class="btn btn-col">Apply Coupon</button>
                  </form>
                  <div class="coupon-err-msg"><?php echo $msg; ?></div>
                  </span></li>
                            </ul>
                        </section>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 ftSize tableDesign">
                <table width="100%" border="0" cellpadding="5" cellspacing="10">
                    <tr class="ttHaed">
                        <th width="100%" scope="col">Customer Details <span><a
                                        href="my-account">Edit Customer Details</a></span></th>
                    </tr>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="0"
                       class="table table-bordered table-responsive">
                    <tr>
                        <td width="30%"><strong>Name</strong></td>
                        <td width="70%"><?php echo $res->user_prefix . '.&nbsp;' . $res->user_first_name . '&nbsp;' . $res->user_last_name; ?></td>
                    </tr>

                    <tr>
                        <td><strong>Address Line</strong></td>
                        <td><?php echo $res->primary_address; ?></td>
                    </tr>

                    <tr>
                        <td><strong>Town / City</strong></td>
                        <td><?php echo $res->user_city; ?></td>
                    </tr>

                    <tr>
                        <td><strong>Country</strong></td>
                        <td><?php echo $res->user_country; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Post Code</strong></td>
                        <td><?php echo strtoupper($res->user_pcode); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Mobile/Telephone</strong></td>
                        <td><?php echo $res->user_phone; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Email Address</strong></td>
                        <td><?php echo $res->user_emailid; ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-lg-4 ftSize tableDesign">
                <table width="100%" border="0" cellpadding="5" cellspacing="10">
                    <tr class="ttHaed">
                        <th width="100%">Delivery Details &nbsp;&nbsp;&nbsp;<a href="checkout-delivery">Edit Delivery
                                Details</a></th>
                    </tr>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="0"
                       class="table table-bordered table-responsive">
                    <tr>
                        <td width="30%"><strong>Name</strong></td>
                        <td width="70%"><?php echo $delivery_address->user_prefix . '.&nbsp;' . $delivery_address->user_fname . '&nbsp;' . $delivery_address->user_lname; ?></td>
                    </tr>

                    <tr>
                        <td><strong>Address Line</strong></td>
                        <td><?php echo $delivery_address->primary_address; ?></td>
                    </tr>

                    <tr>
                        <td><strong>Town / City</strong></td>
                        <td><?php echo $delivery_address->user_city; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Country</strong></td>
                        <td><?php echo $delivery_address->country; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Post Code</strong></td>
                        <td><?php echo strtoupper($delivery_address->user_pcode); ?></td>
                    </tr>

                    <tr>
                        <td><strong>Mobile/Telephone</strong></td>
                        <td><?php echo $delivery_address->user_mobile; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Email Address</strong></td>
                        <td><?php echo $delivery_address->delivery_email; ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center">
                <form name="paymentfrm" id="paymentfrm" action="" method="post">
                    <ul>
                        <li>
                            <label>
                                <input type="radio" name="paypal" value="paypal"/>
                                Pay Via PayPal</label>
                        </li>
                        <li>
                            <label>
                                <input type="radio" name="paypal" value="shp"/>
                                Debit/Credit card</label>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                    <button type="submit" name="submit" value="paySubmit" class="btn btn-col">Make Payment</button>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include_once 'footer.php'; ?>