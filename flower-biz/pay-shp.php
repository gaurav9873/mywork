<?php include_once 'header.php';

$dbobj = new ConnectDb();

if (empty($_SESSION['cart']['products'])) {
    header("location:index.php");
} else if (empty($_SESSION['key'])) {
    header("location:login");
} else if ($obj->countProduct() == 0) {
    header("location:my-account");
} else {
}
$urls = "www.sandbox.paypal.com";

$total_price = round($_SESSION['grand_total']);
$uid  = $_SESSION['user_id'];
$request_pid = $obj->DecryptClientId($_REQUEST['orderid']);
$orderID = intval($request_pid);
$orderData = $dbobj->checkOrderIDSiteID($orderID, SITE_ID);
$delivery_address = json_decode($orderData[0]->delivery_address);

$userInfo = $dbobj->userBillingAddresss($uid, SITE_ID);
?>


<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="aboutSec">
                    <div class="aboutContent loader-section">
                        <div class="overlay">
                            <div class="loader"></div>
                            <h1>Please wait while we redirect...</h1>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<form action="https://test.secure-server-hosting.com/secutran/secuitems.php" method="post" name="basketform" id="basketform">
    <input type="hidden" name="shreference" value="SH209673" />
    <input type="hidden" name="checkcode" value="612791" />
    <input type="hidden" name="filename" value="SH209673/payment4.html" />
    <input type="hidden" name="itemcode" value="<?php echo $orderID; ?>" />
    <input type="hidden" name="itemskew" value="<?php echo $orderID; ?>" />
    <input type="hidden" name="itemdesc" value="Flower Corner" />
    <input type="hidden" name="itemquan" value="<?php echo $orderID; ?>" />
    <input type="hidden" name="itemtota" value="<?php echo $orderID; ?>" />
    <input type="hidden" name="itempric" value="<?php echo $total_price; ?>" />
    <input type="hidden" name="cardholdersname" value="<?php echo $userInfo[0]->user_first_name; ?>" />
    <input type="hidden" name="cardholdersemail" value="<?php echo $userInfo[0]->user_emailid; ?>" />
    <input type="hidden" name="address" value="<?php echo $userInfo[0]->primary_address; ?>" />
    <input type="hidden" name="postcode" value="<?php echo $userInfo[0]->user_postcode; ?>" />
    <input type="hidden" name="telephone" value="<?php echo $userInfo[0]->user_phone; ?>" />
    <input type="hidden" name="transactionamount" value="<?php echo $total_price; ?>" />
    <input type="hidden" name="cardholdercity" value="<?php echo $userInfo[0]->user_city; ?>" />
    <input type="hidden" name="deliveryname" value="<?php echo $delivery_address->user_fname; ?>"/>
    <input type="hidden" name="deliveryAddr1" value="<?php echo $delivery_address->primary_address; ?>" />
    <input type="hidden" name="deliveryCity" value="<?php echo $delivery_address->user_city; ?>" />
    <input type="hidden" name="deliveryPostcode" value="<?php echo $delivery_address->post_code; ?>" />
    <input type="hidden" name="transactioncurrency" value="GBP" />
    <input type="hidden" name="secuitems" value="<?php echo $orderID; ?>|sku1|Flower Corner Item|<?php echo $total_price; ?> |<?php echo $obj->countProduct(); ?>|<?php echo $total_price;?>" />
    <input type="hidden" name="callbackurl" value="http://52.39.230.23/nicola-admin/flower-biz/shp-success.php" />
    <input type="hidden" name="callbackdata" value="pname|flower|price|<?php echo $total_price; ?>|orderid|<?php echo $orderID; ?>" />
    <input type="hidden" name="merchanturl" value="http://52.39.230.23/nicola-admin/flower-biz/review.php?orderid=<?php echo $orderID; ?>">
</form><script type="text/javascript">document.getElementById("basketform").submit(); </script>


<?php include_once 'footer.php'; ?>