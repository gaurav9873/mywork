<?php
include_once 'header.php';

$obj =  new ConnectDb();
$cFunc = new CustomFunctions();

$product_lists = $obj->unassign_site();
?>
<script>
$(document).ready(function(){
	
	$(".delete-product").on('click', function(){
		var pid = $(this).data('pids');
		var checkstr =  confirm('are you sure you want to remove this?');
		if(checkstr == true){
			if(pid!=''){
				$.ajax({
					url:'action/product.php',
					type:'post',
					data:{action:'delete_product', pids:pid},
					cache:false,
					beforeSend:function(){
					},
					complete:function(){
					},
					success:function(del_resp){
						window.location.href='';
					}
				});
			}
		}
	});
		
})
</script>
<div class="row">

	<div class="box col-md-6">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2>Unassign Product in Site</h2>
                </div>
                <div class="box-content">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
							<th>Count</th>
                            <th>Product name</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
								<?php 
								$count = 1;
								foreach($product_lists as $post_product){
									$product_id = $post_product['product_id'];
									$product_name = $post_product['product_name'];
									$product_code = $post_product['product_code'];
								?>
							<tr>
							<td><?php echo $count; ?></td>
							<td class="center"><?php echo $product_name; ?></td>
							<td class="center">
								<a href="edit-product.php?pid=<?php echo $cFunc->EncryptClientId($product_id); ?>"><span class="label-success label label-default">Edit</span></a> 
								<a href="javascript:void(0);" class="delete-product" data-pids="<?php echo $product_id; ?>"><span class="label-default label label-danger">Delete</span></a>
							</td>
							</tr>
                        <?php $count++; } ?>
                        
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>

</div>




<?php include_once 'footer.php' ?>
