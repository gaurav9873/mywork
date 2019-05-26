<?php
include_once 'header.php'; 

$root_url = SITE_URL;
if(empty($_SESSION['cart']['products']['Standard']) AND empty($_SESSION['cart']['products']['Large'])){
	header("location:$root_url");
}

$msg = '';
$userprifix = isset($_SESSION['user_prefix']) ? $_SESSION['user_prefix'] : '';

$obj = new CustomFunctions();
$dbobj = new ConnectDb();

$holiday_dates = $dbobj->holiday_list();
$holiday_date = array();
foreach($holiday_dates as $holiday_val){
	array_push($holiday_date, $holiday_val['holiday_date']);
}

$current_date = date("Y-m-d");
$tmp_address = $dbobj->check_ordered_process($_SESSION['user_id'], SITE_ID, $current_date);
$address_id = isset($tmp_address->id) ? $tmp_address->id : '';
$deladdress = isset($tmp_address->delivery_address) ? $tmp_address->delivery_address : '';
$addressval = json_decode($deladdress);
?>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="javascripts/delivery-chkout.js"></script>
<script src="postcodes/crafty_postcode.class.js"></script>

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
        <div class="billingInformaion">
			<div class="mandatory" id="mandatory" style="color:red;display:none">Please fill all mandatory fields.</div>
          <div class="billingform">
			  <h4>Enter your delivery information</h4>
            <form name="deliverfrm" id="deliverfrm" class="deliver-form" action="" method="post"> 
			<input type="hidden" class="postaction" name="action" value="<?php echo isset($_SESSION['key']) ? 'saveAddress' : ''; ?>">
            <input type="hidden" name="addressid" value="<?php echo $obj->EncryptClientId($address_id); ?>">
			  <div class="billInformation newFormDe ttc">
                <div class="form-group">
                  <label for="Name" class="col-sm-3 control-label">Name <span>*</span></label>
                  <div class="col-sm-3">
                    <select class="form-control" name="user_prefix" id="user_prefix">
                        <option value="">Select title</option>
                        <option <?php if (isset($addressval->user_prefix) == 'Mr') {
                            echo 'selected';
                        } else {
                            echo '';
                        } ?> value="Mr">Mr.
                        </option>
                        <option <?php if (isset($addressval->user_prefix) == 'Mrs') {
                            echo 'selected';
                        } else {
                            echo '';
                        } ?> value="Mrs">Mrs
                        </option>
                        <option <?php if (isset($addressval->user_prefix) == 'Ms') {
                            echo 'selected';
                        } else {
                            echo '';
                        } ?> value="Ms">Ms
                        </option>
                        <option <?php if (isset($addressval->user_prefix) == 'Miss') {
                            echo 'selected';
                        } else {
                            echo '';
                        } ?> value="Miss">Miss
                        </option>
					</select>
                  </div>
                  <div class="col-sm-3">
                    <input class="form-control validate" name="user_fname" id="user_fname" data-title="Please enter first name" value="<?php echo isset($addressval->user_fname) ? $addressval->user_fname : ''; ?>" placeholder="First Name" type="text">
                  </div>
                  <div class="col-sm-3">
                    <input class="form-control validate" name="user_lname" id="user_lname" data-title="Please enter last name" value="<?php echo isset($addressval->user_lname) ? $addressval->user_lname : ''; ?>" placeholder="Last Name" type="text">
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="PostCode" class="col-sm-3 control-label">Search Address</label>
                  <div class="col-sm-3">
                    <input class="form-control" name="post_code" id="post_code" value="<?php echo isset($addressval->post_code) ? $addressval->post_code : ''; ?>" placeholder="Post Code" type="text" style="text-transform:uppercase" maxlength="8">
                    <div class="delivery-cost" id="cost"></div>
                  </div>
                  <div class="col-sm-6">
                   <!--<button type="button" id="searchPin" onclick="cp_obj.doLookup()">Search</button>-->
                   <button type="button" id="searchPin">Search</button>
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
                    <input class="form-control validate" name="user_address1" id="user_address1" data-title="Please enter address line 1" value="<?php echo isset($addressval->primary_address) ? $addressval->primary_address : ''; ?>" placeholder="Address1" type="text">
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="Address2" class="col-sm-3 control-label">Address2</label>
                  <div class="col-sm-9">
                    <input class="form-control" name="user_address2" id="user_address2" value="<?php echo isset($addressval->secondary_address) ? $addressval->secondary_address : '';?>" placeholder="Address2" type="text">
                  </div>
                </div>
                
                 <div class="form-group">
                  <label for="City" class="col-sm-3 control-label">City <span>*</span></label>
                  <div class="col-sm-9">
                    <input class="form-control validate" name="user_city" id="user_city" data-title="Please enter city" value="<?php echo isset($addressval->user_city) ? $addressval->user_city : ''; ?>" placeholder="City" type="text">
                  </div>
                </div>
                
                 <!--<div class="form-group">
                  <label for="County" class="col-sm-3 control-label">County </label>
                  <div class="col-sm-9">
                    <input class="form-control" name="user_county" id="user_county" value="<?php //echo isset($_SESSION['user_county']) ? $_SESSION['user_county'] : '';?>" placeholder="County" type="text">
                  </div>
                </div>-->
               
               <div class="form-group">
                  <label for="County" class="col-sm-3 control-label">Country </label>
                  <div class="col-sm-9">
                    <strong>United Kingdom</strong>
                  </div>
                </div> 
                
                <div class="form-group">
                  <label for="County" class="col-sm-3 control-label">Postcode <span>*</span></label>
                  <div class="col-sm-9">
                    <input class="form-control validate user_pcode" name="user_pcode" id="user_pcode" data-title="Please enter postcode" value="<?php echo isset($addressval->post_code) ? $addressval->post_code : ''; ?>" placeholder="Postcode" style="text-transform:uppercase" maxlength="8" type="text">
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="Address1" class="col-sm-3 control-label">Mobile/Telephone</label>
                  <div class="col-sm-9">
                     <input class="form-control" name="user_mobile" id="user_mobile" placeholder="Mobile" value="<?php echo isset($addressval->user_mobile) ? $addressval->user_mobile : ''; ?>" type="text">
                  </div>
                </div>

                
                <div class="form-group">
                  <label for="Address1" class="col-sm-3 control-label">Email</label>
                  <div class="col-sm-9">
                     <input class="form-control" name="delivery_email" id="delivery_email" placeholder="Email" value="<?php echo isset($addressval->delivery_email) ? $addressval->delivery_email : ''; ?>" type="text">
                  </div>
                </div>
                
               
                

              </div>
			  
			  
              <div class="cardMassages formDee frmdee">
                <div class="form-group">
                  <label><span>*</span>Delivery Date :</label>
                 <input type="text" class="dp1 form-control valid validate" name="delivery_date" id="delivery_date" data-title="Please select delivery date" value="<?php echo isset($addressval->delivery_date) ? $addressval->delivery_date : '';?>" readonly placeholder="Delivery Date"/>
				 <i class="fa fa-calendar"></i>
                </div>
                <div class="form-group">
                  <label for="title"><span>*</span>Card Message :</label>
                  <textarea name="user_card_msg" id="user_card_msg" class="form-control validate" data-title="Please enter card message" cols="15" role="15"><?php echo isset($addressval->user_card_msg) ? $addressval->user_card_msg : '';?></textarea>
                </div>
                <div class="form-group">
                  <label>Note to Florist :</label>
                  <textarea name="user_notes" id="user_notes" class="form-control" role="15"><?php echo isset($addressval->user_notes) ? $addressval->user_notes : '';?></textarea>
                </div>
				<div class="form-group delcost" style="display:none;">
					<div class="sdelivery-cost" style="color:red;font-weight:bold;"></div>
				</div>
                <?php if(isset($_SESSION['key'])){ ?>
					<button type="button" id="save_delivery_address" value="saveAddress" class="btn btn-col btnDirection">Save Delivery</button><br />
                 <?php } ?>
                 
              </div>
              
           </form>
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
			dateFormat:'dd-mm-yy',
			beforeShowDay: function (date) {
				 var datestring = jQuery.datepicker.formatDate('dd-mm-yy', date);
				 return [ disabledDates.indexOf(datestring) == -1 ]			}
		 });
   });
	
});
</script>

<?php include_once 'footer.php'; ?>