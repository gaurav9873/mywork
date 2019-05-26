<?php
include_once 'header.php';

$obj = new Connectdb();
$cFunc = new CustomFunctions();

$shipping_id = $cFunc->DecryptClientId($_REQUEST['ship_id']);

$shipping_data = $obj->get_row_by_id('op2mro9899_shipping', 'id', intval($shipping_id));

$id = $shipping_data[0]['id'];
$location_name = $shipping_data[0]['location_name'];
$outer_post_code = $shipping_data[0]['outer_post_code'];
$inner_post_code = $shipping_data[0]['inner_post_code'];
$delivery_charges = $shipping_data[0]['delivery_charges'];
//$holiday_charges = $shipping_data[0]['holiday_charges'];
//$holiday_date = $shipping_data[0]['holiday_date'];

if(isset($_POST['submit'])){
	
	$locationName = $cFunc->xss_clean($_POST['location_name']);
	$outerPostcode = $cFunc->xss_clean($_POST['outer_postcode']);
	$innerPostcode = $cFunc->xss_clean($_POST['inner_postcode']);
	$deliveryCharges = $cFunc->xss_clean($_POST['delivery_charges']);
	//$holidayCharges = $cFunc->xss_clean($_POST['holiday_charges']);
	//$holidayDate = $cFunc->xss_clean($_POST['holiday_date']);
	$shop_id = $_SESSION['shop_id'];
	
	$row_arr = array('location_name' => $locationName, 'outer_post_code' => $outerPostcode, 'inner_post_code' => $innerPostcode, 'delivery_charges' => $deliveryCharges, 
					 'site_id' => $shop_id);
	
	//print_r($row_arr); die;				 
	
	$update_stmt = $obj->update_row('op2mro9899_shipping', $row_arr,"WHERE id = '$shipping_id'");
	if($update_stmt){
		header("location:edit-shipping.php?ship_id=".$_REQUEST['ship_id']."&msg=success");
	}
	
	
}
?>


<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
	$(document).ready(function(){
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
                        <input class="form-control validate" id="location_name" name="location_name" placeholder="Enter location name" value="<?php echo $location_name; ?>" type="text">
                    </div>
                    <div class="form-group">
                        <label for="postCode">Outer PostCode</label>
                        <input class="form-control validate" id="outer_postcode" name="outer_postcode" placeholder="Enter outer post code" type="text" value="<?php echo $outer_post_code; ?>" maxlength="4">
                    </div>
                    
                    <div class="form-group">
                        <label for="postCode">Inner PostCode (last 3 codes,"XXX" for all)</label>
                        <input class="form-control validate" id="inner_postcode" name="inner_postcode" placeholder="Enter inner post code" type="text" value="<?php echo $inner_post_code; ?>" maxlength="3">
                    </div>
                    
                     <div class="form-group">
                        <label for="postCode">Delivery Charges </label>
                        <input class="form-control validate" id="delivery_charges " name="delivery_charges" placeholder="Enter delivery charges" value="<?php echo $delivery_charges; ?>" type="text">
                    </div>
                    
                     <!--<div class="form-group">
                        <label for="postCode">Holiday Delivery Charges </label>
                        <input class="form-control" id="holiday_charges " name="holiday_charges" placeholder="Enter holiday delivery charges" value="<?php echo $holiday_charges; ?>" type="text">
                    </div>-->
                    
                    <!--<div class="form-group">
                        <label for="postCode">Holiday Date </label>
                        <input class="form-control datepicker" id="holiday_date " name="holiday_date" placeholder="Enter holiday delivery charges" value="<?php echo $holiday_date; ?>" type="text" readonly>
                    </div>-->
                    
                    <button type="submit" name="submit" class="btn btn-default">Submit</button>
                </form>

            </div>
        </div>
    </div>
    <!--/span-->

</div>


<?php include_once 'footer.php'; ?>
