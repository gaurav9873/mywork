<?php
include_once 'header.php';

$obj = new Connectdb();
$cFunc = new CustomFunctions();
if(isset($_POST['submit'])){
	
	$location_name = $cFunc->xss_clean($_POST['location_name']);
	$outer_postcode = $cFunc->xss_clean($_POST['outer_postcode']);
	$inner_postcode = $cFunc->xss_clean($_POST['inner_postcode']);
	$delivery_charges = $cFunc->xss_clean($_POST['delivery_charges']);
	//$holiday_charges = $cFunc->xss_clean($_POST['holiday_charges']);
	//$holiday_date = $cFunc->xss_clean($_POST['holiday_date']);
	$shop_id = $_SESSION['shop_id'];
	$created_ip = $cFunc->get_client_ip();
	$date = date("Y-m-d H:i:s");
	
	$arr = array('location_name' => $location_name, 'outer_post_code' => $outer_postcode, 'inner_post_code' => $inner_postcode, 'delivery_charges' => $delivery_charges, 'site_id' => $shop_id, 'created_date' => $date, 'created_ip' => $created_ip);
	$ins_stmt = $obj->insert_records('op2mro9899_shipping', $arr);
	if($ins_stmt){
		header("location:shipping-list.php?msg=successfull");
	}
	
}
?>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
$(document).ready(function(){
	$("#shipping_frm").on('submit', function(evt){
		
		var errFlag = false;
		
		$(".validate").each(function(){
			if($(this).val()==''){
				$(this).css('border-color', 'red');
				errFlag = true;
			}else{
			}
		});
		
		
		$(".validate").on('keypress change', function(){
			if($(this).val() == ''){
				$(this).css('border-color', 'red');
				errFlag = true;
			}
			
			if($(this).val()!=''){
				$(this).css('border-color', '');
			}
		});
		
		if(errFlag){
			evt.preventDefault();
		}
	});
	
	$(document).on('focus',".datepicker", function(){
		$(this).datepicker({dateFormat:'yy-mm-dd'});
	});
	
});
</script>

<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-edit"></i> Shipping Attribute</h2>
            </div>
            <div class="box-content">
                <form name="shipping_frm" id="shipping_frm" class="shipping_frm" method="post" role="form">
                    <div class="form-group">
                        <label for="location">Location Name</label>
                        <input class="form-control validate" id="location_name" name="location_name" placeholder="Enter location name" type="text">
                    </div>
                    <div class="form-group">
                        <label for="postCode">Outer PostCode</label>
                        <input class="form-control validate" id="outer_postcode" name="outer_postcode" placeholder="Enter outer post code" type="text" maxlength="4">
                    </div>
                    
                    <div class="form-group">
                        <label for="postCode">Inner PostCode (last 3 codes,"XXX" for all)</label>
                        <input class="form-control validate" id="inner_postcode" name="inner_postcode" placeholder="Enter inner post code" type="text" maxlength="4">
                    </div>
                    
                     <div class="form-group">
                        <label for="postCode">Delivery Charges </label>
                        <input class="form-control validate" id="delivery_charges " name="delivery_charges" placeholder="Enter delivery charges" type="text">
                    </div>
                    
                    <!--<div class="form-group">
                        <label for="postCode">Holiday Delivery Charges </label>
                        <input class="form-control" id="holiday_charges " name="holiday_charges" placeholder="Enter holiday delivery charges" value="0.00" type="text">
                    </div>-->
                    
                    <!--<div class="form-group">
                        <label for="postCode">Holiday Date </label>
                        <input class="form-control datepicker" id="holiday_date " name="holiday_date" placeholder="Enter holiday delivery charges" value="" type="text">
                    </div>-->
                    
                    <button type="submit" name="submit" class="btn btn-default">Submit</button>
                </form>

            </div>
        </div>
    </div>
    <!--/span-->

</div>


<?php include_once 'footer.php'; ?>
