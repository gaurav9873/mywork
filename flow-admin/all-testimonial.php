<?php
include_once 'header.php';

$obj = new Connectdb();
$cFunc = new CustomFunctions();

$lists = $obj->fetchAllRecord('op2mro9899_testimonial', 'id');

?>

<script>
$(document).ready(function(){
	$(".status").on('click', function(){
		var page_id = $(this).attr('data-id');
		var chk_status = $(this).attr('data-status');
		var loader = $(this).siblings('.ajloader');
		if(page_id!=''){
			$.ajax({
				url:'action/testimonial.php',
				type:'POST',
				data:{
					action:'status',
					pid:page_id,
					status:chk_status
				},
				beforeSend:function(){
					$(loader).show();
				},
				complete:function(){
					$(loader).hide();
				},
				success:function(resp){
					window.location.href='';
				}
			});
		}
	});
	
	
	$(".delete_row").on('click', function(){
		var del_id = $(this).attr('data-pid');
		var alert_msg = "Are you sure you want to delete this row?";
		var chk = confirm(alert_msg);
		var shloader = $(this).siblings(".shloader");
		var row_id = $(this).closest('tr');
		var row_ids = $(this).closest('tr').attr('data-row-id');
		if(chk == true){
			$.ajax({
				url:'action/testimonial.php',
				type:'POST',
				data:{
					action:'delete',
					delID:del_id
				},
				beforeSend:function(){
					$(row_id).addClass('changeColour');
				},
				complete:function(){
					$(shloader).hide();
				},
				success:function(responce){
					if(responce){
						var json = $.parseJSON(responce);
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
	
	
});
</script>



<div class="row">
	<div class="alert alert-success" id="del-msg" style="display:none;">successfully deleted.</div>
    <div class="box col-md-12">
    <div class="box-inner">
    <div class="box-header well" data-original-title="">
        <h2><i class="glyphicon glyphicon-user"></i> All Testimonial</h2>
    </div>
    <div class="box-content">
    <table class="table table-striped table-bordered bootstrap-datatable datatable responsive">
    <thead>
    <tr>
        <th>Title</th>
        <th>Date registered</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
		

	  <?php 
		foreach($lists as $postvals){
			$id = $postvals['id'];
            $user_title = $postvals['user_title'];
            $user_content = $postvals['user_content'];
            $created_date = $postvals['created_date'];
            $created_ip = $postvals['created_ip'];
            $created_dates = date_create($created_date);
            $status = $postvals['status'];
            $chk_status = (($status == 1) ? 'Active' : 'Deactive');
            $data_status = (($status == 1) ? '0' : '1');
            $status_color = (($status == 1) ? 'label-success label label-default' : 'label-default label label-danger');
			// label label-default
	  ?>

			<tr class="alternate" data-row-id=<?php echo $id; ?>">
			<td><?php echo $user_title; ?></td>
			<td class="center"><?php echo date_format($created_dates,"Y/m/d"); ?></td>
			<td class="center">
			<a href="javascript:void(0);" class="status" data-id="<?php echo $id; ?>" data-status="<?php echo $data_status; ?>"><span class="<?php echo $status_color; ?>"><?php echo $chk_status; ?></span></a>
			<img class="ajloader" style="display:none;" src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif">
			</td>
			<td class="center">
			<a class="btn btn-info" href="edit-testimonial.php?id=<?php echo $cFunc->EncryptClientId($id); ?>">
			<i class="glyphicon glyphicon-edit icon-white"></i>
			Edit
			</a>
			<a class="btn btn-danger delete_row" data-pid="<?php echo $id; ?>" href="javascript:void(0);">
			<i class="glyphicon glyphicon-trash icon-white"></i>
			Delete
			</a>
			<img class="shloader" style="display:none;" src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif">
			</td>
			
	<?php } ?>
   
    <!--<span class="label-default label">Inactive</span>-->
    
   
    </tbody>
    </table>
    </div>
    </div>
    </div>
    <!--/span-->

    </div><!--/row-->

<?php include_once 'footer.php'; ?>
