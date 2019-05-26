$(document).ready(function () {
    var WH = $(window).height();
    var SH = $('body').prop("scrollHeight");

    function ajaxCall(url, parameters, req) {
        $.ajax({
            type: 'POST',
            contentType: 'application/json',
            url: url,
            dataType: "json",
            data: parameters,
            beforeSend: function () {
                $(".loader1").show();
            },
            complete: function () {
                $(".loader1").hide();
            },
            success: req,
        });
    }

    function customRequest(u, d, callback) {
        $.ajax({
            type: "post",
            url: u,
            data: d,
            success: function (data) {
                console.log(data);
                if (typeof callback == 'function') {
                    callback(data);
                }
            }
        });
    }

    function validateFrm() {
        var errorFlag = [];
        errorFlag.push('true');
        var valid = true;
        var msg = "";
        $(".validate").each(function () {
            if ($(this).val() == '') {
                $(this).css("border", "2px solid red");
                errorFlag.push('false');
                $(this).focus();
                msg += $(this).attr('data-title') + " !\n\n";
                valid = false;
            } else {
                $(this).css('border-color', 'green');

                if (this.id == 'user_fname') {
                    var fnames = $("#user_fname").val();
                    if (validate_name(fnames) == false) {
                        $(this).css('border-color', 'red');
                        errorFlag.push('false');
                    }
                }

                if (this.id == 'user_lname') {
                    var fnames = $("#user_lname").val();
                    if (validate_name(fnames) == false) {
                        $(this).css('border-color', 'red');
                        errorFlag.push('false');
                    }
                }

                if (this.id == 'post_code') {
                    var postcodes = $("#post_code").val();
                    if (valid_postcode(postcodes) == false) {
                        $(this).css('border-color', 'red');
                        errorFlag.push('false');
                    }
                }

                if (this.id == 'delivery_email') {
                    var chkMail = $("#delivery_email").val();
                    if (isValidEmailAddress(chkMail) == false) {
                        $(this).css('border-color', 'red');
                        errorFlag.push('false');
                    }
                }
                if (this.id == 'user_telephone') {
                    var chktel = $("#user_telephone").val();
                    if (ValidateTelephone(chktel) == false) {
                        $(this).css('border-color', 'red');
                        errorFlag.push('false');
                    }
                }

                if (this.id == 'user_mobile') {
                    var cbhmob = $("#user_mobile").val();
                    if (validateMobile(cbhmob) == false) {
                        $(this).css('border-color', 'red');
                        errorFlag.push('false');
                    }
                }
            }
        });
        if (!valid) alert(msg);
        return errorFlag;

    }


    $("#save_delivery_address").on('click', function () {
        var validDeliveryAddress = validateFrm();
        if (validDeliveryAddress.indexOf('false') < 0) {
            var url_path = 'action/checkout-login';
            var data_frm_string = JSON.stringify($('#deliverfrm').serializeObject());
            ajaxCall(url_path, data_frm_string, function (responce) {
                if (responce.status == 'false') {
                    $(".user_pcode").css('border-color', '').css('border-color', 'red');
                    $(".delcost").show();
                    $(".sdelivery-cost").empty().append(responce.msg);
                } else if (responce.status == 'true') {
                    window.location.href = "review";
                } else {
                }
            });
        }
    });

    $("#pwdLink").on('click', function () {
        $("#forgetpwd").show();
        $("#loginsec").hide();
    });

    $("#lgfrmlink").on('click', function () {
        $("#forgetpwd").hide();
        $("#loginsec").show();
    });

    $("#searchPin").on('click', function () {
        var pcode = $("#post_code").val();
        $(".user_pcode").val(pcode);
    });

    $("#searchPin").on('click', function () {
        var post_codes = $("#post_code").val();
        if (post_codes) {
            customRequest('action/shipping-charge.php', {
                action: 'qtspcode',
                post_codes: post_codes
            }, function (respcode) {
                var response = JSON.parse(respcode);
                if (response.status == 'true') {
                    var delivery_cost = response.delivery_charges;
                    $(".delivery-cost").empty();
                    cp_obj.doLookup();
                    $("#crafty_postcode_result_display").show();
                } else if (response.status == 'false') {
                    var msg = response.msg;
                    $(".delivery-cost").empty().append(msg);
                    $("#crafty_postcode_result_display").hide();
                } else {
                }
            });
        }
    });


    function isValidEmailAddress(emailAddress) {
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        return pattern.test(emailAddress);
    }

    function ValidateTelephone(telephone) {
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

    function validate_name(obj) {
        var regex = /^[a-zA-Z]+$/;
        return regex.test(obj);
    }

    function myTrim(x) {
        return x.replace(/^\s+|\s+$/gm, '');
    }

});
