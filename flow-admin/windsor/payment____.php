<?php

include_once 'include/init.php';

// PayPal settings
$paypal_email = 'gaurav.mysql@gmail.com';
$return_url = 'http://54.191.172.136:82/florist-windsor/success.php';
$cancel_url = 'http://54.191.172.136:82/florist-windsor/cancel.php';
$notify_url = 'http://54.191.172.136:82/florist-windsor/payment.php';

$item_name = 'Test Item, Test Item1, Test Item2';
$item_amount = 5.00;
$hata = $_POST;
@mail('gaurav.s@perceptive-solutions.com', 'PAYPAL PRE POST DATA', json_encode($hata,true));
 
// Check if paypal request or response
if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])){
	$querystring = '';
	
	// Firstly Append paypal account to querystring
	$querystring .= "?business=".urlencode($paypal_email)."&";
	
	// Append amount& currency (£) to quersytring so it cannot be edited in html
	
	//The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
	$querystring .= "item_name=".urlencode($item_name)."&";
	$querystring .= "amount=".urlencode($item_amount)."&";
	
	//loop for posted values and append to querystring
	foreach($_POST as $key => $value){
		$value = urlencode(stripslashes($value));
		$querystring .= "$key=$value&";
	}
	
	// Append paypal return addresses
	$querystring .= "return=".urlencode(stripslashes($return_url))."&";
	$querystring .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
	$querystring .= "notify_url=".urlencode($notify_url);
	
	// Append querystring with custom field
	//$querystring .= "&custom=".USERID;
	
	// Redirect to paypal IPN
	header('location:https://www.sandbox.paypal.com/cgi-bin/webscr'.$querystring);
	exit();
} else {
	
	//@mail('gaurav.s@perceptive-solutions.com', 'PAYPAL POST - VERIFIED RESPONSE', 'abc');
	//Database Connection
	
	$dbhost = "localhost";
	$dbname = "florist_admin";
	$dbusername = "florist_admin";
	$dbpassword = "pcs@pcs";

	$link = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbusername, $dbpassword);
	
	// Response from Paypal

	// read the post from PayPal system and add 'cmd'
	$req = 'cmd=_notify-validate';
	foreach ($_POST as $key => $value) {
		$value = urlencode(stripslashes($value));
		$value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i','${1}%0D%0A${3}',$value);// IPN fix
		$req .= "&$key=$value";
	}
	
	
	
	$item_name = $_POST['item_name'];
	$item_number = $_POST['item_number'];
	$payment_status = $_POST['payment_status'];
	$payment_amount = $_POST['mc_gross'];
	$payment_currency = $_POST['mc_currency'];
	$txn_id = $_POST['txn_id'];
	$receiver_email = $_POST['receiver_email'];
	$payer_email = $_POST['payer_email'];
	$custom = $_POST['custom'];

	
	/*$post_data = array('item_name' => $item_name, 'item_number' => $item_number, 'payment_status' => $payment_status, 
						'mc_gross' =>  $payment_amount, 'mc_currency' => $payment_currency, 'txn_id' => $txn_id, 
						'receiver_email' => $receiver_email, 'payer_email' => $payer_email, 'custom' => $custom, 
						'order_id' => $_SESSION['orderID']);*/
	
	
	
	// post back to PayPal system to validate
	$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	
	$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
	
	if (!$fp) {
		// HTTP ERROR
		
		
		
	} else {
		fputs($fp, $header . $req);
		while (!feof($fp)) {
			$res = fgets ($fp, 1024);

	
			 if (true || strcmp($res, "VERIFIED") == 0) {
				
				/*$order_url = 'http://54.191.172.136:82/florist-admin/flowers/api/payement-detail';
				$req = $obj->httpPost($order_url, $post_data);*/
				
				if($item_number){
					$sql = "INSERT INTO op2mro9899_payments(item_name,
									item_number,
									payment_status,
									mc_gross,
									mc_currency,
									txn_id,
									receiver_email,
									payer_email,
									filmReview) 
					VALUES (
						:item_name, 
						:item_number, 
						:payment_status, 
						:mc_gross, 
						:mc_currency,
						:txn_id,
						:receiver_email,
						:payer_email
					)";

					$stmt = $link->prepare($sql);

					$stmt->bindParam(':item_name', $item_name, PDO::PARAM_STR);       
					$stmt->bindParam(':item_number', $item_number, PDO::PARAM_STR); 
					$stmt->bindParam(':payment_status', $payment_status, PDO::PARAM_STR);
					$stmt->bindParam(':mc_gross', $mc_gross, PDO::PARAM_STR);
					$stmt->bindParam(':mc_currency', $mc_currency, PDO::PARAM_STR);
					$stmt->bindParam(':txn_id', $txn_id, PDO::PARAM_STR);
					$stmt->bindParam(':receiver_email', $receiver_email, PDO::PARAM_STR);
					$stmt->bindParam(':payer_email', $payer_email, PDO::PARAM_STR);
					$stmt->execute();
										
					@mail('gaurav.s@perceptive-solutions.com', 'PAYPAL POST - VERIFIED RESPONSE', print_r($stmt, true));
			}
											
				// Used for debugging
				// mail('user@domain.com', 'PAYPAL POST - VERIFIED RESPONSE', print_r($post, true));
						
				// Validate payment (Check unique txnid & correct price)
				
				//$valid_txnid = check_txnid($data['txn_id']);
				//$valid_price = check_price($data['payment_amount'], $data['item_number']);
				
				// PAYMENT VALIDATED & VERIFIED!
				/*if ($valid_txnid && $valid_price) {
					
					//$orderid = updatePayments($data);
					
					if ($orderid) {
						
						// Payment has been made & successfully inserted into the Database
					} else {
						
						// Error inserting into DB
						// E-mail admin or alert user
						// mail('user@domain.com', 'PAYPAL POST - INSERT INTO DB WENT WRONG', print_r($data, true));
					}
				} else {
					// Payment made but data has been changed
					// E-mail admin or alert user
				}*/
			
			} elseif ($res=='INVALID') {
			
				/*$url = 'http://54.191.172.136:82/florist-admin/flowers/api/payement-detail';
				$responce = $obj->httpPost($url, $post_data);*/
				
				
				
				
				$sql = "INSERT INTO op2mro9899_payments(item_name,
								item_number,
								payment_status,
								mc_gross,
								mc_currency,
								txn_id,
								receiver_email,
								payer_email,
								filmReview) 
				VALUES (
					:item_name, 
					:item_number, 
					:payment_status, 
					:mc_gross, 
					:mc_currency,
					:txn_id,
					:receiver_email,
					:payer_email
				)";

				$stmt = $link->prepare($sql);

				$stmt->bindParam(':item_name', $item_name, PDO::PARAM_STR);       
				$stmt->bindParam(':item_number', $item_number, PDO::PARAM_STR); 
				$stmt->bindParam(':payment_status', $payment_status, PDO::PARAM_STR);
				$stmt->bindParam(':mc_gross', $mc_gross, PDO::PARAM_STR);
				$stmt->bindParam(':mc_currency', $mc_currency, PDO::PARAM_STR);
				$stmt->bindParam(':txn_id', $txn_id, PDO::PARAM_STR);
				$stmt->bindParam(':receiver_email', $receiver_email, PDO::PARAM_STR);
				$stmt->bindParam(':payer_email', $payer_email, PDO::PARAM_STR);
				$stmt->execute();
				
										
				@mail('gaurav.s@perceptive-solutions.com', 'PAYPAL POST - INSERT INTO DB WENT WRONG', print_r($stmt, true));
				
							
				// PAYMENT INVALID & INVESTIGATE MANUALY!
				// E-mail admin or alert user
				
				// Used for debugging
				//@mail("user@domain.com", "PAYPAL DEBUGGING", "Invalid Response<br />data = <pre>".print_r($post, true)."</pre>");
			}
		}
	fclose ($fp);
	}
}
?>
