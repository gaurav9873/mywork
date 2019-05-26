$(document).ready(function () {

    /*jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
        phone_number = phone_number.replace(/\s+/g, "");
        return this.optional(element) || phone_number.length > 9 &&
        phone_number.match(/^(\+?1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
    }, "Please specify a valid phone number");*/


    //var rootURL = "http://54.191.172.136:82/florist-admin/flowers/api/";
    $('#billing-frm').validate({
        rules: {
            user_prefix: "required",
            user_fname: "required",
            user_lname: "required",
            post_code: "required",
            user_pcode: {required: true, minlength: 5, maxlength:10},
            address_list: "required",
            user_hnumber: "required",
            primary_address: "required",
            user_city:"required",
            user_country:"required",
            user_phone:{required: true, number: true, minlength:10, maxlength:12},
            user_emailid:{required: true, email: true},
            user_password: { required: true, minlength: 6, maxlength: 10,} ,
            cnfpassword: { equalTo: "#user_password", minlength: 6, maxlength: 10},

        },

        messages: {
            user_prefix: "please select prefix",
            user_fname: "Please enter your first name",
            user_lname: "Please enter your last name",
            post_code: "Please enter your post code",
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

        submitHandler: function (form, evt) {
            evt.stopImmediatePropagation();
            evt.preventDefault();
            var formData =  JSON.stringify($('#billing-frm').serializeObject());
            $.ajax({
                type:'POST',
                contentType: 'application/json',
                url:'action/billing-address',
                dataType: "json",
                data:formData,
                beforeSend:function(){
                    $(".up-wait").show();
                },
                complete:function(){
                    $(".up-wait").hide();
                },
                success: function(resp){
                   if(resp.cart_val == 'null'){
                        $(".smsg").empty().html(resp.message).show();
                        window.location.href='';
                   }
                   if(resp.cart_val == 'true'){
                       window.location.href = "review";
                   }
                    setTimeout('$(".smsg").hide()',7000);
                }
            });

        }
    });

    //Update Password
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
        submitHandler: function (form, event) {
            event.stopImmediatePropagation();
            event.preventDefault();
            var formDataVal =  JSON.stringify($('#changepass').serializeObject());
            $.ajax({
                type:'POST',
                contentType: 'application/json',
                url:'action/billing-address',
                dataType: "json",
                data:formDataVal,
                beforeSend:function(){
                    $(".ch-wait").show();
                },
                complete:function(){
                    $(".ch-wait").hide();
                },
                success: function(responce){
                    if(responce.status == 'true'){
                        $(".smsg").empty().html(responce.message).show();
                    }
                    if(responce.status == 'false'){
                        $(".smsg").empty().html(responce.message).show();
                    }
                    setTimeout('$(".smsg").hide()',7000);
                }
            });

        }

    });


    $("#billing_address").on('change', function(){
        var val = $(this).val();
        if(val!=''){
            window.location.href="my-account?id="+val+"";
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

    setTimeout('$(".smsg").hide()',3000);

    $("#searchPin").on('click', function(){
        var pcode = $("#post_code").val();
        $(".user_pcode").val(pcode);
    });


});
