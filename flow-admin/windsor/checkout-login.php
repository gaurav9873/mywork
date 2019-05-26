<?php
include_once 'header.php'; 

if(empty($_SESSION['cart']['products'])){
	header("location:login.php");
}

$msg = '';
if(isset($_POST['user_email']) and ($_POST['user_password'])){
	
	$url = 'http://54.191.172.136:82/florist-admin/flowers/api/login';
	$post_data = $_POST;
	$curl_responce = $obj->httpPost($url, $post_data);
	if($curl_responce->status == 'true'){
		$res = $curl_responce->data;
		$_SESSION['user_id'] = $res->user_id;
		$_SESSION['email'] = $res->emailID;
		$_SESSION['first_name'] = $res->fname;
		$_SESSION['last_name'] = $res->lname;
		$_SESSION['key'] = $res->user_key;
		header("location:my-account.php");
	}else{
		$msg = 'invalid credentials';
	}
	
}


//$_SESSION['args'] = array('start_time' => time(), 'IP' => $_SERVER['REMOTE_ADDR'], 'userAgent' => $_SERVER['HTTP_USER_AGENT']);

$userprifix = isset($_SESSION['user_prefix']) ? $_SESSION['user_prefix'] : '';
?>


<script>
$(document).ready(function(){
	
	
	function ajaxCall(url, parameters, req){
		$.ajax({
			type:'POST',
			contentType: 'application/json',
			url:url,
			dataType: "json",
			data: parameters,
			success: req,
		});	
	}

	function validateFrm(){

		var errorFlag = [];
		errorFlag.push('true');
		$(".validate").each(function(){
			if($(this).val()==''){
			$(this).css("border","2px solid red");
			errorFlag.push('false');
			}else{
				$(this).css('border-color', 'green');

				if(this.id == 'delivery_email'){
					var chkMail = $("#delivery_email").val();
					if(isValidEmailAddress(chkMail)==false){
						$(this).css('border-color', 'red');
						errorFlag.push('false');
					}
				}
				if(this.id == 'user_telephone'){ 
					var chktel = $("#user_telephone").val();
					if(ValidateTelephone(chktel)==false){
						$(this).css('border-color', 'red');
						errorFlag.push('false');
					}
				} 

				if(this.id == 'user_mobile'){
					var cbhmob = $("#user_mobile").val();
					if(validateMobile(cbhmob)==false){
						$(this).css('border-color', 'red');
						errorFlag.push('false');
					}
				}
			}
		});		
		return errorFlag;

	}



	$("#continueSignup").on('click', function(evt){
		evt.preventDefault();
		
		var isValid = validateFrm();
		if(isValid.indexOf('false')<0){

			var vals = $(".regfrm").val();
			if ($("#regfrm:checked").length == 0){
				alert("please check register account");
				return false;
			}
			$(".postaction").val(vals);
			var url = 'action/checkout-login.php';
			var dataString =  JSON.stringify($('#deliverfrm').serializeObject());

			ajaxCall(url, dataString, function(data){
				if(data.status){
					window.location.href="registration.php";
				}
			});
		}else{
			return false;	
		}	
	});



	$("#loginSubmit").on('click', function(e){
		
		e.preventDefault();
		var isValidLoginFrm = validateFrm();
		if(isValidLoginFrm.indexOf('false')<0){

			var login_email = $("#login_email").val();
			var login_password = $("#login_password").val();
			if(login_email =='' || login_password ==''){
				$(".login_email").css("border","2px solid red");
				$(".login_password").css("box-shadow","0 0 3px red");
				alert("Please fill all fields...!!!!!!");
				return false;
			}
			
			var path = 'action/checkout-login.php';
			var frmParameters = JSON.stringify($('#deliverfrm').serializeObject());
			ajaxCall(path, frmParameters, function(reqdata){
				if(reqdata.status){
					$('#loginfrm').submit();
				}
			});
		}else{
			return false;
		}
		
	});
	
	
	$("#save_delivery_address").on('click', function(){
		var validDeliveryAddress = validateFrm();
		if(validDeliveryAddress.indexOf('false')<0){
			var url_path = 'action/checkout-login.php';
			var data_frm_string = JSON.stringify($('#deliverfrm').serializeObject());
			ajaxCall(url_path, data_frm_string, function(reponce){
					if(reponce.status){
						window.location.href="my-account.php";
					}
			});
		}
	});
	

});

function isValidEmailAddress(emailAddress) {
	var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
	return pattern.test(emailAddress);
}

function ValidateTelephone(telephone){ 
    var pattern = /^(\(?(0|\+44)[1-9]{1}\d{1,4}?\)?\s?\d{3,4}\s?\d{3,4})$/;
    return pattern.test(telephone);
}

function validateMobile(mobileText) {
	var filter = /^[0-9-+]+$/;
	return filter.test(mobileText);
}
</script>

<section>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="billingInformaion">
          <div class="billingform">
            <form name="deliverfrm" id="deliverfrm" class="deliver-form" action="" method="post"> 
			<input type="hidden" class="postaction" name="action" value="<?php echo isset($_SESSION['key']) ? 'saveAddress' : ''; ?>">
            <h4>Enter your delivery information</h4>
              <div class="billInformation formDe">
                
                <div class="form-group ppCode">
                  <label for="title1"><span>*</span>Search By Post Code :</label>
                  <input class="form-control validate" name="post_code" id="post_code" value="<?php echo isset($_SESSION['post_code']) ? $_SESSION['post_code'] : ''; ?>" type="text">
                  <button type="button" id="searchPin">Search</button>
                </div>
                
                <div class="form-group">
                  <label for="title"><span>*</span>Select Address :</label>
                  <select name="delivery_address" id="delivery_address" class="findColAddress form-control searchPin validate">
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
                
                <div class="form-group">
                  <label for="title"><span>*</span>Title :</label>
                  <select name="user_prefix" id="user_prefix" class="user_prefix form-control validate">
					<option <?php if($userprifix == 'Mr'){ echo 'selected';}else{ echo ''; } ?> value="Mr">Mr.</option>
					<option <?php if($userprifix == 'Ms'){ echo 'selected';}else{ echo '';} ?> value="Ms">Ms</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="f-name"><span>*</span>First Name :</label>
                  <input class="form-control validate" name="user_fname" id="user_fname" value="<?php echo isset($_SESSION['user_fname']) ? $_SESSION['user_fname'] : ''; ?>" type="text">
                </div>
                <div class="form-group">
                  <label for="l-name"><span>*</span>Last Name :</label>
                  <input class="form-control validate" name="user_lname" id="user_lname" value="<?php echo isset($_SESSION['user_lname']) ? $_SESSION['user_lname'] : '' ?>" type="text">
                </div>
                <div class="form-group">
                  <label for="mobile"><span>*</span>Mobile :</label>
                  <input class="form-control validate" name="user_mobile" id="user_mobile" value="<?php echo isset($_SESSION['user_mobile']) ? $_SESSION['user_mobile'] : ''; ?>" type="text">
                </div>
                <div class="form-group">
                  <label for="telephone"><span>*</span>Telephone :</label>
                  <input class="form-control validate" name="user_telephone" id="user_telephone" value="<?php echo isset($_SESSION['user_telephone']) ? $_SESSION['user_telephone'] : ''; ?>" type="text">
                </div>
                <div class="form-group">
                  <label for="fax">Fax :</label>
                  <input class="form-control" name="user_fax" id="user_fax" value="<?php echo isset($_SESSION['user_fax']) ? $_SESSION['user_fax'] : ''; ?>" type="text">
                </div>
                <div class="form-group">
                  <label for="email"><span>*</span>Email :</label>
                  <input class="form-control validate" name="delivery_email" id="delivery_email" value="<?php echo isset($_SESSION['delivery_email']) ? $_SESSION['delivery_email'] : ''; ?>" type="text">
                </div>
                <div class="form-group">
                  <label for="city"><span>*</span>City :</label>
                  <input class="form-control validate" name="user_city" id="user_city" value="<?php echo isset($_SESSION['user_city']) ? $_SESSION['user_city'] : ''; ?>" type="text">
                </div>
                
                <div class="form-group">
                  <label for="Address1"><span>*</span>Address Line1 :</label>
                  <input class="form-control validate" name="user_address1" id="user_address1" value="<?php echo isset($_SESSION['primary_address']) ? $_SESSION['primary_address'] : ''; ?>" type="text">
                </div>
                <div class="form-group">
                  <label for="address2">Address Line2 :</label>
                  <input class="form-control" name="user_address2" id="user_address2" value="<?php echo isset($_SESSION['secondary_address']) ? $_SESSION['secondary_address'] : '';?>" type="text">
                </div>
                
                <div class="form-group">
                  <label for="country "><span>*</span>County :</label>
                  <input class="form-control validate" name="user_county" id="user_county" value="<?php echo isset($_SESSION['user_county']) ? $_SESSION['user_county'] : '';?>" type="text">
                </div>
                <div class="form-group">
                  <label for="country "><span>*</span>Country  :</label>
                  <br />
                  <strong>United Kingdom</strong>
                </div>
              </div>
              <div class="cardMassages formDee">
                <div class="form-group">
                  <label><span>*</span>Deliery Date :</label>
                 <input type="text" class="dp1 form-control valid validate" name="delivery_date" id="delivery_date" value="<?php echo isset($_SESSION['delivery_date']) ? $_SESSION['delivery_date'] : '';?>" readonly placeholder="Delivery Date"/>
				 <i class="fa fa-calendar"></i>
                </div>
                <div class="form-group">
                  <label for="title"><span>*</span>Card Message :</label>
                  <textarea name="user_card_msg" id="user_card_msg" class="form-control validate" cols="15" role="15"><?php echo isset($_SESSION['user_card_msg']) ? $_SESSION['user_card_msg'] : '';?></textarea>
                </div>
                <div class="form-group">
                  <label>Note to Florist :</label>
                  <textarea name="user_notes" id="user_notes" class="form-control" role="15"><?php echo isset($_SESSION['user_notes']) ? $_SESSION['user_notes'] : '';?></textarea>
                </div>
               
                <?php if(isset($_SESSION['key'])){ ?>
					<button type="button" id="save_delivery_address" value="saveAddress" class="btn btn-col btnDirection">Save Delivery</button>
                 <?php } ?>
              </div>
           </form>
          </div>
        </div>
      </div>
    </div>
  
  <?php if(!isset($_SESSION['key'])){ ?>
    <div class="row">
     <form name="signupfrm" id="signupfrm" class="signupfrm" action="" method="post">
		  <div class="col-lg-6 col-md-6 col-sm-6">
			<div class="sec-login"> <strong>Are you A New Customer ?</strong>
			  <p>Register with us for a faster checkout, to track the status of your order and more. You can also checkout as a guest.</p>

			  </div>
              
              
              
			<div class="wait" style="display:none; color:red;">Please wait...</div>
            
			
			<button type="submit" value="continueSignup" id="continueSignup" class="btn btn-col2 btnDirectionLeft">REGISTER</button>
			<!--<a href="" class="btn btn-col2 btnDirectionLeft">Continue</a>-->
		  </div>
      </form>
      
      <form name="loginfrm" id="loginfrm" class="loginfrm" action="" method="post">
		  
		  <div class="col-lg-6 col-md-6 col-sm-6">
			<div class="sec-login lg-form"> <strong>I'm A Returning Customer</strong>
			  <p>To continue, please enter your email address and password that you use for your account.</p>
			  <div class="errmsg" style="color:red;"><?php echo $msg; ?></div>
			  <div class="form-group">
				<label class="">Email Address:</label>
				<input class="form-control login_email" id="login_email" name="user_email" type="email">
			  </div>
			  <div class="form-group pwdSec">
				<label class="">Password:</label>
				<input class="form-control login_password" id="login_password" name="user_password" type="password">
			  </div>
			  <button type="submit" id="loginSubmit" value="continueLogin" class="btn btn-col btnDirection">LOGIN</button>
			  <a href="javascript:void(0);" class="pwdLink">Forgot your password?</a> </div>
		  </div>
      </form>
      
    </div>
  </div>
  
  <?php } ?>
  
  
</section>

<link href="date-picker/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" href="date-picker/css/foundation-datepicker.min.css">

<!-- DATEPICKER FILES -->
<script src="date-picker/foundation-datepicker.js"></script>
<script src="date-picker/foundation-datepicker.vi.js"></script>

<!-- foundation datepicker end -->
<script src="date-picker/foundation.min.js"></script>

<script>
$(document).ready(function(){
	//$(function(){
		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		jQuery('.dp1').fdatepicker({
			format: 'yyyy-mm-dd',
			disableDblClickSelection: true,
			closeButton: true,
			onRender: function (date) {
				return date.valueOf() < now.valueOf() ? 'disabled' : '';
			}
		});	
	//});
});
</script>

<?php include_once 'footer.php'; ?>
