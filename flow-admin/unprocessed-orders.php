<?php
include_once 'header.php';
error_reporting(0);
$shop_id = $_SESSION['shop_id'];
$obj = new Connectdb();
$cFunc = new CustomFunctions();
$data = $obj->unprocessed_orders($shop_id);
?>

<script>
    $(document).ready(function(){
        $(".processdata").on('click', function(){
            var process_id = $(this).data('id');
            var orderid = $(this).data('orderid');
            var shopid = $(this).data('shopid');
            var loaders = $(this).siblings('.ajloaders');
            if(process_id!=''){
                $.ajax({
                    url:'action/process-order.php',
                    type:'POST',
                    data:{
                        action:'processdata',
                        pids:process_id,
                        orderid:orderid,
                        shopid:shopid
                    },
                    beforeSend:function(){
                        $(loaders).show();
                    },
                    complete:function(){
                        $(loaders).hide();
                    },
                    success:function(resdata){
                       console.log(resdata);
                    }
                });
            }
        });
    });
</script>

    <div class="row">
        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-content">

                    <table class="table table-striped table-bordered bootstrap-datatable datatable responsive">
                        <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Ordered Date </th>
                            <th>Delivery Date</th>
                            <th>Customer Name</th>
                            <th>Total Quantity</th>
                            <th>Payement Status</th>
                            <th>Order Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach ($data as $postval){
                                $id = $postval->id;
                                $product = json_decode($postval->product);
                                $quantity = isset($product[0]->quantity) ? $product[0]->quantity : '0';
                                $gift = $postval->gift;
                                $delivery_address = json_decode($postval->delivery_address);
                                //print_r($delivery_address);
                                $customer_name = $delivery_address->user_fname;

                                $order_id = $postval->order_id;
                                $delivery_charges = $postval->delivery_charges;
                                $discount = $postval->discount;
                                $site_id = $postval->site_id;
                                $user_id = $postval->user_id;
                                $customer_id = $postval->customer_id;
                                $order_date = $postval->order_date;
                                $order_process = $postval->order_process;
                                $process_status = (($order_process == 1) ? 'Processed' : 'Unprocessed');
                                $delivery_date = $postval->delivery_date;

                        ?>
                        <tr>
                            <td><?php echo $order_id; ?></td>
                            <td class="center"><?php echo $order_date; ?></td>
                            <td class="center"><?php echo $delivery_date; ?></td>
                            <td class="center"><?php echo $customer_name; ?></td>
                            <td class="center"><?php echo $quantity; ?></td>
                            <td class="center"><?php echo $process_status; ?></td>
                            <td class="center"><?php echo $process_status; ?></td>
                            <td class="center">

                                <a class="processdata" data-shopid="<?php echo $shop_id; ?>" data-id="<?php echo $id; ?>" data-orderid="<?php echo $order_id; ?>" href="javascript:void(0);">
                                    <span class="label-warning label label-default">Process</span></a>
                                <img class="ajloaders" style="display:none;" src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif">

                                <!--<a class="cancel-status" data-val-id="10279" data-status="cancel" href="javascript:void(0);">
                                    <span class="label-default label label-danger">Delete</span>
                                </a>-->
                            </td>

                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--/span-->

    </div><!--/row-->
<?php include_once 'footer.php'; ?>
<script>
    $(function () {
        $("table tr th:nth-child(3)").trigger('click').trigger('click');
    });
</script>
