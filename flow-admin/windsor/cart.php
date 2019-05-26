<?php 
include_once 'header.php'; 

$root_url = SITE_URL;
if(empty($_SESSION['cart']['products'])){
	header("location:$root_url");
}


$array_val = array_count_values($_SESSION['cart']['gift']);
ksort($array_val);


$product_array = array();
foreach($_SESSION['cart']['products'] as $size=>$valarr){
	foreach($valarr as $data){
		$product_attributes = array('size'=>$size,'product_id'=>$data['product_id'],'quantity'=>$data['quantity']);
		array_push($product_array,$product_attributes);
	}	
}


if(!empty($_GET["action"])) {
	$action = $_GET['action'];
	switch($action){
		case "empty":
			unset($_SESSION["cart"]["products"]);
			unset($_SESSION["cart"]["gift"]);
			unset($_SESSION['c_code']);
			header("location:$root_url");
		break;
	}
}


//Gift item
$gift_api = API_PATH.'gift_item/'.SITE_ID;
$json_data_val = $obj->getCurl($gift_api);
$gift_array = $json_data_val->gift_item;


?>

<script>
$(document).ready(function(){
	
	$(".remove_product").on('click', function(){
		var pid = this.id;
		var trid = $(this).closest('tr').data('key');
		var pkey = $(this).closest('tr').data('product-key');
		var ptype = $(this).closest('tr').data('type');
		var checkstr =  confirm('are you sure you want to remove this?');
		if(checkstr==true){
			$.ajax({
				url:'action/cart.php?action=remove_item',
				type:'POST',
				data:{action:'remove_item', item_id:trid, pkey:pkey, ptype:ptype},
				cache:false,
				beforeSend:function(){
				},
				complete:function(){
				},
				success:function(resp){
					window.location.href='';
				}
			});
		}
	});
	
	
	$(".remove_gift").on('click', function(){
		var id = $(this).data('gift-key');
		var check = confirm('are you sure you want to remove this?');
		if(check==true){
			$.ajax({
				url:'action/cart.php',
				type:'POST',
				data:{action:'remove_gift', gift_id:id},
				cache:false,
				beforeSend:function(){
				},
				complete:function(){
				},
				success:function(req){
					window.location.href='';
				}
			});
		}
	});
	
	
	$(".product_quantity").on('change', function(){
		var qty = $(this).val();
		var type = $(this).closest('tr').data('type');
		var pro_key = $(this).closest('tr').data('product-key');
		var pro_type = $(this).closest('tr').data('type');
		
		if(qty!=''){
			$.ajax({
				url:'action/cart.php',
				type:'POST',
				data:{action:'update_qty', pqty:qty, proKey:pro_key, proType:pro_type},
				cache:false,
				beforeSend:function(){
				},
				complete:function(){
				},
				success:function(res_qty){
					window.location.href='';
				}
			});
		}
	});
	
	
	$(".gift_quantity").on('change', function(){
		
		var giftQty = $(this).val();
		var gift_id = $(this).data('id');
		
		if(giftQty!=''){
			$.ajax({
				url:'action/cart.php',
				type:'POST',
				data:{action:'giftqty', gift_quantity:giftQty, giftID:gift_id},
				cache:false,
				beforeSend:function(){
				},
				complete:function(){
				},
				success:function(responce){
					window.location.href='';
				}
			});
		}
	});
	

});
</script>

<section>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <h2>Your Shopping Cart</h2>
        <div class="cardImg"><img src="images/paymoney.png" alt="" /></div>
        <div class="cartTable">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-bordered table-striped">
            <tr>
              <th width="10%" scope="col">Product</th>
              <th width="30%" scope="col">Product Name</th>
              <th width="15%" scope="col">Item Code</th>
              <th width="10%" scope="col">Qty.</th>
              <th width="10%" scope="col">Unit Price</th>
              <th width="15%" scope="col">Total</th>
              <th width="10%" scope="col">&nbsp;</th>
            </tr>
            
            <?php
				$product_price = array();
				$gift_price = array();
				foreach($product_array as $key => $val){
					$product_size = strtolower($val['size']);
					$pid = $obj->DecryptClientId($val['product_id']);
					$qty = $val['quantity'];
					
					//Product Api
					$product_api = API_PATH.'product_cart/'.$pid.'';
					$post_product = $obj->getCurl($product_api);
					$productPid = $post_product->pid;
					$product_name = $post_product->product_name;
					$regular_price = $post_product->regular_price;
					$large_price = $post_product->large_price;
					$disscount_price = $post_product->disscount_price;
					$product_code = $post_product->product_code;
					$img_id = $post_product->img_id;
					$thumbnail_path = $post_product->thumbnail_path;
					
					$price = (($disscount_price <> '') ? $disscount_price : $regular_price);
					$final_price = (($product_size == 'large') ? $price+$large_price : $price);
					$quantity_price = $final_price*$qty;
					array_push($product_price, $quantity_price);
            ?>
            <tr data-key="<?php echo $key; ?>" data-product-key="<?php echo $val['product_id']; ?>" data-type="<?php echo $product_size; ?>">
              <td>
              <img src="<?php echo IMG_PATH.$thumbnail_path; ?>" width="606" height="487" class="products" /></td>
              <td><strong><?php echo $product_name; ?></strong><br />
                <strong>Size:</strong> <?php echo $product_size; ?><br />
               </td>
              <td><?php echo $product_code; ?></td>
              <td>
				  <select name="product_quantity" class="product_quantity" id="product_quantity">
						<?php echo $obj->product_quantity($qty); ?>
					</select>
              </td>
              <td>£ <?php echo $final_price; ?></td>
              <td>£<?php echo $quantity_price; ?></td>
              <td><button class="remove_product" id="remove_product">Remove</button></td>
            </tr>
            <?php } 
            
				foreach($array_val as $key => $val){
					
					$gift_url = API_PATH.'gifts/'.$key.'';
					$json = $obj->getCurl($gift_url);
					$gifts = $json->post_gift;
					foreach($gifts as $k => $gift_val){
						$gift_name = $gift_val->gift_name;
						$gift_regular_price = $gift_val->regular_price;
						$gift_disccount_price = $gift_val->disccount_price;
						$short_note = $gift_val->short_note;
						$description = $gift_val->description;
						$gift_thumb = $gift_val->thumbnail_path;
						$total_qty = $val*$gift_regular_price;
						array_push($gift_price, $total_qty);
            ?>
                       
            <tr>
              <td><img src="<?php echo IMG_PATH.$gift_thumb; ?>" width="606" height="487" class="products" /></td>
              <td><strong>Gift Name: <?php echo $gift_name; ?></strong></td>
              <td></td>
              <td>
					<select name="gift_quantity" id="gift_quantity" class="gift_quantity" data-id="<?php echo $key; ?>">
						<?php echo $obj->product_quantity($val); ?>
					</select>
              </td>
              <td>£<?php echo $gift_regular_price; ?></td>
              <td>£<?php echo $total_qty; ?></td>
              <td><button class="remove_gift" id="remove_gift" data-gift-key="<?php echo $key; ?>">Remove</button></td>
            </tr>
            <?php } }
            
				$product_cart = array_sum($product_price);
				$gift_cart = array_sum($gift_price);
				$total_price = $product_cart+$gift_cart;
            ?>

            <tr class="tatalPrice">
              <td colspan="4">&nbsp;</td>
              <td>Sub. Totla (excluding delivery charges)</td>
              <td><strong>£ <?php echo $total_price; ?></strong></td>
              <td></td>
            </tr>
 
          </table>
        </div>
			<!--<button type="submit" class="btn btn-col btnDirection">Checkout</button>-->
			<a href="checkout-login.php" class="btn btn-col btnDirection">Checkout</a>
			<a href="<?php echo SITE_URL; ?>" class="btn btn-col2 btnDirectionLeft">Continue shopping </a>
			<a href="cart.php?action=empty" class="btn btn-col2 btnDirectionLeft" style="margin-left:15px;">Delete Cart</a>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <h2>Add a Gift to make it personal</h2>
        <ul id="content-slider" class="content-slider foo">
          <!--<form name="gifFrm" id="gifFrm" class="gift-frm" action="" method="post">-->
				<?php
					$count = 1;
					foreach($gift_array as $post_val){ 
						
						$giftIds = $obj->EncryptClientId($post_val->gift_id);
						//$gID = $post_val->id;
						$giftName = $post_val->gift_name;
						$regularPrice = $post_val->regular_price;
						$gidtDescription = $post_val->description;
						$shortNote = $post_val->short_note;
						$thumbnail_img = $post_val->medium_path;
						$selected = (($count == 1) ? 'selected' : '');
						echo '<li>
								<label>
								<div class="productItem"><span class="'.$selected.'"><img src="'.IMG_PATH.$thumbnail_img.'" alt="" /></span></div>
								<div class="chose-opt">
								<input name="gift_items" value="'.$giftIds.'" class="checkbox-class"  type="checkbox" />'.$giftName.'</div>
								<strong>£'.$regularPrice.'</strong>
								</label>
						</li>';
					$count++; }
				?>
			<!--</form>-->
        </ul>
      </div>
    </div>
  </div>
</section>

<script>
$(document).ready(function(){
	
	$(".checkbox-class").on('change', function(){
		 if(this.checked) {
				var chk_disabled = $(".checkbox-class").not(this).prop('disabled', true);
				var chkval = $(this).val();
				if(chkval!=''){
					$.ajax({
						url:'action/cart.php',
						type:'POST',
						data:{action:'add_gift', giftKey:chkval},
						cache:false,
						beforeSend:function(){
						},
						complete:function(){
						},
						success:function(responce){
							window.location.href='';
						}
					});
				}
			}
	});
	
});

</script>

<?php include_once 'footer.php'; ?>
