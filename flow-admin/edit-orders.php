<?php 
include_once 'header.php';

$obj =  new ConnectDb();
$custom_obj = new CustomFunctions();

$redirect = (($_REQUEST['type'] == 'allorder') ? 'product-orders.php' : 'upcoming-orders.php');

$order_id = $custom_obj->DecryptClientId($_REQUEST['order']);
$order_data = $obj->get_row_by_id('op2mro9899_delivery_address', 'order_id', $order_id);
$post_data = $order_data[0];

$card_message = $post_data['card_message'];
$florist_instruction = $post_data['florist_instruction'];
$delivery_date = $post_data['delivery_date'];
$id = $post_data['id'];

if(isset($_POST['submit'])){
	$deliveryDate = $_POST['delivery_date'];
	$cardMessage = $_POST['card_message'];
	$floristInstruction = $_POST['florist_instruction'];
	
	$update_args = array('delivery_date' => $deliveryDate, 'card_message' => $cardMessage, 'florist_instruction' => $floristInstruction);
	$update_statement = $obj->update_row('op2mro9899_delivery_address', $update_args, 'WHERE id='.$id.'');
	if($update_statement){
		header("location:".$redirect."");
	}
}

?>

<link rel="stylesheet" type='text/css' href="datepicker/default.css">
<script type='text/javascript' src="datepicker/zebra_datepicker.js"></script>
<script>
$(document).ready(function(){
	$('#delivery_date').Zebra_DatePicker({
		direction: false,
	});
});
</script>
<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-edit"></i> Edit Orders</h2>
            </div>
            <div class="box-content">
                <form name="frm" id="frm" action="" method="post">
                    <div class="form-group">
                        <label for="giftName">Delivery Date</label>
                        <input class="form-control validate" id="delivery_date" name="delivery_date" value="<?php echo $delivery_date; ?>" type="text" readonly>
                    </div>
                    <div class="form-group">
                        <label for="description">Card Message</label>
                        <textarea class="form-control" id="card_message" name="card_message"><?php echo $card_message; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="giftName">Florist Instruction</label>
                        <textarea class="form-control" id="florist_instruction" name="florist_instruction"><?php echo $florist_instruction; ?></textarea>
                    </div>
                    <button type="submit" name="submit" class="btn btn-default">Submit</button>
                </form>

            </div>
        </div>
    </div>
    <!--/span-->
</div>
<?php include_once 'footer.php'; ?>
