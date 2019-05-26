 <?php
include_once '../include/class-library.php';
include_once '../include/init.php';
$obj = new CustomFunctions();
$dbobj = new ConnectDb();
session_start();

if($obj->is_ajax()) {

    //All Products
    $product_array = array();
    foreach ($_SESSION['cart']['products'] as $size => $valarr) {
        foreach ($valarr as $data) {
            $product_attributes = array('size' => $size, 'product_id' => $data['product_id'], 'quantity' => $data['quantity']);
            array_push($product_array, $product_attributes);
        }
    }

    //All Gifts
    $array_val = array_count_values($_SESSION['cart']['gift']);
    ksort($array_val);

    $json = json_decode(file_get_contents("php://input"));
    $action = $json->payment_type;

    $current_date = date("Y-m-d");
    $tmp_address = $dbobj->check_ordered_process($_SESSION['user_id'], SITE_ID, $current_date);
    $delivery_address = json_decode($tmp_address->delivery_address);
    $orderID = $tmp_address->order_id;
    $tmpID = $tmp_address->id;
    $ramount = round($_SESSION['grand_total']);

    $discount_offer = isset($_SESSION['discount_offer']) ? $_SESSION['discount_offer'] : '5555';

    //User Billing Address
    $key = $_SESSION['key'];
    //$billing_address = API_PATH . 'user-data/' . $key . '';
    //$data = $obj->getCurl($billing_address);
    $billingAddress = $loginObj->get_default_address($key, SITE_ID);
    $res = json_decode($billingAddress);
    $_SESSION['user_billing_email_id'] = $res->user_emailid;
    $_SESSION['default_email'] = $res->user_emailid;
    $couponCodes = isset($_SESSION['c_code']) ? $_SESSION['c_code'] : '';

    $post_data = array('product' => json_encode($product_array), 'gift' => json_encode($array_val), 'delivery_charges' => json_encode($_SESSION['delivery_charges']),
        'discount' => json_encode($discount_offer), 'submit_process' => '1', 'coupon_code' => $couponCodes);
    $condition = array('id' => $tmpID);
    $update_stmt = $dbobj->updateRow('op2mro9899_tmp_order', $post_data, $condition);
    if ($update_stmt['status'] == true) {
        //delivery address
        $usr_del_address = array('post_code' => $delivery_address->post_code, 'user_prefix' => $delivery_address->user_prefix, 'user_name' => $delivery_address->user_fname,
            'user_lname' => $delivery_address->user_lname, 'mobile_number' => $delivery_address->user_mobile, 'telephone_number' => $delivery_address->user_mobile,
            'fax_number' => $delivery_address->user_mobile, 'email_address' => $delivery_address->delivery_email, 'city' => $delivery_address->user_city,
            'primary_address' => $delivery_address->primary_address, 'secondary_address' => $delivery_address->secondary_address,
            'country' => $delivery_address->country, 'user_pcode' => $delivery_address->post_code, 'delivery_date' => $delivery_address->delivery_date,
            'card_message' => $delivery_address->user_card_msg, 'florist_instruction' => $delivery_address->user_notes, 'order_id' => $orderID,
            'user_id' => $_SESSION['user_id'], 'customer_id' => $_SESSION['customer_id'], 'user_key' => $_SESSION['key'],
            'ordered_date' => date("Y-m-d"), 'site_id' => SITE_ID, 'created_ip' => $obj->getUserIP(), 'created_date' => date("Y-m-d H:i:s")
        );

        $insStmt = $dbobj->insertRecords('op2mro9899_delivery_address', $usr_del_address);
        if($insStmt){
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
                    $product_ids = $obj->DecryptClientId($pval->product_id);
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
                        'order_id' => $orderID, 'ordered_date' => date("Y-m-d"), 'created_date' => date("Y-m-d H:i:s"), 'created_ip' => $obj->getUserIP(), 'flag' => 'product', 'site_id' => SITE_ID);

                    $order_stmt = $dbobj->insertRecords('op2mro9899_ordered_product', $pro_array_args);
                    if($order_stmt){
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
                                    'gift_id' => $gft_id, 'gift_code' => 'GIFT', 'user_id' => $_SESSION['user_id'], 'user_key' => $_SESSION['key'], 'order_id' => $orderID,
                                    'ordered_date' => date("Y-m-d"), 'created_date' => date("Y-m-d H:i:s"), 'created_ip' => $obj->getUserIP(), 'flag' => 'gift', 'site_id' => SITE_ID);

                                $gift_order_stmt = $dbobj->insertRecords('op2mro9899_ordered_product', $gift_args);
                            }
                        }

                        $payer_id = (($action == 'shp') ? '&nbsp;' : 'GP8NP5BVATYML');
                        $offer = (($discount_offer == '5555') ? '0' : $discount_offer);
                        //Payment Data
                        $payment_args = array('item_name' => 'Flowers', 'item_number' => $orderID, 'payment_status' => 'Pending', 'mc_gross' => round($_SESSION['grand_total']),
                            'mc_currency' => 'GBP', 'txn_id' => $orderID, 'receiver_email' => $_SESSION['default_email'], 'payer_email' => $_SESSION['default_email'],
                            'order_id' => $orderID, 'payment_date' => date("Y-m-d"), 'payer_id' => $payer_id, 'payment_type' => $action,
                            'user_id' => $_SESSION['user_id'], 'user_key' => $_SESSION['key'], 'delivery_charges' => $_SESSION['delivery_charges'],
                            'discount_offer' => $offer, 'site_id' => SITE_ID, 'ordered_date' => date("Y-m-d"), 'created_date' => date("Y-m-d H:i:s"),
                            'created_ip' => $obj->getUserIP()
                        );
                        $ord_payement_detail = $dbobj->insertRecords('op2mro9899_payments', $payment_args);
                        if($ord_payement_detail){
                            echo json_encode(array('status' => 'true', 'message' => 'Please wit while we redirect payment gateway', 'payment_type' => $action, 'oid' =>  $obj->EncryptClientId($orderID)));
                        }

                    }

                }
            }
        }
    }else{
        echo json_encode(array('status' => 'false', 'message' => 'Something went wrong', 'payment_type' => $action));
    }
}