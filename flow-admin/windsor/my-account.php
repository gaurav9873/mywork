<?php
include_once 'header.php';

if(empty($_SESSION['key'])){
	header("location:login.php");
}

	$msg = '';
	$req_id = isset($_REQUEST['id'])? $_REQUEST['id'] : '';


	$user_id = $_SESSION['user_id'];
	$first_name = $_SESSION['first_name'];
	$last_name = $_SESSION['last_name'];
	$key = $_SESSION['key'];

	//Update address
	/*if(isset($_POST['submit_val']) == 'submit_address'){
		unset($_POST['submit_val']);
		unset($_POST['save']);
		$post_url = API_PATH.'addresses/'.$key.'';
		$post_data = $_POST;
		$request_data = $obj->httpPost($post_url, $post_data);
		if($request_data->status == 'true'){
			$msg = '<font face="verdana" color="green">Record has been updated successfully</font>';
		}else{
			$msg = '<font face="verdana" color="red">Something went wrong please try again later</font>';
		}
	}*/
	
	if(isset($_POST['save'])){
		unset($_POST['submit_val']);
		unset($_POST['save']);
		$post_url = API_PATH.'addresses/'.$key.'';
		$post_data = $_POST;
		$request_data = $obj->httpPost($post_url, $post_data);
		if($request_data->status == 'true'){
			$msg = '<font face="verdana" color="green">Record has been updated successfully</font>';
		}else{
			$msg = '<font face="verdana" color="red">Something went wrong please try again later</font>';
		}
	}
	
	
	
	//Account Detail
	$ac_api = API_PATH.'account-detail/'.$key.'';
	$data_val = $obj->getCurl($ac_api);
	$user_detail = $data_val->user_detail[0];
	$user_emailID = $user_detail->user_email;
	$userAccountPassword = $user_detail->user_password;
	$enc_password = hash('sha256', $userAccountPassword);
	
	
	if(isset($_POST['email_id']) and ($_POST['user_password'])){
		$post_string = $_POST;
		$account_api  = API_PATH.'update-account/'.$key.'';
		$req = $obj->httpPost($account_api, $post_string);
		if($req->status == 'true'){
			$msg = '<font face="verdana" color="green">Password change successfully</font>';
		}else{
			$msg = '<font face="verdana" color="red">Something went wrong please try again later</font>';
		}
	}


	//Default Billing Address
	if($req_id){
		$url = API_PATH.'billing-address-id/'.$key.'/'.$req_id.'';
	}else{
		$url = API_PATH.'user-data/'.$key.'';
	}

	$data = $obj->getCurl($url);
	$res = $data->user_record;
	
	$id = $res[0]->id;
	$user_prefix = $res[0]->user_prefix;
	$user_first_name = $res[0]->user_first_name;
	$user_last_name = $res[0]->user_last_name;
	$user_postcode = $res[0]->user_postcode;
	$post_address = $res[0]->post_address;
	$user_house_number = $res[0]->user_house_number;
	$primary_address = $res[0]->primary_address;
	$secondary_address = $res[0]->secondary_address;
	$user_city = $res[0]->user_city;
	$user_county = $res[0]->user_county;
	$user_country = $res[0]->user_country;
	$user_phone = $res[0]->user_phone;
	$user_emailid = $res[0]->user_emailid;
	$u_id = $res[0]->user_id;
	$default_address = $res[0]->default_address;
	$checked_address = (($default_address == '1') ? 'checked' : '');

	//All Addresses
	$address_api = API_PATH.'billing-address/'.$key.'';
	$address_val = $obj->getCurl($address_api);
	$billingAddress =  $address_val->billing_address;
	$total = count($billingAddress);
	

	//Order History
	$order_history_api = API_PATH.'order-history/'.$key.'';
	$orderData = $obj->getCurl($order_history_api);
	$order_history = $orderData->order_history;
	
	
	if(isset($_POST['delete'])){
		
		$delete_api = API_PATH.'delete-billing-address/'.$key.'/'.$id.'';
		$res_val = $obj->getCurl($delete_api);	
		if($res_val->status == 'true'){
			header("location:my-account.php?msg=deleted");
		}else{
			$msg = '<font face="verdana" color="red">Something went wrong please try again later</font>';
		}
	}
	
	if(isset($_REQUEST['msg']) == 'deleted'){
		$msg = '<font face="verdana" color="green">successfully deleted</font>';
	}
	
	
//$post_code = 'RG404XX';
//echo $innerCode = substr($post_code, -3).'<br />';
//echo $outerCode = substr($post_code, 0, -3).'<br />';	

//~ echo '=======<br />';
//~ 
//~ echo $InPost=substr($post_code,-3).'<br/>';
//~ echo $OutPost=substr($post_code,0,-3).'<br/>';
//~ 
//~ echo $InXPost=substr($post_code,-3,1).'<br/>';
//~ echo $InXPosts=substr($post_code,-3,2).'<br/>';
//~ 
//~ echo $InXPost=$InXPost."XX";
?>

<script src="validation/jquery.validate.min.js"></script>
<script src="validation/additional-methods.min.js"></script>
<script src="javascripts/user-form.js"></script>
<script>
$(document).ready(function(){
	$("#save").on('click', function(){
		$( "#billing-frm" ).submit();
 	});
 	
 	//~ $("#addMore").on('click', function(){
		//~ $("#billing-frm").find('input:text, input:password, input:file, select, textarea').val('');
		//~ $("#billing-frm").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
	//~ });
	
	$("#billing_address").on('change', function(){
		var val = $(this).val();
		if(val!=''){
			window.location.href="my-account.php?id="+val+"";
		}
	});
	
	$("#default_address").on('change', function(){
		var chk = $(this).is(':checked');
		if(chk == true){
			var chk_val = '1';
			$(".bill_address").val(chk_val);
		}
		if(chk == false){
			var chkVal = '0';
			$(".bill_address").val(chkVal);
		}
	});
	
	//$(".smsg").delay(4000).hide();
	setTimeout('$(".smsg").hide()',3000);
	
});
</script>

<section>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-3"></div>
      <div class="col-lg-6">
        <h2>My Account</h2>
        <div class="smsg"><?php echo $msg; ?></div>
        <div class="responsive-tabs">
          <h2>Billing Address</h2>
          <div>
            <div class="billingInformaion">
              <div class="billingform checkoutsummary">
					<form name="billing-frm" id="billing-frm" class="form-horizontal" action="" method="post">
						  <input type="hidden" name="bill_address" id="bill_address" class="bill_address" value="">
						  <input type="hidden" name="active_address" id="active_address" class="active_address" value="<?php echo $obj->EncryptClientId($id); ?>">
						  <input type="hidden" name="submit_val" class="submit_val" value="submit_address">
						  <div class="billInformation newFormDe">
							<div class="form-group">
							  <label for="Billingaddress" class="col-sm-8 control-label">Select From Billing Addresses</label>
							  <div class="col-sm-4">
								<select class="form-control" id="billing_address" name="billing_address">
										<option value="">Please select address</option>
										<?php
											$count = 1;
											foreach($billingAddress as $k => $val){
												
												$bill_id = $obj->EncryptClientId($val->id);
												$selected = (($bill_id == $req_id) ? "selected" :(($bill_id == $obj->EncryptClientId($id)) ? "selected" : ""));
												echo '<option '.$selected.' value="'.$bill_id.'">Address'.$count.'</option>';
											$count++; }
										?>
								</select>
							  </div>
							</div>
							<div class="form-group">
							  <?php if($default_address == '0'){ ?>
								  <label for="DefaultAddress" class="col-sm-12 control-label">Make This Your Default Address 
									<input type="checkbox" value="1" id="default_address" name="default_address" />
								  </label>
							  <?php }else{ ?>
								  <label for="DefaultAddress" class="col-sm-12 control-label"> <strong>This is Your Default Address</strong> </label>
							  <?php } ?>
							</div>
							<div class="form-group">
							  <label for="Name" class="col-sm-3 control-label">Name <span>*</span></label>
							  <div class="col-sm-3">
								<select class="form-control" name="user_prefix" id="user_prefix">
									<option value="">Select prefix</option>
									<option value="Mr" <?php echo (($user_prefix == 'Mr') ? 'selected' : ''); ?>>Mr.</option>
									<option value="Ms" <?php echo (($user_prefix == 'Ms') ? 'selected' : ''); ?>>Ms.</option>
								</select>
							  </div>
							  <div class="col-sm-3">
								<input type="text" class="form-control" name="user_fname" id="user_fname" value="<?php echo $user_first_name; ?>" placeholder="First Name">
							  </div>
							  <div class="col-sm-3">
								<input type="text" class="form-control" name="user_lname" id="user_lname" value="<?php echo $user_last_name; ?>" placeholder="Last Name">
							  </div>
							</div>
							<div class="form-group">
							  <label for="PostCode" class="col-sm-3 control-label">Search Address<span>*</span></label>
							  <div class="col-sm-3">
								<input type="text" class="form-control" name="post_code" id="post_code" value="<?php echo $user_postcode; ?>" placeholder="Post Code">
							  </div>
							  <div class="col-sm-6">
								<button type="button" id="searchPin" class="search">Search</button>
							  </div>
							</div>
							<div class="form-group">
							  <label for="HouseNumber" class="col-sm-3 control-label">Select Address</label>
							  <div class="col-sm-9">
								<select name="address_list" id="address_list" class="findColAddress form-control searchPin">
								  <option value="">Select Address</option>
								  <option value="Flat 1 Penfold Court Sutton Road,Headington Oxford">Flat 1 Penfold Court Sutton Road,Headington Oxford</option>
								  <option value="Flat 2 Penfold Court Sutton Road,Headington Oxford">Flat 2 Penfold Court Sutton Road,Headington Oxford</option>
								  <option value="Flat 3 Penfold Court Sutton Road,Headington Oxford">Flat 3 Penfold Court Sutton Road,Headington Oxford</option>
								  <option value="Flat 4 Penfold Court Sutton Road,Headington Oxford">Flat 4 Penfold Court Sutton Road,Headington Oxford</option>
								  <option value="Flat 5 Penfold Court Sutton Road,Headington Oxford">Flat 5 Penfold Court Sutton Road,Headington Oxford</option>
								  <option value="Flat 6 Penfold Court Sutton Road,Headington Oxford">Flat 6 Penfold Court Sutton Road,Headington Oxford</option>
								  <option value="Flat 7 Penfold Court Sutton Road,Headington Oxford">Flat 7 Penfold Court Sutton Road,Headington Oxford</option>
								  <option value="Flat 8 Penfold Court Sutton Road,Headington Oxford">Flat 8 Penfold Court Sutton Road,Headington Oxford</option>
								  <option value="Flat 9 Penfold Court Sutton Road,Headington Oxford">Flat 9 Penfold Court Sutton Road,Headington Oxford</option>
								  <option value="Flat 10 Penfold Court Sutton Road,Headington Oxford">Flat 10 Penfold Court Sutton Road,Headington Oxford</option>
								  <option value="Flat 11 Penfold Court Sutton Road,Headington Oxford">Flat 11 Penfold Court Sutton Road,Headington Oxford</option>
								  <option value="Flat 12 Penfold Court Sutton Road,Headington Oxford">Flat 12 Penfold Court Sutton Road,Headington Oxford</option>
								  <option value="Flat 13 Penfold Court Sutton Road,Headington Oxford">Flat 13 Penfold Court Sutton Road,Headington Oxford</option>
								  <option value="Flat 14 Penfold Court Sutton Road,Headington Oxford">Flat 14 Penfold Court Sutton Road,Headington Oxford</option>
								  <option value="Flat 15 Penfold Court Sutton Road,Headington Oxford">Flat 15 Penfold Court Sutton Road,Headington Oxford</option>
								  <option value="Flat 16 Penfold Court Sutton Road,Headington Oxford">Flat 16 Penfold Court Sutton Road,Headington Oxford</option>
								  <option value="Flat 17 Penfold Court Sutton Road,Headington Oxford">Flat 17 Penfold Court Sutton Road,Headington Oxford</option>
								  <option value="Flat 18 Penfold Court Sutton Road,Headington Oxford">Flat 18 Penfold Court Sutton Road,Headington Oxford</option>
								</select>
							  </div>
							</div>
							<div class="form-group">
							  <label for="HouseNumber" class="col-sm-3 control-label">House Number / Name <span>*</span></label>
							  <div class="col-sm-9">
								<input type="text" class="form-control" name="user_hnumber" id="user_hnumber" value="<?php echo $user_house_number; ?>" placeholder="House Number / Name">
							  </div>
							</div>
							<div class="form-group">
							  <label for="Address1" class="col-sm-3 control-label">Address1 <span>*</span></label>
							  <div class="col-sm-9">
								<input type="text" class="form-control" name="primary_address" id="primary_address" value="<?php echo $primary_address; ?>" placeholder="Address1">
							  </div>
							</div>
							<div class="form-group">
							  <label for="Address2" class="col-sm-3 control-label">Address2</label>
							  <div class="col-sm-9">
								<input type="text" class="form-control" name="secondary_address" id="secondary_address" value="<?php echo $secondary_address ?>" placeholder="Address2">
							  </div>
							</div>
							<div class="form-group">
							  <label for="City" class="col-sm-3 control-label">City <span>*</span></label>
							  <div class="col-sm-9">
								<input type="text" class="form-control" name="user_city" id="user_city" value="<?php echo $user_city; ?>" placeholder="City">
							  </div>
							</div>
							<div class="form-group">
							  <label for="County" class="col-sm-3 control-label">County <span>*</span></label>
							  <div class="col-sm-9">
								<input type="text" class="form-control" name="user_county" id="user_county" value="<?php echo $user_county; ?>" placeholder="County">
							  </div>
							</div>
							<div class="form-group">
							  <label for="Country" class="col-sm-3 control-label">Country <span>*</span></label>
							  <div class="col-sm-9">
								<input type="text" class="form-control" name="user_country" id="user_country" value="<?php echo $user_country; ?>" placeholder="Country">
							  </div>
							</div>
							<div class="form-group">
							  <label for="Phone" class="col-sm-3 control-label">Phone <span>*</span></label>
							  <div class="col-sm-9">
								<input type="text" class="form-control" name="user_phone" id="user_phone" value="<?php echo $user_phone; ?>" placeholder="Phone">
							  </div>
							</div>
							<div class="form-group">
							  <label for="Email" class="col-sm-3 control-label">Email <span>*</span></label>
							  <div class="col-sm-9">
								<input type="text" class="form-control" name="user_emailid" id="user_emailid" value="<?php echo $user_emailid; ?>" placeholder="Email">
							  </div>
							</div>
						  </div>
					   
					  </div>
					</div>
					<div class="form-group lftBtn">
						<?php if($default_address =='1'){ ?>
							<a onclick="alert('Please mark an another address as your default billing address before deleting the current default billing address');" class="btn btn-col btnDirection" style="margin-left:10px;"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>
						<?php }else{ ?>
							<button type="submit" name="delete" value="delete" class="btn btn-col btnDirection" style="margin-left:10px;"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
						<?php }	?>
					<?php 
					if($total == 5){ ?>
						<a onclick="alert('You already have 5 saved billing addresses in your account. To add a new billing address please delete one of the existing billing addresses from your account');" id="addMore" class="btn btn-col btnDirection"><i class="fa fa-plus" aria-hidden="true"></i> Add More Address</a>
					 <?php }else{ ?>
						<a href="add-more-billing-address.php" id="addMore" class="btn btn-col btnDirection"><i class="fa fa-plus" aria-hidden="true"></i> Add More Address</a>
					<?php }
					?>
						<button type="submit" name="save" id="save" class="btn btn-col btnDirection" style="margin-right:10px;"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
					</div>
				</form>
          </div>
          
          <h2>Change Password</h2>
          <div>
            <div class="billingInformaion">
              <div class="billingform checkoutsummary">
                <form name="change-pass" id="changepass" action="" method="post" class="form-horizontal">
					  <div class="cardMassages formDee nowidth">
						<div class="form-group">
						  <label for="Email" class="col-sm-3 control-label">Email ID <span>*</span></label>
						  <div class="col-sm-9">
							<input type="text" name="email_id" class="form-control" id="Email" value="<?php echo $user_emailID; ?>" placeholder="Email">
						  </div>
						</div>
						<div class="form-group">
						  <label for="password" class="col-sm-3 control-label">New Password <span>*</span></label>
						  <div class="col-sm-9">
							<input type="password" name="user_password" class="form-control" value="" id="password" placeholder="Password">
						  </div>
						</div>
						<div class="form-group">
						  <label for="cnfpassword" class="col-sm-3 control-label">Confirm Password <span>*</span></label>
						  <div class="col-sm-9">
							<input type="password" name="confirm_pass" class="form-control" id="cnfpassword" placeholder="Confirm Password">
						  </div>
						</div>
						<div class="form-group">
						  <label for="cnfpassword" class="col-sm-3 control-label">&nbsp;</label>
						  <div class="col-sm-9">
							<button type="submit" id="changePassword" class="RegisterBt">Register</button>
						  </div>
						</div>
					  </div>
                </form>
              </div>
            </div>
          </div>
          
          <h2>Order History</h2>
          <div>
          
          <div class="billingInformaion">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered table-condensed table-responsive ftSize">
                  <tr style=" background:#f8f8f8;">
                    <td align="right" width="15%" scope="col">Order Id</td>
                    <td width="30%" scope="col">Order date (DD-MM-YY)</td>
                    <td width="20%" scope="col">Delivery Status</td>
                    <td align="right" width="10%" scope="col">Total</td>
                    <td align="right" width="25%" scope="col">Payment Status</td>
                  </tr>
                  
                  <?php
					foreach($order_history as $order_val){ //print_r($order_val);
						$user_order_id = $order_val->order_id;
						$payment_status = $order_val->payment_status;
						$order_status = $order_val->order_status;
						//$product_qty = $order_val->product_qty;
						//$gift_quantity = $order_val->gift_quantity;
						$total_orders = $order_val->total_orders;
						$order_date = $order_val->created_date;
						$date = date_create($order_date);
					  echo '<tr>
						<td align="right">'.$user_order_id.'</td>
						<td>'.date_format($date,"Y/M/D H:i:s").'</td>
						<td>'.$order_status.'</td>
						<td align="right">'.$total_orders.'</td>
						<td align="right">'.$payment_status.'</td>
					  </tr>';
					}
                  ?>
                  
                </table>
          </div>
          
          </div>
          
        </div>
        <a href="review.php" class="btn btn-col btnDirection">Continue</a>
        <!--<button type="submit" class="btn btn-col btnDirection">Continue</button>-->
      </div>
      <div class="col-lg-3"></div>
    </div>
  </div>
</section>
<script src="javascripts/responsiveTabs.js"></script> 
<script>
$(document).ready(function() {
	RESPONSIVEUI.responsiveTabs();
})
</script>

<?php include_once 'footer.php'; ?>
