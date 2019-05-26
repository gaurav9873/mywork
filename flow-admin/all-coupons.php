<?php
include_once 'header.php';

$obj = new ConnectDb();
$customObj = new CustomFunctions(); 

$shop_id = $_SESSION['shop_id'];

$records = $obj->fetchAllRecord('op2mro9899_coupons', 'id', $shop_id);

?>

<script>
$(document).ready(function(){
	$(".delClass").on('click', function(){
		var id = $(this).attr('data-did');
		var row_ids = $(this).closest('tr').attr('data-row-id');
		var row_id = $(this).closest('tr');
		var checkstr =  confirm('are you sure you want to remove this?');
		if(checkstr == true){
			if(id!=''){
				$.ajax({
					url:'action/coupons.php',
					type:'post',
					data:{action:'delete',did:id},
					beforeSend:function(){
						$(row_id).addClass('changeColour');
					},
					complete:function(){},
					success:function(resp){
						var json = $.parseJSON(resp);
						//console.log(json.msg);
						$('.alternate').filter("[data-row-id='" + row_ids + "']").fadeOut(1000, function(){ 
							$(this).remove();
						});
						$("#del-msg").show();			
						$('#del-msg').delay(5000).slideUp("slow", function() { $('#del-msg').hide();});
					}
				});
			}
		}

	});
});
</script>

<div class="row">
	<div class="alert alert-success" id="del-msg" style="display:none;">successfully deleted.</div>
        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2>All Coupons</h2>
                </div>
                <div class="box-content">
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>Category Name</th>
                            <th>Discount Offer</th>
                            <th>Min Order</th>
                            <th>Product Code</th>
                            <th>Valid From</th>
                            <th>Valid Upto</th>
                            <th>Total Coupons</th>
                            <th>Coupon Code</th>
                            <th>Created Date</th>
                            <th>Created IP</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
							
							<?php
								foreach($records as $key => $post_val){
									
									$id = $post_val['id'];
									$cat_id = $obj->getCategoryName($post_val['cat_id']);
									$cat_names = (($post_val['cat_id'] == 0) ? 'All' : $cat_id);
									$discount_offer = $post_val['discount_offer'];
									$min_order = $post_val['min_order'];
									$product_code = $post_val['product_code'];
									$valid_from = $post_val['valid_from'];
									$valid_upto = $post_val['valid_upto'];
									$total_coupons = $post_val['total_coupons'];
									$coupon_code = $post_val['coupon_code'];
									$created_date = $post_val['created_date'];
									$created_ip = $post_val['created_ip'];
									
									echo '<tr class="alternate" data-row-id="'.$customObj->EncryptClientId($id).'">
									<td>'.$cat_names.'</td>
									<td class="center">'.$discount_offer.'</td>
									<td class="center">'.$min_order.'</td>
									<td class="center">'.$product_code.'</td>
									<td class="center">'.$valid_from.'</td>
									<td class="center">'.$valid_upto.'</td>
									<td class="center">'.$total_coupons.'</td>
									<td class="center">'.$coupon_code.'</td>
									<td class="center">'.$created_date.'</td>
									<td class="center">'.$created_ip.'</td>
									<td class="center">
									<a href="edit-coupons.php?coupon_id='.$customObj->EncryptClientId($id).'"><span class="label-success label label-default">Edit</span></a>
									<a href="javascript:void(0);" class="delClass" data-did="'.$customObj->EncryptClientId($id).'"><span class="label-default label label-danger">Delete</span></a>
									</td>
									</tr>';
								}
							?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div><!--/span-->


<?php include_once 'footer.php'; ?>
