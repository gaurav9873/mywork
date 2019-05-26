<?php
require_once('header.php'); 

$domainObj = new ConnectDb();
$cFunc = new CustomFunctions();

$db_rows = $domainObj->fetchAll('op2mro9899_add_domain');
$json = json_encode($db_rows);

?>

<script type="text/javascript">
$(document).ready(function(){
	get_shops();
	function get_shops(){
		var json = <?php echo $json?>;
		var html = '';
		$.each(json, function(k,v){
			html += template(v);
		});
		$("#site-data").empty().append(html);
	}
	
	function template(obj) {
		var html = '';
		var status = ((obj.status == 0) ? 'label-danger' : '');
		var status_name = ((obj.status == 0) ? 'Banned' : 'Active');
		var active_status = ((obj.status == 0) ? '1' : '0');
		html += '<tr class="alternate" data-row-id="'+obj.site_id+'">';
		html += '<td class="site-name">'+obj.domain_name+'</td>';
		html += '<td>'+obj.created_date+'</td>';
		html += '<td>'+obj.created_ip+'</td>';
		html +='<td class="center"><a href="javascript:void(0);" id="delete_row" class="delete_row" data-id="'+obj.site_id+'"><span class="label-default label label-danger">Delete</span></a></td>';
		html += '<td class="center"><a href="javascript:void(0);" class="edit-site" data-id="'+obj.site_id+'"><span class="label label-success">Edit</span></a></td>'
		html += '<td class="center"><a href="javascript:void(0);" class="active-status" data-val="'+obj.site_id+'" data-status="'+active_status+'"><span class="label-success label '+status+'">'+status_name+'</span></a></td>'
		html += '</tr>';
		return html;
	}
	
	
	//$(".edit-site").on('click', function(){
	$(document).on('click', '.edit-site', function(){
		var eid = $(this).attr("data-id");
		var name = $(this).closest('tr').find('.site-name').text();
		$(".form-control").val(name);
		$(".add").hide();
		$(".edit").show();
		$(".form-group").append('<input type="hidden" class="edit-id" name="edit-id" value="">');
		$(".edit-id").val(eid);
	});
	
	$(document).on('click', '.active-status', function(){
		var vals = $(this).data('val');
		var status_id = $(this).data('status');
		if(vals!=''){
			$.ajax({
				url:'action/domain.php',
				type:'POST',
				cache:false,
				data:{
					action:'active_status',
					active_id:status_id,
					active_site:vals
				},
				beforeSend:function(){
				},
				complete:function(){
				},
				success:function(sudata){
					var res_data = $.parseJSON(sudata);
					if(res_data.status == true){
						window.location.href='';
					}
				}
			});
		}
	});
	

	$(document).on('click', '.edit', function(){
		var name = $("#domain_name").val();
		var sid = $(".edit-id").val();
		
		if(sid!=''){
			$.ajax({
				url:'action/domain.php',
				type:'POST',
				cache:false,
				data:{
					action:'update',
					site_name:name,
					site_id:sid
				},
				beforeSend: function() {
				},
				complete: function(){
				},
				success: function(responce){
					window.location.href='';
					/*$(".add").show();
					$(".edit").hide();
					$("#sfrm")[0].reset();*/
				}
			});
		}
		
		
	});
	
	
	$(".add").on('click', function(){
		var domain_val = $("#domain_name").val();
		var errorFlag = true;
		if(domain_val == ''){
			$(".form-group").addClass('has-error');
			$(".err").show().css('color', 'red');
			return false;
		}else{
			$.ajax({
				url:'action/domain.php',
				type:'POST',
				cache:false,
				data:{
					action:'insert',
					value:domain_val 
				},
				beforeSend: function() {	
					$('.ajax-loaders').show();
				},
				complete: function(){
					$('.ajax-loaders').hide();
				},
				success: function(json){
					var Data = $.parseJSON(json);
					var html = template(Data[0]);
					$("#site-data").prepend(html);
					$("#add-alert").show();
					$('#add-alert').delay(5000).fadeOut('slow');
					$("#sfrm")[0].reset();
				}
			});
		}
	});
	
	

    $(document).on('click', '.delete_row', function(){
		var alert_msg = "Are you sure you want to delete this row?";
		var chk = confirm(alert_msg);
		if(chk){
			var delID = $(this).attr("data-id");
			var row_ids = $(this).closest('tr').attr('data-row-id');
			var row_id = $(this).closest('tr').addClass('changeColour');
			
			if(delID!=''){
				$.ajax({
					url:'action/domain.php',
					type:'POST',
					cache:false,
					data:{
						action:'Delete',
						del_id:delID
					},
					beforeSend: function(){
						row_id;
						
					},
					complete: function(){
						//remove_class;
					},
					success: function(res){
						if(res){
							var json = $.parseJSON(res);
							//alert(json.msg);
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
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-edit"></i>Add Site</h2>
            </div>
            
            <div class="box-content">
				<div class="alert alert-info" id="add-alert" style="display:none;">Domain added successfully</div>
					<form name="sfrm" id="sfrm" action="" role="form" method="post">
						<div class="form-group">
							<label for="exampleInputEmail1">Add Domian Name / Site Name</label>
							<input class="form-control" id="domain_name" name="domain_name"  placeholder="Enter Site Name" type="text">
							<ul class="err" style="display:none;"><li>Please enter domain name.</li></ul>
						</div>
						
						<button type="button" class="btn btn-default add" >Submit</button>
						<button type="button" class="btn btn-default edit" style="display:none;">Edit</button>
						<ul class="ajax-loaders" style="display:none;"><li><img src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif"></li></ul>
					</form>
            </div>
        </div>
    </div>
    <!--/span-->
</div>

<?php
/*
 SELECT a.id, a.category_name, a.parent_category, a.domain_id, b.img_path, b.site_id
FROM category a
INNER JOIN category_image AS b ON FIND_IN_SET( a.id, b.site_id )
ORDER BY a.id DESC
LIMIT 0 , 30
 */ 
?>

<div class="row">
	<div class="alert alert-success" id="del-msg" style="display:none;">successfully deleted.</div>
        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2>All Lists</h2>
                </div>
                <div class="box-content">
                    <table class="table table-bordered table-striped table-condensed" id="did">
                        <thead>
                        <tr>
                            <th>Domain Name</th>
                            <th>Registered Date</th>
                            <th>Registred IP</th>
                            <th>Delete</th>
                            <th>Edit</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody id="site-data" class="site-data">
							
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


<?php require('footer.php'); ?>
