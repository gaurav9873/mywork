<?php
include_once 'header.php';

$shop_id = $_SESSION['shop_id'];
$obj = new Connectdb();
$cFunc = new CustomFunctions();

//$page_lists = $obj->fetchAllRecord('op2mro9899_pages', 'id');
$page_lists = $obj->get_page_by_domian_id($shop_id);
?>

<script>
$(document).ready(function(){
	$(".status").on('click', function(){
		var page_id = $(this).attr('data-id');
		var chk_status = $(this).attr('data-status');
		var loader = $(this).siblings('.ajloader');
		if(page_id!=''){
			$.ajax({
				url:'action/page.php',
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
	
	//Active On Home Page
	$(".chk-status").on('click', function(){
		var pageid = $(this).attr('data-pid');
		var chkstatus = $(this).attr('data-pstatus');
		var loaders = $(this).siblings('.ajloaders');
		
		if(pageid!=''){
			$.ajax({
				url:'action/page.php',
				type:'POST',
				data:{
					action:'home_status',
					pids:pageid,
					statuss:chkstatus
				},
				beforeSend:function(){
					$(loaders).show();
				},
				complete:function(){
					$(loaders).hide();
				},
				success:function(resdata){
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
				url:'action/page.php',
				type:'POST',
				data:{
					action:'delete',
					delID:del_id
				},
				beforeSend:function(){
					//$(shloader).show();
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
        <h2><i class="glyphicon glyphicon-user"></i> All Pages</h2>
    </div>
    <div class="box-content">
    <table class="table table-striped table-bordered bootstrap-datatable datatable responsive">
    <thead>
    <tr>
        <th>Page Name</th>
        <th>Date registered</th>
        <th>Status</th>
        <th>Actions</th>
        <th>Home Content</th>
    </tr>
    </thead>
    <tbody>
		
	<?php	
	  foreach($page_lists as $post_pages){
			$page_id = $post_pages['id'];
			$page_name = $post_pages['page_name'];
			$short_description = $post_pages['short_description'];
			$created_date = date_create($post_pages['created_date']);
			$created_ip = $post_pages['created_ip'];
			$status = $post_pages['status'];
			$show_home_page = $post_pages['show_home_page'];
			$chk_status = (($status == 1) ? 'Active' : 'Deactive');
			$status_color = (($status == 1) ? 'label-success label label-default' : 'label-default label label-danger');
			$data_status = (($status == 1) ? '0' : '1');
			$hstatus = (($show_home_page == 1) ? '0' : '1');
			$chk_active = (($show_home_page == 1) ? 'Active' : 'Inactive');
			$chkStatus = (($show_home_page == 1) ? 'label-success' : '');

			echo '<tr class="alternate" data-row-id="'.$page_id.'">
			<td>'.$page_name.'</td>
			<td class="center">'.date_format($created_date,"Y/m/d").'</td>
			<td class="center">
			<a href="javascript:void(0);" class="status" data-id="'.$page_id.'" data-status="'.$data_status.'"><span class="'.$status_color.'">'.$chk_status.'</span></a>
			<img class="ajloader" style="display:none;" src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif">
			</td>
			<td class="center">
			<a class="btn btn-info" href="edit-page.php?page_id='.$cFunc->EncryptClientId($page_id).'">
			<i class="glyphicon glyphicon-edit icon-white"></i>
			Edit
			</a>
			<a class="btn btn-danger delete_row" data-pid="'.$page_id.'" href="javascript:void(0);">
			<i class="glyphicon glyphicon-trash icon-white"></i>
			Delete
			</a>
			<img class="shloader" style="display:none;" src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif">
			</td>
			<td class="center ">
				<a href="javascript:void(0);" class="chk-status" data-pid="'.$page_id.'" data-pstatus="'.$hstatus.'">
				<span class="'.$chkStatus.' label-default label">'.$chk_active.'</span></a>
				<img class="ajloaders" style="display:none;" src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif">
			</td>
			</tr>';
	}
   ?>
    <!--<span class="label-default label">Inactive</span>-->
    
   
    </tbody>
    </table>
    </div>
    </div>
    </div>
    <!--/span-->

    </div><!--/row-->

<?php include_once 'footer.php'; ?>
