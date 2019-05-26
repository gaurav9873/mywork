<?php 
include_once 'header.php';

$key = $_SESSION['key'];

if(empty($_SESSION['key'])){
	header("location:login.php");
}



//All Addresses
$address_api = API_PATH.'billing-address/'.$key.'';
$address_val = $obj->getCurl($address_api);
$billingAddress =  $address_val->billing_address;
$total = count($billingAddress);

if($total == 5){
	header("location:my-account.php");
}

if(isset($_POST['addBillingAddress']) == 'addBillingAddress'){
	
	$post_data = $_POST;
	$billing_api = API_PATH.'addmore-billing-address/'.$_SESSION['key'].'';
	$post_address = $obj->httpPost($billing_api, $post_data);
	if($post_address->status == 'true'){
		header("location:my-account.php");
	}
}

?>

<section>
  <div class="container-fluid">
    <div class="row">
    <div class="col-lg-3"></div>
      <div class="col-lg-6 col-md-12">
        <h2>Your Billing Address </h2>
        <div class="requiredText"><span>*</span>Required</div>
        
        <!--<div class="row">
        <div class="col-lg-5"></div>
        <div class="col-lg-7">
        <div class="form-group topfrm">
          <label for="Billingaddress" class="col-sm-6 control-label">Select From Billing Addresses</label>
          <div class="col-sm-6" style="padding-right:0;">
            <select class="form-control" id="Billingaddress">
              <option>Form 1</option>
              <option>Form 2 </option>
              <option>Form 3 </option>
              <option>Form 4 </option>
            </select>
          </div>
        </div>
        </div>
        </div>-->
        
        <div class="billingInformaion">
          <div class="billingform checkoutsummary">
          
          	<form name="billing-frm" id="billingfrm" action="" class="form-horizontal" method="post">
              
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
							<option value="Mr">Mr.</option>
							<option value="Ms">Ms.</option>
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
						<button type="button" id="searchPin" class="search">Search</button>
					  </div>
					</div>
					<div class="form-group">
					  <label for="HouseNumber" class="col-sm-3 control-label">Select Address</label>
					  <div class="col-sm-9">
						<select name="addrList" id="findSelect" class="findColAddress form-control searchPin">
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
