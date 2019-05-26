<?php
include_once 'header.php';

$obj = new ConnectDb();
$customObj = new CustomFunctions();

$coupon_id = $customObj->DecryptClientId($_GET['coupon_id']);

$cat = $obj->fetchCategoryTree();

$rows = $obj->get_row_by_id('op2mro9899_coupons', 'id', intval($coupon_id));
$id = $rows[0]['id'];
$cat_id = $rows[0]['cat_id'];
$discount_offer = $rows[0]['discount_offer'];
$min_order = $rows[0]['min_order'];
$product_code = $rows[0]['product_code'];
$valid_from = $rows[0]['valid_from'];
$valid_upto = $rows[0]['valid_upto'];
$total_coupons = $rows[0]['total_coupons'];
$coupon_code = $rows[0]['coupon_code'];

$shop_id = $_SESSION['shop_id'];

if(isset($_POST['submit'])){
	
	$coupon_category = intval($_POST['coupon_category']); 
	$dis_offer = $_POST['discount_offer']; 
	$minOrder = $_POST['min_order']; 
	$pCode = $_POST['product_code'];
	$valid_start = $_POST['valid_from'];
	$valid_end = $_POST['valid_upto']; 
	$totalCoupons = $_POST['total_coupons'];
	
	$update_arr = array('cat_id' => $coupon_category, 'discount_offer' => $dis_offer, 'min_order' => $minOrder, 'product_code' => $pCode, 
						'valid_from' => $valid_start, 'valid_upto' => $valid_end, 'total_coupons' => $totalCoupons, 'site_id' => $shop_id);
	$update_stmt = $obj->update_row('op2mro9899_coupons', $update_arr, "WHERE id = '$coupon_id'");
	if($update_stmt){
		header("location:edit-coupons.php?coupon_id=".$_REQUEST['coupon_id']."&msg=success");
	}
}
?>
<link rel="stylesheet" type='text/css' href="datepicker/default.css">
<script type='text/javascript' src="datepicker/zebra_datepicker.js"></script>

<script>
$(document).ready(function(){
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
                        <select name="coupon_category" class="coupon_category form-control required" id="coupon_category">
							<option value="0">Select category</option>
								<?php
								foreach($cat as $cat_view){
									$cat_ids = $cat_view['id'];
									$category_name = $cat_view['name'];
									$selected = (($cat_ids == $cat_id) ? 'selected' : '');
									echo '<option '.$selected.' value="'.$cat_ids.'">'.$category_name.'</option>';
								}
								?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="discount">Discount</label>
                        <input type="text" class="form-control required" name="discount_offer" id="discount_code" value="<?php echo $discount_offer; ?>" placeholder="Discount">
                    </div>
                    
                    <div class="form-group">
                        <label for="discount">Min. Order</label>
                        <input type="text" class="form-control required" name="min_order" id="min_order" value="<?php echo $min_order; ?>" placeholder="Min. Order">
                    </div>
                    
                    <div class="form-group">
                        <label for="discount">Product Code</label>
                        <input type="text" class="form-control" name="product_code" id="product_code" value="<?php echo $product_code; ?>" placeholder="Product Code">
                    </div>
                    
                    <div class="form-group">
                        <label for="discount">Valid From (NA)</label>
                        <input type="text" class="form-control required" name="valid_from" id="valid_from" value="<?php echo $valid_from; ?>" placeholder="Valid From (NA)">
                    </div>
                    
                    <div class="form-group">
                        <label for="discount">Valid Upto</label>
                        <input type="text" class="form-control required" name="valid_upto" id="valid_upto" value="<?php echo $valid_upto; ?>" placeholder="Valid Upto">
                    </div>
                    
                    <div class="form-group">
                        <label for="discount">Total Coupons</label>
                        <input type="text" class="form-control required" name="total_coupons" id="total_coupons" value="<?php echo $total_coupons; ?>" placeholder="Total Coupons">
                    </div>
                    

                    <button type="submit" name="submit" value="submit" class="btn btn-default">Submit</button>
                </form>

            </div>
        </div>
    </div>
    <!--/span-->

</div><!--/row-->


<?php include_once 'footer.php'; ?>
