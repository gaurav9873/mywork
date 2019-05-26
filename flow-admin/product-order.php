<?php
include_once 'header.php';

$obj =  new ConnectDb();
$custom_obj = new CustomFunctions();
$shop_id = $_SESSION['shop_id'];

//$cat_id = intval($_REQUEST['cat']);
$child_id = isset($_REQUEST['cat']) ? $_REQUEST['cat'] : '0';
$parent_id = isset($_REQUEST['parent_id']) ? $_REQUEST['parent_id'] : '';
$cat_id = (($child_id == 0) ? $parent_id : $child_id);

$products = $obj->get_product_by_cat_id($shop_id, $cat_id,0, 999);
?>
<script src="https://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://necolas.github.com/normalize.css/2.0.1/normalize.css"> 
<script>
$(document).ready(function(){
	$( "#sortable" ).sortable();
	
	
	//~ $(document).on('click','#save-reorder',function(){
		//~ var list = new Array();
		//~ $('#sortable').find('.ui-state-default').each(function(){
			//~ var id=$(this).attr('data-id');    
			//~ list.push(id);
		//~ });
		//~ var data=JSON.stringify(list);
		//~ 
		//~ $.ajax({
			//~ url:'action/category.php',
			//~ type:'POST',
			//~ data: {action:'sort_category',data:data},
			//~ cache:false,
			//~ beforeSend:function(){
			//~ },
			//~ complete:function(){
			//~ },
			//~ success:function(resp){
				//~ if(resp=='saved'){
					//~ window.location.href='all-categories.php';
				//~ }
			//~ }
		//~ });
		//~ 
	//~ });
	
	
	$("#save-order").on("click", function(){
		var list = new Array();
		$('#sortable').find('.ui-state-default').each(function(){
			var id=$(this).attr('data-id');
			list.push(id);
		});
		var data = JSON.stringify(list);
		$.ajax({
			url:'action/product.php',
			type:'post',
			data:{action:'sort_product', data_val:data},
			cache:false,
			beforeSend:function(){
			},
			complete:function(){
			},
			success:function(responce){
				window.location.href='products-list.php?cat_id=<?php echo $child_id; ?>&parent_id=<?php echo $parent_id;?>';
			}
		});
	});
});
</script>
<div class="box col-md-6">
	<div class="box-inner">
		<div class="box-header well" data-original-title="">
			<h2>Sort Products</h2>
		</div>
		<div class="box-content">
			<a class="btn btn-success" id="save-order" href="javascript:void(0);"><i class="glyphicon glyphicon-zoom-in icon-white"></i>Save Order</a>
			<table class="table">
				<thead>
				<tr>
					<th>Product Name</th>
				</tr>
				</thead>
				<tbody id="sortable">
					<?php 
						foreach($products as $pval){
							$id = $pval['id'];
							$pid = $pval['pid'];
							$product_name = $pval['product_name'];
					?>
						<tr class="ui-state-default" data-id="<?php echo $id; ?>">
							<td><?php echo $product_name; ?></td>
						</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php include_once 'footer.php'; ?>
