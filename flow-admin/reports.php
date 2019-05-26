<?php
include_once 'header.php';

$cObj = new Userreports();

$filter_type = isset($_REQUEST['ftype']) ? $_REQUEST['ftype'] : '';
$start_date = isset($_REQUEST['st_date']) ? $_REQUEST['st_date'] : ''; 
$end_date = isset($_REQUEST['tdate']) ? $_REQUEST['tdate'] : '';
$limit = isset($_REQUEST['flimit']) ? $_REQUEST['flimit'] : '';
$report_type = isset($_REQUEST['rtype']) ? $_REQUEST['rtype'] : '';
$site_id = $_SESSION['shop_id'];

$label = (($filter_type == 'monthly') ? 'Month' : ($filter_type == 'yearly') ? 'Year' : 'days');
$label_title = (($report_type == 'total_orders') ? 'All Orders' : (($report_type == 'new_customer') ? 'All Customers' : (($report_type == 'product_popularity') ? 'Popular Products' : '')));

if($report_type == 'new_customer'){
	$cust_record = $cObj->get_customer_by_year($filter_type, $start_date, $end_date, $site_id, $limit);
	$records = json_decode($cust_record);

}else if($report_type == 'total_orders'){
	$total_order =  $cObj->total_order_by_year($filter_type, $start_date, $end_date, $site_id, $limit);
	$total_order_records = json_decode($total_order);
}else if($report_type == 'ordered_products'){
		$ordered_products = $cObj->ordered_products($filter_type, $start_date, $end_date, $site_id, $limit);
		$order_records = json_decode($ordered_products);
		
}else if($report_type == 'product_popularity'){
	$popular_products = $cObj->popular_products($filter_type, $start_date, $end_date, $site_id, $limit);
	$popular_repo = json_decode($popular_products);

}else{}

$current_month = date('m', strtotime('-1 month'));
$current_date = date("m");
$current_year = date("Y");

$array_records = (($report_type == 'total_orders') ? $total_order_records : (($report_type == 'new_customer') ? $records : 
				 (($report_type == 'product_popularity') ? $popular_repo : (($report_type == 'ordered_products') ? $order_records : ''
				 ))));

if(isset($_REQUEST['st_date'])){
	$total_vals = array();
	array_walk_recursive($array_records, function($item, $key) use (&$total_vals){
		if($_REQUEST['rtype'] == 'total_orders'){
			
			array_push($total_vals, $item->totalorder);
			
		}else if($_REQUEST['rtype'] == 'new_customer'){
			
			array_push($total_vals, $item->total);
			
		}else if($_REQUEST['rtype'] == 'ordered_products'){
			array_push($total_vals, $item->qty);
		}else if($_REQUEST['rtype'] == 'product_popularity'){
			array_push($total_vals, $item->totalQuantity);
		}else{}
	});
}

?>

<script>
	$(document).ready(function(){
		$("#view-product").on('click', function(evt){
			evt.preventDefault();
			var start_month = $(".start_month").val();
			var start_day = $(".start_day").val();
			var start_year = $(".start_year").val();
			
			var end_month = $(".end_month").val();
			var end_day = $(".end_day").val();
			var end_year = $(".end_year").val();
			
			var filter_limit = $(".filter_limit").val();
			var filter_type = $(".filter_type").val();
			var report_type = $(".report_type").val();
			
			var start_date = start_year+'-'+start_month+'-'+start_day;
			var to_date = end_year+'-'+end_month+'-'+end_day;
			
			window.location.href = "https://www.fleurdelisflorist.co.uk/florist-admin/reports.php?st_date="+start_date+"&tdate="+to_date+"&flimit="+filter_limit+"&ftype="+filter_type+"&rtype="+report_type+"";
			
		});
	});
</script>
<div class="row">
  <div class="box col-md-12">
    <div class="box-inner" style="height:auto;">

      <div class="box-header well frmContrl" data-original-title="" style="height:auto;">
		 <p>Start Date</p>
        <select name="start_month" id="start_month" class="start_month form-control">
			<?php echo $cObj->start_months(); ?>
		</select>
        
        <select name="start_day" id="start_day" class="start_day form-control">
          <?php echo $cObj->start_day(); ?>
       </select>
       
       <select name="start_year" id="start_year" class="start_year form-control">
          	<?php echo $cObj->start_year(); ?>
       </select>
       <br /><br /><br />
       
       <p>To Date</p>
       
       <select name="end_month" id="end_month" class="end_month form-control">
			<?php echo $cObj->end_month();	?>
		</select>
        
        <select name="end_day" id="end_day" class="end_day form-control">
           <?php echo $cObj->end_day();  ?>
       </select>
       
       <select name="end_year" id="end_year" class="end_year form-control">
          	<?php  echo $cObj->end_year(); ?>
       </select>
       
       <br /><br /><br />
       <select name="filter_limit" id="filter_limit" class="filter_limit form-control">
			<?php echo $cObj->filter_limit(); ?>
       </select>
       
       <select name="filter_type" id="filter_type" class="filter_type form-control">
		   <?php echo $cObj->filter_type(); ?>
       </select>
       
       <select name="report_type" id="report_type" class="report_type form-control">
		   <?php echo $cObj->report_type(); ?>
       </select>
       
       
        <a class="btn btn-success" id="view-product" href="javscript:void(0);"><i class="glyphicon glyphicon-zoom-in icon-white"></i>View</a>
      </div>
    </div>
  </div>
</div>





<div class="row">
	<div class="box col-md-12">
		<div class="box-inner">
			<div class="box-header well" data-original-title="">
				<h2><?php echo $label_title; ?></h2>
				<div class="box-icon">                    
				</div>
			</div>
			<div class="box-content">
				
				<?php if($report_type == 'new_customer'){ ?>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
						<tr>
							<th><?php echo $label; ?></th>
							<th>Percentage</th>
							<th>Total</th>
						</tr>
						</thead>
						<tbody>
							<?php 						
								
								$sum_total = array();
								foreach($records as $values){
									$total_users = $values->total;
									$years = $values->regdate;
									$date_formate = date("M", strtotime("$years/12/10"));
									$chk_date_month = (($filter_type == 'monthly') ? $date_formate : $years);
									
									$sum_vals = array_sum($total_vals);
									$percent = $total_users/$sum_vals;
									$percentage = number_format( $percent * 100, 2 ) . '%';
									echo '<tr>
									<td>'.$chk_date_month.'</td>
									<td>'.$percentage.'</td>
									<td class="center">'.$total_users.'</td>
									</tr>';
								}
							?>
							<tr>
							  <td>Total</td>
							  <td>&nbsp;</td>
							  <td class="center"><?php echo array_sum($total_vals); ?></td>
							</tr>
						
						</tbody>
					</table>
				<?php }else if($report_type == 'total_orders'){ ?>
					
					<table class="table table-bordered table-striped table-condensed">
						<thead>
						<tr>
							<th>Order Year</th>
							<th>Percetage</th>
							<th>Total</th>
						</tr>
						</thead>
						<tbody>
							<?php 
							foreach($total_order_records as $order_values){
								$total_orders = $order_values->totalorder;
								$years = $order_values->orderdate;
								$date_formate = date("M", strtotime("$years/12/10"));
								$chk_date_month = (($filter_type == 'monthly') ? $date_formate : $years);
								$sum_vals = array_sum($total_vals);
								$percent = $total_orders/$sum_vals;
								$percentage = number_format( $percent * 100, 2 ) . '%';
								echo '<tr>
								<td>'.$chk_date_month.'</td>
								<td>'.$percentage.'</td>
								<td class="center">'.$total_orders.'</td>
								</tr>';
							}
							?>
							
							<tr>
							  <td>Total</td>
							  <td>&nbsp;</td>
							  <td class="center"><?php echo array_sum($total_vals); ?></td>
							</tr>
						
						</tbody>
					</table>
					
			  <?php }else if($report_type == 'ordered_products'){ //Ordered Products ?>
				  
				  <table class="table table-bordered table-striped table-condensed">
						<thead>
						<tr>
							<th>Order Year</th>
							<th>Product Code</th>
							<th>Product Name</th>
							<th>Percentage</th>
							<th>Quantity</th>
						</tr>
						</thead>
						<tbody>
							<?php 
							
							$arr = array_unique($cObj->array_columns($order_records, "orddate"));
							
							$grouped_types = array();
							foreach($order_records as $key => $order_values){
																
								  if( $order_values->qty == "" ) 
									unset($order_records[$key]);
        
								$orddate = $order_values->orddate;
								$qty = $order_values->qty;
								$product_name = $order_values->product_name;
								$product_code = $order_values->product_code;
								$sum_vals = array_sum($total_vals);
								$percent = $qty/$sum_vals;
								$percentage = number_format( $percent * 100, 2 ) . '%';
								$group_year = isset($arr[$key]) ? $arr[$key] : '&nbsp';
								$year_date_month = (($filter_type == 'monthly') ? date("M", strtotime("$orddate/12/10")) : $group_year);
								 
								echo '<tr>
								<td>'.$year_date_month.'</td>
								<td>'.$product_code.'</td>
								<td>'.$product_name.'</td>
								<td>'.$percentage.'</td>
								<td class="center">'.$qty.'</td>
								</tr>';
							}
							?>
							
							<tr>
							  <td>Total</td>
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>
							  <td class="center"><?php echo array_sum($total_vals); ?></td>
							</tr>
						
						</tbody>
					</table>
				  
			  <?php }else if($report_type == 'product_popularity'){ ?>
			  
						
						<table class="table table-bordered table-striped table-condensed">
						<thead>
						<tr>
							<th>Order Year</th>
							<th>Product Code</th>
							<th>Product Name</th>
							<th>Percentage</th>
							<th>Quantity</th>
						</tr>
						</thead>
						<tbody>
							<?php
							$array_args = array_unique($cObj->array_columns($popular_repo, "orderdate"));
							foreach($popular_repo as $keys => $productsVals){
								$orderdates = $productsVals->orderdate;
								$product_code = $productsVals->product_code;
								$product_name = $productsVals->product_name;
								$totalQuantity = $productsVals->totalQuantity;
								$total_sum_vals = array_sum($total_vals);
								$percent = $totalQuantity/$total_sum_vals;
								$percentage = number_format( $percent * 100, 2 ) . '%';
								$grouped_years = isset($array_args[$keys]) ? $array_args[$keys] : '&nbsp';
								$years_dates_months = (($filter_type == 'monthly') ? date("M", strtotime("$orderdates/12/10")) : $grouped_years);
								echo '<tr>
								<td>'.$years_dates_months.'</td>
								<td>'.$product_code.'</td>
								<td>'.$product_name.'</td>
								<td>'.$percentage.'</td>
								<td class="center">'.$totalQuantity.'</td>
								</tr>';

							}

								echo '<tr>
								<td>Total</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td class="center">'.array_sum($total_vals).'</td>
								</tr>';
							?>
						</tbody>
					</table>
			  
			  
			  
			  <?php } ?>
				
				
				
			</div>
		</div>
	</div>
</div>


<?php include_once 'footer.php'; ?>
