<?php
include_once 'header.php';

$obj =  new ConnectDb();
$cFunc = new CustomFunctions();

$order_id = intval($_GET['oid']);
$nicola_order_detail = $obj->nicola_order_detail($order_id);
$order_detail = $nicola_order_detail[0];

$orderID = $order_detail->orderID;
$datetime = $order_detail->datetime;
$ip = $order_detail->ip;
$customerID = $order_detail->customerID;
$title = $order_detail->title;
$forename = $order_detail->forename;
$surname = $order_detail->surname;
$address1 = $order_detail->address1;
$address2 = $order_detail->address2;
$town = $order_detail->town;
$county = $order_detail->county;
$country = $order_detail->country;
$postcode = $order_detail->postcode;
$telephone = $order_detail->telephone;
$fax = $order_detail->fax;
$email = $order_detail->email;
$company = $order_detail->company;
$deliveryCompany = $order_detail->deliveryCompany;
$deliveryName = $order_detail->deliveryName;
$deliveryAddress1 = $order_detail->deliveryAddress1;
$deliveryAddress2 = $order_detail->deliveryAddress2;
$deliveryTown = $order_detail->deliveryTown;
$deliveryCounty = $order_detail->deliveryCounty;
$deliveryCountry = $order_detail->deliveryCountry;
$deliveryPostcode = $order_detail->deliveryPostcode;
$deliveryTelephone = $order_detail->deliveryTelephone;
$ccName = $order_detail->ccName;
$ccNumber = $order_detail->ccNumber;
$ccExpiryDate = $order_detail->ccExpiryDate;
$ccType = $order_detail->ccType;
$ccStartDate = $order_detail->ccStartDate;
$ccIssue = $order_detail->ccIssue;
$ccCVV = $order_detail->ccCVV;
$currencyID = $order_detail->currencyID;
$goodsTotal = $order_detail->goodsTotal;
$shippingTotal = $order_detail->shippingTotal;
$taxTotal = $order_detail->taxTotal;
$discountTotal = $order_detail->discountTotal;
$giftCertTotal = $order_detail->giftCertTotal;
$status = $order_detail->status;
$shippingMethod = $order_detail->shippingMethod;
$paymentID = $order_detail->paymentID;
$paymentName = $order_detail->paymentName;
$paymentDate = $order_detail->paymentDate;
$authInfo = $order_detail->authInfo;
$terms = $order_detail->terms;
$shippingID = $order_detail->shippingID;
$randID = $order_detail->randID;
$orderPrinted = $order_detail->orderPrinted;
$orderNotes = $order_detail->orderNotes;
$paymentNameNative = $order_detail->paymentNameNative;
$shippingMethodNative = $order_detail->shippingMethodNative;
$languageID = $order_detail->languageID;
$giftCertOrder = $order_detail->giftCertOrder;
$referURL = $order_detail->referURL;
$accTypeID = $order_detail->accTypeID;
$affiliateID = $order_detail->affiliateID;
$offerCode = $order_detail->offerCode;
$e_delivery_date = $order_detail->e_delivery_date;
$lineID = $order_detail->lineID;
$extraFieldID = $order_detail->extraFieldID;
$extraFieldName = $order_detail->extraFieldName;
$extraFieldTitle = $order_detail->extraFieldTitle;
$exvalID = $order_detail->exvalID;
$content = $order_detail->content;
$contentNative = $order_detail->contentNative;
$productID = $order_detail->productID;
$code = $order_detail->code;
$name = $order_detail->name;
$qty = $order_detail->qty;
$weight = $order_detail->weight;
$price = $order_detail->price;
$nameNative = $order_detail->nameNative;
$taxamount = $order_detail->taxamount;
$isDigital = $order_detail->isDigital;
$digitalFile = $order_detail->digitalFile;
$digitalReg = $order_detail->digitalReg;
$downloadID = $order_detail->downloadID;
$ooprice = $order_detail->ooprice;
$ootaxamount = $order_detail->ootaxamount;
$supplierID = $order_detail->supplierID;
$suppliercode = $order_detail->suppliercode;

$reg_date = strtotime($datetime); 
$regDate = date('d-M-Y', $reg_date);

$date = str_replace('/', '-', $e_delivery_date);
$delivery_date =  date('d-M-Y', strtotime($date));

$payement_data = explode('&', $authInfo);
$pdata = explode('=', $authInfo);
$status = isset($pdata[3]) ? $pdata[3] : '';
$pstatus = (($status == 'Payment Confirmed') ? 'Paid' : 'Pending');
?>

<table colspan="0" cellpadding="0" border="1" width="100%">
	<tr>
		<th width="30%">Order ID</th>
		<td width="70%"><?php echo $orderID; ?></td>
	</tr>
	
	<tr>
		<th width="30%">Customer Name</th>
		<td width="70%"><?php echo $title.''.$forename.''.$surname; ?></td>
	</tr>
	
	<tr>
		<th width="30%">Customer Telephone</th>
		<td width="70%"><?php echo $telephone; ?></td>
	</tr>
	
	<tr>
		<th width="30%">Customer Postcode</th>
		<td width="70%"><?php echo $postcode; ?></td>
	</tr>
	
	<tr>
		<th width="30%">Product Name</th>
		<td width="70%"><?php echo $name; ?></td>
	</tr>
	
	<tr>
		<th width="30%">Ordered Date </th>
		<td width="70%"><?php echo $regDate; ?></td>
	</tr>
	<tr>
		<th width="30%">Delivery Date</th>
		<td width="70%"><?php echo $delivery_date; ?></td>
	</tr>
	
	<tr>
		<th width="30%">Total Quantity</th>
		<td width="70%"><?php echo $qty; ?></td>
	</tr>
	<tr>
		<th width="30%">Total Price</th>
		<td width="70%"><?php echo $price; ?></td>
	</tr>
	<tr>
		<th width="30%">Shipping Price</th>
		<td width="70%"><?php echo $taxTotal; ?></td>
	</tr>
	<tr>
		<th width="30%">Payment Status</th>
		<td width="70%"><?php echo $pstatus; ?></td>
	</tr>
	<tr>
		<th width="30%">Card Message</th>
		<td width="70%"><?php echo $content; ?></td>
	</tr>
	<tr>
		<th width="30%">Note to florist</th>
		<td width="70%"><?php echo $contentNative; ?></td>
	</tr>
	<tr>
		<th width="30%">Delivery Name</th>
		<td width="70%"><?php echo $deliveryName; ?></td>
	</tr>
	<tr>
		<th width="30%">Delivery Address1</th>
		<td width="70%"><?php echo $deliveryAddress1; ?></td>
	</tr>
	<tr>
		<th width="30%">Delivery Address2</th>
		<td width="70%"><?php echo $deliveryAddress2; ?></td>
	</tr>
	<tr>
		<th width="30%">Delivery Town</th>
		<td width="70%"><?php echo $deliveryTown; ?></td>
	</tr>
	<tr>
		<th width="30%">Delivery County</th>
		<td width="70%"><?php echo $deliveryCounty; ?></td>
	</tr>
	<tr>
		<th width="30%">Delivery Postcode</th>
		<td width="70%"><?php echo $deliveryPostcode; ?></td>
	</tr>
	<tr>
		<th width="30%">Delivery Telephone</th>
		<td width="70%"><?php echo $deliveryTelephone; ?></td>
	</tr>
</table>


<?php include_once 'footer.php'; ?>
