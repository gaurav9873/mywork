<?php 
include_once 'header.php'; 
$dbobj = new ConnectDb();

if(empty($_SESSION['cart']['products']['Standard']) AND empty($_SESSION['cart']['products']['Large'])){
	$root_url = 'my-account.php';
}else{
	$root_url = 'checkout-delivery.php';
}
?>
<script src="postcodes/crafty_postcode.class.js"></script>
<script>
   var cp_obj = CraftyPostcodeCreate();
   cp_obj.set("access_token", "5a31d-5c42e-48f25-016e3"); // your token here
   cp_obj.set("result_elem_id", "crafty_postcode_result_display");
   cp_obj.set("form", "user-frm");
   cp_obj.set("elem_street1"  , "primary_address");
   cp_obj.set("elem_street2"  , "secondary_address");
   cp_obj.set("elem_town"     , "user_city");
   cp_obj.set("elem_county", "user_county");
   cp_obj.set("elem_country", "user_country");
   cp_obj.set("elem_postcode" , "post_code");
   //cp_obj.set("elem_postcode" , "user_pcode");
</script>
<script src="validation/jquery.validate.min.js"></script>
<script src="validation/additional-methods.min.js"></script>
<script src="javascripts/user-form.js"></script>

<div class="loader1" style="display: none;">
    <div class="overlay">
        <div class="loader"></div>
        <h1>Please wait...</h1>
    </div>
</div>
<section class="responsPnone">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">

          <h2>Registration Form</h2>
          <div class="error-message" id="ermsg" style="color:red; text-align:center;font-weight:bold;"></div>
          <div class="requiredText"><span>*</span>Required</div>
          
          <div class="billingInformaion">
          <div class="billingform nopart">
            <form name="user-frm" id="user-frm" action="" class="form-horizontal" method="post">
              <h4>* This will be your default Billing Address.</h4>
             
              <div class="billInformation newFormDe">
                <div class="form-group">
                  <label for="Name" class="col-sm-3 control-label">Name <span>*</span></label>
                  <div class="col-sm-3">
                    <select class="form-control require" name="user_prefix" id="user_prefix">
						<option value="">Select title</option>
						<option <?php echo isset($_POST['user_prefix'])=='Mr' ? 'selected' : 'Mr'; ?> value="Mr">Mr</option>
						<option <?php echo isset($_POST['user_prefix'])=='Mrs' ? 'selected' : 'Mrs'; ?> value="Mrs">Mrs</option>
						<option <?php echo isset($_POST['user_prefix'])=='Ms' ? 'selected' : 'Ms'; ?> value="Ms">Ms</option>
						<option <?php echo isset($_POST['user_prefix'])=='Miss' ? 'selected' : 'Miss'; ?> value="Miss">Miss</option>
					</select>
                  </div>
                  <div class="col-sm-3">
                    <input type="text" class="form-control require" name="user_fname" id="user_fname" value="<?php echo isset($_POST['user_fname']) ? $_POST['user_fname'] : ''; ?>" placeholder="First Name">
                  </div>
                  <div class="col-sm-3">
                    <input type="text" class="form-control require" name="user_lname" id="user_lname" value="<?php echo isset($_POST['user_lname']) ? $_POST['user_lname'] : ''; ?>" placeholder="Last Name">
                  </div>
                </div>
                <div class="form-group">
                  <label for="PostCode" class="col-sm-3 control-label">Search Address</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control require" name="post_code" id="post_code" value="<?php echo isset($_POST['post_code']) ? $_POST['post_code'] : ''; ?>" placeholder="Post Code" style="text-transform:uppercase" maxlength="10">
                  </div>
                  <div class="col-sm-6">
                    <button type="button" id="searchPin" onclick="cp_obj.doLookup()">Search</button>
                  </div>
                </div>
                <div class="form-group">
                  <label for="HouseNumber" class="col-sm-3 control-label">Select Address</label>
                  <div class="col-sm-9">
                    <span id="crafty_postcode_result_display">&nbsp;</span>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="Address1" class="col-sm-3 control-label">Address1 <span>*</span></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control require" name="primary_address" value="<?php echo isset($_POST['primary_address']) ? strip_tags(trim($_POST['primary_address'])) : ''; ?>" id="primary_address" placeholder="Address1">
                  </div>
                </div>
                <div class="form-group">
                  <label for="Address2" class="col-sm-3 control-label">Address2</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="secondary_address" id="secondary_address" value="<?php echo isset($_POST['secondary_address']) ? strip_tags(trim($_POST['secondary_address'])) : ''; ?>" placeholder="Address2">
                  </div>
                </div>
                <div class="form-group">
                  <label for="City" class="col-sm-3 control-label">City <span>*</span></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control require" name="user_city" id="user_city" value="<?php echo isset($_POST['user_city']) ? $_POST['user_city'] : ''; ?>" placeholder="City">
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="Country" class="col-sm-3 control-label">Country <span>*</span></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control require" name="user_country" id="user_country" value="<?php echo isset($_POST['user_country']) ? $_POST['user_country'] : ''; ?>" placeholder="Country">
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="Country" class="col-sm-3 control-label">Postcode <span>*</span></label>
                  <div class="col-sm-9">
                    <input class="form-control user_pcode require" name="user_pcode" id="user_pcode" value="<?php echo isset($_POST['user_pcode']) ? $_POST['user_pcode'] : ''; ?>" placeholder="Postcode" style="text-transform:uppercase" maxlength="10" type="text">
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="Phone" class="col-sm-3 control-label">Mobile/Telephone <span>*</span></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control require" name="user_phone" id="user_phone" value="<?php echo isset($_POST['user_phone']) ? $_POST['user_phone'] : ''; ?>" placeholder="Phone">
                  </div>
                </div>
                
              </div>
                
              <div class="cardMassages formDee">
                <div class="form-group">
				
                  <label for="Email" class="col-sm-3 control-label">Email ID <span>*</span></label>
                  <div class="col-sm-9">
                  <input type="text" class="form-control user_emailid require" name="user_emailid" id="user_emailid" value="<?php echo isset($_POST['user_emailid']) ? $_POST['user_emailid'] : ''; ?>" placeholder="Email">
                  </div>
                </div>
                <div class="form-group">
                  <label for="password" class="col-sm-3 control-label">Password <span>*</span></label>
                  <div class="col-sm-9">
                    <input type="password" class="form-control require" name="user_password" id="user_password" placeholder="Password">
                  </div>
                </div>
                <div class="form-group">
                  <label for="cnfpassword" class="col-sm-3 control-label">Confirm Password <span>*</span></label>
                  <div class="col-sm-9">
                    <input type="password" class="form-control require" name="cnfpassword" id="cnfpassword" placeholder="Confirm Password">
                  </div>
                </div>
                <div class="form-group">
                  <label for="cnfpassword" class="col-sm-3 control-label">&nbsp;</label>
                  <div class="col-sm-9">
                    <button type="submit" name="register" id="registerCust" class="RegisterBt">Register</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
          
      </div>
    </div>
  </div>
</section>
<?php include_once 'footer.php'; ?>
