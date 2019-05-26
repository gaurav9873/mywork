<?php
include_once 'header.php';

$obj =  new ConnectDb();
$cFunc = new CustomFunctions();

$count_all_orders = $obj->nicola_orders();
$count = count($count_all_orders);

$pages = new Paginator($count,9,array(30,50,100,250,'All'));
$nicola_orders = $obj->nicola_orders($pages->limit_start, $pages->limit_end);

?>


<script>

$(document).ready(function(){
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
                <h2><i class="glyphicon glyphicon-user"></i> All Orders</h2>
               <?php echo "<span class=\"filterCat\">".$pages->display_jump_menu().$pages->display_items_per_page()."</span>"; ?> 
                
                </span>
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
                            <th>Action</th>            
                        </tr>
                    </thead>
                    <tbody>

						<?php 
							foreach($nicola_orders as $post_value){
									
								$orderID = $post_value->orderID;
								$datetime = $post_value->datetime;
								$ip = $post_value->ip;
								$customerID = $post_value->customerID;
								$title = $post_value->title;
								$forename = $post_value->forename;
								$surname = $post_value->surname;
								$address1 = $post_value->address1;
								$address2 = $post_value->address2;
								$town = $post_value->town;
								$county = $post_value->county;
								$country = $post_value->country;
								$postcode = $post_value->postcode;
								$telephone = $post_value->telephone;
								$fax = $post_value->fax;
								$email = $post_value->email;
								$company = $post_value->company;
								$deliveryCompany = $post_value->deliveryCompany;
								$deliveryName = $post_value->deliveryName;
								$deliveryAddress1 = $post_value->deliveryAddress1;
								$deliveryAddress2 = $post_value->deliveryAddress2;
								$deliveryTown = $post_value->deliveryTown;
								$deliveryCounty = $post_value->deliveryCounty;
								$deliveryCountry = $post_value->deliveryCountry;
								$deliveryPostcode = $post_value->deliveryPostcode;
								$deliveryTelephone = $post_value->deliveryTelephone;
								$ccName = $post_value->ccName;
								$ccNumber = $post_value->ccNumber;
								$ccExpiryDate = $post_value->ccExpiryDate;
								$ccType = $post_value->ccType;
								$ccStartDate = $post_value->ccStartDate;
								$ccIssue = $post_value->ccIssue;
								$ccCVV = $post_value->ccCVV;
								$currencyID = $post_value->currencyID;
								$goodsTotal = $post_value->goodsTotal;
								$shippingTotal = $post_value->shippingTotal;
								$taxTotal = $post_value->taxTotal;
								$discountTotal = $post_value->discountTotal;
								$giftCertTotal = $post_value->giftCertTotal;
								$status = $post_value->status;
								$shippingMethod = $post_value->shippingMethod;
								$paymentID = $post_value->paymentID;
								$paymentName = $post_value->paymentName;
								$paymentDate = $post_value->paymentDate;
								$authInfo = $post_value->authInfo;
								$terms = $post_value->terms;
								$shippingID = $post_value->shippingID;
								$randID = $post_value->randID;
								$orderPrinted = $post_value->orderPrinted;
								$orderNotes = $post_value->orderNotes;
								$paymentNameNative = $post_value->paymentNameNative;
								$shippingMethodNative = $post_value->shippingMethodNative;
								$languageID = $post_value->languageID;
								$giftCertOrder = $post_value->giftCertOrder;
								$referURL = $post_value->referURL;
								$accTypeID = $post_value->accTypeID;
								$affiliateID = $post_value->affiliateID;
								$offerCode = $post_value->offerCode;
								$e_delivery_date = $post_value->e_delivery_date;
								$lineID = $post_value->lineID;
								$extraFieldID = $post_value->extraFieldID;
								$extraFieldName = $post_value->extraFieldName;
								$extraFieldTitle = $post_value->extraFieldTitle;
								$exvalID = $post_value->exvalID;
								$content = $post_value->content;
								$contentNative = $post_value->contentNative;
								
								$productID = $post_value->productID;
								$code = $post_value->code;
								$name = $post_value->name;
								$qty = $post_value->qty;
								$weight = $post_value->weight;
								$price = $post_value->price;
								$nameNative = $post_value->nameNative;
								$taxamount = $post_value->taxamount;
								$isDigital = $post_value->isDigital;
								$digitalFile = $post_value->digitalFile;
								$digitalReg = $post_value->digitalReg;
								$downloadID = $post_value->downloadID;
								$ooprice = $post_value->ooprice;
								$ootaxamount = $post_value->ootaxamount;
								$supplierID = $post_value->supplierID;
								$suppliercode = $post_value->suppliercode;
								
								$reg_date = strtotime($datetime); 
								$regDate = date('d-M-Y', $reg_date);
								
								 $date = str_replace('/', '-', $e_delivery_date);
								 $delivery_date =  date('d-M-Y', strtotime($date));
								 
								$payement_data = explode('&', $authInfo);
								$pdata = explode('=', $authInfo);
								 $status = isset($pdata[3]) ? $pdata[3] : '';
								//$transaction_number = str_replace('&Status','', $pdata[2]);
								$pstatus = (($status == 'Payment Confirmed') ? 'Paid' : 'Pending');
  
						?>

                        <tr>
                            <td><?php echo $orderID; ?></td>
                            <td class="center"><?php echo $regDate; ?></td>
                            <td class="center"><?php echo $delivery_date; ?></td>
                            <td class="center"><?php echo $forename; ?></td>
                            <td class="center"><?php echo $qty; ?></td>
                            <td class="center"><?php echo $price; ?></td>
                            <td class="center">
                                <span class="label-success label label-default"><?php echo $pstatus; ?></span>
                            </td>
                            
                            <td class="center">
                                <a class="" href="view-order-detail.php?oid=<?php echo $orderID; ?>" type="allorder">
                                    <span class="label-warning label label-default">View</span>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                        
                    </tbody>
                </table>

                <ul class="pagination pagination-centered">
					<?php
						echo '<li>'.$pages->display_pages().'<li>';
						echo "<p class=\"paginate\">Page: $pages->current_page of $pages->num_pages</p>\n";
					?>
                </ul>

            </div>
        </div>
    </div>
    <!--/span-->
</div>

<?php include_once 'footer.php'; ?>
