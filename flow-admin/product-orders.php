<?php
include_once 'header.php';

$obj =  new ConnectDb();
$cFunc = new CustomFunctions();

$shop_id = $_SESSION['shop_id'];

//search string
$skey = isset($_REQUEST['skey']) ? $_REQUEST['skey'] : '';

//date value
$sdate = isset($_REQUEST['sdate']) ? $_REQUEST['sdate'] : '';
$edate = isset($_REQUEST['edate']) ? $_REQUEST['edate'] : '';


$countData = $obj->order_by_date_range($shop_id, $sdate, $edate);
$dval = json_decode($countData);
$cval = count($dval);


$orders = $obj->allOrders($shop_id);
$orders_count = json_decode($orders);
$count_data = count($orders_count);

if($sdate!='' && $edate!=''){
	$pages = new Paginator($cval,9,array(25,50,100,250,'All'));
	$allOrders = $obj->order_by_date_range($shop_id, $sdate, $edate, $pages->limit_start, $pages->limit_end);
	$json_decode = json_decode($allOrders);
}else if($skey!=''){
	$allOrders = $obj->serach_ordered_product($shop_id, $skey);
	$json_decode = json_decode($allOrders);
}else{
	$pages = new Paginator($count_data,9,array(25,50,100,250,'All'));
	$allOrders = $obj->allOrders($shop_id, $pages->limit_start, $pages->limit_end);
	$json_decode = json_decode($allOrders);
}

$order_st_args = array('due' => 'DUE', 'dispatch' => 'DISPATCHED');

//echo $cFunc->randomPassword();
?>

<link rel="stylesheet" type='text/css' href="datepicker/default.css">
<script type='text/javascript' src="datepicker/zebra_datepicker.js"></script>

<!--<script type="text/javascript" src="javascripts/jquery.sortElements.js"></script>-->

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
					console.log(responce);
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
	
	
	$('#start_date').Zebra_DatePicker({
		direction: false,
		pair: $('#valid_upto')
	});

	$('#end_date').Zebra_DatePicker({
		direction: 0
	});
	
	
	$("#date-filter").on('click', function(){
		var start_date = $("#start_date").val();
		var end_date = $("#end_date").val();
		
		if(start_date == ''){
			alert('Please enter start date');
			return false;
		}else if(end_date == ''){
			alert('Please enter end date');
			return false;
		}else{
			window.location.href='product-orders.php?sdate='+start_date+'&edate='+end_date+'';
		}
	});
	
	$("#search-product").on('click', function(){
		var search_txt = $(".search_txt").val();
		if(search_txt ==''){
			alert('Plesae enter search keyword');
			return false;
		}else{
			window.location.href='product-orders.php?skey='+search_txt+'';
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
	
	$('.search_keyword').keypress(function(e) {
		if(e.which == 13) {
			$(this).blur();
			$('#search-product').focus().click();
		}
	});
	
});
</script>


<div class="row">
  <div class="box col-md-12">
    <div class="box-inner" style="height:50px;">
      <div class="box-header well frmContrl" data-original-title="" style="height:48px;">

			<input type="text" name="start_date" class="dateRng" id="start_date" placeholder="Start date" value="<?php echo $sdate; ?>">
			<input type="text" name="end_date" class="dateRng" id="end_date" placeholder="End date" value="<?php echo $edate; ?>">
			<a class="btn btn-success sdate" id="date-filter" href="javascript:void(0);"><i class="glyphicon glyphicon-zoom-in icon-white"></i>View</a>
		
		 <input type="text" name="search_keyword" placeholder="Search by orderid, name, price and status" class="search_txt form-control search_keyword" style="width:350px" value="<?php echo $skey; ?>">
		 <a class="btn btn-success" id="search-product" href="javascript:void(0);"><i class="glyphicon glyphicon-zoom-in icon-white"></i>Search</a> 
		 <a class="btn btn-danger" href="product-orders.php"><i class="glyphicon glyphicon-trash icon-white"></i>Reset</a>
      </div>
    </div>
  </div>
</div>
		 

<div class="row">	
        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2><i class="glyphicon glyphicon-user"></i> All Orders</h2>
                    <?php if(!$skey){ echo "<span class=\"filterCat\">".$pages->display_jump_menu().$pages->display_items_per_page()."</span>"; }?> 
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
                    
                     <ul class="pagination pagination-centered">
						<?php
							if(!$skey){
								echo '<li>'.$pages->display_pages().'<li>';
								echo "<p class=\"paginate\">Page: $pages->current_page of $pages->num_pages</p>\n";
							}
						?>
                    </ul>
                    
                </div>
            </div>
        </div>
        <!--/span-->
    </div>
<?php include_once 'footer.php'; ?>
