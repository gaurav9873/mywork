<?php
include_once 'header.php';

$obj =  new ConnectDb();
$cFunc = new CustomFunctions();

$data = $obj->duplicate_product_list();

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
                    <h2>Bordered Table</h2>

                    <div class="box-icon">
                        <a href="#" class="btn btn-setting btn-round btn-default"><i class="glyphicon glyphicon-cog"></i></a>
                        <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                        <a href="#" class="btn btn-close btn-round btn-default"><i class="glyphicon glyphicon-remove"></i></a>
                    </div>
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
								foreach($data as $post_data){
									$pid = $post_data['pid'];
									$product_name = $post_data['product_name'];
							?>
							<tr>
							<td><?php echo $count; ?></td>
							<td class="center"><?php echo $product_name; ?></td>
							<td class="center">
								<a href="edit-product.php?pid=<?php echo $cFunc->EncryptClientId($pid); ?>"><span class="label-success label label-default">Edit</span></a> 
								<a href="javascript:void(0);" class="delete-product" data-pids="<?php echo $pid; ?>"><span class="label-default label label-danger">Delete</span></a>
							</td>
							</tr>
                        
                        <?php $count++; } ?>
                        </tbody>
                    </table>
                    <ul class="pagination pagination-centered">
                        <li><a href="#">Prev</a></li>
                        <li class="active">
                            <a href="#">1</a>
                        </li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">Next</a></li>
                    </ul>
                </div>
            </div>
        </div>

</div>


<?php include_once 'footer.php'; ?>
