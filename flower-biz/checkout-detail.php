<?php 
include_once 'header.php'; 
?>
<section>
	<div class="container-fluid"> 
        <div class="row">    		
            <div class="col-lg-12">             	                       
                <h2>your checkout summary </h2>	                
                <div class="requiredText"><span>*</span>Required</div> 
             	
             	<?php
					$ptotal = 0;
					$gtotal = 0;
					foreach($_SESSION['cart_products'] as $key => $post_val){
						$quantity = $post_val['quantity'];
						$gift_items = $post_val['gift_items'];
						$pro_size = $post_val['pro_size'];
						$product_id = $post_val['product_id'];
						$delivery_date = $post_val['delivery_date'];
						$card_text = $post_val['card_text'];
						$user_instruction = $post_val['user_instruction'];
						$date =  date("l jS \of F Y", strtotime($delivery_date));
						
						//product api
						$product_url = API_PATH.'product_cart/'.$product_id.'';
						$products = $obj->getCurl($product_url);
						$product_name = $products->product_name;
						$standard_price = $products->regular_price;
						$large_price = $products->large_price;
						$medium_path = $products->medium_path;
						$thumbnail_path = $products->thumbnail_path;
						$product_price = (($pro_size == 'Standard') ? $standard_price : $large_price);
						$product_total = $product_price*$post_val['quantity'];
						$ptotal +=$product_total;
						
						//gift api
						$gift_items = implode(",", array_keys($post_val['gift_items']));
						$gift_url = API_PATH.'gifts/'.$gift_items.'';
						$json = $obj->getCurl($gift_url);
						$json_data = isset($json->gifts) ? $json->gifts : '';
             	?>
             	
             	<div class="billingInformaion">                       
                     <div class="checkoutSummary">
                        <h4>your Order 1</h4>
                        <figure class="imgSec"><img src="images/reviewPageImg.jpg" alt="flower image" /></figure>
                        <section class="checkoutDetail">
                            <blockquote class="productAndGift">
                                <div class="productSec">
                                    <h5>Product</h5>
                                    <samp><?php echo $product_name; ?></samp>
                                    <span>£<?php echo $product_total; ?></span>
                                    <div class="rgularColour">Regular</div>
                                </div>
                            </blockquote>                        
                            <blockquote class="productAndGift">
                                <?php
									foreach($json_data as $key=>$postData){
										$gift_id = $postData->id;
										$gift_name = $postData->gift_name;
										$gift_price = $postData->regular_price;
										$disccount_price = $postData->disccount_price;
										$qty = $post_val['gift_items'][$gift_id];
										$giftTotal = $qty*$gift_price;
										$gtotal += $giftTotal;
										
                                ?>
                                <div class="giftSec">
                                    <h5>Gift</h5>
                                    <samp><?php echo $gift_name; ?></samp>
                                    <span>£<?php echo $giftTotal; ?></span>
                                </div>
                                <?php } ?>
                            </blockquote>
                            <div class="totalAmnt">
                                <span>Total Amount</span>
                                <strong>£<?php echo $ptotal+$gtotal; ?></strong>
                            </div>
                            <div class="dateAndCalander">
                                <div class="dates"><?php echo $date; ?> </div>
                                <div class="calander"><a href="javascript:void(0)"><i class="fa fa-calendar"></i> (Change Date)</a></div>                            
                            </div>
                        </section>
                    </div>                    
                     <div class="billingform">
                        <form> 
                              <div class="billInformation">                              	
                        		 <h4>Enter your delivery information</h4>  
                                  <div class="form-group">
                                    <label for="title"><span>*</span>Title :</label>
                                    <input type="text" class="form-control" id="title">
                                  </div>
                                  <div class="form-group">
                                    <label for="f-name"><span>*</span>First Name :</label>
                                    <input type="text" class="form-control" id="f-name">
                                  </div>
                                  <div class="form-group">
                                    <label for="l-name"><span>*</span>Last Name :</label>
                                    <input type="text" class="form-control" id="l-name">
                                  </div>
                                  
                                  <div class="form-group">
                                    <label for="mobile"><span>*</span>Mobile :</label>
                                    <input type="text" class="form-control" id="mobile">
                                  </div>
                                  <div class="form-group">
                                    <label for="telephone">Telephone :</label>
                                    <input type="text" class="form-control" id="telephone">
                                  </div>
                                  <div class="form-group">
                                    <label for="fax">Fax :</label>
                                    <input type="text" class="form-control" id="fax">
                                  </div>
                                  <div class="form-group">
                                    <label for="email">Email :</label>
                                    <input type="text" class="form-control" id="email">
                                  </div>
                                  <div class="form-group">
                                    <label for="city"><span>*</span>City :</label>
                                    <input type="text" class="form-control" id="city">
                                  </div>
                                  <div class="form-group">
                                    <label for="postcode"><span>*</span>Postcode :</label>
                                    <input type="text" class="form-control" id="postcode">
                                  </div>
                                  <div class="form-group">
                                    <label for="country "><span>*</span>Country  :</label>
                                    <input type="text" class="form-control" id="country">
                                  </div>
                                  <div class="form-group">
                                    <label for="country "><span>*</span>Country  :</label>
                                  	<strong> United Kingdom </strong>
                                  </div>
                                  
                                  <div class="form-group">
                                    <label for="Address1"><span>*</span>Address Line1 :</label>
                                    <input type="text" class="form-control" id="Address1">
                                  </div>     
                                  <div class="form-group">
                                    <label for="address2">Address Line2 :</label>
                                    <input type="text" class="form-control" id="address2">
                                  </div>  
                              </div> 
                              <div class="cardMassages">
                              	<h4>Enter a Card Message</h4>  
                              	  <div class="form-group">
                                    <label><span>*</span>Title :</label>
                                       <select class="form-control" >
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                      </select>
                                  </div>
                                  <div class="form-group">
                                    <label for="messages">Your Personal messages(Max 200 Characters) :</label>
                                    <textarea class="form-control" rows="3" id="messages"><?php echo $card_text; ?></textarea>
                                  </div>
                                  <div class="form-group">
                                    <label for="instructions">Please enter any delivery instructions(Max 200 Characters) :</label>
                                    <textarea class="form-control" rows="3" id="instructions"><?php echo $user_instruction; ?></textarea>
                                  </div>
                              </div>                         
                         </form> 
                      </div>
                  </div>
                  <?php } ?>
                  
                
                <button type="submit" class="btn btn-col btnDirection" onclick="window.location.href='checkout'">Proceed to Billing </button>
                <button type="submit" class="btn btn-col2 btnDirectionLeft">My Cart</button> 
            </div>
    	</div>
    </div>
</section>

<?php include_once 'footer.php'; ?>
