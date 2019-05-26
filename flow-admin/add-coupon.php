<?php
include_once 'header.php';

include_once 'includes/class.coupon.php';

$obj = new ConnectDb();
$cFunc = new CustomFunctions();

$cat = $obj->fetchCategoryTree();

$shop_id = $_SESSION['shop_id'];

if(isset($_POST['submit'])){
	
	$no_of_coupons = 1;
	$length = 6;
	$prefix = 'FLO';
	$suffix = '-suffix';
	$numbers = 'true';
	$letters = 'true';
	$symbols = 'false';
	$random_register = 'true';
	$mask = 'XXX-XXX';
	$coupons = coupon::generate_coupons($no_of_coupons, $length, $prefix, $suffix, $numbers, $letters, $symbols, $random_register, $mask);

	
	$coupon_category = intval($_POST['coupon_category']);
	$discount_offer = $cFunc->xss_clean($_POST['discount_offer']);
	$min_order = $cFunc->xss_clean($_POST['min_order']);
	$product_code = $_POST['product_code'];
	$valid_from = $_POST['valid_from'];
	$valid_upto = $_POST['valid_upto'];
	$total_coupons = $cFunc->xss_clean($_POST['total_coupons']);
	$generate_coupons = $coupons[0];
	$created_ip = $cFunc->get_client_ip();
	$date = date("Y-m-d H:i:s");
	
	
	$insert_array = array('cat_id' => $coupon_category, 'discount_offer' => $discount_offer, 'min_order' => $min_order, 'product_code' => $product_code, 
						  'valid_from' => $valid_from, 'valid_upto' => $valid_upto, 'total_coupons' => $total_coupons, 'coupon_code' => $generate_coupons,
						   'site_id' => $shop_id, 'created_date' => $date, 'created_ip' => $created_ip);
	$insert_stmt = $obj->insert_records('op2mro9899_coupons', $insert_array);
	
	if($insert_stmt){
		header("location:all-coupons.php?msg=success");
	}
	
}
?>

<link rel="stylesheet" type='text/css' href="datepicker/default.css">
<script type='text/javascript' src="datepicker/zebra_datepicker.js"></script>
<script>
$(document).ready(function(){
	$("#coupon_frm").on('submit', function(evt){
		
		var errFlag = false;
		
		$(".required").each(function(){
			if($(this).val()==''){
				$(this).css('border-color', 'red');
				errFlag = true;
			}else{
			}
		});
		
		$(".required").on('keypress change', function(){
			if($(this).val()==''){
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
	
	
	$('#valid_from').Zebra_DatePicker({
		direction: true,
		pair: $('#valid_upto')
	});

	$('#valid_upto').Zebra_DatePicker({
		direction: 1
	});
	
});
</script>


<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-edit"></i> Add Coupons</h2></div>
            <div class="box-content">
                <form name="coupon_frm" id="coupon_frm" class="coupon_frm" action="" method="post" role="form">
                    <div class="form-group">
                        <label for="categorySelector">Select Category</label>
                        <select name="coupon_category" class="coupon_category form-control" id="coupon_category">
							<option value="0">Select category</option>
								<?php
								foreach($cat as $cat_view){
									$cat_id = $cat_view['id'];
									$category_name = $cat_view['name'];
									echo '<option value="'.$cat_id.'">'.$category_name.'</option>';
								}
								?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="discount">Discount</label>
                        <input type="text" class="form-control required" name="discount_offer" id="discount_code" placeholder="Discount">
                    </div>
                    
                    <div class="form-group">
                        <label for="discount">Min. Order</label>
                        <input type="text" class="form-control required" name="min_order" id="min_order" placeholder="Min. Order">
                    </div>
                    
                    <div class="form-group">
                        <label for="discount">Product Code</label>
                        <input type="text" class="form-control" name="product_code" id="product_code" placeholder="Product Code">
                    </div>
                    
                    <div class="form-group">
                        <label for="discount">Valid From (NA)</label>
                        <input type="text" class="form-control required" name="valid_from" id="valid_from" placeholder="Valid From (NA)">
                    </div>
                    
                    <div class="form-group">
                        <label for="discount">Valid Upto</label>
                        <input type="text" class="form-control required" name="valid_upto" id="valid_upto" placeholder="Valid Upto">
                    </div>
                    
                    <div class="form-group">
                        <label for="discount">Total Coupons</label>
                        <input type="text" class="form-control required" name="total_coupons" id="total_coupons" placeholder="Total Coupons">
                    </div>
                    

                    <button type="submit" name="submit" value="submit" class="btn btn-default">Submit</button>
                </form>

            </div>
        </div>
    </div>
    <!--/span-->

</div><!--/row-->

<?php include_once 'footer.php'; ?>
