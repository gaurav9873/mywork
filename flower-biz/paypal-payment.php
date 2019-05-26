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


$request_pid = $obj->DecryptClientId($_REQUEST['orderid']);
$orderID = intval($request_pid);
$orderData = $dbobj->checkOrderIDSiteID($orderID, SITE_ID);
$total_price = round($_SESSION['grand_total']);
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

<form id="paypal_form" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" name="frm">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="paypal@example.com">
    <input type="hidden" name="item_name" value="Flowers">
    <input type="hidden" name="amount" value="<?php echo $total_price; ?>">
    <input type="hidden" name="notify_url" value="http://localhost/flower/flower-biz/payment.php"/>
    <input type="hidden" name="return" value="http://localhost/flower/flower-biz/success.php"/>
    <input type="hidden" name="item_number" value="<?php echo $orderID; ?>">
    <input type="hidden" name="currency_code" value="GBP">
    <input type="hidden" name="rm" value="2"/>
</form>
<script type='text/javascript'>document.getElementById('paypal_form').submit(); </script>


<?php include_once 'footer.php'; ?>
