$(document).ready(function(){
	var WH = $(window).height();  
    var SH = $('body').prop("scrollHeight");
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
			$(this).focus();
			}else{
				$(this).css('border-color', 'green');
				
				if(this.id == 'user_fname'){
					var fnames = $("#user_fname").val();
					if(validate_name(fnames)==false){
						$(this).css('border-color', 'red');
						errorFlag.push('false');
					}
				}
				
				if(this.id == 'user_lname'){
					var fnames = $("#user_lname").val();
					if(validate_name(fnames)==false){
						$(this).css('border-color', 'red');
						errorFlag.push('false');
					}
				}
				
				if(this.id == 'post_code'){
					var postcodes = $("#post_code").val();
					if(valid_postcode(postcodes)==false){
						$(this).css('border-color', 'red');
						errorFlag.push('false');
					}
				}

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
		var userPrefix = $("#user_prefix").val();
		var userFname = $("#user_fname").val();
		var userLname = $("#user_lname").val();
		var userPcode = $("#post_code").val();
		var isValid = validateFrm();
		if(isValid.indexOf('false')<0){
			$(".mandatory").hide();
			//$(".postaction").val(vals);
			var url = 'action/checkout-login';
			var dataString =  JSON.stringify($('#deliverfrm').serializeObject());

			ajaxCall(url, dataString, function(data){
				if(data.status){
					window.location.href="registration";
				}
			});
		}else{
			$(".mandatory").show();
			if((userPrefix!='') && (userFname=!'') && (userLname!='') && (userPcode!='')){
				$('html, body').stop().animate({scrollTop: SH-WH}, 1000);
			}
			return false;	
		}	
	});
	
	$("#loginSubmit").on('click', function(e){
		e.preventDefault();
		var userPrefix = $("#user_prefix").val();
		var userFname = $("#user_fname").val();
		var userLname = $("#user_lname").val();
		var userPcode = $("#post_code").val();
		var isValidLoginFrm = validateFrm();
		if(isValidLoginFrm.indexOf('false')<0){
			$(".mandatory").hide();
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
			$(".mandatory").show();
			if((userPrefix!='') && (userFname=!'') && (userLname!='') && (userPcode!='')){
				$('html, body').stop().animate({scrollTop: SH-WH}, 1000);
			}
			return false;
		}
		
	});
	
	$("#save_delivery_address").on('click', function(){		
		var validDeliveryAddress = validateFrm();
		if(validDeliveryAddress.indexOf('false')<0){
			var url_path = 'action/checkout-login';
			var data_frm_string = JSON.stringify($('#deliverfrm').serializeObject());
			ajaxCall(url_path, data_frm_string, function(reponce){
					if(reponce.status == 'true'){
						window.location.href="review";
					}
			});
		}
	});
	
	$("#pwdLink").on('click', function(){
		$("#forgetpwd").show();
		$("#loginsec").hide();
	});
	
	$("#lgfrmlink").on('click', function(){
		$("#forgetpwd").hide();
		$("#loginsec").show();
	});
	
	$("#forgetPass").on('click', function(){
		var emailtxt = $(".emailtxt").val();
		if(emailtxt!=''){
			if(isValidEmailAddress(emailtxt) == false){
				alert("Please enter valid email");
				return false;
			}else{
				var form_array = JSON.stringify($('#fpwd').serializeObject());
				$.ajax({
					url:rootUrl+'forget-password',
					type:'post',
					contentType: 'application/json',
					dataType: "json",
					data:form_array,
					cache:false,
					beforeSend:function(){
						$(".waitwheel").show();
					},
					complete:function(){
						$(".waitwheel").hide();
					},
					success:function(responce){
						$('.sumsg').html(responce.msg).show();
						$('#fpwd')[0].reset();
						setTimeout(function(){
							$('.sumsg').fadeOut();
						},3000);
					}
				});
			}
		}
	});
	
	/*$("#searchPin").on('click', function(){
		var postcodes = $("#post_code").val();
		if(valid_postcode(postcodes)==false){
			$("#post_code").css('border-color', 'red');
			return false;
		}else{
			postcodes = postcodes.replace(/ +/g, "");
			var innerCode = postcodes.substring(postcodes.length-3);
			var outer_code3 = postcodes.substring(0,3);
			var outer_code4 = postcodes.substring(0,4);
			if(postcodes!=''){
				$.ajax({
					cache:false,
					url:rootUrl+'check-postcode/'+postcodes,
					type:'get',
					dataType: "text",
					async: false,
					beforeSend:function(){},
					complete:function(){},
					success:function(respcode){
						var response=JSON.parse(respcode);
						var len = response.shipping_cost.length;
						if(len == 0){
							$("#cost").empty();
							$("#location").empty();
						}else{
							var current = 0;
							$.each(response, function() {
								$.each(this, function(k, v) { 
									if(v.inner_post_code==innerCode){
										$("#cost").empty().append('<strong>Delivery Cost</strong>'+v.delivery_charges);
										$("#location").empty().append('<strong>Delivery Location</strong>'+v.location_name);
									}else{
										if(v.inner_post_code=='XXX'){
											$("#cost").empty().append('<strong>Delivery Cost</strong>'+v.delivery_charges);
											$("#location").empty().append('<strong>Delivery Location</strong>'+v.location_name);
										}
									}
								});
							});
						current++; }
					}
				});
			}
	   }
		
	});*/
	
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

function valid_postcode(postcode) {
    //postcode = postcode.replace(/\s/g, "");
    var regex = /^[A-Za-z]{1,2}[\d]{1,2}([A-Za-z])?\s?[\d][A-Za-z]{2,3}$/;
    return regex.test(postcode);
}

function validate_name(obj){
	 var regex = /^[a-zA-Z]+$/;
	 return regex.test(obj);
}

function myTrim(x) {
    return x.replace(/^\s+|\s+$/gm,'');
}
	
});
