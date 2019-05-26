<?php
include_once 'header.php';

//print_r($_SESSION);

$obj =  new ConnectDb();
$cFunc = new CustomFunctions();

$shop_id = $_SESSION['shop_id'];

$today_orders = $obj->today_orders($shop_id);
$json_decode = json_decode($today_orders);


$tomorrow_order = $obj->tomorrow_order($shop_id);
$json_data = json_decode($tomorrow_order);

$latest_ten = $obj->allOrders($shop_id, 0, 10);
$json_decode_record = json_decode($latest_ten);

//print_r($latest_ten); die;

$order_st_args = array('due' => 'DUE', 'dispatch' => 'DISPATCHED');
?>

<script>
$(document).ready(function(){
	$(".orderstatus").on('change', function(){
		var order_val = $(this).val();
		var loader = $(this).siblings(".status-loader");
		var order_id = $(this).data('id');
		var oids = $(this).data('order-ids');
		
		if(order_id!=''){
			$.ajax({
				url:'action/product-order.php',
				type:'post',
				data:{action:'change_status', status_val:order_val, oid:order_id, orderids:oids},
				cache:false,
				beforeSend:function(){
					$(loader).show();
				},
				complete:function(){
					$(loader).hide();
				},
				success:function(responce){
					if(responce){
						window.location.href='';
					}
				}
			});
		}	
	});
	
	
	$(".cancel-status").on('click', function(){
		var cancel_val = $(this).data('val-id');
		var status = $(this).data('status');
		if(cancel_val!=''){
			$.ajax({
				url:'action/product-order.php',
				type:'post',
				data:{action:'cancel_order', status_value:status, cid:cancel_val},
				cache:false,
				beforeSend:function(){
					//$(loader).show();
				},
				complete:function(){
					//$(loader).hide();
				},
				success:function(resp){
					window.location.href='';
				}
			});
		}	
	});
	
	
		//Sorting
	
	$('#orderdate, #deliverydate').click(function(){
	    var table = $(this).parents('table').eq(0)
	    var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
	    this.asc = !this.asc
	    if (!this.asc){rows = rows.reverse()}
	    for (var i = 0; i < rows.length; i++){table.append(rows[i])}
	})
	function comparer(index) {
	    return function(a, b) {
	        var valA = getCellValue(a, index), valB = getCellValue(b, index)
	        return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB)
	    }
	}
	function getCellValue(row, index){ return $(row).children('td').eq(index).html() }
	
});
</script>


<div class="row">
        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2><i class="glyphicon glyphicon-user"></i>  Today's Deliveries</h2>
                </div>
                <div class="box-content">
                    <table class="table table-striped table-bordered responsive">
                        <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Ordered Date </th>
                            <th>Delivery Date</th>
                            <th>Customer Name</th>
                            <th>Total Quantity</th>
                            <th>Total Price</th>
                            <th>Payement Status</th>
                            <th>Order Status</th>
                            <th>Action</th>
                            <th>Print Status</th>
                        </tr>
                        </thead>
                        <tbody>

					<?php
						foreach($json_decode as $order_val){
							$id = $order_val->id;
							$user_name = $order_val->user_name;
							$email_address = $order_val->email_address;
							$order_id = $order_val->order_id;
							$user_id = $order_val->user_id;
							$delivery_date = date("d-M-Y", strtotime($order_val->delivery_date));
							$created_date = date("d-M-Y", strtotime($order_val->ordered_date));
							$payment_id = $order_val->payment_id;
							$item_number = $order_val->item_number;
							$payment_status = $order_val->payment_status;
							$mc_gross = $order_val->mc_gross;
							$total_quantity = $order_val->total_quantity;
							$order_status = $order_val->order_status;
							//$status = (($payment_status == 'Completed') ? 'label-success' : 'label-warning');
							$status = ($payment_status == 'Completed') ? "label-success" : (($payment_status == 'Paid')  ? "label-success" : "label-warning");
							$print_status = $order_val->print_status;
					?>
                        <tr>
                            <td><?php echo $order_id; ?></td>
                            <td class="center"><?php echo $created_date; ?></td>
                            <td class="center"><?php echo $delivery_date; ?></td>
                            <td class="center"><?php echo $user_name; ?></td>
                            <td class="center"><?php echo $total_quantity; ?></td> 
                            <td class="center"><?php echo $mc_gross; ?></td> 
                            <td class="center">
                                <span class="<?php echo $status; ?> label label-default"><?php echo $payment_status; ?></span>
                            </td>
                             <td class="center">
                             
								<select name="order_status" id="orderstatus" class="orderstatus" data-id="<?php echo $payment_id; ?>" data-order-ids="<?php echo $order_id; ?>">
									<?php
										foreach($order_st_args as $key => $val){
											
											$sel = (($key == $order_status) ? 'selected' : '');
											echo '<option '.$sel.' value="'.$key.'">'.$val.'</option>';
										}
										if($order_status == 'cancel'){
											echo '<option selected value="cancel">CANCELED</option>';
										}
									?>
								</select>
								<img class="status-loader" style="display:none;" src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif">
                             </td> 
                            <td class="center">
								<a class="" href="view-receipt.php?order_id=<?php echo $cFunc->EncryptClientId($order_id); ?>&type=allorder">
                                    <span class="label-warning label label-default">View</span>
                                </a>
                                
                                <a class="" href="edit-orders.php?order=<?php echo $cFunc->EncryptClientId($order_id); ?>&type=allorder">
                                    <span class="label-success label label-default">Edit</span>
                                </a>
                                                        
								<a class="cancel-status" data-val-id="<?php echo $payment_id; ?>" data-status="cancel" href="javascript:void(0);">
									<span class="label-default label label-danger">Cancel</span>
								</a>
								
								<a class="" data-val-id="<?php echo $payment_id; ?>" data-status="cancel" href="pdf.php?invoice=<?php echo BASE_PATH; ?>/pdf-format.php?order_id=<?php echo $cFunc->EncryptClientId($order_id); ?>" target="_blank">
									 <span class="label-warning label label-default">Genrate PDF</span>
								</a>
                            </td>
                             <td class="center">
                                <?php if($print_status == 1){?>
                                <span class="label-success label label-default">Printed</span>
                                <?php }else{ ?>
									<span class="label label-default">Not Printed</span>
								<?php } ?>
                            </td>
                        </tr>
                        
                        <?php } ?>
                                 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--/span-->

    </div><br /><br />

<div class="row">
        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2><i class="glyphicon glyphicon-user"></i> Tomorrow's Deliveries</h2>
                </div>
                <div class="box-content">
                    <table class="table table-striped table-bordered responsive">
                        <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Ordered Date </th>
                            <th>Delivery Date</th>
                            <th>Customer Name</th>
                            <th>Total Quantity</th>
                            <th>Total Price</th>
                            <th>Payement Status</th>
                            <th>Order Status</th>
                            <th>Action</th>
                            <th>Print Status</th>
                        </tr>
                        </thead>
                        <tbody>

					<?php
						foreach($json_data as $order_vals){ //print_r($order_vals);
							$id = $order_vals->id;
							$user_name = $order_vals->user_name;
							$email_address = $order_vals->email_address;
							$order_id = $order_vals->order_id;
							$user_id = $order_vals->user_id;
							$delivery_date = date("d-M-Y", strtotime($order_vals->delivery_date));
							$created_date = date("d-M-Y", strtotime($order_vals->ordered_date));
							$payment_id = $order_vals->payment_id;
							$item_number = $order_vals->item_number;
							$payment_status = $order_vals->payment_status;
							$mc_gross = $order_vals->mc_gross;
							$total_quantitys = $order_vals->total_quantity;
							$order_status = $order_vals->order_status;
							//$status = (($payment_status == 'Completed') ? 'label-success' : 'label-warning');
							$status = ($payment_status == 'Completed') ? "label-success" : (($payment_status == 'Paid')  ? "label-success" : "label-warning");
							$prints_status = $order_vals->print_status;
					?>
                        <tr>
                            <td><?php echo $order_id; ?></td>
                            <td class="center"><?php echo $created_date; ?></td>
                            <td class="center"><?php echo $delivery_date; ?></td>
                            <td class="center"><?php echo $user_name; ?></td>
                            <td class="center"><?php echo $total_quantitys; ?></td> 
                            <td class="center"><?php echo $mc_gross; ?></td> 
                            <td class="center">
                                <span class="<?php echo $status; ?> label label-default"><?php echo $payment_status; ?></span>
                            </td>
                             <td class="center">
                             
								<select name="order_status" id="orderstatus" class="orderstatus" data-id="<?php echo $payment_id; ?>" data-order-ids="<?php echo $order_id; ?>">
									<?php
										foreach($order_st_args as $key => $val){
											$sel = (($key == $order_status) ? 'selected' : '');
											echo '<option '.$sel.' value="'.$key.'">'.$val.'</option>';
										}
										if($order_status == 'cancel'){
											echo '<option selected value="cancel">CANCELED</option>';
										}
									?>
								</select>
								<img class="status-loader" style="display:none;" src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif">
                             </td> 
                            <td class="center">
                                <a class="" href="view-receipt.php?order_id=<?php echo $cFunc->EncryptClientId($order_id); ?>&type=allorder">
                                    <span class="label-warning label label-default">View</span>
                                </a>
                                
                                <a class="" href="edit-orders.php?order=<?php echo $cFunc->EncryptClientId($order_id); ?>&type=allorder">
                                    <span class="label-success label label-default">Edit</span>
                                </a>
                                                        
								<a class="cancel-status" data-val-id="<?php echo $payment_id; ?>" data-status="cancel" href="javascript:void(0);">
									<span class="label-default label label-danger">Cancel</span>
								</a>
								
								<a class="" data-val-id="<?php echo $payment_id; ?>" data-status="cancel" href="pdf.php?invoice=<?php echo BASE_PATH; ?>/pdf-format.php?order_id=<?php echo $cFunc->EncryptClientId($order_id); ?>" target="_blank">
									 <span class="label-warning label label-default">Genrate PDF</span>
								</a>
                            </td>
                             <td class="center">
                                <?php if($prints_status == 1){?>
                                <span class="label-success label label-default">Printed</span>
                                <?php }else{ ?>
									<span class="label label-default">Not Printed</span>
								<?php } ?>
                            </td>
                        </tr>
                        
                        <?php } ?>
                                 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--/span-->
    </div>


	
	<div class="row">
        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2><i class="glyphicon glyphicon-user"></i> Latest 10 Orders</h2>
                </div>
                <div class="box-content">
                    <table class="table table-striped table-bordered responsive">
                        <thead>
                        <tr>
                            <th>Order ID</th>
                            <th id="orderdate" class="sorting">Ordered Date </th>
                            <th id="deliverydate" class="sorting">Delivery Date</th>
                            <th>Customer Name</th>
                            <th>Total Quantity</th>
                            <th>Total Price</th>
                            <th>Payement Status</th>
                            <th>Order Status</th>
                            <th>Action</th>
                            <th>Print Status</th>
                        </tr>
                        </thead>
                        <tbody>
							
							<?php
							foreach($json_decode_record as $latest_order){
								$ids = $latest_order->id;
								$user_names = $latest_order->user_name;
								$email_addresses = $latest_order->email_address;
								$order_ids = $latest_order->order_id;
								$user_ids = $latest_order->user_id;
								$delivery_dates = date("d-M-Y", strtotime($latest_order->delivery_date));
								$created_dates = date("d-M-Y", strtotime($latest_order->ordered_date));
								$payment_ids = $latest_order->payment_id;
								$item_numbers = $latest_order->item_number;
								$payment_statuss = $latest_order->payment_status;
								$mc_grosss = $latest_order->mc_gross;
								$total_qtys = $latest_order->total_quantity;
								$order_statuss = $latest_order->order_status;
								//$pstatus = (($payment_statuss == 'Completed') ? 'label-success' : 'label-warning');
								$pstatus = ($payment_statuss == 'Completed') ? "label-success" : (($payment_statuss == 'Paid')  ? "label-success" : "label-warning");
								$prs_status = $latest_order->print_status;
							?>

					    <tr>
                            <td><?php echo $order_ids; ?></td>
                            <td class="center"><?php echo $created_dates; ?></td>
                            <td class="center"><?php echo $delivery_dates; ?></td>
                            <td class="center"><?php echo $user_names; ?></td>
                            <td class="center"><?php echo $total_qtys; ?></td> 
                            <td class="center"><?php echo $mc_grosss; ?></td> 
                            <td class="center">
                                <span class="<?php echo $pstatus; ?> label label-default"><?php echo $payment_statuss; ?></span>
                            </td>
                             <td class="center">
                             
								<select name="order_status" id="orderstatus" class="orderstatus" data-id="<?php echo $payment_ids; ?>" data-order-ids="<?php echo $order_ids; ?>">
									<?php
										foreach($order_st_args as $keyes => $key_vals){
											$sel = (($keyes == $order_statuss) ? 'selected' : '');
											echo '<option '.$sel.' value="'.$keyes.'">'.$key_vals.'</option>';
										}
										if($order_statuss == 'cancel'){
											echo '<option selected value="cancel">CANCELED</option>';
										}
									?>
								</select>
								<img class="status-loader" style="display:none;" src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif">
                             </td> 
                            <td class="center">
                                
                                <a class="" href="view-receipt.php?order_id=<?php echo $cFunc->EncryptClientId($order_ids); ?>&type=allorder">
                                    <span class="label-warning label label-default">View</span>
                                </a>
                                
                                <a class="" href="edit-orders.php?order=<?php echo $cFunc->EncryptClientId($order_ids); ?>&type=allorder">
                                    <span class="label-success label label-default">Edit</span>
                                </a>
                                                        
								<a class="cancel-status" data-val-id="<?php echo $payment_ids; ?>" data-status="cancel" href="javascript:void(0);">
									<span class="label-default label label-danger">Cancel</span>
								</a>
								
								<a class="" data-val-id="<?php echo $payment_ids; ?>" data-status="cancel" href="pdf.php?invoice=<?php echo BASE_PATH; ?>/pdf-format.php?order_id=<?php echo $cFunc->EncryptClientId($order_ids); ?>" target="_blank">
									 <span class="label-warning label label-default">Genrate PDF</span>
								</a>
                            </td>
                             <td class="center">
                                <?php if($prs_status == 1){?>
                                <span class="label-success label label-default">Printed</span>
                                <?php }else{ ?>
									<span class="label label-default">Not Printed</span>
								<?php } ?>
                            </td>
                        </tr>
                        
                          <?php } ?>                     
                                         
                                                         
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--/span-->
    </div>


<?php include_once 'footer.php'; ?>
