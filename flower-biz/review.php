<?php include_once 'header.php';

$dbobj = new ConnectDb();
$root_url = SITE_URL;
$current_date = date("Y-m-d");
$tmp_address = $dbobj->check_ordered_process($_SESSION['user_id'], SITE_ID, $current_date);
$delivery_address = json_decode($tmp_address->delivery_address);
$orderID = $tmp_address->order_id;
$tmpID = $tmp_address->id;


if (empty($_SESSION['cart']['products'])) {
    header("location:$root_url");
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
    $coupon_data = $obj->getCurl($coupon_api);
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
$billing_address = API_PATH . 'user-data/' . $key . '';
$data = $obj->getCurl($billing_address);
$res = $data->user_record;
$_SESSION['user_billing_email_id'] = $res[0]->user_emailid;
$_SESSION['default_email'] = $res[0]->user_emailid;

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
$post_codes = $delivery_address->post_code;
$sid = SITE_ID;
//Holiday Charges
$holiday_charges = $dbobj->holiday_charges($delivery_address->delivery_date);
//Shipping charge
$shp_charges = $dbobj->get_shipping_charge($post_codes, intval($sid));
if (empty($shp_charges)) {
    $shp_msg = 'Location not serviceable please check post code.';
} else {
    if (empty($holiday_charges)) {
        $delivery_charges = $shp_charges[0]->delivery_charges;
    } else {
        $delivery_charges = $holiday_charges[0]->special_charges;
    }

}
$delivery_charges = isset($delivery_charges) ? $delivery_charges : '0';
$_SESSION['delivery_charges'] = $delivery_charges;

$delivery_email = isset($_SESSION['delivery_email']) ? $_SESSION['delivery_email'] : ' ';
$first_last_name = $res[0]->user_first_name . ' ' . $res[0]->user_last_name;
?>
<script src="javascripts/cart-data.js" type="text/javascript"></script>

<div class="loader1" style="display: none;">
    <div class="overlay">
        <div class="loader"></div>
        <h1>Please wait...</h1>
    </div>
</div>

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
                                        $pid = $obj->DecryptClientId($val['product_id']);
                                        $qty = $val['quantity'];


                                        //Product Api
                                        $product_api = API_PATH . 'product_cart/' . $pid . '';
                                        $post_product = $obj->getCurl($product_api);
                                        $productPid = $post_product->pid;
                                        $product_cart_price_bysiteid = API_PATH . 'product_cart_price_bysiteid/' . $pid . '/' . SITE_ID . '';
                                        $post_pdct = $obj->getCurl($product_cart_price_bysiteid);
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
                                                $msg = "<font style='color:#228B22;'>Coupon has been applied on:<b>" . $obj->implodeArrayKeys($panme) . "</b><br />
											Discount availed is £" . $diffrence . "</font>";
                                            }
                                        } else {
                                            unset($_SESSION['c_code']);
                                            $_SESSION['discount_offer'] = 0;
                                            $msg = "<font style='color:#ff6347;'>The minimum order of these products <strong>" . $obj->implodeArrayKeys($panme) . "</strong> 
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
                                            $gft_json = $obj->getCurl($gft_url);
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
                        <th width="100%" scope="col">Customer Details <span><a href="my-account.php">Edit Customer Details</a></span>
                        </th>
                    </tr>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="0"
                       class="table table-bordered table-responsive">
                    <tr>
                        <td width="30%"><strong>Name</strong></td>
                        <td width="70%"><?php echo $res[0]->user_prefix . '.&nbsp;' . $res[0]->user_first_name . '&nbsp;' . $res[0]->user_last_name; ?></td>
                    </tr>

                    <tr>
                        <td><strong>Address Line</strong></td>
                        <td><?php echo $res[0]->primary_address; ?></td>
                    </tr>

                    <tr>
                        <td><strong>Town / City</strong></td>
                        <td><?php echo $res[0]->user_city; ?></td>
                    </tr>

                    <tr>
                        <td><strong>Country</strong></td>
                        <td><?php echo $res[0]->user_country; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Post Code</strong></td>
                        <td><?php echo strtoupper($res[0]->user_pcode); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Mobile/Telephone</strong></td>
                        <td><?php echo $res[0]->user_phone; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Email Address</strong></td>
                        <td><?php echo $res[0]->user_emailid; ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-lg-4 ftSize tableDesign">
                <table width="100%" border="0" cellpadding="5" cellspacing="10">
                    <tr class="ttHaed">
                        <th width="100%">Delivery Details &nbsp;&nbsp;&nbsp;<a href="checkout-delivery.php">Edit
                                Delivery Details</a></th>
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
                        <td><?php echo strtoupper($delivery_address->post_code); ?></td>
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
                <?php
                if (empty($shp_charges)) {
                    echo '<h2>' . $shp_msg . '</h2>';
                } else {
                    ?>
                    <form name="paymentfrm" id="paymentfrm" action="" method="post">
                        <ul>
                            <li><label><input type="radio" name="paypal" value="paypal"/>Pay Via PayPal</label></li>
                            <li><label><input type="radio" name="paypal" value="shp"/>Debit/Credit card</label></li>
                        </ul>
                        <div class="clearfix"></div>
                        <button type="submit" name="submit" value="paySubmit" class="btn btn-col">Make Payment</button>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
<?php include_once 'footer.php'; ?>
