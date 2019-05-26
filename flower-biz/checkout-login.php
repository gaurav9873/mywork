<?php
include_once 'header.php'; 

if(empty($_SESSION['cart']['products']['Standard']) AND empty($_SESSION['cart']['products']['Large'])){
	header("location:login");
}

if(isset($_SESSION['key'])!=''){
	header("location:checkout-delivery");
}

$msg = '';
if(isset($_POST['user_email']) and ($_POST['user_password'])){
	
	$url = API_PATH.'login';
	//$post_data = $_POST;
	
	$user_email = filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL);
	$user_password = $_POST['user_password'];
	$site_id = intval(SITE_ID);
	$post_data = array('user_email' => $user_email, 'user_password' => $user_password, 'site_id' => $site_id);
	
	$curl_responce = $obj->httpPost($url, $post_data);
	if($curl_responce->status == 'true'){
		$res = $curl_responce->data;
		$_SESSION['user_id'] = $res->user_id;
		$_SESSION['email'] = $res->emailID;
		$_SESSION['first_name'] = $res->fname;
		$_SESSION['last_name'] = $res->lname;
		$_SESSION['key'] = $res->user_key;
		header("location:my-account");
	}else if($curl_responce->status == 'updatepassword'){
		$msg = 'New site has been introduced.Please check your mail box and re-set the password to login to the new site.';
	}else{
		$msg = 'Invalid credentials';
	}
	
}
$userprifix = isset($_SESSION['user_prefix']) ? $_SESSION['user_prefix'] : '';

$dbobj = new ConnectDb();

$holiday_dates = $dbobj->holiday_list();
$holiday_date = array();
foreach($holiday_dates as $holiday_val){
	array_push($holiday_date, $holiday_val['holiday_date']);
}
?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="javascripts/delivery-chkout.js"></script>
<script src="postcodes/crafty_postcode.class.js"></script>
<script>
	var rootUrl = '<?php echo API_PATH; ?>';
</script>

<section class="responsPnone">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="billingInformaion">
          <div class="mandatory" id="mandatory" style="color:red;display:none">Please fill all mandatory fields.</div>
          <div class="billingform">
            <h4>Enter your delivery information</h4>
            
              <div class="row">
              <div class="billInformation newFormDe ttc col-md-6">
              <form name="deliverfrm" id="deliverfrm" class="deliver-form" action="" method="post">
				  <input type="hidden" class="postaction" name="action" value="<?php echo isset($_SESSION['key']) ? 'saveAddress' : ''; ?>">
				  <input type="hidden" name="site_id" value="<?php echo SITE_ID; ?>" />
                <div class="form-group">
                  <label for="Name" class="col-sm-3 control-label">Name <span>*</span></label>
                  <div class="col-sm-3">
                    <select class="form-control validate" name="user_prefix" id="user_prefix">
                      <option value="">Select title</option>
                      <option <?php if($userprifix == 'Mr'){ echo 'selected';}else{ echo ''; } ?> value="Mr">Mr.</option>
                      <option <?php if($userprifix == 'Mrs'){ echo 'selected';}else{ echo ''; } ?> value="Mrs">Mrs</option>
                      <option <?php if($userprifix == 'Ms'){ echo 'selected';}else{ echo '';} ?> value="Ms">Ms</option>
                      <option <?php if($userprifix == 'Miss'){ echo 'selected';}else{ echo '';} ?> value="Miss">Miss</option>
                    </select>
                  </div>
                  <div class="col-sm-3">
                    <input class="form-control validate" name="user_fname" id="user_fname" value="<?php echo isset($_SESSION['user_fname']) ? $_SESSION['user_fname'] : ''; ?>" placeholder="First Name" type="text">
                  </div>
                  <div class="col-sm-3">
                    <input class="form-control validate" name="user_lname" id="user_lname" value="<?php echo isset($_SESSION['user_lname']) ? $_SESSION['user_lname'] : ''; ?>" placeholder="Last Name" type="text">
                  </div>
                </div>
                <div class="form-group">
                  <label for="PostCode" class="col-sm-3 control-label">Search Address</label>
                  <div class="col-sm-3">
                    <input class="form-control" name="post_code" id="post_code" value="<?php echo isset($_SESSION['post_code']) ? $_SESSION['post_code'] : ''; ?>" placeholder="Post Code" type="text" style="text-transform:uppercase" maxlength="7">
                    <div class="delivery-cost" id="cost"></div>
                    <div class="delivery-cost location" id="location"></div>
                  </div>
                  <div class="col-sm-6">
                    <button type="button" id="searchPin" onclick="cp_obj.doLookup()">Search</button>
                  </div>
                </div>
                <div class="form-group">
                  <label for="HouseNumber" class="col-sm-3 control-label">Select Address</label>
                  <div class="col-sm-9"> <span id="crafty_postcode_result_display">&nbsp;</span> </div>
                </div>
                <div class="form-group">
                  <label for="Address1" class="col-sm-3 control-label">Mobile</label>
                  <div class="col-sm-9">
                    <input class="form-control" name="user_mobile" id="user_mobile" placeholder="Mobile" value="<?php echo isset($_SESSION['user_mobile']) ? $_SESSION['user_mobile'] : ''; ?>" type="text">
                  </div>
                </div>
                <div class="form-group">
                  <label for="Address1" class="col-sm-3 control-label">Telephone</label>
                  <div class="col-sm-9">
                    <input class="form-control" name="user_telephone" id="user_telephone" placeholder="Telephone" value="<?php echo isset($_SESSION['user_telephone']) ? $_SESSION['user_telephone'] : ''; ?>" type="text">
                  </div>
                </div>
                <div class="form-group">
                  <label for="Address1" class="col-sm-3 control-label">Fax</label>
                  <div class="col-sm-9">
                    <input class="form-control" name="user_fax" id="user_fax" placeholder="Fax" value="<?php echo isset($_SESSION['user_fax']) ? $_SESSION['user_fax'] : ''; ?>" type="text">
                  </div>
                </div>
                <div class="form-group">
                  <label for="Address1" class="col-sm-3 control-label">Email</label>
                  <div class="col-sm-9">
                    <input class="form-control" name="delivery_email" id="delivery_email" placeholder="Email" value="<?php echo isset($_SESSION['delivery_email']) ? $_SESSION['delivery_email'] : ''; ?>" type="text">
                  </div>
                </div>
                <div class="form-group">
                  <label for="Address1" class="col-sm-3 control-label">Address1 <span>*</span></label>
                  <div class="col-sm-9">
                    <input class="form-control validate" name="user_address1" id="user_address1" value="<?php echo isset($_SESSION['primary_address']) ? $_SESSION['primary_address'] : ''; ?>" placeholder="Address1" type="text">
                  </div>
                </div>
                <div class="form-group">
                  <label for="Address2" class="col-sm-3 control-label">Address2</label>
                  <div class="col-sm-9">
                    <input class="form-control" name="user_address2" id="user_address2" value="<?php echo isset($_SESSION['secondary_address']) ? $_SESSION['secondary_address'] : '';?>" placeholder="Address2" type="text">
                  </div>
                </div>
                <div class="form-group">
                  <label for="City" class="col-sm-3 control-label">City <span>*</span></label>
                  <div class="col-sm-9">
                    <input class="form-control validate" name="user_city" id="user_city" value="<?php echo isset($_SESSION['user_city']) ? $_SESSION['user_city'] : ''; ?>" placeholder="City" type="text">
                  </div>
                </div>
                <div class="form-group">
                  <label for="County" class="col-sm-3 control-label">County </label>
                  <div class="col-sm-9">
                    <input class="form-control" name="user_county" id="user_county" value="<?php echo isset($_SESSION['user_county']) ? $_SESSION['user_county'] : '';?>" placeholder="County" type="text">
                  </div>
                </div>
                <div class="form-group">
                  <label for="County" class="col-sm-3 control-label">Country </label>
                  <div class="col-sm-9"> <strong>United Kingdom</strong> </div>
                </div>
                
                <div class="form-group">
                  <label for="County" class="col-sm-3 control-label">Postcode<span>*</span></label>
                  <div class="col-sm-9">
                    <input class="form-control validate" name="user_pcode" id="user_pcode" value="" placeholder="Postcode" style="text-transform:uppercase" maxlength="7" type="text">
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-3 control-label">Delivery Date :<span>*</span></label>
                  <div class="col-sm-9">
                    <input type="text" class="dp1 form-control valid validate" name="delivery_date" id="delivery_date" value="<?php echo isset($_SESSION['delivery_date']) ? $_SESSION['delivery_date'] : '';?>" readonly placeholder="Delivery Date"/>
                    <i class="fa fa-calendar" style="position:absolute; top:12px; right:25px;"></i> </div>
                </div>
                <div class="form-group">
                  <label for="title" class="col-sm-3 control-label">Card Message :<span>*</span></label>
                  <div class="col-sm-9">
                    <textarea name="user_card_msg" id="user_card_msg" class="form-control validate" cols="15" role="15"><?php echo isset($_SESSION['user_card_msg']) ? $_SESSION['user_card_msg'] : '';?></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Note to Florist :</label>
                  <div class="col-sm-9">
                    <textarea name="user_notes" id="user_notes" class="form-control" role="15"><?php echo isset($_SESSION['user_notes']) ? $_SESSION['user_notes'] : '';?></textarea>
                  </div>
                </div>
                <?php if(isset($_SESSION['key'])){ ?>
                <button type="button" id="save_delivery_address" value="saveAddress" class="btn btn-col btnDirection">Save Delivery</button>
                <?php } ?>
                </form>
              </div>
            
            <div class="cardMassages formDee frmdee col-md-6">
              <?php if(!isset($_SESSION['key'])){ ?>
              <div class="row">
                <form name="signupfrm" id="signupfrm" class="signupfrm" action="" method="post">
                  <div class="col-lg-12">
                    <div class=""> <strong>Are you A New Customer ?</strong>
                      <p>Register with us for a faster checkout, to track the status of your order and more. You can also checkout as a guest.</p>
                    </div>
                    <div class="wait" style="display:none; color:red;">Please wait...</div>
                    <button type="submit" value="continueSignup" id="continueSignup" class="btn btn-col2 btnDirectionLeft">REGISTER</button>
                  </div>
                </form>
                <div  id="loginsec">
                  <form name="loginfrm" id="loginfrm" class="loginfrm" action="" method="post">
                    <div class="col-lg-12">
                      <div class="sec-login lg-form lftFrm"> <strong>I'm A Returning Customer</strong>
                        <p>To continue, please enter your email address and password that you use for your account.</p>
                        <div class="errmsg" style="color:red;"><?php echo $msg; ?></div>
                        <div class="form-group">
                          <label class="">Email Address:</label>
                          <input class="form-control login_email" id="login_email" name="user_email" type="email">
                        </div>
                        <div class="form-group"> <!--pwdSec-->
                          <label class="">Password:</label>
                          <input class="form-control login_password" id="login_password" name="user_password" type="password">
                        </div>
                        <button type="submit" id="loginSubmit" value="continueLogin" class="btn btn-col btnDirection bbtmrg">LOGIN</button>
                        <a href="javascript:void(0);" class="pwdLink" id="pwdLink">Forgot your password?</a> </div>
                    </div>
                  </form>
                </div>
                <div id="forgetpwd" style="display:none;">
                  <form name="fpwd" id="fpwd" action="" method="post">
                    <div class="col-lg-12">
                      <div class="sec-login lg-form lftFrm"> <strong>Forgot your password?</strong>
                        <p>Simply Enter the Email address you used to sign up, we'll email to you your UserName and Password. </p>
                        <div class="form-group">
                          <label class="">Enter Email Address:</label>
                          <input class="form-control emailtxt" name="forget_email" id="emailtxt" type="email">
                          <div class="waitwheel" style="display:none; color:red;">Please Wait...</div>
                          <div class="sumsg" style="display:none; color:green;"></div>
                        </div>
                        <div class="clearfix"></div>
                        <button type="button" class="btn btn-col btnDirection bbtmrg" id="forgetPass">Submit</button>
                        <a href="javascript:void(0);" class="pwdLink" id="lgfrmlink">Login</a> </div>
                    </div>
                  </form>
                </div>
              </div>
              <?php } ?>
            </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
   var cp_obj = CraftyPostcodeCreate();
   cp_obj.set("access_token", "5a31d-5c42e-48f25-016e3"); // your token here
   cp_obj.set("result_elem_id", "crafty_postcode_result_display");
   cp_obj.set("form", "deliverfrm");
   cp_obj.set("elem_company"  , "companyname");
   cp_obj.set("elem_street1"  , "user_address1");
   cp_obj.set("elem_street2"  , "user_address2");
   //cp_obj.set("elem_street3"  , "address3");
   cp_obj.set("elem_town"     , "user_city");
   cp_obj.set("elem_postcode" , "post_code");
</script>
<link href="date-picker/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" href="date-picker/css/foundation-datepicker.min.css">

<!-- DATEPICKER FILES --> 
<script src="date-picker/foundation-datepicker.js"></script> 
<script src="date-picker/foundation-datepicker.vi.js"></script> 

<!-- foundation datepicker end --> 
<script src="date-picker/foundation.min.js"></script> 
<script>
$(document).ready(function(){
	var disabledDates = [<?php echo '"'.implode('","', $holiday_date).'"' ?>];
	
	$(function () {
		 $(".dp1").datepicker({
			minDate: new Date().getHours() >= 15 ? 1 : 0,
			dateFormat:'yy-mm-dd',
			beforeShowDay: function (date) {
				 var datestring = jQuery.datepicker.formatDate('yy-mm-dd', date);
				 return [ disabledDates.indexOf(datestring) == -1 ]
			}
		 });
   });
	
});
</script>
<?php include_once 'footer.php'; ?>
