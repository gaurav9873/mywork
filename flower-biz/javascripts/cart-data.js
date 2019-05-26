$(document).ready(function () {

    $("#coupons").on('submit', function (evt) {
        var c_code = $(".c_code").val();
        if (c_code == '') {
            $(".c_code").css({"border-color": "#ff6347", "border-width": "2px", "border-style": "solid"});
            return false;
        }
    });

    $("#paymentfrm").on("submit", function (e) {
        e.preventDefault();
        var isChecked = $("input[name=paypal]:checked").length;
        var chkVal = $("input[name=paypal]:checked").val();
        if (isChecked > 0) {
            //$("#paymentfrm").submit();
            var cartData = {"payment_type": chkVal};
            $.ajax({
                type: 'POST',
                contentType: 'application/json',
                url: 'action/cart-data',
                dataType: "json",
                data: JSON.stringify(cartData),
                beforeSend: function () {
                    $(".loader1").show();
                },
                complete: function () {
                    $(".loader1").hide();
                },
                success: function (resp) {
                    if (resp.payment_type == 'paypal') {
                        window.location = "paypal-payment?orderid=" + resp.oid;
                    }
                    if (resp.payment_type == 'shp') {
                        window.location = "pay-shp?orderid=" + resp.oid;
                    }
                }
            });

        } else {
            //e.preventDefault();
            alert("Please check payement type");
            return false;
        }
    });


});