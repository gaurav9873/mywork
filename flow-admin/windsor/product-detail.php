<?php include_once 'header.php';

$request_pid = $obj->DecryptClientId($_REQUEST['product_id']);
$pid = intval($request_pid);
$url = API_PATH.'product_detail/'.$pid.'';
$products = $obj->getCurl($url);
$product_detail = $products->product_detail;

$pids = $product_detail[0]->pid;
$product_name = $product_detail[0]->product_name;
$product_description = $product_detail[0]->description;
$product_short_description = $product_detail[0]->short_description;

$regular_price = $product_detail[0]->regular_price;
$large_price = $product_detail[0]->large_price;
$disscount_price = $product_detail[0]->disscount_price;

$product_code = $product_detail[0]->product_code;
$final_price = (($disscount_price <> '') ? $disscount_price : $regular_price);
$price_diff = $large_price + $final_price;
$standard_percentage = $obj->calculatePercentage($regular_price, $final_price);

//Product Image
$imgUrl = API_PATH.'product_image/'.$pid.'';
$img_arr = $obj->getCurl($imgUrl);
$product_image = $img_arr->product_image;


//Gift item
$gift_url = API_PATH.'gift_item/1';
$json_data = $obj->getCurl($gift_url);
$gift_arr = $json_data->gift_item;


if(!isset($_SESSION['cart']['products']) || !is_array($_SESSION['cart']['products'])){
    $_SESSION['cart']['products']=array();
}

if(!isset($_SESSION['cart']['gift']) || !is_array($_SESSION['cart']['gift'])){
    $_SESSION['cart']['gift']=array();
}


if(isset($_POST['submit'])){
	unset($_POST['submit']);
	$ptype = $_POST['pro_size'];
	$product_id = $_POST['product_id'];
	$quantity = $_POST['quantity'];
	$gift_items = isset($_POST['gift_items']) ? $_POST['gift_items'] : '';
	
	if(isset($_SESSION['cart']['products'][$ptype][$product_id])){ 
		$tempquan = $_SESSION['cart']['products'][$ptype][$product_id]['quantity'] + $quantity ;
		$_SESSION['cart']['products'][$ptype][$product_id] =  array('product_id'=>$product_id,'quantity'=>$tempquan);
	} else {
		//$_SESSION['cart']['products'][$ptype][$product_id]['quantity'] = $quantity;
		$_SESSION['cart']['products'][$ptype][$product_id] =  array('product_id'=>$product_id,'quantity'=>$quantity);
	}

	if(!empty($gift_items)){
		if(isset($_SESSION['cart']['gift'])){
			foreach($gift_items as $gid){
				array_push($_SESSION['cart']['gift'],$gid);
			}
		} else {
			$_SESSION['cart']['gift'] = $gift_items;
		}
	}
	//array_push($_SESSION['cart_products'], $_POST);
	header("location:cart.php");
}

?>

<script>
$(document).ready(function(){

	$(".fl-size").on('click', function(){
		var psize = $(this).text();
		$(".active-class").removeClass("active-class");
		$(this).addClass("active-class");
		$(".size").val(psize);
		if(psize == 'Standard'){
			$(".standard_price").show();
			$(".large_price").hide();
		}
		
		if(psize == 'Large'){
			$(".standard_price").hide();
			$(".large_price").show();
		}
		
	});

});


/*function share_fb(url) {
  window.open('https://www.facebook.com/sharer/sharer.php?u='+url,'facebook-share-dialog',"width=626,height=436")
}*/

</script>

<section>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-4 col-md-4">
        <div class="demo">
          <div class="item">
            <div class="clearfix prodctView">
              <ul id="image-gallery" class="gallery list-unstyled cS-hidden">
				<?php
					foreach($product_image as $pimg){
						$img_id = $pimg->img_id;
						$full_path = $pimg->full_path;
						$medium_path = $pimg->medium_path;
						$thumbnail_path = $pimg->thumbnail_path;
						echo '<li data-thumb="'.IMG_PATH.$medium_path.'"><img src="'.IMG_PATH.$medium_path.'" /> </li>';
					}
				?>	
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-8 col-md-8 productDetail">
		<form name="pfrm" id="pfrm" class="pfrm" action="" method="POST">
		<input type="hidden" class="size" name="pro_size" value="Standard">
		<input type="hidden" name="product_id" value="<?php echo $_REQUEST['product_id']; ?>">
        <h2><?php echo $product_name; ?></h2>
        <div class="price standard_price">£ <?php echo $final_price; if($disscount_price <> '') { echo '<del>£ '.$regular_price.' </del>'; }?>
        <samp>(<?php echo $standard_percentage['diff']; ?>% off)</samp></div>
        <div class="price large_price" style="display:none;">£<?php echo $price_diff; ?></div>
        <div class="flowerQyt"> Qty :
           <select name="quantity" id="quantity" class="quantity">  
				<?php 
					for($i=1; $i<=10; $i++){
						echo  '<option value="'.$i.'">'.$i.'</option>';
					}
				?>
          </select>
        </div>
        <div class="shareLinks"> <a href="javascript:void(0);"><i class="fa fa-share-alt"></i>Share</a>
          <ul>
            <li><i class="fa fa-facebook"></i>Facebook</li>
            <li><i class="fa fa-twitter"></i>Twitter</li>
          </ul>
        </div>
        <div class="flowerSize">
          <p><span>* </span>Size:</p>
			<a href="javascript:void(0);" class="fl-size active-class">Standard</a> 
			<a href="javascript:void(0);" class="fl-size">Large</a> 
			<a class="addNum">Make it Large for + £<?php echo $large_price; ?></a>
		</div>
        <div class="item giftProd">
          <p class="gftLine"><strong>Add a Gift to make it personal</strong></p>
          <ul id="content-slider" class="content-slider">
		
					<?php
						foreach($gift_arr as $post_item){
							$gift_name = $post_item->gift_name;
							$regular_price = $post_item->regular_price;
							$disccount_price = $post_item->disccount_price;
							$description = $post_item->description;
							$short_note = $post_item->short_note;
							$full_path = $post_item->full_path;
							$medium_path = $post_item->medium_path;
							$thumbnail_path = $post_item->thumbnail_path;
							$gift_order = $post_item->gift_order;
							$gift_id = $post_item->gift_id;
							$site_id = $post_item->site_id;
							
														
							echo '<li>
							<label>
							<div class="productItem"><span><img src="'.IMG_PATH.$thumbnail_path.'" alt="" /></span></div>
							<div class="chose-opt">
							<input type="checkbox" name="gift_items[]" id="gift_items" class="gift_items" value="'.$gift_id.'" />
							'.$gift_name.'</div>
							<strong>£'.$regular_price.'</strong>
							</label>
							</li>';
							
						}
					?>
          </ul>
        </div>
        <button type="submit" name="submit" class="btn btn-col btnDirectionLeft" >Add to cart </button>
      </div>
    </div>
  </div>
  </form>
</section>
<div class="clearfix"></div>
<section>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="tabs">
          <div class="tab">
            <input type="radio" name="css-tabs" id="tab-1" checked class="tab-switch">
            <label for="tab-1" class="tab-label">Discription</label>
            <div class="tab-content bgType">
             <?php echo $product_description; ?>
            </div>
          </div>
          <div class="tab">
            <input type="radio" name="css-tabs" id="tab-2" class="tab-switch">
            <label for="tab-2" class="tab-label">Size Options</label>
            <div class="tab-content bgType"><?php echo $product_short_description; ?></div>
          </div>
        </div>
        <div class="panel panel-default">
          <div class="panel-heading"> Please note that seasonal availability of individual stems may vary from those in the picture. Our professional florist may substitute flowers for a suitable alternative, similar in style, quality and value. </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include_once 'footer.php'; ?>
