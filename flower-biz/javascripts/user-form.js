$(document).ready(function () {
	
	/*jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
		phone_number = phone_number.replace(/\s+/g, "");
		return this.optional(element) || phone_number.length > 9 && 
		phone_number.match(/^(\+?1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
	}, "Please specify a valid phone number");*/
	
	
	//var rootURL = "http://54.191.172.136:82/florist-admin/flowers/api/";
    $('#user-frm').validate({
        rules: {
			user_prefix: "required",
			user_fname: "required",
            user_lname: "required",
            user_pcode: {required: true, minlength: 5, maxlength:10},
            address_list: "required",
            user_hnumber: "required",
            primary_address: "required",
            user_city:"required",
            user_country:"required",
            user_phone:{required: true, number: true, minlength:10, maxlength:15},
            user_emailid:{required: true, email: true},
            user_password: { required: true, minlength: 6, maxlength: 10,} , 
			cnfpassword: { equalTo: "#user_password", minlength: 6, maxlength: 10},
			
        },
        
        messages: {
			user_prefix: "please select prefix",
            user_fname: "Please enter your first name",
            user_lname: "Please enter your last name",
            user_pcode: "Please enter your post code",
            address_list: "Please select your address",
            user_hnumber: "Please enter your house number",
            primary_address: "Please enter your address",
            user_city: "Please enter your city",
            user_country: "Please enter your country",
            user_phone: "Please enter your valid phone number",
            user_emailid: "Please enter your valid email id",
            user_password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 6 characters long"
            },
            cnfpassword:"confirm password not matched",
        },
        
         submitHandler: function (form, event) {
             event.preventDefault();
              //form.submit();
			 //$(".wait").show();
			 //$('#user-frm').submit();
			 
            //alert('valid form submitted');
			var data =  JSON.stringify($('#user-frm').serializeObject());
				$.ajax({
				type:'POST',
				contentType: 'application/json',
				url:'action/register',
				dataType: "json",
				data: data,
                beforeSend:function(xhr){
                     $(".loader1").show();
                },
                complete:function(){
                  $(".loader1").hide();
                },
				success: function(responce){

				    if(responce.email_status == 'true'){
				        $(".error-message").empty().html(responce.message).show();
                    }
                    if(responce.login_status == 'true'){
                        $(".error-message").empty().html(responce.message).show();
                        window.location.href = "checkout-delivery";
                    }
                    if(responce.login_status == 'false'){
                        window.location.href = "checkout-delivery";
                    }
                    $('html, body').animate({
                        scrollTop: $("#ermsg").offset()
                    }, 1000);
                    setTimeout('$("#ermsg").hide()',7000);
                }
			});
        }
    });
    
    
    $("#changepass").validate({
		 rules: {
			email_id: "required",
            user_password: { required: true, minlength: 6, maxlength: 10,} , 
			confirm_pass: { equalTo: "#password", minlength: 6, maxlength: 10},
        },
        
         messages: {
			email_id: "Please enter your valid email id",
            user_password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 6 characters long"
            },
            confirm_pass:"confirm password not matched",
        },
         submitHandler: function (form) {
			  form.submit();
		 }
        
	});
    

});
