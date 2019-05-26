<?php
include_once 'header.php';

$obj = new ConnectDb();
$customObj = new CustomFunctions(); 
$shop_id = $_SESSION['shop_id'];
//$records = $obj->fetchAllRecord('op2mro9899_gallery_category', 'id');

$records = $obj->fetchByOrder('op2mro9899_gallery_category', 'cat_order');
?>
<script src="js/jquery-ui.js"></script>
<link rel="stylesheet" href="css/normalize.css">
<script>
$(document).ready(function(){
	
	$( "#sortable" ).sortable();
	$(document).on('click','#save-reorder',function(){
		var list = new Array();
		$('#sortable').find('.alternate').each(function(){
			var id=$(this).attr('data-row-id');    
			list.push(id);
		});
		var data=JSON.stringify(list);
		$.ajax({
			url:'action/gallery-category.php',
			type:'POST',
			data: {action:'updateOrder',data:data},
			cache:false,
			beforeSend:function(){
			},
			complete:function(){
			},
			success:function(resp){
				if(resp == 'Order Saved'){
					window.location.href='';
				}
			}
		});
	});
	$(".delClass").on('click', function(){
		var id = $(this).data('did');
		var row_ids = $(this).closest('tr').attr('data-row-id');
		var row_id = $(this).closest('tr');
		
		if(id!=''){
			$.ajax({
				url:'action/gallery-category.php',
				type:'post',
				data:{action:'delete', cat_id:id},
				cache:false,
				beforeSend:function(){
					$(row_id).addClass('changeColour');
				},
				complete:function(){},
				success:function(res){
					if(res){
							var json = $.parseJSON(res);
							$('.alternate').filter("[data-row-id='" + row_ids + "']").fadeOut(1000, function(){
								$(this).remove();
							});
							$("#del-msg").show();
							$('#del-msg').delay(5000).fadeOut('slow');
						}
				}
			});
		}
	});
	
	
	$(".gal-status").on('click', function(){
		var lid = $(this).data('id');
		var data_status = $(this).data('status');
		if(lid!=''){
			$.ajax({
				url:'action/gallery-category.php',
				type:'post',
				data:{action:'gallery_status', gall_id:lid, gall_status:data_status},
				cache:false,
				beforeSend:function(){},
				complete:function(){},
				success:function(status_responce){
					var responce_json = $.parseJSON(status_responce);
					if(responce_json.status == true){
						window.location.href='';
					}else{
						alert(responce_json.msg);
					}
				}
			});
		}		
	});
	
});
</script>

<div class="row">
		<div class="alert alert-success" id="del-msg" style="display:none;">successfully deleted.</div>
        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2>All Gallery Category</h2>
                </div>
                <div class="box-content">
					<button class="btn btn-default" id="save-reorder">Save</button><br /><br />
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>Category Name</th>
                            <th>Created Date</th>
                            <th>Created Ip</th>
                            <th>Action</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody id="sortable">
							
							<?php
								foreach($records as $val){
									$category_name = $val['category_name'];
									$id = $val['id'];
									$created_date = $val['created_date'];
									$created_ip = $val['created_ip'];
									$status = $val['status'];
									$label = (($status == 1) ? 'Active' : 'Banned');
									$label_color = (($status == 0) ? 'label-danger' : 'label-default');
									$status_val = (($status == 1) ? '0' : '1');
									echo '<tr class="alternate" data-row-id="'.$customObj->EncryptClientId($id).'">
									<td>'.$category_name.'</td>
									<td class="center">'.$created_date.'</td>
									<td class="center">'.$created_ip.'</td>
									<td class="center">
									<a href="edit-gallery-category.php?cat_id='.$customObj->EncryptClientId($id).'"><span class="label-success label label-default">Edit</span></a>
									<a href="javascript:void(0);" class="delClass" data-did="'.$customObj->EncryptClientId($id).'"><span class="label-default label label-danger">Delete</span></a>
									</td>
									<td class="center">
										<a href="javascript:void(0);" class="gal-status" data-id="'.$customObj->EncryptClientId($id).'" data-status="'.$status_val.'"><span class="label-success label '.$label_color.'">'.$label.'</span></a>
									</td>
									</tr>';
								}
							?>
                     
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


<?php include_once 'footer.php'; ?>
