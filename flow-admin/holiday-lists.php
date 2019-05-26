<?php
include_once 'header.php';

$obj = new ConnectDb();
$customObj = new CustomFunctions();

$holiday_lists = $obj->fetchByOrder('op2mro9899_holiday', 'holiday_date');

?>
<script>
$(document).ready(function(){
	$(".delete-holiday").on('click', function(){
		var alert_msg = "Are you sure you want to delete this row?";
		var chk = confirm(alert_msg);
		if(chk){
			var row_ids = $(this).closest('tr').attr('data-row-id');
			var row_id = $(this).closest('tr').addClass('changeColour');	
			var hid = $(this).data('id');
			if(hid!=''){
				$.ajax({
					url:'action/holiday.php',
					type:'POST',
					cache:false,
					data:{action:'delete_holiday', del_id:hid},
					beforeSend:function(){
						row_id;
					},
					complete:function(){
					},
					success:function(sudata){
						if(sudata){
							var json = $.parseJSON(sudata);
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
	
	
	$(".allowed-charge").on('click', function(){

		var cid = $(this).data('cid');
		var aid = $(this).data('aid');
		if(cid!=''){
			$.ajax({
				url:'action/holiday.php',
				type:'POST',
				cache:false,
				data:{action:'allowes_charge', alcid:cid, alid:aid},
				beforeSend:function(){},
				complete:function(){},
				success:function(respdata){
					var data = $.parseJSON(respdata);
					if(data.status=='true'){
						window.location.href='';
					}else if(data.status=='false'){
						alert(data.msg);
					}else{}
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
                    <h2>Holiday Lists</h2>
                    <div class="box-icon"></div>
                </div>
                <div class="box-content">
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>Holiday Description</th>
                            <th>Year</th>
                            <th>Month</th>
                            <th>Holiday Date</th>
                            <th>Holiday Charge</th>
                            <th>Allowed Charge</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                       <?php
						foreach($holiday_lists as $listval){
								$holiday_id = $listval['id'];
								$current_year =  $listval['current_year'];
								$current_month = $listval['current_month'];
								$holiday_date = $listval['holiday_date'];
								$holidat_desc = $listval['holidat_desc'];
								$special_charges = $listval['special_charges'];
								$allowed_date = $listval['allowed_date'];
								$allowed_text = (($allowed_date == 0) ?  'Not allowed' : 'Allowed');
								$allowed_class = (($allowed_date == 0) ?  'label-default label label-danger' : 'label-success label-default label');
								$allowed_status = (($allowed_date == 0) ?  '1' : '0');
                       ?>
                        <tr class="alternate" data-row-id="<?php echo $holiday_id; ?>">
                            <td><?php echo $holidat_desc; ?></td>
                            <td class="center"><?php echo $current_year; ?></td>
                            <td class="center"><?php echo $current_month; ?></td>
                            <td class="center"><?php echo $holiday_date; ?></td>
                            <td class="center"><?php echo $special_charges; ?></td>
                            <td class="center">
                                <a href="javascript:void(0);" class="allowed-charge" data-cid="<?php echo $holiday_id; ?>" data-aid="<?php echo $allowed_status; ?>">
									<span class="<?php echo $allowed_class; ?>"><?php echo $allowed_text; ?></span></a>
                            </td>
                            <td class="center">
                                <a href="javascript:void(0);" class="delete-holiday" data-id="<?php echo $holiday_id; ?>"><span class="label-default label label-danger">Delete</span></a>
                            </td>
                        </tr>
                       <?php } ?>
                       
                        
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>


<?php include_once 'footer.php'; ?>
