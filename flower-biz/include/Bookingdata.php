<?php
include_once 'dbinfo.php';
include_once 'class-library.php';

class Bookingdata
{
    const STOREID = '2';
    const APIPATH = "http://localhost/flower/flow-admin/flowers/api/";

    public function getInstance()
    {
        $db = new ConnectDb();
        return $db;
    }

    public function getMailInstance()
    {
        $modalObj = new CustomFunctions();
        return $modalObj;
    }

    public function customFunction()
    {
        $custObj = new CustomFunctions();
        return $custObj;
    }

    public function checkorderid($orderid)
    {
        $orderidStmt = $this->getInstance()->checkOrderID($orderid);
        return $orderidStmt;
    }

    public function checkUserSalt($userid)
    {
        $checkUserAuth = $this->getInstance()->chk_usr_salt($userid);
        return $checkUserAuth;
    }

    public function userPaymentDetail($orderid)
    {
        $paymentDetail = $this->getInstance()->user_payment_details($orderid);
        return $paymentDetail;
    }

    public function couponCode($couponCode)
    {
        $update_coupon_number = Bookingdata::APIPATH . 'update-coupon/' . $couponCode . '/' . Bookingdata::STOREID . '';
        $upStmts = $this->customFunction()->getCurl($update_coupon_number);
        return $upStmts;
    }


    public function updatePaymentStatus($payment_status, $txn_id, $payment_type, $payer_email, $payment_date, $orderid)
    {
        $authOrderID = $this->checkorderid($orderid);
        $ord_orderID = $authOrderID[0]->order_id;
        $couponCode = isset($authOrderID[0]->coupon_code) ? $authOrderID[0]->coupon_code : '';

        if ($ord_orderID == $orderid) {

            if ($couponCode) {
                $this->couponCode($couponCode);
            }

            $pay_status = 'Paid';
            $order_args = array('payment_status' => $payment_status, 'txn_id' => $txn_id, 'payment_type' => $payment_type, 'payer_email' => $payer_email, 'payment_date' => $payment_date);
            $condition = array('order_id' => $orderid);
            $update_stmt = $this->getInstance()->update_record('op2mro9899_payments', $order_args, $condition);
            return $update_stmt;
        }

    }

    public function invoiceOrderDetails($orderid)
    {
        $invoiceOrder = $this->getInstance()->invoice_order_details($orderid);
        return $invoiceOrder;
    }

    public function mailTemplate($orderid, $payment_amount, $payment_currency)
    {
        $invoice_order_details = $this->invoiceOrderDetails($orderid);
        $chk_oid = $this->checkorderid($orderid);
        $ord_delivery_address = $chk_oid[0]->delivery_address;
        //Delivery Address
        $delivery_address = json_decode($ord_delivery_address);
        //User Billing Address
        $user_billing_addresss = $this->getInstance()->user_billing_addresss($delivery_address->user_id, Bookingdata::STOREID);
        if (empty($user_billing_addresss)) {
            $user_billing_addresss = $this->getInstance()->user_billing_addresss_custID($delivery_address->customer_id, Bookingdata::STOREID);
        }
        $post_bill = $user_billing_addresss[0];

        $ordered_date = date("Y-m-d");

        //Payment Detail
        $user_payment_details = $this->userPaymentDetail($orderid);
        $payment_id = $user_payment_details[0]->payment_id;
        $item_number = $user_payment_details[0]->item_number;
        $payment_status = $user_payment_details[0]->payment_status;
        $order_status = $user_payment_details[0]->order_status;
        $mc_gross = $user_payment_details[0]->mc_gross;
        $mc_currency = $user_payment_details[0]->mc_currency;
        $txn_id = $user_payment_details[0]->txn_id;
        $delivery_charges = $user_payment_details[0]->delivery_charges;
        $discount_offer = $user_payment_details[0]->discount_offer;

        $default_email = $delivery_address->email;
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
				<td colspan="2" style="border-bottom: 1px solid #ccc; border-right: medium none; padding: 2mm 0;"><p style="text-align:center; font-weight:bold; margin:0;">Order Number : ' . $orderid . '</p></td>
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

        $subj = 'Order Number : ' . $orderid . '';
        $to = $default_email;
        $from = 'orders@nicola.co.uk';
        $name = 'Nicolaflorist';
        $this->getMailInstance()->smtpmailer($to, $from, $name, $subj, $msg);


        //Admin Mail
        $mail_template = 'Dear Admin, <br /> order has been placed, Orderid is: ' . $orderid . '';
        $order_subject = 'Fleurdelisflorist order confirmation';
        $order_to = 'orders@nicola.co.uk';
        $order_from = 'orders@nicola.co.uk';
        $order_name = 'Fleurdelisflorist';
        $this->getMailInstance()->smtpmailer($order_to, $order_from, $order_name, $order_subject, $mail_template);

    }


}