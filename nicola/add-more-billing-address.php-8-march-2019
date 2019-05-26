<?php 
include_once 'header.php';

$key = $_SESSION['key'];

if(empty($_SESSION['key'])){
	header("location:login");
}



//All Addresses
$address_api = API_PATH.'billing-address/'.$key.'';
$address_val = $obj->getCurl($address_api);
$billingAddress =  $address_val->billing_address;
$total = count($billingAddress);

if($total == 5){
	header("location:my-account");
}

if(isset($_POST['addBillingAddress']) == 'addBillingAddress'){
	
	$post_data = $_POST;
	$billing_api = API_PATH.'addmore-billing-address/'.$_SESSION['key'].'';
	$post_address = $obj->httpPost($billing_api, $post_data);
	if($post_address->status == 'true'){
		$bill_id = $obj->EncryptClientId($post_address->bill_id);
		header("location:my-account?id=".$bill_id."");
	}
}

?>
<script src="postcodes/crafty_postcode.class.js"></script>
<script>
   var cp_obj = CraftyPostcodeCreate();
   cp_obj.set("access_token", "5a31d-5c42e-48f25-016e3"); // your token here
   cp_obj.set("result_elem_id", "crafty_postcode_result_display");
   cp_obj.set("form", "billing-frm");
   cp_obj.set("elem_street1"  , "primary_address");
   cp_obj.set("elem_street2"  , "secondary_address");
   cp_obj.set("elem_town"     , "user_city");
   cp_obj.set("elem_county", "user_county");
   cp_obj.set("elem_country", "user_country");
   cp_obj.set("elem_postcode" , "post_code");
</script>
<section>
  <div class="container-fluid">
    <div class="row">
    <div class="col-lg-3"></div>
      <div class="col-lg-6 col-md-12">
        <h2>Your Billing Address </h2>
        <div class="requiredText"><span>*</span>Required</div>
        <div class="billingInformaion">
          <div class="billingform checkoutsummary">
          
          	<form name="billing-frm" id="billingfrm" action="" class="form-horizontal" method="post">
				  <input type="hidden" name="site_id" value="<?php echo SITE_ID; ?>" />
				  <div class="billInformation newFormDe">
				  
					<div class="form-group">
						<label for="DefaultAddress" class="col-sm-12 control-label">Make This Your Default Address 
							<input value="1" id="default_address" name="default_address" type="checkbox">
						</label>
					</div>
				  
					<div class="form-group">
					  <label for="Name" class="col-sm-3 control-label">Name <span>*</span></label>
					  <div class="col-sm-3">
						<select class="form-control require" name="user_prefix" id="user_prefix">
							<option value="">Select prefix</option>
							<option value="Mr"   <?php echo (($user_prefix == 'Mr') ? 'selected' : ''); ?>>Mr.</option>
							<option value="Mrs"  <?php echo (($user_prefix == 'Mrs') ? 'selected' : ''); ?>>Mrs</option>
							<option value="Ms"   <?php echo (($user_prefix == 'Ms') ? 'selected' : ''); ?>>Ms.</option>
							<option value="Miss" <?php echo (($user_prefix == 'Miss') ? 'selected' : ''); ?>>Miss</option>
						</select>
					  </div>
					  <div class="col-sm-3">
						<input type="text" class="form-control require" name="user_fname" id="user_fname" placeholder="First Name">
					  </div>
					  <div class="col-sm-3">
						<input type="text" class="form-control require" name="user_lname" id="user_lname" placeholder="Last Name">
					  </div>
					</div>
					<div class="form-group">
					  <label for="PostCode" class="col-sm-3 control-label">Search Address<span>*</span></label>
					  <div class="col-sm-3">
						 <input type="text" class="form-control require" name="post_code" id="post_code" placeholder="Post Code">
					  </div>
					  <div class="col-sm-6">
						<button type="button" id="searchPin" class="search" onclick="cp_obj.doLookup()">Search</button>
					  </div>
					</div>
					<div class="form-group">
						<label for="HouseNumber" class="col-sm-3 control-label">Select Address</label>
						<div class="col-sm-9"> <span id="crafty_postcode_result_display">&nbsp;</span> </div>
					</div>
					<div class="form-group">
					  <label for="HouseNumber" class="col-sm-3 control-label">House Number / Name <span>*</span></label>
					  <div class="col-sm-9">
						<input type="text" class="form-control require" name="user_hnumber" id="user_hnumber" placeholder="House Number / Name">
					  </div>
					</div>
					<div class="form-group">
					  <label for="Address1" class="col-sm-3 control-label">Address1 <span>*</span></label>
					  <div class="col-sm-9">
						<input type="text" class="form-control require" name="primary_address" id="primary_address" placeholder="Address1">
					  </div>
					</div>
					<div class="form-group">
					  <label for="Address2" class="col-sm-3 control-label">Address2</label>
					  <div class="col-sm-9">
						<input type="text" class="form-control" name="secondary_address" id="secondary_address" placeholder="Address2">
					  </div>
					</div>
					<div class="form-group">
					  <label for="City" class="col-sm-3 control-label">City <span>*</span></label>
					  <div class="col-sm-9">
						<input type="text" class="form-control require" name="user_city" id="user_city" placeholder="City">
					  </div>
					</div>
					<div class="form-group">
					  <label for="County" class="col-sm-3 control-label">County <span>*</span></label>
					  <div class="col-sm-9">
						<input type="text" class="form-control require" name="user_county" id="user_county" placeholder="County">
					  </div>
					</div>
					<div class="form-group">
					  <label for="Country" class="col-sm-3 control-label">Country <span>*</span></label>
					  <div class="col-sm-9">
						<input type="text" class="form-control require" name="user_country" id="user_country" placeholder="Country">
					  </div>
					</div>
					<div class="form-group">
					  <label for="Phone" class="col-sm-3 control-label">Phone <span>*</span></label>
					  <div class="col-sm-9">
						<input type="text" class="form-control require" name="user_phone" id="user_phone" placeholder="Phone">
					  </div>
					</div>
					<div class="form-group">
					  <label for="Email" class="col-sm-3 control-label">Email <span>*</span></label>
					  <div class="col-sm-9">
						<input type="text" class="form-control user_emailid require" name="user_emailid" id="user_emailid" placeholder="Email">
					  </div>
					</div>
				  </div>
            
			  </div>
			</div>
			<button type="submit" name="addBillingAddress" value="addBillingAddress" class="btn btn-col btnDirection">save</button>
			<!--<button type="submit" class="btn btn-col2 btnDirectionLeft" onclick="window.location.href='shoppingCart.html'">My Cart</button>-->
        </form>
      </div>
    <div class="col-lg-3"></div>
    </div>
  </div>
</section>




<?php include_once 'footer.php'; ?>
