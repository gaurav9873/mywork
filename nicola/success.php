<?php include_once 'header.php'; ?>

<br/><br/>
<section>
    <?php
    /*$dbobj = new ConnectDb();
    $custObj = new CustomFunctions();

    //print_r($_REQUEST); die;

    if (isset($_POST['txn_id'])) {

        $item_name = isset($_POST['item_name']) ? $_POST['item_name'] : '';
        $item_number = isset($_POST['item_number']) ? $_POST['item_number'] : '';
        $payment_status = 'Paid';
        $payment_amount = isset($_POST['mc_gross']) ? $_POST['mc_gross'] : '';
        $payment_currency = isset($_POST['mc_currency']) ? $_POST['mc_currency'] : '';
        $txn_id = isset($_POST['txn_id']) ? $_POST['txn_id'] : '';
        $payer_email = isset($_POST['payer_email']) ? $_POST['payer_email'] : '';
        $payment_date = isset($_POST['payment_date']) ? $_POST['payment_date'] : '';
        $payer_id = isset($_POST['payer_id']) ? $_POST['payer_id'] : '';
        $payment_type = isset($_POST['payment_type']) ? $_POST['payment_type'] : '';
        $custom = isset($_POST['custom']) ? $_POST['custom'] : '';
        $discount_offer = isset($_SESSION['discount_offer']) ? $_SESSION['discount_offer'] : '0';
        $couponCodes = isset($_SESSION['c_code']) ? $_SESSION['c_code'] : '';
        $default_email = $_SESSION['default_email'];
        $customer_id = $_SESSION['customer_id'];




        //OrderId Temp table
        $chk_oid = $dbobj->checkOrderID($item_number);
        //print_r($chk_oid); die;
        $ord_product = $chk_oid[0]->product;
        $ord_gift = $chk_oid[0]->gift;
        $ord_delivery_address = $chk_oid[0]->delivery_address;
        $ord_orderID = $chk_oid[0]->order_id;
        $ord_delivery_charges = $chk_oid[0]->delivery_charges;
        $ord_discount = $chk_oid[0]->discount;
        $ord_siteID = $chk_oid[0]->site_id;
        $couponCode = $chk_oid[0]->coupon_code;
        $ordered_date = date("Y-m-d");

        if ($couponCode) {
            $update_coupon_number = API_PATH . 'update-coupon/' . $couponCode . '/' . SITE_ID . '';
            $upStmts = $obj->getCurl($update_coupon_number);
            print_r($upStmts); die;
            unset($_SESSION['c_code']);
        }

        $chk_usr_salt = $dbobj->chk_usr_salt($_SESSION['key']);
        $usrID = $chk_usr_salt[0]->id;
        $user_custID = $chk_usr_salt[0]->customer_id;
        $usr_unique_key = $chk_usr_salt[0]->unique_key;

        if ($ord_orderID == $item_number) {

            $pay_status = (($payment_status == 'Completed') ? 'Paid' : 'Pending');
            $order_args = array('payment_status' => $payment_status, 'txn_id' => $txn_id, 'payment_type' => $payment_type, 'payer_email' => $payer_email, 'payment_date' => $payment_date);
            $condition = array('order_id' => $ord_orderID);
            $update_stmt = $dbobj->update_record('op2mro9899_payments', $order_args, $condition);

            //Update tmp record
            $order_status = array('order_process' => '1');
            $orderCondition = array('order_id' => $ord_orderID);
            $updateStmt = $dbobj->update_record('op2mro9899_tmp_order', $order_status, $orderCondition);

            //Mail Template
            //Payement Detail
            $user_payment_details = $dbobj->user_payment_details($ord_orderID);
            $payment_id = $user_payment_details[0]->payment_id;
            $item_number = $user_payment_details[0]->item_number;
            $payment_status = $user_payment_details[0]->payment_status;
            $order_status = $user_payment_details[0]->order_status;
            $mc_gross = $user_payment_details[0]->mc_gross;
            $mc_currency = $user_payment_details[0]->mc_currency;
            $txn_id = $user_payment_details[0]->txn_id;
            $delivery_charges = $user_payment_details[0]->delivery_charges;
            $discount_offer = $user_payment_details[0]->discount_offer;

            //Billing Address
            $user_billing_addresss = $dbobj->user_billing_addresss($_SESSION['user_id'], SITE_ID);
            if (empty($user_billing_addresss)) {
                $user_billing_addresss = $dbobj->user_billing_addresss_custID($customer_id, SITE_ID);
            }
            $post_bill = $user_billing_addresss[0];
            print_r($post_bill);
            $defaultEmail = $dbobj->getDefaultUser($customer_id, SITE_ID);
            print_r($defaultEmail); die();
            //Delivery Address
            $delivery_address = json_decode($ord_delivery_address);
            print_r($delivery_address); die;
            $msg = '';
            $msg .= '<!DOCTYPE html>
				<html>
				<head>
				<meta charset="utf-8">
				<title>Print Invoice</title>
				</head>
				<body style="width: 100%; margin: 0;padding: 0;">
				<table width="700" border="0" cellspacing="0" cellpadding="0" align="center" style="font:10pt Arial;">
				<tr>
				<td align="left" valign="top">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
				<td colspan="2" style="border-bottom: 1px solid #ccc; border-right: medium none; padding: 2mm 0;"><p style="text-align:center; font-weight:bold; margin:0;">Order Number : ' . $ord_orderID . '</p></td>
				</tr>';
            $msg .= '<tr>
				<td width="50%" align="left" valign="top"><table width="95%" border="0" cellspacing="10" cellpadding="10" style="border:1px solid #ccc; text-align:left; font-weight:100; margin-top:10px;">
				<tr>
				<th> <h1 style="font-size:14pt; color: #000; font-weight: normal; margin:0 0 10px;">Billing Address</h1>
				<p style="margin:0; padding:0; line-height:22px; font-size:12px; font-weight:100;">Name: ' . $post_bill->user_first_name . ' <br />
				Last Name: ' . $post_bill->user_last_name . ' <br />
				Address Line 1: ' . $post_bill->primary_address . ' <br />
				Address Line 2: ' . $post_bill->secondary_address . ' <br />
				Town / City: ' . $post_bill->user_city . ' <br />
				Country: ' . $post_bill->user_country . ' <br />
				Post Code: ' . $post_bill->user_postcode . ' <br />
				Telephone Number: ' . $post_bill->user_phone . ' <br />
				Email Address: ' . $post_bill->user_emailid . '</p>
				</th>
				</tr>
				</table></td>';
            $msg .= '<td width="50%" align="right" valign="top"><table width="100%" border="0" cellspacing="10" cellpadding="10" style="border:1px solid #ccc; text-align:left; font-weight:100; margin-top:10px;">
				<tr>
				<th> <h1 style="font-size:14pt; color: #000; font-weight: normal; margin:0 0 10px;">Delivery Address</h1>
				<p style="margin:0; padding:0; line-height:22px; font-size:12px; font-weight:100;">Name: ' . $delivery_address->user_fname . '<br />
				Last Name: ' . $delivery_address->user_lname . '<br />
				Address Line 1: ' . $delivery_address->primary_address . '<br />
				Address Line 2: ' . $delivery_address->secondary_address . '<br />
				Town / City: ' . $delivery_address->user_city . '<br />
				Country: ' . $delivery_address->country . '<br />
				Post Code: ' . $delivery_address->post_code . '<br />
				Telephone Number: ' . $delivery_address->user_mobile . '<br />
				Email Address: ' . $delivery_address->delivery_email . '<br />
				</p>
				</th>
				</tr>
				</table></td>
				</tr>
				<tr>
				<td colspan="2" valign="top" align="right" style="padding:0;"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#f8f8f8" style=" margin-top:10px; border-top:1px solid #ccc; border-bottom:1px solid #ccc;">
				<tr>
				<td style="padding:10px; border:none;" width="33.33%">Order Date : ' . date("d-M-Y", strtotime($ordered_date)) . '</td>
				<td style="padding:10px; border:none;" align="center" width="33.33%">Delivery Date : ' . date("d-M-Y", strtotime($delivery_address->delivery_date)) . '</td>
				<td style="padding:10px; border:none;" align="right" width="33.33%">Currency : GBP</td>
				</tr>
				</table></td>
				</tr>
				</table>
				<table width="100%" border="0" cellspacing="0" cellpadding="5" style="margin-top:10px;">
				<tr bgcolor="#eeeeee" style="background:#eeeeee;">
				<th style="border: 1px solid #ccc; padding: 2mm; width:10%;" scope="col">Sl. No.</th>
				<th style="border: 1px solid #ccc; padding: 2mm; width:35%;" scope="col">Product</th>
				<th style="border: 1px solid #ccc; padding: 2mm; width:15%;" scope="col">Quantity</th>
				<th style="border: 1px solid #ccc; padding: 2mm; width:15%;" scope="col">Product Rate</th>
				<th style="border: 1px solid #ccc; padding: 2mm; width:15%;" scope="col">Gift Rate</th>
				<th style="border: 1px solid #ccc; padding: 2mm; width:10%;" scope="col">Total</th>
				</tr>';

            $invoice_order_details = $dbobj->invoice_order_details($ord_orderID);


            $count = 1;
            foreach ($invoice_order_details as $post_values) {
                $product_names = $post_values->product_name;
                $product_prices = $post_values->product_price;
                $product_qty_prices = $post_values->product_qty_price;
                $product_qtys = $post_values->product_qty;
                $product_ids = $post_values->product_id;
                $product_sizes = $post_values->product_size;
                $product_codes = $post_values->product_code;
                $gift_names = $post_values->gift_name;
                $gift_prices = $post_values->gift_price;
                $gift_qty_prices = $post_values->gift_qty_price;
                $gift_quantitys = $post_values->gift_quantity;
                $gift_ids = $post_values->gift_id;
                $flags = $post_values->flag;
                if ($flags == 'product') {
                    $msg .= '<tr>
						<td style="border: 1px solid #ccc; padding: 2mm;">' . $count . '</td>
						<td style="border: 1px solid #ccc; padding: 2mm;">' . $product_names . '<br />
						Size : ' . $product_sizes . '</td>
						<td style="border: 1px solid #ccc; padding: 2mm;">' . $product_qtys . '</td>
						<td style="border: 1px solid #ccc; padding: 2mm;">£' . $product_prices . '</td>
						<td style="border: 1px solid #ccc; padding: 2mm;">£' . $product_prices . '</td>
						<td style="border: 1px solid #ccc; padding: 2mm;">£' . $product_qty_prices . '</td>
						</tr>';
                }
                if ($flags == 'gift') {

                    $msg .= '<tr>
						<td style="width:10%;">' . $count . '</td>
						<td style="width:35%; text-align:left; padding-left:10px;">' . $gift_names . '<br /></td>
						<td class="mono" style="width:15%;">' . $gift_quantitys . '</td>
						<td style="width:15%;" class="mono"></td>
						<td style="width:15%;" class="mono">£' . $gift_prices . '</td>
						<td style="width:10%;" class="mono">£' . $gift_qty_prices . '</td>
						</tr>';
                }
                $count++;
            }

            $msg .= '<tr>
				<td style="border: 1px solid #ccc; padding: 2mm;" colspan="4"></td>
				<td style="border: 1px solid #ccc; padding: 2mm;">Shipping :</td>
				<td style="border: 1px solid #ccc; padding: 2mm;" class="mono">£' . $delivery_charges . '</td>
				</tr>
				<tr>
				<td style="border: 1px solid #ccc; padding: 2mm;" colspan="4"></td>
				<td style="border: 1px solid #ccc; padding: 2mm;">Discount :</td>
				<td style="border: 1px solid #ccc; padding: 2mm;" class="mono">' . $discount_offer . '</td>
				</tr>
				<tr>
				<td style="border: 1px solid #ccc; padding: 2mm;" colspan="4"></td>
				<td style="border: 1px solid #ccc; padding: 2mm;">Total :</td>
				<td style="border: 1px solid #ccc; padding: 2mm;" class="mono">£' . $payment_amount . '</td>
				</tr>';

            $msg .= '</table>
				<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-top:25px;">
				<caption style="text-align:left; margin-bottom:5px;">
				<strong>Total Amount :</strong>
				</caption>
				<tr>
				<td style="width:15%; border: 1px solid #ccc; padding: 2mm;">' . $payment_currency . '</td>
				<td style="width:15%; border: 1px solid #ccc; padding: 2mm;" class="mono">£' . $payment_amount . '</td>
				</tr>
				<tr>
				<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
				<td style=" border: 1px solid #ccc; padding: 2mm;" colspan="3">Card Message : ' . $delivery_address->user_card_msg . '</td>
				</tr>
				<tr>
				<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
				<td style=" border: 1px solid #ccc; padding: 2mm;" colspan="3">Note to Florist : ' . $delivery_address->user_notes . '</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				</body>
				</html>';

            $subj = 'Order Number : ' . $ord_orderID . '';
            $to = $default_email;
            $from = 'orders@nicola.co.uk';
            $name = 'Nicolaflorist';
            $custObj->smtpmailer($to, $from, $name, $subj, $msg);


            //Admin Mail
            $mail_template = 'Dear Admin, <br /> order has been placed, Orderid is: ' . $ord_orderID . '';
            $order_subject = 'Fleurdelisflorist order confirmation';
            $order_to = 'orders@nicola.co.uk';
            $order_from = 'orders@nicola.co.uk';
            $order_name = 'Fleurdelisflorist';
            $custObj->smtpmailer($order_to, $order_from, $order_name, $order_subject, $mail_template);
            //$delte_temp_data = $dbobj->deleteRow('op2mro9899_tmp_order', 'order_id', $ord_orderID);
            $delte_temp_data = $item_number;
            if ($delte_temp_data) {
                unset($_SESSION["cart"]["products"]);
                unset($_SESSION["cart"]["gift"]);
                unset($_SESSION['c_code']);
                header("location:success.php?msg=success");
            } else {
                unset($_SESSION["cart"]["products"]);
                unset($_SESSION["cart"]["gift"]);
                unset($_SESSION['c_code']);
                header("location:index.php");
            }
        }

    }*/
    //header("location:success.php?msg=success");
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="aboutSec">
                    <div class="aboutContent">
                        <span style="font-size:24px; font-weight:bold">Thank you for your order..</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<?php include_once 'footer.php'; ?>
