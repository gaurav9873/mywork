<?php
include_once 'header.php';

$obj = new ConnectDb();
$customObj = new CustomFunctions(); 

//$records = $obj->fetchAllRecord('op2mro9899_galleries', 'id');

$records = $obj->get_record_orderby('op2mro9899_galleries', 'category_order');
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
			url:'action/gallery.php',
			type:'POST',
			data: {action:'galleryOrder',data:data},
			cache:false,
			beforeSend:function(){
			},
			complete:function(){
			},
			success:function(resp){
				if(resp=='saved'){
					window.location.href='';
				}
			}
		});
	});
	
	
	$(".delClass").on('click', function(){
		var id = $(this).data('did');
		if(id!=''){
			$.ajax({
				url:'action/gallery.php',
				type:'POST',
				data:{action:'delete_gallery', gallery_id:id},
				cache:false,
				beforeSend:function(){
				},
				complete:function(){
				},
				success:function(responce){
					window.location.href='';
				}
			});
		}
	});
	
	$(".gal-status").on('click', function(){
		var lid = $(this).data('id');
		var data_status = $(this).data('status');
		if(lid!=''){
			$.ajax({
				url:'action/gallery.php',
				type:'post',
				data:{action:'status', gall_id:lid, gall_status:data_status},
				cache:false,
				beforeSend:function(){},
				complete:function(){},
				success:function(status_responce){
					if(status_responce=='success'){
						window.location.href='';
					}else{
						$(".alert-danger").show();
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
                            <th>Gallery Name</th>
                            <th>Category Name</th>
                            <th>Created Date</th>
                            <th>Created Ip</th>
                            <th>Action</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="sortable">
							<?php
							foreach($records as $value){
								$id = $value['id'];
								$gallery_name = $value['gallery_name'];
								$category_id = $value['category_id'];
								$created_date = $value['created_date'];
								$created_ip = $value['created_ip'];
								$status = $value['cat_status'];
								$label = (($status == 1) ? 'Active' : 'Banned');
								$label_color = (($status == 0) ? 'label-danger' : 'label-default');
								$status_val = (($status == 1) ? '0' : '1');
								echo '<tr class="alternate" data-row-id="'.$id.'">
								<td>'.$gallery_name.'</td>
								<td class="center">'.$obj->getGalleryCtegoryName($category_id).'</td>
								<td class="center">'.$created_date.'</td>
								<td class="center">'.$created_ip.'</td>
								<td class="center">
								<a href="edit-gallery-images.php?gallery_id='.$customObj->EncryptClientId($id).'"><span class="label-success label label-default">Edit</span></a>
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

	<div class="alert alert-danger" style="display:none;">
		<button type="button" class="close" data-dismiss="alert">×</button>
		OOPS!! Something went wrong.
	</div>

<?php include_once 'footer.php'; ?>
