<div class="col-sm-2 col-lg-2">
	<div class="sidebar-nav">
		<div class="nav-canvas">
			<div class="nav-sm nav nav-stacked">

			</div>
			<ul class="nav nav-pills nav-stacked main-menu">
				<li class="nav-header">Main</li>
				<!--<li><a class="ajax-link" href="index.php"><i class="glyphicon glyphicon-home"></i><span> Dashboard</span></a></li>-->
				<?php if($_SESSION['user_level'] == 1 ){ ?>
				<li><a class="ajax-link" href="upcoming-orders.php"><i class="glyphicon glyphicon-list"></i><span> upcoming orders</span></a></li>
				<li><a class="ajax-link" href="product-orders.php"><i class="glyphicon glyphicon-list"></i><span> All Orders</span></a></li>
                <li><a class="ajax-link" href="unprocessed-orders.php"><i class="glyphicon glyphicon-list"></i><span> Unprocessed orders</span></a></li>
				<?php if($_SESSION['shop_id'] == 3){ ?>
					<li><a class="ajax-link" href="nicola-orders.php"><i class="glyphicon glyphicon-list"></i><span> Nicola Orders</span></a></li>
				<?php } ?>
				<li><a class="ajax-link" href="customer-list.php"><i class="glyphicon glyphicon-list"></i><span> All Customers</span></a></li>
				<li><a class="ajax-link" href="add-domain.php"><i class="glyphicon glyphicon-edit"></i><span> Add Domain</span></a></li>
				<li><a class="ajax-link" href="add-useres.php"><i class="glyphicon glyphicon-edit"></i><span> Add Useres</span></a></li>
				<li><a class="ajax-link" href="add-category.php"><i class="glyphicon glyphicon-edit"></i><span> Add Categories</span></a></li>
				<li><a class="ajax-link" href="all-categories.php"><i class="glyphicon glyphicon-list"></i><span> All Categories</span></a></li> 
				<li><a class="ajax-link" href="add-product.php"><i class="glyphicon glyphicon-edit"></i><span> Add Products</span></a></li>
				<li><a class="ajax-link" href="products-list.php"><i class="glyphicon glyphicon-list"></i><span> All Products</span></a></li>
				<li><a class="ajax-link" href="duplicate-list.php"><i class="glyphicon glyphicon-list"></i><span> Duplicate Products</span></a></li>
				<li><a class="ajax-link" href="unassign-list.php"><i class="glyphicon glyphicon-list"></i><span> Unassign site Product</span></a></li>
				<li><a class="ajax-link" href="add-pages.php"><i class="glyphicon glyphicon-edit"></i><span> Add Pages</span></a></li>
				<li><a class="ajax-link" href="all-pages.php"><i class="glyphicon glyphicon-list"></i><span> All Pages</span></a></li>
				<li><a class="ajax-link" href="add-gift.php"><i class="glyphicon glyphicon-edit"></i><span> Add Gifts</span></a></li>
				<li><a class="ajax-link" href="all-gifts.php"><i class="glyphicon glyphicon-gift"></i><span> All Gifts</span></a></li>
				<li><a class="ajax-link" href="shipping.php"><i class="glyphicon glyphicon-edit"></i><span> Shipping</span></a></li>
				<li><a class="ajax-link" href="shipping-list.php"><i class="glyphicon glyphicon-list"></i><span> All Shipping Codes</span></a></li>
				<li><a class="ajax-link" href="add-coupon.php"><i class="glyphicon glyphicon-edit"></i><span> Add Coupon</span></a></li>
				<li><a class="ajax-link" href="all-coupons.php"><i class="glyphicon glyphicon-list"></i><span> All Coupons</span></a></li>
				<li><a class="ajax-link" href="add-gallery-category.php"><i class="glyphicon glyphicon-edit"></i><span> Gallery Category</span></a></li>
				<li><a class="ajax-link" href="all-gallery-category.php"><i class="glyphicon glyphicon-list"></i><span> All Gallery Category</span></a></li>
				<li><a class="ajax-link" href="add-galleries.php"><i class="glyphicon glyphicon-edit"></i><span> Add Gallery Images</span></a></li>
				<li><a class="ajax-link" href="gallery-list.php"><i class="glyphicon glyphicon-signal"></i><span> Gallery List</span></a></li>
				<li><a class="ajax-link" href="add-slider.php"><i class="glyphicon glyphicon-edit"></i><span> Add Slider</span></a></li>
				<li><a class="ajax-link" href="all-slider.php"><i class="glyphicon glyphicon-film"></i><span> All Slider</span></a></li>
				<li><a class="ajax-link" href="add-testimonial.php"><i class="glyphicon glyphicon-edit"></i><span> Add Testimonial</span></a></li>
				<li><a class="ajax-link" href="all-testimonial.php"><i class="glyphicon glyphicon-list"></i><span> All Testimonial</span></a></li>
				<li><a class="ajax-link" href="add-holiday-dates.php"><i class="glyphicon glyphicon-calendar"></i><span> Holiday Calender</span></a></li>
				<li><a class="ajax-link" href="holiday-lists.php"><i class="glyphicon glyphicon-list"></i><span> All Holidays</span></a></li>
				<li><a class="ajax-link" href="reports.php"><i class="glyphicon glyphicon-list"></i><span> All Reports</span></a></li>
				<?php }else if(isset($_SESSION['user_level']) == 2){ ?>
				<li><a class="ajax-link" href="upcoming-orders.php"><i class="glyphicon glyphicon-list"></i><span> upcoming orders</span></a></li>
				<?php }else{} ?>
			</ul>
			<!--<label id="for-is-ajax" for="is-ajax"><input id="is-ajax" type="checkbox"> Ajax on menu</label>-->
		</div>
	</div>
</div>
<div style="display: none"><?php print_r($_SESSION); ?></div>
