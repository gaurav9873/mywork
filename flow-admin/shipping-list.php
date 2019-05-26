<?php
include_once 'header.php';

$obj = new Connectdb();
$cFunc = new CustomFunctions();
$shop_id = $_SESSION['shop_id'];
$rows = $obj->fetchAllRecord('op2mro9899_shipping', 'id', $shop_id); 

?>

<script>
$(document).ready(function(){
	$(".delClass").on('click', function(){
		var row_ids = $(this).closest('tr').attr('data-row-id');
		var row_id = $(this).closest('tr');
		var delID = $(this).attr('data-did');
		var checkstr =  confirm('are you sure you want to remove this?');
		if(checkstr == true){
			if(delID!=''){
				$.ajax({
					url:'action/shipping',
					type:'POST',
					data:{action:'delete_shipping', ship_id:delID},
					beforeSend:function(){
						$(row_id).addClass('changeColour');
					},
					complete:function(){
					},
					success:function(resp){
						if(resp){
							var json = $.parseJSON(resp);
							$('.alternate').filter("[data-row-id='" + row_ids + "']").fadeOut(1000, function(){ 
								$(this).remove();
							});
							$("#del-msg").show();
							$('#del-msg').delay(5000).fadeOut('slow');
						}
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
                    <h2>All Shipping</h2>
                </div>
                <div class="box-content">
					
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>Location Name</th>
                            <th>Outer Post Code</th>
                            <th>Inner Post Code</th>
                            <th>Delivery Charges</th>
                            <th>Created IP</th>
                            <th>Created Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="sortable">
							
						<?php 
							foreach($rows as $val){
								$id = $val['id'];
								$location_name = $val['location_name'];
								$outer_post_code = $val['outer_post_code'];
								$inner_post_code = $val['inner_post_code'];
								$delivery_charges = $val['delivery_charges'];
								//$holiday_charges = $val['holiday_charges'];
								$created_date = $val['created_date'];
								$created_ip = $val['created_ip'];
						?>
                        <tr class="alternate" data-row-id="<?php echo $cFunc->EncryptClientId($id); ?>">
                            <td><?php echo $location_name; ?></td>
                            <td class="center"><?php echo $outer_post_code; ?></td>
                            <td class="center"><?php echo $inner_post_code; ?></td>
                            <td class="center"><?php echo $delivery_charges; ?></td>
                            <td class="center"><?php echo $created_date; ?></td>
                            <td class="center"><?php echo $created_ip; ?></td>
                            <td class="center">
								<a href="edit-shipping.php?ship_id=<?php echo $cFunc->EncryptClientId($id); ?>"><span class="label-success label label-default">Edit</span></a>
								<a href="javascript:void(0);" class="delClass" data-did="<?php echo $cFunc->EncryptClientId($id); ?>"><span class="label-default label label-danger">Delete</span></a>
							</td>
                        </tr>
                        <?php } ?>
                       
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div><!--/span-->

<?php include_once 'footer.php'; ?>
