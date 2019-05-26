<?php
include_once 'header.php';

$obj = new Connectdb();
$cFunc = new CustomFunctions();

if(isset($_POST['submit'])){
	

	$holiday_list = $_POST['holiday_list'];
	$holiday_desc = $_POST['holiday_desc'];
	$holiday_charges = $_POST['holiday_charges'];
	$count_val = count($holiday_list);
	$count = 1;
	
	
	foreach( $holiday_list as $key => $val ) {
		
		$month = date("m", strtotime($val));
		$year = date("Y", strtotime($val));
		$description = $holiday_desc[$key];
		$special_charges = $holiday_charges[$key];
		$charge_status = (($special_charges!='') ? '1' : '0');
		$args = array('current_year' => $year, 'current_month' => $month, 'holiday_date' => $val, 'holidat_desc' => $description, 'special_charges' => $special_charges, 'allowed_date' => $charge_status);
		$obj->insert_records('op2mro9899_holiday', $args);
		if($count_val = $count){
			header("location:holiday-lists.php");
		}
		
	$count++; }
}
?>

<link rel="stylesheet" href="base/jquery-ui.css">
<script src="base/jquery-ui.js"></script>
<script>

$(document).ready(function(){
	var maxField = 25;
	var addButton = $('.add_button');
	var wrapper = $('.field_wrapper');
	
	var field_count = 1;
	$(addButton).click(function(){
		if(field_count < maxField){
			field_count++;
			var fieldHTML = $('<div><div class="row rvals"><div class="input-group col-md-3" style="float: left; margin-right: 10px; margin-left:15px; margin-top:15px;"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar red"></i></span><input class="form-control datepicker" name="holiday_list[]" value="" placeholder="Select Date" type="text"></div><div class="input-group col-md-3" style="float: left; margin-right:10px; margin-top:15px;"><span class="input-group-addon"><i class="glyphicon glyphicon-star yellow"></i></span><input class="form-control" name="holiday_desc[]" placeholder="Description" type="text"></div><div class="input-group col-md-3"  style="float: left; margin-right:10px; margin-top:15px;"><span class="input-group-addon"><i class="glyphicon glyphicon-gbp red"></i></span><input class="form-control" name="holiday_charges[]" placeholder="Holiday charges" type="text"></div><div class="input-group col-md-2" style="float: left; margin-right:10px; margin-top:18px;"><a href="javascript:void(0);" class="remove_button" title="Remove field"><img src="img/remove.png"></a></div></div></div>');
			$(wrapper).append(fieldHTML);
		}
	});
	$(wrapper).on('click', '.remove_button', function(e){
		e.preventDefault();
		$(this).parent().parent('div').remove();
		field_count--;
	});
	
	$(document).on('focus',".datepicker", function(){
		$(this).datepicker({dateFormat:'yy-mm-dd'});
	});
	
}); 
  
</script>
<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-edit"></i> Holiday Calender</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-setting btn-round btn-default"><i class="glyphicon glyphicon-cog"></i></a>
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                    <a href="#" class="btn btn-close btn-round btn-default"><i class="glyphicon glyphicon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <form name="frm" id="frm" action="" method="post" role="form">
                <div class="field_wrapper">
					<div>
						<div class="row">    
							<div class="input-group col-md-3"  style="float: left; margin-right: 10px; margin-left:15px; margin-top:15px;">
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar red"></i></span>
							<input class="form-control datepicker" value="" name="holiday_list[]" placeholder="Select Date" type="text">
						</div>

						<div class="input-group col-md-3"  style="float: left; margin-right:10px; margin-top:15px;">
							<span class="input-group-addon"><i class="glyphicon glyphicon-star red"></i></span>
							<input class="form-control" name="holiday_desc[]" placeholder="Description" type="text">
						</div>
						
						<div class="input-group col-md-3"  style="float: left; margin-right:10px; margin-top:15px;">
							<span class="input-group-addon"><i class="glyphicon glyphicon-gbp red"></i></span>
							<input class="form-control" name="holiday_charges[]" placeholder="Holiday charges" type="text">
						</div>

						<div class="input-group col-md-2"  style="float: left; margin-right:10px; margin-top:18px;">
							<a href="javascript:void(0);" class="add_button" title="Add field"><img src="img/Add.png"></a>
						</div>
						</div>
					</div> 
                </div>
                <br />
                <div class="row">
                    <div class="col-md-2">
						<button type="submit" name="submit" class="btn btn-default">Submit</button>
                    </div>
                  </div>
                </form>

            </div>
        </div>
    </div>
    <!--/span-->

</div>

<?php include_once 'footer.php'; ?>
