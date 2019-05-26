<?php
include_once 'header.php';

$obj =  new ConnectDb();
$cFunc = new CustomFunctions();

$shop_id = $_SESSION['shop_id'];
$cat_order = $obj->parent_category_order();
$cat_obj = $cat_order[0]->cat_id;

$unassign_products = $obj->unassign_product($shop_id, 0,1000);
$count_unassign_products = count($unassign_products);

$count_rows = $obj->count_row('op2mro9899_products');
$parent_category = $obj->get_parent_category(1);

$child_id = isset($_REQUEST['cat_id']) ? $_REQUEST['cat_id'] : '';
$parent_id = isset($_REQUEST['parent_id']) ? $_REQUEST['parent_id'] : '';
$cat_ids = (($child_id == 0) ? $parent_id : $child_id);

$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$baseurl = basename($actual_link);
$cat_id = (($baseurl == 'products-list.php') ? $cat_obj : $cat_ids);

$count_cat_products = $obj->count_cat_product($cat_id);

$search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';


if(isset($_REQUEST['cat_id'])){
	$pages = new Paginator($count_cat_products, 9, array(25,50,100,250,'All'));
	$products = $obj->get_product_by_cat_id($shop_id, $cat_id, $pages->limit_start, $pages->limit_end);

}else if(isset($_REQUEST['search_key'])){
	$products = $obj->search_product($search_key);
}else if(isset($_REQUEST['uncat_product'])){
	$pages = new Paginator($count_unassign_products, 9, array(25,50,100,250,'All'));
	$products = $obj->unassign_product($shop_id, $pages->limit_start, $pages->limit_end);
}else{
	$pages = new Paginator($count_cat_products, 9, array(25,50,100,250,'All'));
	$products = $obj->get_product_by_cat_id($shop_id, $cat_obj, $pages->limit_start, $pages->limit_end);
}
?>
<script>
$(document).ready(function(){
	
	$(".pcat").on('change', function(){
		var cart_vals = $(".pcat").val();
		if(cart_vals!=''){
			$.ajax({
				url:'action/product.php',
				type:'post',
				data:{action:'parent_cat', cat_id:cart_vals},
				cache:false,
				beforeSend:function(){
				},
				complete:function(){
				},
				success:function(responce){
					var data = $.parseJSON(responce);
					$('.child_cat').empty();
					$('.child_cat').append('<option value="0" >Please select child category</option>');
					$.each(data, function(i, item){
						$('.child_cat').append('<option value="'+item.cat_id+'" >'+item.category_name+'</option>');
					});
				},
			});
		}
	});
	
		
	$("#view-product").on('click', function(){
		var child_val = $(".child_cat").val();
		var parent_val = $(".pcat").val();
			if(child_val!=''){
				window.location.href='products-list.php?cat_id='+child_val+'&parent_id='+parent_val+'';
			}
	});
	
	
	$('.search_string').keypress(function(e) {
		if(e.which == 13) {
			$(this).blur();
			$('#search-product').focus().click();
		}
	});
	
	
	$("#search-product").on('click', function(){
		var search_txt = $(".search_txt").val();
		if(search_txt == ''){
			alert("please enter search keyword");
			return false;
		}else{
			window.location.href='products-list.php?search_key='+search_txt+'';
		}
	});
	
	$(".delete-product").on('click', function(){
		var pid = $(this).data('pids');
		var checkstr =  confirm('are you sure you want to remove this?');
		if(checkstr == true){
			if(pid!=''){
				$.ajax({
					url:'action/product.php',
					type:'post',
					data:{action:'delete_product', pids:pid},
					cache:false,
					beforeSend:function(){
					},
					complete:function(){
					},
					success:function(del_resp){
						window.location.href='';
					}
				});
			}
		}
	});
	
});

</script>

<div class="row">
  <div class="box col-md-12">
    <div class="box-inner" style="height:50px;">
      <div class="box-header well frmContrl" data-original-title="" style="height:48px;">

        <select name="parent_category" id="parent-category" class="pcat form-control">
        <option value="">Please select category</option>
        <?php 
        foreach($parent_category as $vals){
			$cat_id = $vals->cat_id;
			$category_name = $vals->category_name;
			$selected = (($cat_id == $parent_id) ? 'selected' : '');
			echo '<option value="'.$cat_id.'" '.$selected.'>'.$category_name.'</option>';
        }
        ?>
        </select>
        
        <select name="child_category" id="child_category" class="child_cat form-control">
            <option value="0">Please select child category</option>
            <?php 
				if($child_id!=0){
					echo '<option value="'.$child_id.'" selected>'.$obj->getCategoryName($child_id).'</option>';
				}
            ?>
        </select>
        
        <a class="btn btn-success" id="view-product" href="#"><i class="glyphicon glyphicon-zoom-in icon-white"></i>View</a>
        
        <?php if(isset($_REQUEST['cat_id'])){
			echo '<a class="btn btn-info" href="product-order.php?cat='.$child_id.'&parent_id='.$parent_id.'"><i class="glyphicon glyphicon-edit icon-white"></i>Sort Order</a>';
        }
        ?> 
        
        <input type="text" name="search_keyword" class="search_txt form-control search_string" value="">  
              
        <a class="btn btn-success" id="search-product" href="javascript:void(0);"><i class="glyphicon glyphicon-zoom-in icon-white"></i>Search</a> 
        
        <a class="btn btn-success" id="search-product" href="products-list.php?uncat_product=1"><i class="glyphicon glyphicon-zoom-in icon-white"></i>Unassigned</a> 
        
        <a class="btn btn-danger" href="products-list.php"><i class="glyphicon glyphicon-trash icon-white"></i>Reset</a> 

      </div>
    </div>
  </div>
</div>


<div class="row">
  <div class="box col-md-12">
    <div class="box-inner">
      <div class="box-header well" data-original-title="" style="height:40px;">
        <h2>All Products</h2>
        <?php if(!$search_key){ echo "<span class=\"filterCat\">".$pages->display_jump_menu().$pages->display_items_per_page()."</span>"; }?> 
      </div>
      <div class="box-content">
        <table class="table table-bordered table-striped table-condensed">
          <thead>
            <tr>
              <th>Product Name</th>
              <th>Short Description</th>
              <th>Price</th>
              <th>Disscount Price</th>
              <th>Product Code</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="pdata">
				<?php
				foreach($products as $post_products){
					$pid = $post_products['pid'];
					$product_name  = $post_products['product_name'];
					$description = substr($post_products['description'],0,50);
					$short_description = substr($post_products['short_description'],0,50);
					$regular_price = $post_products['regular_price'];
					$disscount_price = $post_products['disscount_price'];
					$product_code = $post_products['product_code'];
					//$created_date = $post_products['created_date'];
					//$created_ip = $post_products['created_ip'];
				?>
            <tr>
              <td><?php echo $product_name; ?></td>
              <td class="center"><?php echo strip_tags($short_description).'...'; ?></td>
              <td class="center"><?php echo $regular_price; ?></td>
              <td class="center"><?php echo $disscount_price; ?></td>
              <td class="center"><?php echo $product_code; ?></td>
              <td class="center"><a href="edit-product.php?pid=<?php echo $cFunc->EncryptClientId($pid); ?>"><span class="label-success label label-default">Edit</span></a> 
              <a href="javascript:void(0);" class="delete-product" data-pids="<?php echo $pid; ?>"><span class="label-default label label-danger">Delete</span></a></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <ul class="pagination pagination-centered">
			<?php
				if(!$search_key){
					echo '<li>'.$pages->display_pages().'<li>';
					echo "<p class=\"paginate\">Page: $pages->current_page of $pages->num_pages</p>\n";
				}
			?>
        </ul>
      </div>
    </div>
  </div>
</div>
<?php include_once 'footer.php' ?>
