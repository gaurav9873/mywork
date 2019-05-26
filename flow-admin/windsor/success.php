<?php
include_once 'header.php';
?>


<section>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
	
	
	
	 
<?php

	//print_r($_REQUEST);
	
	if(isset($_POST['item_name'])){

		$item_name = isset($_POST['item_name']) ? $_POST['item_name'] : '';
		$item_number = isset($_POST['item_number']) ? $_POST['item_number'] : '';
		$payment_status = isset($_POST['payment_status']) ? $_POST['payment_status'] : '';
		$payment_amount = isset($_POST['mc_gross']) ? $_POST['mc_gross'] : '';
		$payment_currency = isset($_POST['mc_currency']) ? $_POST['mc_currency'] : '';
		$txn_id = isset($_POST['txn_id']) ? $_POST['txn_id'] : '';
		$receiver_email = isset($_POST['receiver_email']) ? $_POST['receiver_email'] : '';
		$payer_email = isset($_POST['payer_email']) ? $_POST['payer_email'] : '';
		$payment_date = isset($_POST['payment_date']) ? $_POST['payment_date'] : '';
		$payer_id = isset($_POST['payer_id']) ? $_POST['payer_id'] : '';
		$payment_type = isset($_POST['payment_type']) ? $_POST['payment_type'] : '';
		$custom = isset($_POST['custom']) ? $_POST['custom'] : '';
		$discount_offer = isset($_SESSION['discount_offer']) ? $_SESSION['discount_offer'] : '0';

		//check orderId
		$chkOrderID = API_PATH.'orderid/'.$item_number;
		$json_data_val = $obj->getCurl($chkOrderID);
		$get_order_id = $json_data_val->order_ids[0];
		$orderID = $get_order_id->order_id;
		$products = json_decode($get_order_id->product);
		if($orderID == $item_number){

			$delivery_args = array( 'post_code' => $_SESSION['post_code'], 
									'user_prefix' => $_SESSION['user_prefix'], 
									'user_fname' => $_SESSION['user_fname'], 
									'user_lname' => $_SESSION['user_lname'],
									'user_mobile' => $_SESSION['user_mobile'], 
									'user_telephone' => $_SESSION['user_telephone'], 
									'user_fax' => $_SESSION['user_fax'], 
									'delivery_email' => $_SESSION['delivery_email'], 
									'user_city' => $_SESSION['user_city'], 
									'primary_address' => $_SESSION['primary_address'], 
									'secondary_address' => $_SESSION['secondary_address'], 
									'user_county' => $_SESSION['user_county'], 
									'country' => $_SESSION['country'], 
									'delivery_date' => $_SESSION['delivery_date'],
									'user_card_msg' => $_SESSION['user_card_msg'], 
									'user_notes' => $_SESSION['user_notes'], 
									'user_id' => $_SESSION['user_id'],
									'key' => $_SESSION['key'], 
									'user_id' => $_SESSION['user_id'], 
									'oid' => $orderID,);



			$post_data = array( 'item_name' => $item_name, 
								'item_number' => $item_number, 
								'payment_status' => $payment_status, 
								'mc_gross' => $payment_amount, 
								'mc_currency' => $payment_currency, 
								'txn_id' => $txn_id, 
								'receiver_email' => $receiver_email, 
								'payer_email' => $payer_email,
								'order_id' => $orderID, 
								'payment_date' => $payment_date, 
								'payer_id' => $payer_id, 
								'payment_type' => $payment_type,
								'user_id' => $_SESSION['user_id'], 
								'user_key' => $_SESSION['key'],
								'delivery_charges' => $_SESSION['delivery_charges'],
								'discount_offer' => $discount_offer);
			
			$order_url = API_PATH.'payement-detail';
			$req = $obj->httpPost($order_url, $post_data);
			if($req){
				unset($_SESSION['discount_offer']);
				$product_url = API_PATH.'ordered-product/'.$orderID.'/'.$_SESSION['key'].'';
				$postOrderID = $obj->httpPost($product_url, $delivery_args);
				print_r($postOrderID);
				if($postOrderID){
					header("location:success.php?msg=success");
				}
			}

		}
	}

	// $paypalPayment = $_REQUEST['payment_status'];
	//$paypalstatus = 'Completed';//Paypal
	//$paypalstatus = 'Pending'; //Sandbox
	//@mail('gaurav.s@perceptive-solutions.com', 'PAYPAL SUCCESS POST DATA', json_encode($_REQUEST,true));
?>
	 
	 
	 
	 <div class="pay-success">Thank you for your purchase..</div>
	 
	 
	
</section>
	</div>
		</div>
			</div>

<?php include_once 'footer.php'; ?>
