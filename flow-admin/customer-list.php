<?php
include_once 'header.php';

$shop_id = $_SESSION['shop_id'];

$obj =  new ConnectDb();
$customer_obj = new CustomFunctions();

//search string
$skey = isset($_REQUEST['skey']) ? $_REQUEST['skey'] : '';

//date value
$sdate = isset($_REQUEST['sdate']) ? $_REQUEST['sdate'] : '';
$edate = isset($_REQUEST['edate']) ? $_REQUEST['edate'] : '';


if($sdate!='' && $edate!=''){
	//Count Date Filter
	$countCustomerData = $obj->countCustomerData($shop_id, $sdate, $edate);
	$pages = new Paginator($countCustomerData,9,array(30,50,100,250,'All'));
	$all_customer = $obj->customer_date_search($shop_id, $sdate, $edate, $pages->limit_start, $pages->limit_end);
}else if($skey!=''){
	$all_customer = $obj->search_customers($shop_id, $skey);
}else{
	$count = $obj->count_customer_lists($shop_id);
	$pages = new Paginator($count,9,array(30,50,100,250,'All'));
	$all_customer = $obj->customer_lists($shop_id, $pages->limit_start, $pages->limit_end);
}


?>

<link rel="stylesheet" type='text/css' href="datepicker/default.css">
<script type='text/javascript' src="datepicker/zebra_datepicker.js"></script>
<script>
$(document).ready(function(){
	
	$('#start_date').Zebra_DatePicker({
		direction: false,
		pair: $('#valid_upto')
	});

	$('#end_date').Zebra_DatePicker({
		//direction: 1
	});
	
	
	$("#date-filter").on('click', function(){
		var start_date = $("#start_date").val();
		var end_date = $("#end_date").val();
		
		if(start_date == '' && end_date == ''){
			alert("Please enter start date and enddate");
			return false;
		}else{
			window.location.href='customer-list.php?sdate='+start_date+'&edate='+end_date+'';
		}
		
	});
	
	$("#search-product").on('click', function(){
		var search_keyword = $(".search_txt").val();
		if(search_keyword!=''){
			window.location.href='customer-list.php?skey='+search_keyword+'';
		}
	});
	
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

		<form name="frm" id="frm" action="" method="post">
			<input type="text" name="start_date" value="<?php echo $sdate; ?>" id="start_date" placeholder="Start Date" class="sdate dateRng">
			<input type="text" name="end_date" value="<?php echo $edate; ?>" id="end_date" placeholder="End Date" class="edate dateRng">
			<a class="btn btn-success sdate" id="date-filter" href="javascript:void(0);"><i class="glyphicon glyphicon-zoom-in icon-white"></i>View</a>
		</form>
		
		 <input type="text" name="search_keyword" class="search_txt form-control search_keyword" value="">
		 <a class="btn btn-success" id="search-product" href="javascript:void(0);"><i class="glyphicon glyphicon-zoom-in icon-white"></i>Search</a> 
		 <a class="btn btn-danger" href="customer-list.php"><i class="glyphicon glyphicon-trash icon-white"></i>Reset</a>
      </div>
    </div>
  </div>
</div>


<div class="row">
	        <div class="box col-md-12">
		
            <div class="box-inner">
                <div class="box-header well" data-original-title="">		
                    <h2>All Customers</h2>
                     <?php if(!$skey){ echo "<span class=\"filterCat\">".$pages->display_jump_menu().$pages->display_items_per_page()."</span>"; } ?> 
                     <?php if($sdate!='' && $edate!=''){ ?> 
						<a href="export.php?sdate=<?php echo $sdate; ?>&edate=<?php echo $edate; ?>" target="_blank" id="export" style="float:right"><span class="label-success label label-default">Export Csv</span></a>
                    <?php }elseif($skey!=''){ ?>
						<a href="export.php?skey=<?php echo $skey; ?>" id="export" target="_blank" style="float:right"><span class="label-success label label-default">Export Csv</span></a>
					<?php }else{ ?>
						<a href="export.php" id="export" target="_blank" style="float:right"><span class="label-success label label-default">Export Csv</span></a>
					<?php } ?>
                </div>
                <div class="box-content">
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>Username</th>
                            <th>Lastname</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Primary Address</th>
                            <th>Customer ID</th>
                            <th>Date registered</th>
                            <th>Date Time</th>
                            <th>IP Address</th>
                        </tr>
                        </thead>
                        <tbody>
							
							<?php 
								foreach($all_customer as $val){
									$cust_id = $val['id'];
									$user_email = $val['user_emailid'];
									$user_first_name = $val['user_first_name'];
									$user_last_name = $val['user_last_name'];
									$phone_number = $val['user_phone'];
									$primary_address = $val['primary_address'];
									$customer_id = $val['customer_id'];
									$reg_date = date("d-M-Y", strtotime($val['reg_date']));
									$created_date = $val['created_date'];
									$created_ip = $val['created_ip'];
							?>
							
							<tr>
								<td><?php echo $user_first_name; ?></td>
								<td><?php echo $user_last_name; ?></td>
								<td><?php echo $phone_number; ?></td>
								<td><?php echo $user_email; ?></td>
								<td><?php echo $primary_address; ?></td>
								<td><?php echo $customer_id; ?></td>
								<td class="center"><?php echo $reg_date; ?></td>
								<td class="center"><?php echo $created_date; ?></td>
								<td class="center"><?php echo $created_ip; ?></td>
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
    </div>
<?php include_once 'footer.php'; ?>
