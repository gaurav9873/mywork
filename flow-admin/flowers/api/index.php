<?php
require 'Slim/Slim.php';
require_once 'custom-functions.php';
require_once 'jwt_helper.php';
$app = new Slim();
error_reporting(E_ALL);
ini_set('display_errors', '1');
header("Content-Type:application/json");
//header("Access-Control-Allow-Origin: *");
$headers = apache_request_headers();
//print_r($headers); die;
$auth = $headers['Authorization'];
$key = "pjuJ-Xp;/t0y<.X:#06.]7&M[YWLly.sOa0h|t!{yRnG,B!RF`r}CfNQ{)#w*f";
$decoded = JWT::decode($auth, $key, array('HS256'));
$decoded_array = (array) $decoded;
$user_id = $decoded_array['user_id'];
$host = $headers['Host'];
$tmphost = 'localhost';
$tmpuserid = '8302e8318c2ed9cc976c54f45dfcebd3';

$app->get('/products', 'getAllProducts');
$app->get('/category_products/:site_id/:cat_id', 'getProductBySiteIdAndCategoryID');
$app->get('/cat-product/:site_id/:cat_id', 'get_product_bysiteid_andcategoryid');
$app->get('/cat-product-image/:productID', 'get_product_image');
$app->get('/product_detail/:product_id/:site_id', 'productDetailByID');
$app->get('/product_image/:pid', 'product_image');
$app->get('/product_cart/:pid', 'getProductDetailByPid');
$app->get('/product_cart_price_bysiteid/:product_id/:sid', 'get_product_bypid_andsiteid');
$app->get('/gift_item/:site_id', 'getGiftsBySiteID');
$app->get('/gift-attribs/:gift_id', 'get_gifts_attributes');
$app->get('/gift-attribs-prices/:pid', 'get_attributes_price');
$app->get('/gifts/:giftID', 'gifDetailByID');
$app->get('/category-image/:site_id', 'getCategoryImageBySiteID');
$app->get("/all-categories/:site_id", 'getAllCategoryBySiteID');
$app->get('/parent-category', 'getParentCategory');
$app->get('/child-category/:parents_id', 'childCategoryByID');
$app->get('/all-childs', 'getAllChildCategory');
$app->get("/gallery-image/:site_id", 'getGalleryCategory');
$app->get("/all-gallery/:cat_id", "getGalleryImageByCatID");
$app->get("/sub-category/:cid", "gallery_subcat");
$app->get("/sub-cat-image/:gal_id", "gallery_subcat_image");
$app->get("/site-category/:siteId", "getParentCategoryBySiteID");
$app->get('/user-data/:api_key', 'getUserRecord');
$app->get('/billing-address/:user_key', 'userBillingAddresses');
$app->get('/billing-address-id/:apiKey/:address_id', 'userAddressByID');
$app->post("/advance-search", "searchProductByIds");
$app->post('/register', 'registerCustomer');
$app->post('/login', 'userLogin');
$app->post('/forget-password', 'forget_password');
$app->post('/product-order', 'productOrder');
$app->post('/addresses/:user_key', 'updateAddress');
$app->get('/orderid/:order_id', 'checkOrderID');
$app->post('/payement-detail', 'insertPaymentDetail');
$app->post('/ordered-product/:order_id/:user_key', 'orderedProduct');
$app->get('/check-postcode/:post_code/:site_id', 'checkPostCode');
$app->get('/default-charge', 'default_delivery_charge');
$app->get('/account-detail/:user_key', 'getAccountDetail');
$app->post('/update-account/:user_key', 'updateAccountDetail');
$app->get('/payement-history/:user_id', 'payement_detail_by_user_id');
$app->get('/order-history/:user_key', 'getUserOrderDetail');
$app->post('/addmore-billing-address/:user_key', 'addMoreBillingAddress');
$app->get('/delete-billing-address/:user_key/:billing_id', 'deleteAddress');
$app->get('/child-cat/:site_id/:parentID', 'allChildCategory');
$app->get('/check-coupon/:coupon_code/:site_id', 'checkCouponCode');
$app->get('/cat-desc/:site_id/:cat_ids', 'get_category_detail');
$app->get('/search-product/:search_string', 'search_product');
$app->get('/pro-img/:pid', 'search_pimage');
$app->get('/slider/:site_id', 'get_slider');
$app->get('/home-content/:site_id', 'get_home_content');
$app->get('/page-menu', 'get_menu_page');
$app->get('/page-detail/:page_id', 'get_page_content_by_id');
$app->get('/cat-menu/:site_id', 'category_on_menu');
$app->get('/child-cat-menu/:parents_id', 'child_category_menu');
$app->get('/update-coupon/:couponCode/:site_id', 'update_coupon_number');
$app->get('/pcat-smap', 'get_all_parent_category');
$app->get('/child-smap/:parent_cat_id', 'get_all_child_category');
$app->get('/shipping-cost', 'get_all_shipping_codes');
$app->get('/all-order/:orderid', 'order_detail_by_orderid');
$app->get('/all-testimonial', 'getall_testimonial');
$app->get('/match-holiday-date/:holiday_date', 'match_holiday_date');
if($user_id == $tmpuserid && $host == $tmphost || $host == 'www.fleurdelisflorist.co.uk'){
	$app->run();
}else{
	$app->stop();
    exit;
}

$api_error = array();

	function getUserIP(){
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];

		if(filter_var($client, FILTER_VALIDATE_IP)){
			$ip = $client;
		}
		elseif(filter_var($forward, FILTER_VALIDATE_IP)){
			$ip = $forward;
		} else {
			$ip = $remote;
		}
		return $ip;
	}
	
	function EncryptClientId($id){
		return substr(md5($id) , 0, 8) . dechex($id);
	}

	function DecryptClientId($id){
		$md5_8 = substr($id, 0, 8);
		$real_id = hexdec(substr($id, 8));
		return ($md5_8 == substr(md5($real_id) , 0, 8)) ? $real_id : 0;
	}
	
	function unique_salt() {
		return substr(sha1(mt_rand()), 0, 22);
	}
	

	function userExist($api){
		$sql = "SELECT id, customer_id, unique_key FROM op2mro9899_customers_login WHERE unique_key = :api";
		try{
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindParam("api", $api);
			$stmt->execute();
			//$count = $stmt->rowCount();
			$record = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			return $record;
		}catch(PDOException $e){
			echo '{"error":{"text": '.$e->getMessage().'}}';
			return false;
		}
	}
	
	function insert_records($table,$data){
		
		if(!empty($data) && is_array($data)){
			$columns = '';
			$values  = '';
			$i = 0;
			$data['created_date'] = date("Y-m-d H:i:s");
			$data['created_ip'] = getUserIP();
			
			$columnString = implode(',', array_keys($data));
			$valueString = ":".implode(',:', array_keys($data));
			
			 $sql = "INSERT INTO $table ($columnString) VALUES ($valueString)";
			
			$db = getConnection();
			$query = $db->prepare($sql);
			
			foreach($data as $key=>$val){
				$query->bindValue(':'.$key, $val);
			}
		
			$insert = $query->execute();
			if($insert){
				$data = $db->lastInsertId();
			return $data;
			} else {
				return false;
			}
		} else {
			throw new Exception('data variable not found');
			return false;
		}
	}
	
	
	function update_record($table,$data,$conditions){
		try{
			
			if(!empty($data) && is_array($data)){
				$colvalSet = '';
				$whereSql = '';
				$i = 0;
				
				foreach($data as $key=>$val){
					$pre = ($i > 0)?', ':'';
					$val = htmlspecialchars(strip_tags($val));
					$colvalSet .= $pre.$key."='".$val."'";
					$i++;
				}
				if(!empty($conditions)&& is_array($conditions)){
					$whereSql .= ' WHERE ';
					$i = 0;
					foreach($conditions as $key => $value){
						$pre = ($i > 0)?' AND ':'';
						$whereSql .= $pre.$key." = '".$value."'";
						$i++;
					}
				}
				$sql = "UPDATE ".$table." SET ".$colvalSet.$whereSql;
				$db = getConnection();
				$query = $db->prepare($sql);
				$update = $query->execute();
				$api_error['status'] = 'true';
				$api_error['affected_rows'] = $query->rowCount();
			}else{
				$api_error['status'] = 'false';
				$api_error['affected_rows'] = 0;
				$api_error['message'] = "Record not found";
				throw new Exception($api_error);
			}
		 }catch(PDOException $e){
			throw new Exception($e);
			
		 }
		 return $api_error;
	}
	
	
	function deleteRecord($table,$conditions){
		try{
			$whereSql = '';
			if(!empty($conditions)&& is_array($conditions)){
				$whereSql .= ' WHERE ';
				$i = 0;
				foreach($conditions as $key => $value){
					$pre = ($i > 0)?' AND ':'';
					$whereSql .= $pre.$key." = '".$value."'";
					$i++;
				}
				
				$sql = "DELETE FROM ".$table.$whereSql;
				$db = getConnection();
				$delete = $db->exec($sql);
				return $delete;
			}else{
				$api_error['status'] = 'false';
				$api_error['affected_rows'] = 0;
				$api_error['message'] = "Record not found";
				throw new Exception($api_error);
			}
			
		}catch(PDOException $e){
			throw new Exception($e);
		}
	}
	
	
	function deleteAddress($user_key, $billing_id){
		try{
			$chk_valid_user = userExist($user_key);
			$unique_key = $chk_valid_user[0]->unique_key;
			if($unique_key == $user_key){
				$condition = array('id' => $billing_id);
                $delete = deleteRecord('op2mro9899_customers_billing_address',$condition);
                if($delete){
					echo json_encode(array('status' => 'true', 'message' => 'successfully deleted'));
				}else{
					echo json_encode(array('status' => 'false', 'message' => 'something went wrong'));
				}
			}
		}catch(PDOException $e){
			echo '{"error":{"text": '.$e->getMessage().'}}';
			return false;
		}
	}
	
	
	
	function updateAddress($user_key){
		try{
			$auth = userExist($user_key);
			$unique_key = $auth[0]->unique_key;
			if($unique_key == $user_key){
				$uid = $auth[0]->id;
				$customer_id = $auth[0]->customer_id;
				$request = Slim::getInstance()->request();
				$post_vars = json_decode($request->getBody());
				
				$adressId  = DecryptClientId($post_vars->active_address);
				$update_args = array( 'user_prefix' => $post_vars->user_prefix, 
									  'user_first_name' => $post_vars->user_fname, 
									  'user_last_name' => $post_vars->user_lname, 
									  'user_postcode' => $post_vars->post_code,  
									  'user_house_number' => '&nbsp;',
									  'primary_address' => $post_vars->primary_address, 
									  'secondary_address' => $post_vars->secondary_address,
									  'user_city' => $post_vars->user_city, 
									  //'user_county' => $post_vars->user_county,
									  'user_country' => $post_vars->user_country, 
									  'user_pcode' => $post_vars->user_pcode,
									  'user_phone' => $post_vars->user_phone,
									  'user_emailid' => $post_vars->user_emailid,
									  'customer_id' => $customer_id,
									  'site_id' => $post_vars->site_id
									);
				
				if(isset($post_vars->default_address)){
					if($post_vars->default_address == 1){
						$change_staus = array('default_address' => 0);
						$con = array('user_id' => $uid);
						$changeStmt = update_record('op2mro9899_customers_billing_address', $change_staus, $con);
						if($changeStmt){
							$update_args['default_address'] = 1;
						}
					}
				}
									
				$condition = array('id' => $adressId);
				try{
					$update_stmt = update_record('op2mro9899_customers_billing_address', $update_args, $condition);
					
					echo json_encode($update_stmt);
				} catch(PDOException $e){
					echo json_encode($e->getMessage());
				}
				
			}else{
				echo json_encode(array('status' => 'false', 'message' => 'you are not valid user'));
			}
		} catch(PDOException $e){
			echo json_encode($e->getMessage());
		}
		
	}
	
	
	function addMoreBillingAddress($user_key){
		$request = Slim::getInstance()->request();
		$post_array = json_decode($request->getBody());

		try{
			$chk_valid_usr = userExist($user_key);
			$unique_key = $chk_valid_usr[0]->unique_key;
			$uid = $chk_valid_usr[0]->id;
			$customer_id = $chk_valid_usr[0]->customer_id;
			if($unique_key == $user_key){

				$billing_args = array( 'user_prefix' => $post_array->user_prefix, 
									   'user_first_name' => $post_array->user_fname, 
									   'user_last_name' => $post_array->user_lname, 
									   'user_postcode' => $post_array->post_code, 
									   'user_house_number' => $post_array->user_hnumber,
									   'primary_address' => $post_array->primary_address,
									   'secondary_address' => $post_array->secondary_address,
									   'user_city' => $post_array->user_city,
									   //'user_county' => $post_array->user_county,
									   'user_country' => $post_array->user_country,
									   'user_phone' => $post_array->user_phone,
									   'user_emailid' => $post_array->user_emailid,
									   'user_id' => $uid,
									   'customer_id' => $customer_id,
									   'site_id' => $post_array->site_id
									   );

				if(isset($post_array->default_address)){
					if($post_array->default_address == 1){
						$change_staus = array('default_address' => 0);
						$con = array('user_id' => $uid);
						$changeStmt = update_record('op2mro9899_customers_billing_address', $change_staus, $con);
						if($changeStmt){
							$billing_args['default_address'] = 1;
						}
					}
				}
									   
				$bill_stmt = insert_records('op2mro9899_customers_billing_address', $billing_args);
				if($bill_stmt){
					echo json_encode(array('status' => 'true', 'message' => 'success', 'bill_id' => $bill_stmt));
				}else{
					echo json_encode(array('status' => 'false', 'message' => 'something went wrong'));
				}
					
			}else{
				echo json_encode(array('status' => 'false', 'message' => 'you are not a valid user'));
			}
	} catch(PDOException $e){
		echo '{"error":{"text": '.$e->getMessage().'}}';
		return false;
	}
	}
	
	
	
	
	function login_customer($user_email, $user_password, $site_id){
		$sql = "SELECT id, user_email, user_password, user_first_name, user_last_name, customer_id, unique_key, site_id, pwd_status FROM op2mro9899_customers_login WHERE 
				user_email = :user_email AND user_password = :user_password AND site_id = :site_id";
		try{
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindParam("user_email", $user_email);
			$stmt->bindParam("user_password", $user_password);
			$stmt->bindParam("site_id", $site_id);
			$stmt->execute();
			$userRow = $stmt->fetch(PDO::FETCH_OBJ);
			return $userRow;
		} catch(PDOException $e) {
			echo '{"error":{"text": '.$e->getMessage().'}}';
			return false;
		}
	}
	
	
	
	function isEmailExists($email){
		$sql_query = "SELECT user_email, pwd_status FROM op2mro9899_customers_login WHERE user_email = :email";
		try{
			$db = getConnection();
			$stmt_prepare = $db->prepare($sql_query);
			$stmt_prepare->bindParam("email", $email);
			$stmt_prepare->execute();
			$row_count = $stmt_prepare->rowCount();
			return $row_count;
			$db = null;
		} catch(PDOException $e) {
			echo '{"error":{"text": '.$e->getMessage().'}}';
			return false;
		}
	}
	
	
	function chk_exist_email($emailID, $site_id){
		$sql_query = "SELECT id, user_email, site_id, pwd_status FROM op2mro9899_customers_login WHERE user_email = :emailID AND site_id = :site_id";
		try{
			$db = getConnection();
			$stmt_prepare = $db->prepare($sql_query);
			$stmt_prepare->bindParam("emailID", $emailID);
			$stmt_prepare->bindParam("site_id", $site_id);
			$stmt_prepare->execute();
			$existemail = $stmt_prepare->fetchAll(PDO::FETCH_OBJ);
			return $existemail;
			$db = null;
		} catch(PDOException $e) {
			echo '{"error":{"text": '.$e->getMessage().'}}';
			return false;
		}
	}
	
	function voucher_code($site_id){
		$sql = "SELECT id, coupon_code, site_id FROM `op2mro9899_coupons` WHERE site_id = '".$site_id."' ORDER BY id DESC LIMIT 0, 1";
		try{
			$db = getConnection();
			$stmt_prepare = $db->query($sql);
			$stmt_prepare->execute();
			$voucher = $stmt_prepare->fetchAll(PDO::FETCH_ASSOC);
			return $voucher[0]['coupon_code'];
			$db = null;
		}catch(PDOException $e) {
			echo '{"error":{"text": '.$e->getMessage().'}}';
			return false;
		}
	}
	
	
	function userLogin(){
		
		$request = Slim::getInstance()->request();
		$allPostVars = json_decode($request->getBody());

		try{
			
			$first_time_login = chk_exist_email($allPostVars->user_email, $allPostVars->site_id);
			if($first_time_login[0]->pwd_status == 0){
				$pass = randomPassword();
				$pasword = hash('sha256', $pass);
				$unique_salt = $first_time_login[0]->id.unique_salt();
						
				//Update Password
				$cust_args = array('user_password' => trim($pasword), 'unique_key' => $unique_salt, 'pwd_status' => 1);
				$condition = array('id' => $first_time_login[0]->id);
				$update_stmt = update_record('op2mro9899_customers_login', $cust_args, $condition);
				$vouchers = voucher_code($allPostVars->site_id);
				if($update_stmt){
					echo json_encode(array('status'=>'updatepassword','data'=>array('user_id' => $first_time_login[0]->id, 'emailID' => $first_time_login[0]->user_email, 'pwd_status' => $first_time_login[0]->pwd_status)));
					$msg  = '<!doctype html>
						<html>
						<head>
						<meta charset="utf-8">
						<title>Update Password</title>
						<style type="text/css">
						@font-face {
						font-family: latolight;
						src: url("http://fleurdelisflorist.co.uk/florist-admin/images/lato-light-webfont.woff2") format("woff2"),  
							 url("http://fleurdelisflorist.co.uk/florist-admin/images/lato-light-webfont.woff") format("woff");
						font-weight: normal;
						font-style: normal;
						}
						</style>
						</head>

						<body style="margin:0; padding:0;">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
						<tr>
						<td align="center" valign="top"><table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
						<tr>
						<td height="120" align="center" valign="middle"><a href="#" target="_blank"><img src="http://fleurdelisflorist.co.uk/florist-admin/images/logo.png" width="156" alt="" height="84"></a></td>
						</tr>
						<tr>
						<td><table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border-bottom:1px solid #ccc;">
						<tr align="center" valign="middle">
						<td height="30" width="20%"><a href="#" style="font-size:16px; line-height:55px; font-family: latolight, Arial, Helvetica, sans-serif; text-decoration:none; color:#000000;" target="_blank">Home</a></td>
						<td height="30" width="20%"><a href="#" style="font-size:16px; line-height:55px; font-family: latolight, Arial, Helvetica, sans-serif; text-decoration:none; color:#000000;" target="_blank">Gift Flowers</a></td>
						<td height="30" width="20%"><a href="#" style="font-size:16px; line-height:55px; font-family: latolight, Arial, Helvetica, sans-serif; text-decoration:none; color:#000000;" target="_blank">Weddings</a></td>
						<td height="30" width="20%"><a href="#" style="font-size:16px; line-height:55px; font-family: latolight, Arial, Helvetica, sans-serif; text-decoration:none; color:#000000;" target="_blank">Sympathy</a></td>
						<td height="30" width="20%"><a href="#" style="font-size:16px; line-height:55px; font-family: latolight, Arial, Helvetica, sans-serif; text-decoration:none; color:#000000;" target="_blank">Contact Us</a></td>
						</tr>
						</table></td>
						</tr>
						<tr>
						<td align="center" valign="middle" style="padding:35px 0;">
						<p  style="font-size:24px; line-height:38px; font-family: latolight, Arial, Helvetica, sans-serif; letter-spacing:3px; margin-bottom:0;">Fluer de Lis Florisit, Maidenhead will like to introduce their brand new website.</p>
						<a style="font-size:46px; font-family: latolight, Arial, Helvetica, sans-serif; font-weight:600; text-decoration:none; border-bottom:3px solid #E5437E; color:#E5437E; margin:20px 0 45px 0; display:inline-block;" href="www.fleurdelisflorist.co.uk" target="_blank">fleurdelisflorist.co.uk</a>
						<p  style="font-size:24px; line-height:38px; font-family: latolight, Arial, Helvetica, sans-serif; letter-spacing:3px; margin-bottom:0;">Get <b>25%</b> off  your orders in June and July. For discounts, please use voucher code: '.$vouchers.'</p>
						<p style="font-size:22px; line-height:35px; font-family: latolight, Arial, Helvetica, sans-serif; display:block; margin:0 25px 45px;">Please use this new password to Login: '.$pass.'</p>
						<a style="padding:10px 25px; font-size:14px; font-family: latolight, Arial, Helvetica, sans-serif; color:#fff; text-decoration:none; letter-spacing:3px; border-radius:5px; -moz-border-radius:5px; -webkit-border-radius:5px; text-transform:uppercase; background:#E5437E;" href="#" target="_blank">Login</a></td>
						</tr>
						<tr>
						<td height="361" align="center" valign="middle"><a href="#" target="_blank"><img src="http://fleurdelisflorist.co.uk/florist-admin/images/floristSite.jpg" alt="" width="590" height="361"></a></td>
						</tr>
						<tr>
						<td bgcolor="f3f3f3" align="center" valign="middle" style="padding:15px 15px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
						<td align="center" valign="middle" width="33.33%"><a href="#" target="_blank"  style="font-size:13px; line-height:15px; font-family: latolight, Arial, Helvetica, sans-serif; text-decoration:none; color:#000000;"> NO FREE<br />DELIVERY</a></td>
						<td align="center" valign="middle" width="33.33%"><a href="#" target="_blank"  style="font-size:13px; line-height:15px; font-family: latolight, Arial, Helvetica, sans-serif; text-decoration:none; color:#000000;">NEXT DAY DELIVERY WHEN<br>ORDER BEFORE 3PM</a></td>
						<td align="center" valign="middle" width="33.33%"><a href="#" target="_blank"  style="font-size:13px; line-height:15px; font-family: latolight, Arial, Helvetica, sans-serif; text-decoration:none; color:#000000;">NO FREE<br>RETURNS</a></td>
						</tr>
						</table></td>
						</tr>
						<tr>
						<td align="center" valign="middle" style="padding:25px 0 0;"><a href="#." target="_blank" style="padding:5px 5px 0 5px; border:1px solid #fff;"><img src="http://fleurdelisflorist.co.uk/florist-admin/images/social-facebook.png" width="16" height="16"></a> <a href="#." target="_blank" style="padding:5px 5px 0 5px; border:1px solid #fff;"><img src="http://fleurdelisflorist.co.uk/florist-admin/images/twitter.png" width="16" height="16"></a> <a href="#." target="_blank" style="padding:5px 5px 0 5px; border:1px solid #fff;"><img src="http://fleurdelisflorist.co.uk/florist-admin/images/Google_Plus.png" width="16" height="16"></a> <a href="#." target="_blank" style="padding:5px 5px 0 5px; border:1px solid #fff;"><img src="http://fleurdelisflorist.co.uk/florist-admin/images/youtube.png" width="16" height="16"></a></td>
						</tr>
						<tr>
						<td align="center" valign="middle"><p style="font-size:15px; line-height:22px; font-family: latolight, Arial, Helvetica, sans-serif; text-decoration:none; color:#000000;">&copy; copyright 2010 Fleur de lis<br>
						Ecommerce SolutionPowered By :Infinity</p></td>
						</tr>
						</table></td>
						</tr>
						</table>
						</body>
					</html>';
					$subj = 'Update your password - Fleur de Lis, Maidenehad';
					$to   = $allPostVars->user_email;
					$from = 'info@fleurdelisflorist.co.uk';
					$name = 'Fleur de Lis';
					echo password_smtpmailer($to,$from, $name ,$subj, $msg);
				}
			}else{
				$enc_password = hash('sha256', $allPostVars->user_password);
				$str = trim($enc_password);
				$login = login_customer($allPostVars->user_email, $str, $allPostVars->site_id);
				if($login){
					echo json_encode(array('status'=>'true','data'=>array('user_id' => $login->id, 'emailID' => $login->user_email, 'fname' => $login->user_first_name, 
								'lname' => $login->user_last_name, 'user_key' => $login->unique_key, 'siteKey' => $login->site_id, 'pwd_status' => $login->pwd_status)));
				}else{
					echo json_encode(array('status'=>'false','data'=>array()));
				}
			}
		} catch(PDOException $e) {
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	
	function forget_password(){
		$request = Slim::getInstance()->request();
		$post_string = json_decode($request->getBody());
		try{
			$chk_valid_email = chk_exist_email($post_string->forget_email, $post_string->site_id);
			if(!empty($chk_valid_email)){
				$pass = randomPassword();
				$pasword = hash('sha256', $pass);
				$uids = $chk_valid_email[0]->id;
				$unique_salt = $uids.unique_salt();
				$msg  = 'Please use this password to login: '.trim($pass).'<br /><br /> From <br /> Fleur De Lis, Maidenhead';
				$subj = 'Reset your Password - Fleur de Lis, Maidenehad';
				$to   = $post_string->forget_email;
				$from = 'info@fleurdelisflorist.co.uk';
				$name = 'Fleurdelis';
				password_smtpmailer($to,$from, $name ,$subj, $msg);
				//Update Password
				$cust_args = array('user_password' => $pasword, 'unique_key' => $unique_salt, 'pwd_status' => 1);
				$condition = array('id' => $uids);
				$update_stmt = update_record('op2mro9899_customers_login', $cust_args, $condition);
				if($update_stmt){
					echo json_encode(array('status' => 'true', 'msg' => 'Please use the password sent on your email id to login'));
				}else{
					echo json_encode(array('status' => 'false', 'msg' => 'Sorry, something went wrong please try again later'));
				}
			}else{
				echo json_encode(array('status' => 'false', 'msg' => 'Email cannot be found.'));
			}
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	
	function getUserRecord($api_key){
		$auth = userExist($api_key);
		$unique_key = $auth[0]->unique_key;
		if($unique_key == $api_key){
			$user_id = $auth[0]->id;
			$sql = "SELECT * FROM op2mro9899_customers_billing_address WHERE user_id = :user_id AND default_address = 1";
			try{
				$db = getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam('user_id', $user_id);
				$stmt->execute();
				$user_data = $stmt->fetchAll(PDO::FETCH_OBJ);
				$db = null;
				echo '{"user_record": '.json_encode($user_data).'}';
			}catch(PDOException $e){
				echo '{"error":{"text": '.$e->getMessage().'}}';
				return false;
			}
		} else {
			echo json_encode(array('status' => 'false', 'message' => 'false authenticate')); die;
		}
	}
	
	function userBillingAddresses($user_key){
		$auth = userExist($user_key);
		$unique_key = $auth[0]->unique_key;
		if($unique_key == $user_key){
			$uid = $auth[0]->id;
			$query = "SELECT id, user_id FROM op2mro9899_customers_billing_address WHERE user_id = :uid";
			try{
				$db = getConnection();
				$query_stmt = $db->prepare($query);
				$query_stmt->bindParam("uid", $uid);
				$query_stmt->execute();
				$all_address = $query_stmt->fetchAll(PDO::FETCH_OBJ);
				$db = null;
				echo '{"billing_address": '.json_encode($all_address).'}';
			} catch(PDOException $e){
				echo '{"error":{"text": '.$e->getMessage().'}}';
				return false;
			}
		} else {
			echo json_encode(array('status' => 'false', 'message' => 'false authenticate')); die;
		}
	}
	
	
	
	function userAddressByID($apiKey, $address_id){
		$auth_key = userExist($apiKey);
		$unique_key = $auth_key[0]->unique_key;
		if($unique_key == $apiKey){
			$id = DecryptClientId($address_id);
			$sql = "SELECT * FROM op2mro9899_customers_billing_address WHERE id = :address_id";
			try{
				$db = getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam('address_id', $id);
				$stmt->execute();
				$billingAdd = $stmt->fetchAll(PDO::FETCH_OBJ);
				$db = null;
				echo '{"user_record": '.json_encode($billingAdd).'}';
			}catch(PDOException $e){
				echo '{"error":{"text": '.$e->getMessage().'}}';
				return false;
			}
		} else {
			echo json_encode(array('status' => 'false', 'message' => 'false authenticate'));
		}
	}
	
	
	function registerCustomer(){
		$request = Slim::getInstance()->request();
		$allPostVars = json_decode($request->getBody());
		
		$pasword = hash('sha256', $allPostVars->user_password);
		$enc_password = hash('sha256', $allPostVars->user_password);
		try{
			$exist_email = isEmailExists($allPostVars->user_emailid);
			if($exist_email > 0){
				echo json_encode(array('email_status' => 'false', 'message' => 'This email id is already registered. Please login with your UID & Password'));
			}else{
				
				$rand = sprintf("%06d",rand(1,999999));
				$rand_num = rand(100000,999999);
				$customerID = $rand + $rand_num;
				$reg_date = date("Y-m-d");
				$login_args = array('user_email' => $allPostVars->user_emailid, 'user_password' => trim($pasword), 'user_first_name' => $allPostVars->user_fname, 
				'user_last_name' => $allPostVars->user_lname, 'reg_date' => $reg_date, 'site_id' => '1', 'pwd_status' => '1');

				$insert_stmt = insert_records('op2mro9899_customers_login', $login_args);
				if($insert_stmt){
										
					$cust_id = $customerID.$insert_stmt;
					$usalt = $insert_stmt.unique_salt();
					$cust_args = array('customer_id' => $cust_id, 'unique_key' => $usalt);
					$condition = array('id' => $insert_stmt);
					$update_stmt = update_record('op2mro9899_customers_login', $cust_args, $condition);
										
					$address_args = array(	'user_prefix' => $allPostVars->user_prefix, 
											'user_first_name' => $allPostVars->user_fname, 
											'user_last_name' => $allPostVars->user_lname, 
											'user_postcode' => $allPostVars->post_code,
											'primary_address' => $allPostVars->primary_address, 
											'secondary_address' => $allPostVars->secondary_address, 
											'user_city' => $allPostVars->user_city, 
											//'user_county' => $allPostVars->user_county,
											'user_country' => $allPostVars->user_country, 
											'user_pcode' => $allPostVars->user_pcode,
											'user_phone' => $allPostVars->user_phone, 
											'user_emailid' => $allPostVars->user_emailid,
											'user_id' =>  $insert_stmt,
											'customer_id' => $cust_id,
											'site_id' => '1', 
											'default_address' => '1', 
											'reg_date' => $reg_date);

					$new_user = insert_records('op2mro9899_customers_billing_address', $address_args);

					if($new_user){
						$login = login_customer($allPostVars->user_emailid, $enc_password);
						
						if($login){
							echo json_encode(array('status'=>'true','data'=>array('user_id' => $login->id, 'emailID' => $login->user_email, 'fname' => $login->user_first_name, 
							'lname' => $login->user_last_name, 'user_key' => $login->unique_key, 'siteKey' => $login->site_id)));
						}
					} else {
						echo json_encode(array('status' => 'false', 'message' => 'Something went wrong'));
					}
				}
			}
		} catch(PDOException $e) {
			echo '{"error":{"text": '.$e->getMessage().'}}';
			return false;
		}
	    
	}
	
	
	function checkOrderID($order_id){
		$sql_query = "SELECT * FROM op2mro9899_tmp_order WHERE order_id = :order_id";
		try{
			$db = getConnection();
			$stmt_prepare = $db->prepare($sql_query);
			$stmt_prepare->bindParam("order_id", $order_id);
			$stmt_prepare->execute();
			$order_ids = $stmt_prepare->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"order_ids": ' . json_encode($order_ids) . '}';
		} catch(PDOException $e) {
			echo '{"error":{"text": '.$e->getMessage().'}}';
			return false;
		}
	}
	
	
	function productDecode($oid){
		$sql_query = "SELECT * FROM op2mro9899_tmp_order WHERE order_id = :oid";
		try{
			$db = getConnection();
			$stmt_prepare = $db->prepare($sql_query);
			$stmt_prepare->bindParam("oid", $oid);
			$stmt_prepare->execute();
			$order_products = $stmt_prepare->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			return $order_products;
		} catch(PDOException $e) {
			echo '{"error":{"text": '.$e->getMessage().'}}';
			return false;
		}
	}
	
	
	function insertPaymentDetail(){
		$request = Slim::getInstance()->request();
		$dataString = json_decode($request->getBody());

		try{
			$ordered_dates = date("Y-m-d");
			$payement_args = array('item_name' => $dataString->item_name, 
								   'item_number' => $dataString->item_number, 
								   'payment_status' => $dataString->payment_status, 
								   'mc_gross' =>  $dataString->mc_gross, 
								   'mc_currency' => $dataString->mc_currency, 
								   'txn_id' => $dataString->txn_id, 
						           'receiver_email' => $dataString->receiver_email, 
						           'payer_email' => $dataString->payer_email,
						           'payment_date' => $dataString->payment_date,
						           'payer_id' => $dataString->payer_id,
						           'payment_type' => $dataString->payment_type,
						           'order_id' => $dataString->order_id,
						           'user_id' => $dataString->user_id,
						           'user_key' => $dataString->user_key,
						           'delivery_charges' => $dataString->delivery_charges,
						           'discount_offer' => $dataString->discount_offer,
						           'site_id' => $dataString->site_id,
						           'ordered_date' => $ordered_dates
						           );
						           
			$insert_stmt = insert_records('op2mro9899_payments', $payement_args);
			if($insert_stmt){
				
				/*$order_args = array('order_id' => $orderID);
				$condition = array('id' => $insert_stmt);
				$update_stmt = update_record('op2mro9899_payments', $order_args, $condition);*/
				
				echo json_encode(array('status' => 'true', 'message' => 'success'));
			}else{
				echo json_encode(array('status' => 'false', 'message' => 'something went wrong'));
			}
		}catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}'; 
			return false;
		}
	}
	
	
	
	
	function product_detailByID($pid){
		$sql = "SELECT * FROM op2mro9899_products WHERE pid = :pid";
		try{
			$db = getConnection();
			$statemtnt = $db->prepare($sql);
			$statemtnt->bindParam("pid", $pid);
			$statemtnt->execute();
			$product_recored = $statemtnt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			return $product_recored;
		}catch(PDOException $e){
			echo '{"error":{"text": '.$e->getMessage().'}}';
			return false;
		}
	}
	
	function product_detail_bysiteid($product_id, $site_id){
		$sql = "SELECT product_id, price, site_id FROM op2mro9899_products_price WHERE product_id = :product_id AND site_id = :site_id";
		try{
			$db = getConnection();
			$stmts = $db->prepare($sql);
			$stmts->bindParam('site_id', $site_id);
			$stmts->bindParam('product_id', $product_id);
			$stmts->execute();
			$productPrices = $stmts->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			return $productPrices;
		}catch(PDOException $e){
			echo '{"error":{"text": '.$e->getMessage().'}}';
			return false;
		}
	}
	
	
	function giftItemByID($gift_id){
		$sql = "SELECT * FROM op2mro9899_gifts_type WHERE id = :gift_id";
		
		try{
			$db = getConnection();
			$gift_statement = $db->prepare($sql);
			$gift_statement->bindParam("gift_id", $gift_id);
			$gift_statement->execute();
			$gift_record = $gift_statement->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			return $gift_record;
		}catch(PDOException $e){
			echo '{"error":{"text": '.$e->getMessage().'}}';
			return false;
		}
		
	}
	
	
	function invoice_order_details($order_id){
		$sql = "SELECT * FROM op2mro9899_ordered_product WHERE order_id = :order_id";
		try{
			$db = getConnection();
			$invoice_stmt = $db->prepare($sql);
			$invoice_stmt->bindparam("order_id", $order_id);
			$invoice_stmt->execute();
			$invoice_order = $invoice_stmt->fetchAll(PDO::FETCH_OBJ);
			return $invoice_order;
		}catch(PDOException $e){
			echo '{"error":{"text": '.$e->getMessage().'}}';
			return false;
		}
	}
	
	function user_payment_details($oid){
		$sql = "SELECT * FROM op2mro9899_payments WHERE order_id = :oid";
		try{
			$db = getConnection();
			$oid_stmt = $db->prepare($sql);
			$oid_stmt->bindparam("oid", $oid);
			$oid_stmt->execute();
			$p_detail = $oid_stmt->fetchAll(PDO::FETCH_OBJ);
			return $p_detail;
		}catch(PDOException $e){
			echo '{"error":{"text": '.$e->getMessage().'}}';
			return false;
		}
	}
	
	function user_billing_addresss($user_id){
		$sql_stmt = "SELECT * FROM op2mro9899_customers_billing_address WHERE user_id = :user_id AND default_address = 1";
		try{
			$db = getConnection();
			$billing_stmt = $db->prepare($sql_stmt);
			$billing_stmt->bindParam("user_id", $user_id);
			$billing_stmt->execute();
			$billing_address = $billing_stmt->fetchAll(PDO::FETCH_OBJ);
			return $billing_address;			
		}catch(PDOException $e){
			echo '{"error":{"text": '.$e->getMessage().'}}';
			return false;
		}
	}
	
		
	function orderedProduct($order_id, $user_key){
			
		$request = Slim::getInstance()->request();
		$postString = json_decode($request->getBody());
		$status = array();
		try{
			//Check Valid User
			$auth_key = userExist($user_key);
			$id = $auth_key[0]->id;
			$unique_key = $auth_key[0]->unique_key;
			if($unique_key == $user_key){

				//Fetch detail by Order Id Temp table
				$pro = productDecode($order_id);
				//product detail
				$product_order = json_decode($pro[0]->product);
				//gift detail
				$gift_item = json_decode($pro[0]->gift);

				$ordered_date = date("Y-m-d");
				
				
				$delivery_args = array( 'post_code' => $postString->post_code, 
										'user_prefix' => $postString->user_prefix,
										'user_name' => $postString->user_fname, 
										'user_lname' => $postString->user_lname, 
										'mobile_number' => $postString->user_mobile, 
										'telephone_number' => $postString->user_telephone, 
										'fax_number' => $postString->user_fax, 
										'email_address' => $postString->delivery_email, 
										'city' => $postString->user_city, 
										'primary_address' => $postString->primary_address,
										'secondary_address' => $postString->secondary_address, 
										//'county' => $postString->user_county, 
										'country' => $postString->country, 
										'user_pcode' => $postString->user_pcode, 
										'delivery_date' => $postString->delivery_date, 
										'card_message' => $postString->user_card_msg, 
										'florist_instruction' => $postString->user_notes, 
										'order_id' => $postString->oid, 
										'user_id' => $postString->user_id, 
										'user_key' => $postString->key,
										'ordered_date' => $ordered_date,
										'site_id' => $postString->site_id);

				$insStmt = insert_records('op2mro9899_delivery_address', $delivery_args);
				if($insStmt){
					
							//Insert Ordered Product
							foreach($product_order as $key => $val){
								$product_size = $val->size;
								$product_id = DecryptClientId($val->product_id);
								$quantity = $val->quantity;

								$product_detailByID = product_detailByID($product_id);
								foreach($product_detailByID as $keys => $postVal){
									
									$product_detail_bysiteid = product_detail_bysiteid($product_id, $postString->site_id);
									$prt_price = $product_detail_bysiteid[$keys]->price;
									
									$product_name = $postVal->product_name;
									$regular_price = $postVal->regular_price;
									$large_price = $postVal->large_price;
									$disscount_price = $postVal->disscount_price;
									$product_code = $postVal->product_code;
									$price = (($disscount_price <> '') ? $disscount_price : $prt_price);
									$final_price = (($product_size == 'large') ? $price+$large_price : $price);
									$quantity_price = $final_price*$quantity;

									$array_args = array( 'product_name' => $product_name,
														 'product_qty' => $quantity,
														 'product_price' => $final_price,
														 'product_qty_price' => $quantity_price,
														 'product_id' => $product_id,
														 'product_size' => $product_size,
														 'product_code' => $product_code,
														 'user_id' => $id,
														 'user_key' => $user_key,
														 'order_id' => $order_id,
														 'ordered_date' => $ordered_date,
														 'flag' => 'product',
														 'site_id' => $postString->site_id);

									$product_ins_stmt = insert_records('op2mro9899_ordered_product', $array_args);

								}
							}

							if(!empty($gift_item)){
								//Insert Gift Detail
								foreach($gift_item as $k => $gift_val){
									$gift_qty = $gift_val;
									
									$gift_item_byid = giftItemByID($k);
									$gft_id = $gift_item_byid[0]->id;
									$gift_cat_id = $gift_item_byid[0]->gift_cat_id;
									$gifts_name = $gift_item_byid[0]->gifts_name;
									$gifts_price = $gift_item_byid[0]->gifts_price;
									$total_qty = $gift_qty*$gifts_price;
									
									$gift_args = array( 'gift_name' => $gifts_name,
										'gift_price' => $gifts_price,
										'gift_qty_price' => $total_qty,
										'gift_quantity' => $gift_qty,
										'gift_id' => $gft_id,
										'gift_code' => 'GIFT',
										'user_id' => $id,
										'user_key' => $user_key,
										'order_id' => $order_id,
										'ordered_date' => $ordered_date,
										'flag' => 'gift',
										'site_id' => $postString->site_id
									);
									$gift_order_stmt = insert_records('op2mro9899_ordered_product', $gift_args);
								}
						}
					
					
						//Mail Template
						
						//Payement Detail
						$user_payment_details = user_payment_details($order_id);
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
						$user_billing_addresss = user_billing_addresss($postString->user_id);
						$post_bill = $user_billing_addresss[0];
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
						<td colspan="2" style="border-bottom: 1px solid #ccc; border-right: medium none; padding: 2mm 0;"><p style="text-align:center; font-weight:bold; margin:0;">Order Number : '.$order_id.'</p></td>
						</tr>';
						$msg .= '<tr>
						<td width="50%" align="left" valign="top"><table width="95%" border="0" cellspacing="10" cellpadding="10" style="border:1px solid #ccc; text-align:left; font-weight:100; margin-top:10px;">
						<tr>
						<th> <h1 style="font-size:14pt; color: #000; font-weight: normal; margin:0 0 10px;">Billing Address</h1>
						<p style="margin:0; padding:0; line-height:22px; font-size:12px; font-weight:100;">Name: '.$post_bill->user_first_name.' <br />
						Last Name: '.$post_bill->user_last_name.' <br />
						Address Line 1: '.$post_bill->primary_address.' <br />
						Address Line 2: '.$post_bill->secondary_address.' <br />
						Town / City: '.$post_bill->user_city.' <br />
						Country: '.$post_bill->user_country.' <br />
						Post Code: '.$post_bill->user_postcode.' <br />
						Telephone Number: '.$post_bill->user_phone.' <br />
						Email Address: '.$post_bill->user_emailid.'</p>
						</th>
						</tr>
						</table></td>';
						$msg .= '<td width="50%" align="right" valign="top"><table width="100%" border="0" cellspacing="10" cellpadding="10" style="border:1px solid #ccc; text-align:left; font-weight:100; margin-top:10px;">
						<tr>
						<th> <h1 style="font-size:14pt; color: #000; font-weight: normal; margin:0 0 10px;">Delivery Address</h1>
						<p style="margin:0; padding:0; line-height:22px; font-size:12px; font-weight:100;">Name: '.$postString->user_fname.'<br />
						Last Name: '.$postString->user_lname.'<br />
						Address Line 1: '.$postString->primary_address.'<br />
						Address Line 2: '.$postString->secondary_address.'<br />
						Town / City: '.$postString->user_city.'<br />
						Country: '.$postString->country.'<br />
						Post Code: '.$postString->post_code.'<br />
						Telephone Number: '.$postString->user_phone.'<br />
						Email Address: '.$postString->delivery_email.'<br />
						</p>
						</th>
						</tr>
						</table></td>
						</tr>
						<tr>
						<td colspan="2" valign="top" align="right" style="padding:0;"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#f8f8f8" style=" margin-top:10px; border-top:1px solid #ccc; border-bottom:1px solid #ccc;">
						<tr>
						<td style="padding:10px; border:none;" width="33.33%">Order Date : '.date("d-M-Y", strtotime($ordered_date)).'</td>
						<td style="padding:10px; border:none;" align="center" width="33.33%">Delivery Date : '.date("d-M-Y", strtotime($postString->delivery_date)).'</td>
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

						$invoice_order_details = invoice_order_details($order_id);
						$count = 1;
						foreach($invoice_order_details as $post_values){
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
							if($flags == 'product'){
								$msg .='<tr>
								<td style="border: 1px solid #ccc; padding: 2mm;">'.$count.'</td>
								<td style="border: 1px solid #ccc; padding: 2mm;">'.$product_names.'<br />
								Size : '.$product_sizes.'</td>
								<td style="border: 1px solid #ccc; padding: 2mm;">'.$product_qtys.'</td>
								<td style="border: 1px solid #ccc; padding: 2mm;">£'.$product_prices.'</td>
								<td style="border: 1px solid #ccc; padding: 2mm;">£'.$product_prices.'</td>
								<td style="border: 1px solid #ccc; padding: 2mm;">£'.$product_qty_prices.'</td>
								</tr>';
						} if($flags == 'gift'){

							$msg.='<tr>
							<td style="width:10%;">'.$count.'</td>
							<td style="width:35%; text-align:left; padding-left:10px;">'.$gift_names.'<br /></td>
							<td class="mono" style="width:15%;">'.$gift_quantitys.'</td>
							<td style="width:15%;" class="mono"></td>
							<td style="width:15%;" class="mono">£'.$gift_prices.'</td>
							<td style="width:10%;" class="mono">£'.$gift_qty_prices.'</td>
							</tr>';
						}
						$count++; }

						$msg.='<tr>
						<td style="border: 1px solid #ccc; padding: 2mm;" colspan="4"></td>
						<td style="border: 1px solid #ccc; padding: 2mm;">Shipping :</td>
						<td style="border: 1px solid #ccc; padding: 2mm;" class="mono">£'.$delivery_charges.'</td>
						</tr>
						<tr>
						<td style="border: 1px solid #ccc; padding: 2mm;" colspan="4"></td>
						<td style="border: 1px solid #ccc; padding: 2mm;">Discount :</td>
						<td style="border: 1px solid #ccc; padding: 2mm;" class="mono">'.$discount_offer.'</td>
						</tr>
						<tr>
						<td style="border: 1px solid #ccc; padding: 2mm;" colspan="4"></td>
						<td style="border: 1px solid #ccc; padding: 2mm;">Total :</td>
						<td style="border: 1px solid #ccc; padding: 2mm;" class="mono">£'.$mc_gross.'</td>
						</tr>';

						$msg.='</table>
						<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-top:25px;">
						<caption style="text-align:left; margin-bottom:5px;">
						<strong>Total Amount :</strong>
						</caption>
						<tr>
						<td style="width:70%; border: 1px solid #ccc; padding: 2mm;">'.ucfirst(convert_number_to_wordss($mc_gross)).' only</td>
						<td style="width:15%; border: 1px solid #ccc; padding: 2mm;">'.$mc_currency.'</td>
						<td style="width:15%; border: 1px solid #ccc; padding: 2mm;" class="mono">£'.$mc_gross.'</td>
						</tr>
						<tr>
						<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
						<td style=" border: 1px solid #ccc; padding: 2mm;" colspan="3">Card Message : '.$postString->user_card_msg.'</td>
						</tr>
						<tr>
						<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
						<td style=" border: 1px solid #ccc; padding: 2mm;" colspan="3">Note to Florist : '.$postString->user_notes.'</td>
						</tr>
						</table>
						</td>
						</tr>
						</table>
						</body>
						</html>';

						$subj = 'Order Number : '.$order_id.'';
						$to   = $postString->default_email;
						$from = 'orders@fleurdelisflorist.co.uk';
						$name = 'Fleurdelis';
						echo smtpmailer($to,$from, $name ,$subj, $msg);
						
						//Admin Mail
						$mail_template = 'Dear Admin, <br /> order has been placed, Orderid is: '.$order_id.'';
						$order_subject = 'Fleurdelis order confirmation';
						$order_to   = 'orders@fleurdelisflorist.co.uk';
						$order_from = 'orders@fleurdelisflorist.co.uk';
						$order_name = 'Fleurdelis';
						echo smtpmailer($order_to,$order_from, $order_name ,$order_subject, $mail_template);
						
						$condition = array('order_id' => $postString->oid);
						$delete_stmt = deleteRecord('op2mro9899_tmp_order',$condition);
						if($delete_stmt){
							echo json_encode(array("mail_status" => 'true', "mail_message" => 'successfully deleted'));
						}
				}else{
					echo json_encode(array("status" => "false", "message" => "something went wrong"));
				}

			}
			
		}catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}'; 
			return false;
		}
		
	}
	
	
	function default_delivery_charge($site_id){
		$sql = "SELECT location_name, outer_post_code, inner_post_code, delivery_charges, site_id 
				FROM op2mro9899_shipping WHERE outer_post_code = 'XXX' AND inner_post_code = 'XXX' AND site_id = :site_id";
		try{
			$db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam('site_id', $site_id);
			$stmt->execute();
			$post_codes = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			return $post_codes;
		}catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}'; 
			return false;
		}
		
	}
	
	
	
	function checkPostCode($post_code, $site_id){
		 
		$innerCode = substr($post_code,-3);
		$outerCode = substr($post_code,0,-3);
		$outerCode1 = substr($post_code,0,-4);
        $InXPosts = substr($post_code,-3,2).'X';
		$InnerPost = substr($post_code,-3,1).'XX';


        $sql = "SELECT location_name, outer_post_code, inner_post_code, delivery_charges, holiday_date, site_id FROM `op2mro9899_shipping` 
				WHERE `site_id` = :site_id AND `outer_post_code` = :outerCode OR `outer_post_code` = :outerCode1
				AND ( `inner_post_code` = :innerCode OR `inner_post_code` = :InXPosts OR `inner_post_code` = :InnerPost OR `inner_post_code` = 'XXX')";

		try{
			$db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam('site_id', $site_id);
            $stmt->bindParam('outerCode', $outerCode);
            $stmt->bindParam('outerCode1', $outerCode1);
            $stmt->bindParam('innerCode', $innerCode);
            $stmt->bindParam('InXPosts', $InXPosts);
            $stmt->bindParam('InnerPost', $InnerPost);
			$stmt->execute();
			$post_codes = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			if(empty($post_codes)){
				$pcodes = default_delivery_charge($site_id);
				echo '{"shipping_cost": ' . json_encode($pcodes) . '}';
			}else{
				echo '{"shipping_cost": ' . json_encode($post_codes) . '}';
			}
		}catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}'; 
			return false;
		}
	}
	
	
	
	function returnOrderID($id){
		
		$sql = "SELECT * FROM op2mro9899_tmp_order WHERE id = :id";
		try{
									
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindParam("id", $id);
			$stmt->execute();
			$orders = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"order": ' . json_encode($orders) . '}';
		}catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}'; 
			return false;
		}
	}
	
	
	function productOrder(){
		$request = Slim::getInstance()->request();
		$allPostVars = json_decode($request->getBody());
		
		try{
		
			$product = json_encode($allPostVars->product);
			$gift = json_encode($allPostVars->gift);
			$delivery_address = json_encode($allPostVars->delivery_address);
			$delivery_charges = json_encode($allPostVars->delivery_charges);
			$discount_offer = json_encode($allPostVars->discount_offer);
			$order_id = rand(10,999);
			
			$order_args = array('product' => $product, 'gift' => $gift, 'delivery_address' => $delivery_address, 'delivery_charges' => $delivery_charges,
								'discount' => $discount_offer, 'site_id' => $allPostVars->site_id);
			$insert_stmt = insert_records('op2mro9899_tmp_order', $order_args);
			if($insert_stmt){
				$orderID = $order_id.$insert_stmt;
				$order_args = array('order_id' => $orderID);
				$condition = array('id' => $insert_stmt);
				$update_stmt = update_record('op2mro9899_tmp_order', $order_args, $condition);
				if($update_stmt){
					$orderStmt = returnOrderID($insert_stmt);
					echo $orderStmt;
					//echo json_encode(array('status' => 'true', 'message' => 'success'));
				}
			}else{
				echo json_encode(array('status' => 'false', 'message' => 'something went wrong'));
			}
		}catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}'; 
			return false;
		}
	}
	
	
	function getAccountDetail($user_key){
			
		try{
			$chk_validUser = userExist($user_key);
			$id = $chk_validUser[0]->id;
			$unique_key = $chk_validUser[0]->unique_key;
			if($unique_key == $user_key){
				$sql = "SELECT * FROM op2mro9899_customers_login WHERE id = :id";
				$db = getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("id", $id);
				$stmt->execute();
				$user_detail = $stmt->fetchAll(PDO::FETCH_OBJ);
				$db = null;
				echo '{"user_detail": ' . json_encode($user_detail) . '}';
			}
		}catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}'; 
			return false;
		}
	}
	
	
	function payement_detail_by_user_id($user_id){
		$sql = "SELECT * FROM op2mro9899_payments WHERE user_id =:user_id";
		try{
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindParam("user_id", $user_id);
			$stmt->execute();
			$payement = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"payement_history": ' . json_encode($payement) . '}';
		}catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}'; 
			return false;
		}
	}
	
	
	function getUserOrderDetail($user_key){
		
		$sql = "SELECT a.product_name, a.product_price, a.product_qty_price, a.product_qty, a.product_id, a.product_size, a.product_code, a.gift_name, a.gift_price, 
				a.gift_qty_price, a.gift_quantity, a.gift_id, a.gift_code, COUNT(a.order_id) as `total_orders`, a.user_id, a.created_date, b.item_name, b.item_number, 
				b.payment_status, b.mc_gross, b.mc_currency, b.txn_id, b.receiver_email, b.payer_email, b.payment_date, b.order_id, b.user_id, b.order_status
				FROM `op2mro9899_ordered_product` a INNER JOIN `op2mro9899_payments` as b ON a.order_id = b.order_id WHERE a.user_id = :user_id GROUP BY a.order_id";
		
		try{
			$chk_validUser = userExist($user_key);
			$user_id = $chk_validUser[0]->id;
			$unique_key = $chk_validUser[0]->unique_key;
			if($unique_key == $user_key){
				$db = getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("user_id", $user_id);
				$stmt->execute();
				$user_orders = $stmt->fetchAll(PDO::FETCH_OBJ);
				$db = null;
				echo '{"order_history": ' . json_encode($user_orders) . '}';
			}else{
				echo json_encode(array('status' => 'false', 'message' => 'permission failed'));
			}
		}catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}'; 
			return false;
		}
	}
	
	
	function updateAccountDetail($user_key){
		
		$request = Slim::getInstance()->request();
		$allPostVars = json_decode($request->getBody());
		$pasword = hash('sha256', $allPostVars->user_password);
			
		try{
			$chk_validUser = userExist($user_key);
			$id = $chk_validUser[0]->id;
			$unique_key = $chk_validUser[0]->unique_key;
			if($unique_key == $user_key){
				$array_args = array('user_email' => $allPostVars->email_id, 'user_password' => $pasword, 'site_id' => $allPostVars->siteid);
				$condition = array('id' => $id);
				$update_stmt = update_record('op2mro9899_customers_login', $array_args, $condition);
				if($update_stmt){
					echo json_encode(array('status' => 'true', 'message' => 'success'));
				}else{
					echo json_encode(array('status' => 'false', 'message' => 'something went wrong'));
				}
			}
		}catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}'; 
			return false;
		}
	}
	

	function getAllProducts(){
		$sql = "SELECT p.pid, p.product_name, p.description, p.short_description, p.regular_price, p.disscount_price, pi.pid, pi.medium_path
				FROM `op2mro9899_products` p 
				INNER JOIN `op2mro9899_products_image` pi ON p.pid = pi.pid GROUP BY pi.pid";
		try{
			$db = getConnection();
			$stmt = $db->query($sql);
			$products = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"products": ' . json_encode($products) . '}';
		}catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}'; 
			return false;
		}
	}
	
	
	function searchProductByIds(){
		$request = Slim::getInstance()->request();
		$post_vars = json_decode($request->getBody());
		
		$search_string = isset($post_vars->user_keyword) ? str_replace(' ', '', $post_vars->user_keyword) : '';
		$site_id = $post_vars->site_id;
		if(isset($post_vars->price_range)){
			if(empty($post_vars->parent_category)){ $cat_query = ''; }else{
				$sub_cat = DecryptClientId($post_vars->parent_category);
				$cat_query = (($sub_cat <> '') ? $sub_cat : '');
			}
			
		}
		
		if(isset($post_vars->price_range)){
			if(empty($post_vars->price_range)){
				$query_str = ' ';
			}else{
				$price = $post_vars->price_range;
				$arr = explode('-', $price);
				$count = sizeof($arr);
				if($count == 2){
					$query_str = 'AND t3.price BETWEEN '.$arr[0].' AND '.$arr[1].'';
				}else{
					$query_str = 'AND t3.price > '.$arr[0].'';
				}
			}
		}
		
		$sql = "SELECT t.pid, t.product_name, t.regular_price, t.large_price, t.disscount_price, t1.id, t1.pid, t1.cat_id, t2.medium_path, t2.pid, t3.product_id, t3.price, t3.site_id
				FROM `op2mro9899_products` t 
				INNER JOIN `op2mro9899_products_relation` t1 ON t.pid = t1.pid
				INNER JOIN `op2mro9899_products_image` t2 ON t.pid = t2.pid
				INNER JOIN `op2mro9899_products_price` t3 ON t.pid = t3.product_id 
				WHERE REPLACE(t.product_name, ' ', '') LIKE '%$search_string%' 
				AND REPLACE(t1.cat_id, ' ', '') LIKE '%$cat_query%' AND t3.site_id = $site_id $query_str GROUP BY t1.pid";
		try{
			$db = getConnection();
			$searhStmt = $db->query($sql);
			$sproducts = $searhStmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			$result = array();
			foreach($sproducts as $key => $val){ //print_r($val);
				$data = array('pid' => EncryptClientId($val->pid), 'product_name' => $val->product_name, 
							  'regular_price' => $val->regular_price, 'price' => $val->price, 'large_price' => $val->large_price, 'disscount_price' => $val->disscount_price,
							   'img' => $val->medium_path, );
				array_push($result,$data);
			}
			
			echo json_encode($result);
			
			//echo '{"search_products": ' . json_encode($sproducts) . '}';
		}catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}'; 
			return false;
		}
	}
	
	
	function search_product($search_string){
		$sql ="SELECT * FROM op2mro9899_products WHERE REPLACE(product_name, ' ', '') LIKE :search_string";
		try{
			$db = getConnection();
			$search_stmt = $db->prepare($sql);
			$search_string = '%'.$search_string.'%';
			$search_stmt->bindParam('search_string', $search_string, PDO::PARAM_STR);
			$search_stmt->execute();
			//$search_stmt->debugDumpParams();
			$serach_items = $search_stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"serach_items": ' . json_encode($serach_items) . '}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
		}
	}

	function search_pimage($pid){
		$sql = "SELECT * FROM op2mro9899_products_image WHERE pid = :pid LIMIT 0, 1";
		try{
			$db = getConnection();
			$img_stmt = $db->prepare($sql);
			$img_stmt->bindParam("pid", $pid);
			$img_stmt->execute();
			$img_responce = $img_stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"pimage": ' . json_encode($img_responce) . '}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	

	function getParentCategory(){
		$sql = "SELECT * FROM op2mro9899_category WHERE parent_category = 0";
		
		try{
			$db = getConnection();
			$stmt = $db->query($sql); 
			$parent_categories = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"parent_categories": ' . json_encode($parent_categories) . '}';
		}catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}'; 
			return false;
		}
	}
	
	
	function getAllChildCategory(){
		$sql = "SELECT * FROM `op2mro9899_category` WHERE parent_category <> 0";
		try{
			$db = getConnection();
			$child_stmt = $db->query($sql);
			$chil_cat = $child_stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"chil_cat": ' . json_encode($chil_cat) . '}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	
	function getParentCategoryBySiteID($siteId){
			/*$cat_query = "SELECT t1.cat_id, t1.category_name, t1.active, t1.parent_category, t1.order_on_home, t2.id, t2.category_id, t2.site_id,
						  t3.full_path, t3.medium_path, t3.thumbnail_path, t3.site_id, t3.cat_id
						  FROM `op2mro9899_category` t1 
					      INNER JOIN `op2mro9899_category_relation` t2 ON t1.cat_id = t2.category_id
					      INNER JOIN `op2mro9899_category_image` t3 ON t1.cat_id = t3.cat_id
					      WHERE t1.parent_category = 0 
					      AND t2.site_id = :site_id AND t1.active = 1 ORDER BY t1.order_on_home ASC";*/
			
			$cat_query = "SELECT t1.cat_id, t1.category_name, t1.active, t1.parent_category, t1.order_on_home, t1.full_path, t1.medium_path, t1.thumbnail_path, 
						  t2.site_id, t2.category_id FROM `op2mro9899_category` t1 INNER JOIN op2mro9899_category_relation t2 ON t1.cat_id = t2.category_id
						  WHERE t1.parent_category = 0 AND t2.site_id = :site_id AND t1.active = 1 ORDER BY t1.order_on_home ASC";
			
			try{
				$db = getConnection();
				$query_stmt = $db->prepare($cat_query);
				$query_stmt->bindParam("site_id", $siteId);
				$query_stmt->execute();
				$parent_cat = $query_stmt->fetchAll(PDO::FETCH_OBJ);
				$db = null;
				echo '{"parent_categories": ' . json_encode($parent_cat) . '}';
			}catch(PDOException $e){
				echo '{"error":{"text":'. $e->getMessage() .'}}'; 
				return false;
			}
		  
	}
	
	
	function childCategoryByID($parents_id){
		$sql_query = "SELECT * FROM `op2mro9899_category` WHERE TRUE AND parent_category = :parents_id AND menu = 1 ORDER BY cat_id ASC";
		try{
			$db = getConnection();
			$stmt = $db->prepare($sql_query);
			$stmt->bindParam("parents_id", $parents_id);
			$stmt->execute();
			$child_categories = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"child_categories": ' . json_encode($child_categories) . '}';
		}catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}'; 
			return false;
		}
	}
	
	
	function allChildCategory($site_id, $parentID){
		
		$sql = "SELECT t1.cat_id, t1.category_name, t1.category_description, t1.parent_category, t1.cat_order,
				t2.full_path, t2.medium_path, t2.thumbnail_path, t2.site_id, t2.cat_id, t3.category_id 
				FROM op2mro9899_category t1 
				INNER JOIN op2mro9899_category_image t2 ON t1.cat_id = t2.cat_id 
				INNER JOIN op2mro9899_category_relation t3 ON t1.cat_id = t3.category_id 
				WHERE t2.site_id = :site_id AND t1.parent_category = :parentID GROUP BY t2.cat_id ORDER BY t1.cat_order ASC";
		try{
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindParam("site_id", $site_id);
			$stmt->bindParam("parentID", $parentID);
			$stmt->execute();
			$child_cat = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"child_cat": ' . json_encode($child_cat) . '}';
		}catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}'; 
			return false;
		}
	}


	function getProductBySiteIdAndCategoryID($site_id, $cat_id){
		$sql = "SELECT p.pid, p.product_name, p.description, p.short_description, p.regular_price, p.disscount_price, pr.pid, pr.cat_id, pr.product_order, pi.pid, pi.medium_path 
				FROM `op2mro9899_products` AS p
				INNER JOIN `op2mro9899_products_image` AS pi ON p.pid = pi.pid
				INNER JOIN op2mro9899_products_relation pr ON p.pid = pr.pid
				INNER JOIN op2mro9899_product_site_relation pq ON p.pid = pq.pid
				INNER JOIN op2mro9899_category_relation cr ON pr.cat_id = cr.category_id
				WHERE pq.site_id =:site_id AND cat_id =:cat_id GROUP BY product_name ORDER BY pr.product_order ASC";
		try{
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindParam("site_id", $site_id);
			$stmt->bindParam("cat_id", $cat_id);
			$stmt->execute();
			$cat_products = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"category_products": ' . json_encode($cat_products) . '}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	function get_product_bysiteid_andcategoryid($site_id, $cat_id){
		$sql = "SELECT p.pid, p.product_name, p.short_description, p.description, p.regular_price, p.disscount_price, pr.pid, pr.cat_id, pr.status, pr.product_order, pq.pid, pq.site_id, t.product_id, t.price, t.site_id 
				FROM `op2mro9899_products` AS p 
				INNER JOIN op2mro9899_products_relation pr ON p.pid = pr.pid 
				INNER JOIN op2mro9899_product_site_relation pq ON p.pid = pq.pid 
				INNER JOIN op2mro9899_products_price t ON p.pid = t.product_id 
				WHERE pr.cat_id = :cat_id AND  pq.site_id = :site_id AND t.site_id = :site_id GROUP by p.pid ORDER BY pr.product_order ASC";
		
		/*$sql = "SELECT p.pid, p.product_name, p.regular_price, p.disscount_price, pr.pid, pr.cat_id, pr.status, pr.product_order, pq.pid, pq.site_id
				FROM `op2mro9899_products` AS p
				INNER JOIN op2mro9899_products_relation pr ON p.pid = pr.pid 
				INNER JOIN op2mro9899_product_site_relation pq ON p.pid = pq.pid
				WHERE pq.site_id = :site_id AND pr.cat_id = :cat_id GROUP by p.pid ORDER BY pr.product_order ASC";*/
				
		try{
			$db = getConnection();
			$stmts = $db->prepare($sql);
			$stmts->bindParam("site_id", $site_id);
			$stmts->bindParam("cat_id", $cat_id);
			$stmts->execute();
			$products = $stmts->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"cat_products": ' . json_encode($products) . '}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	
	function get_product_image($productID){
		$sql = "SELECT medium_path, pid, image_order FROM op2mro9899_products_image WHERE pid = :productID ORDER BY image_order ASC LIMIT 0, 1";
		try{
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindParam("productID", $productID);
			$stmt->execute();
			$product_image = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"product_image": ' . json_encode($product_image) . '}';
		}catch(PDOException $e){
			echo '{"error":{"text": '.$e->getMessage().'}}';
			return false;
		}
		
	}

	function productDetailByID($pid, $site_id){
		//$sql = "SELECT * FROM op2mro9899_products WHERE pid = :pid ORDER BY image_order ASC";
		$sql ="SELECT t.pid, t.product_name, t.regular_price, t.description, t.short_description, t.large_price, t.disscount_price, t.product_code, t1.product_id, t1.price, t1.site_id FROM op2mro9899_products t
			   INNER JOIN op2mro9899_products_price t1 ON t.pid = t1.product_id WHERE t.pid = :pid AND t1.site_id = :site_id";
		try{
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindParam("pid", $pid);
			$stmt->bindParam("site_id", $site_id);
			$stmt->execute();
			$product_detail = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"product_detail": ' . json_encode($product_detail) . '}';
		}catch(PDOException $e){
			echo '{"error":{"text": '.$e->getMessage().'}}';
			return false;
		}
	}
	
	
	function product_image($pid){
		$sql = "SELECT * FROM op2mro9899_products_image WHERE pid = :pid ORDER BY image_order ASC";
		try{
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindParam("pid", $pid);
			$stmt->execute();
			$product_image = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"product_image": ' . json_encode($product_image) . '}';
		}catch(PDOException $e){
			echo '{"error":{"text": '.$e->getMessage().'}}';
			return false;
		}
	}
	
	
	function getProductDetailByPid($pid){
		
		/*$sql = "SELECT p.pid, p.product_name, p.regular_price, p.large_price, p.disscount_price, p.product_code, pi.img_id, pi.medium_path, pi.thumbnail_path, pi.pid
				FROM op2mro9899_products p INNER JOIN op2mro9899_products_image pi ON p.pid = pi.pid WHERE p.pid = :pid GROUP BY pi.pid";*/
			
		  $sql = "SELECT p.pid, p.product_name, p.regular_price, p.large_price, p.disscount_price, p.product_code, pi.img_id, pi.medium_path, pi.thumbnail_path, pi.pid, 
				  c.pid, GROUP_CONCAT(DISTINCT(c.cat_id) SEPARATOR ',') as `cat_id`, c.status FROM `op2mro9899_products` p INNER JOIN `op2mro9899_products_image` 
				  pi ON p.pid = pi.pid INNER JOIN `op2mro9899_products_relation` c ON p.pid = c.pid WHERE p.pid = :pid GROUP BY pi.pid";
		
		
		try{
			$db = getConnection();
			$stmt = $db->prepare($sql);  
			$stmt->bindParam("pid", $pid);
			$stmt->execute();
			$product_cart = $stmt->fetchObject();  
			$db = null;
			echo json_encode($product_cart); 
		}catch(PDOException $e){
			echo '{"error":{"text": '.$e->getMessage().'}}';
			return false;
		}
	}
	
	function get_product_bypid_andsiteid($product_id, $sid){		
		  $sql = "SELECT product_id, price, site_id FROM op2mro9899_products_price WHERE product_id = :product_id AND site_id = :sid";
		
		try{
			$db = getConnection();
			$stmt = $db->prepare($sql);  
			$stmt->bindParam("product_id", $product_id);
			$stmt->bindParam("sid", $sid);
			$stmt->execute();
			$product_cart_price = $stmt->fetchObject();  
			$db = null;
			echo '{"product_cart_price": ' . json_encode($product_cart_price) . '}';
		}catch(PDOException $e){
			echo '{"error":{"text": '.$e->getMessage().'}}';
			return false;
		}
	}
	
	
	function getGiftsBySiteID($site_id){
		
		$sql = "SELECT g.id, g.gift_name, g.regular_price, g.disccount_price, g.description, g.short_note, g.full_path, g.medium_path, g.thumbnail_path, g.gift_order,
				gr.id, gr.gift_id, gr.site_id FROM op2mro9899_gifts g INNER JOIN op2mro9899_gifts_relation gr
				ON g.id = gr.gift_id WHERE gr.site_id = :site_id ORDER BY g.gift_order LIMIT 5";
		try{
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindParam("site_id", $site_id);
			$stmt->execute();
			$gift_item = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"gift_item": ' . json_encode($gift_item) . '}';
		}catch(PDOException $e){
			echo '{"error":{"text": '.$e->getMessage().'}}';
			return false;
		}
	}
	
	function get_gifts_attributes($gift_id){
		$sql = "SELECT id, gift_cat_id, gifts_name, gifts_price FROM op2mro9899_gifts_type WHERE gift_cat_id = :gift_id";
		
		try{
			$db = getConnection();
			$stmts = $db->prepare($sql);
			$stmts->bindParam('gift_id', $gift_id);
			$stmts->execute();
			$gift_attribs = $stmts->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"gift_attribs": ' . json_encode($gift_attribs) . '}';
		}catch(PDOException $e){
			echo '{"error":{"text": '.$e->getMessage().'}}';
			return false;
		}
	}
	
	function get_attributes_price($pid){
		$sql = "SELECT id, gift_cat_id, gifts_name, gifts_price FROM op2mro9899_gifts_type WHERE id = :pid";
		try{
			$dbs = getConnection($sql);
			$dbstmt = $dbs->prepare($sql);
			$dbstmt->bindParam('pid', $pid);
			$dbstmt->execute();
			$records = $dbstmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"prices": ' . json_encode($records) . '}'; 
		}catch(PDOException $e){
			echo '{"error":{"text": '.$e->getMessage().'}}';
			return false;
		}
	}

	function getCategoryImageBySiteID($site_id){
		$sql = "SELECT c.cat_id, c.category_name, c.parent_category, ci.full_path, ci.medium_path, ci.thumbnail_path
				FROM op2mro9899_category c
				INNER JOIN op2mro9899_category_image ci ON c.cat_id = ci.cat_id
				WHERE ci.site_id = :site_id";

		try{
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindparam("site_id", $site_id);
			$stmt->execute();
			$cat_images = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"category_images": '.json_encode($cat_images).'}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}


	function getAllCategoryBySiteID($site_id){

		$sql = "SELECT c.cat_id, c.category_name, c.parent_category, cr.id, cr.category_id, cr.site_id FROM op2mro9899_category c 
				INNER JOIN op2mro9899_category_relation cr 
				ON c.cat_id = cr.category_id WHERE cr.site_id = :site_id";
		try{		
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindparam("site_id", $site_id);
			$stmt->execute();
			$all_categories = $stmt->fetchAll(PDO::FETCH_OBJ);
			echo '{"all_categories": '.json_encode($all_categories).'}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	function get_category_detail($site_id, $cat_ids){
		$sql  = "SELECT t.cat_id, t.category_name, t.category_description, t1.full_path, t1.medium_path, t1.thumbnail_path, t1.site_id, t1.cat_id
				 FROM op2mro9899_category t INNER JOIN op2mro9899_category_image t1 ON t.cat_id = t1.cat_id WHERE t.cat_id = :cat_ids AND t1.site_id = :site_id";
		try{
			$db = getConnection();
			$cat_stmt = $db->prepare($sql);
			$cat_stmt->bindParam("site_id", $site_id);
			$cat_stmt->bindParam("cat_ids", $cat_ids);
			$cat_stmt->execute();
			$cat_desc = $cat_stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"cat_desc":'.json_encode($cat_desc).'}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	
	function getGalleryCategory($site_id){
		$sql = "SELECT g.id, g.category_name, g.medium_path, g.status, g.cat_order, gr.gallery_cat_id, gr.site_id 
				FROM op2mro9899_gallery_category g 
				INNER JOIN op2mro9899_gallery_category_relation gr ON g.id=gr.gallery_cat_id 
				WHERE g.status = 1 AND gr.site_id = :site_id ORDER BY g.cat_order ASC";
		try{
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindparam("site_id", $site_id);
			$stmt->execute();
			$cat_images = $stmt->fetchAll(PDO::FETCH_OBJ);
			echo '{"cat_images": '.json_encode($cat_images).'}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	function getGalleryImageByCatID($cat_id){
		
		//$sql = "SELECT * FROM op2mro9899_gallery_images WHERE cat_id = :cat_id";
		$sql = "SELECT * FROM op2mro9899_gallery_images WHERE gid = :cat_id ORDER BY image_order ASC";
		try{
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindParam("cat_id", $cat_id);
			$stmt->execute();
			$all_images = $stmt->fetchAll(PDO::FETCH_OBJ);
			echo '{"all_images": '.json_encode($all_images).'}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	
	function gallery_subcat($cid){
		$sql = "SELECT id, gallery_name, category_id, category_order, cat_status FROM op2mro9899_galleries WHERE category_id = :cid AND cat_status = 1 ORDER BY category_order ASC";
		try{
			$db = getConnection();
			$gallery_stmt = $db->prepare($sql);
			$gallery_stmt->bindParam("cid", $cid);
			$gallery_stmt->execute();
			$sub_cat = $gallery_stmt->fetchAll(PDO::FETCH_OBJ);
			echo '{"sub_cat":'.json_encode($sub_cat).'}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	function gallery_subcat_image($gal_id){
		$sql = "SELECT * FROM `op2mro9899_gallery_images` WHERE gid = :gal_id ORDER BY image_order ASC LIMIT 0, 1";
		try{
			$db = getConnection();
			$img_stmt = $db->prepare($sql);
			$img_stmt->bindParam("gal_id", $gal_id);
			$img_stmt->execute();
			$sub_cat_img = $img_stmt->fetchAll(PDO::FETCH_OBJ);
			echo '{"sub_cat_img":'.json_encode($sub_cat_img).'}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}

	}
	
	function getGiftItemByID($gift_id){
		$sql = "SELECT * FROM op2mro9899_gifts WHERE id IN($gift_id)";
		try{
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindparam("gift_id", $gift_id);
			$stmt->execute();
			$gifts = $stmt->fetchAll(PDO::FETCH_OBJ);
			echo '{"gifts": '.json_encode($gifts).'}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	function gifDetailByID($giftID){
		$sql = "SELECT * FROM op2mro9899_gifts WHERE id = :giftID";
		try{
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindparam("giftID", $giftID);
			$stmt->execute();
			$row = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"post_gift": '.json_encode($row).'}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	
	function checkCouponCode($coupon_code, $site_id){
		$valid_from = date("Y-m-d");
		$valid_upto = date("Y-m-d");
		$sql = "SELECT * FROM `op2mro9899_coupons` WHERE `valid_from` <= :valid_from  AND `valid_upto`  >= :valid_upto 
				AND `total_coupons` > 0 AND coupon_code = :coupon_code AND site_id = :site_id";
		try{
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindparam("valid_from", $valid_from);
			$stmt->bindparam("valid_upto", $valid_upto);
			$stmt->bindparam("coupon_code", $coupon_code);
			$stmt->bindparam("site_id", $site_id);
			$stmt->execute();
			$row = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"coupon_data": '.json_encode($row).'}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	function check_valid_coupon($cCode, $site_id){
		
		$valid_from_date = date("Y-m-d");
		$valid_upto_date = date("Y-m-d");
		$sql = "SELECT * FROM `op2mro9899_coupons` WHERE `valid_from` <= :valid_from_date  AND `valid_upto`  >= :valid_upto_date 
				AND `total_coupons` > 0 AND coupon_code = :cCode AND site_id = :site_id";
		try{
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindparam("valid_from_date", $valid_from_date);
			$stmt->bindparam("valid_upto_date", $valid_upto_date);
			$stmt->bindparam("cCode", $cCode);
			$stmt->bindparam("site_id", $site_id);
			$stmt->execute();
			$row = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			return $row;
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	function update_coupon_number($couponCode, $site_id){
		$str = check_valid_coupon($couponCode, $site_id);
		try{
			if(!empty($str)){
				$data = $str[0];
				$coupon_id = $data->id;
				$total_coupons = $data->total_coupons;
				$decrease_cnumber = $total_coupons-1;
				$coupon_args = array('total_coupons' => $decrease_cnumber);
				$condition = array('id' => $coupon_id);
				$update_stmt = update_record('op2mro9899_coupons', $coupon_args, $condition);
				if($update_stmt){
					echo json_encode(array('msg' => 'true', 'status' => 'Coupon update successfully'));
				}else{
					echo json_encode(array('msg' => 'false', 'status' => 'Something went wrong'));
				}
			}else{
				echo json_encode(array('status' => 'false', 'msg' => 'Not a valid coupon!'));
			}
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	function get_slider($site_id){
		$sql = "SELECT * FROM op2mro9899_slider WHERE site_id = :site_id ORDER BY id DESC";
		try{
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindParam("site_id", $site_id);
			$stmt->execute();
			$slider_row = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"slider": '.json_encode($slider_row).'}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	function get_home_content($site_id){
		
		$sql = "SELECT p.id, p.page_name, p.page_content, p.short_description, p.status, p.show_home_page, p1.page_id, p1.site_id 
				FROM op2mro9899_pages p INNER JOIN op2mro9899_pages_relation p1 ON p.id=p1.page_id WHERE p.status = 1 
				AND p1.site_id = $site_id AND p.show_home_page = 1";
		try{
			$db = getConnection();
			$stmt = $db->query($sql);
			$stmt->execute();
			$page_content = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			$app = Slim::getInstance();
			$app->contentType('application/json');
			echo '{"page_content": ' . json_encode($page_content) . '}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	function get_menu_page(){
		$sql = "SELECT p.id, p.page_name, p.status, p.show_home_page, p1.page_id, p1.site_id FROM op2mro9899_pages p
				INNER JOIN op2mro9899_pages_relation p1 ON p.id=p1.page_id WHERE p.status = 1 AND p1.site_id = 1 
				AND p.show_home_page NOT IN(1)";
		try{
			$db = getConnection();
			$qstmt = $db->query($sql);
			$qstmt->execute();
			$prow = $qstmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			$app = Slim::getInstance();
			$app->contentType('application/json');
			echo '{"pages": ' . json_encode($prow) . '}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	function category_on_menu($site_id){
		//$sql = "SELECT cat_id, category_name, menu, cat_order, cat_type FROM op2mro9899_category WHERE parent_category = 0 AND menu = 1  ORDER BY cat_order ASC";
		$sql = "SELECT a.cat_id, a.category_name, a.menu, a.cat_order, a.cat_type, a.parent_category, a.cat_order, b.id, b.category_id, b.site_id 
				FROM op2mro9899_category a INNER JOIN op2mro9899_category_relation b
				ON a.cat_id = b.category_id WHERE a.parent_category = 0 AND a.menu = 1 AND b.site_id = :site_id ORDER BY a.cat_order ASC";
		try{
			$db = getConnection();
			$mstmt = $db->prepare($sql);
			$mstmt->bindParam("site_id", $site_id);
			$mstmt->execute();
			$cat_menu = $mstmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			$app = Slim::getInstance();
			$app->contentType('application/json');
			echo '{"cat_menu": ' . json_encode($cat_menu) . '}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	
	function child_category_menu($parents_id){
		$sql_query = "SELECT cat_id, category_name, menu, parent_category, cat_order, cat_type FROM `op2mro9899_category` WHERE TRUE AND parent_category = :parents_id AND 
					  menu = 1 ORDER BY cat_order ASC";
		try{
			$db = getConnection();
			$stmt = $db->prepare($sql_query);
			$stmt->bindParam("parents_id", $parents_id);
			$stmt->execute();
			$child_categories = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"child_categories": ' . json_encode($child_categories) . '}';
		}catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}'; 
			return false;
		}
	}
	
	
	function get_page_content_by_id($page_id){
		$sql = "SELECT * FROM op2mro9899_pages WHERE id = :page_id";
		try{
			$db = getConnection();
			$pstmt = $db->prepare($sql);
			$pstmt->bindParam('page_id', $page_id);
			$pstmt->execute();
			$pcon = $pstmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			$app = Slim::getInstance();
			$app->contentType('application/json');
			echo '{"pages_deatil": ' . json_encode($pcon) . '}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	function get_all_parent_category(){
		$sql = "SELECT cat_id, category_name, parent_category FROM op2mro9899_category WHERE parent_category = '0'";
		try{
			$db = getConnection();
			$smap = $db->query($sql);
			$smap->execute();
			$site_mpas = $smap->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			$app = Slim::getInstance();
			$app->contentType('application/json');
			echo '{"site_map":'.json_encode($site_mpas).'}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	
	function get_all_child_category($parent_cat_id){
		$sql = "SELECT cat_id, category_name, parent_category FROM op2mro9899_category WHERE parent_category = :parent_cat_id";
		try{
			$db = getConnection();
			$childStmt = $db->prepare($sql);
			$childStmt->bindParam('parent_cat_id', $parent_cat_id);
			$childStmt->execute();
			$childsmap = $childStmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			$app = Slim::getInstance();
			$app->contentType('application/json');
			echo '{"child_site_map":'.json_encode($childsmap).'}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	
	function get_all_shipping_codes(){
		$sql = "SELECT id, location_name, outer_post_code, inner_post_code, delivery_charges FROM op2mro9899_shipping WHERE TRUE";
		try{
			$db = getConnection();
			$stmts = $db->query($sql);
			$stmts->execute();
			$all_shipping = $stmts->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			$app = Slim::getInstance();
			$app->contentType('application/json');
			echo '{"shipping_code":'.json_encode($all_shipping).'}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	/*function get_ptoduct_id_by_name($product_name){
		$sql = "SELECT pid, product_name FROM op2mro9899_products WHERE product_name = :product_name";
		try{
			$dbs = getConnection();
			$dbs = $dbs->prepare($sql);
			$dbs->bindparam('product_name', $product_name);
			$dbs->execute();
			$pid = $dbs->fetchAll(PDO::FETCH_ASSOC);
			return $pid[0]['pid'];
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}*/
	
	function order_detail_by_orderid($orderid){
		$oid = DecryptClientId($orderid);
		$sql = "SELECT product_name, product_price, product_qty_price, product_qty, product_size, site_id, product_code, gift_name, gift_price, gift_qty_price, gift_quantity,
				gift_id, gift_code, order_id FROM op2mro9899_ordered_product WHERE order_id = :oid AND site_id = 1";
		try{
			$db = getConnection();
			$order_stmt = $db->prepare($sql);
			$order_stmt->bindParam('oid', $oid);
			$order_stmt->execute();
			$all_orders = $order_stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			$app = Slim::getInstance();
			$app->contentType('application/json');
			echo '{"all_orders":'.json_encode($all_orders).'}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	function getall_testimonial(){
		$sql = "SELECT user_title, user_content FROM `op2mro9899_testimonial` WHERE TRUE AND status = 1";
		try{
			$db = getConnection();
			$stmts = $db->query($sql);
			$stmts->execute();
			$all_testimonials = $stmts->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			$app = Slim::getInstance();
			$app->contentType('application/json');
			echo '{"all_testimonial":'.json_encode($all_testimonials).'}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.$e->getMessage().'}}';
			return false;
		}
	}
	
	
	
	function match_holiday_date($holiday_date){
		$sql = "SELECT holiday_date, holidat_desc, special_charges, allowed_date FROM op2mro9899_holiday WHERE holiday_date = :holiday_date AND allowed_date = 1";
		try{
			$db = getConnection();
			$holiday_stmts = $db->prepare($sql);
			$holiday_stmts->bindParam('holiday_date', $holiday_date);
			$holiday_stmts->execute();
			$holiday_records = $holiday_stmts->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			$app = Slim::getInstance();
			$app->contentType('application/json');
			echo '{"holiday_records":'.json_encode($holiday_records).'}';
		}catch(PDOException $e){
			echo '{"error":{"text":'.json_encode($e->getMessage()).'}}';
			return false;
		}
	}
	

	function getConnection() {
		$dbhost="localhost";
		$dbuser="root";
		$dbpass="";
		$dbname="fdl_live";
		$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass,  array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));	
		//$dbh->exec("set names utf8");
		//$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $dbh;
	}

?>
