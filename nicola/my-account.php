<?php
include_once 'header.php';

if (empty($_SESSION['key'])) {
    header("location:login.php");
}

$usrobj = new Login();

$count_cart = $obj->countProduct();
$msg = '';
$req_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$user_id = $_SESSION['user_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];
$key = $_SESSION['key'];


//user address by id
if (isset($_GET['id'])) {
    $address_id = $_GET['id'];
    $adds_id = $obj->DecryptClientId($address_id);
    $aadd_id = $usrobj->user_address_by_id($key, intval($adds_id), SITE_ID);
    $post_datas = json_decode($aadd_id);
} else {
    $chk_usr = $usrobj->get_default_address($key, SITE_ID);
    $post_datas = json_decode($chk_usr);
}

$res = $post_datas;
$id = $res->id;
$user_prefix = $res->user_prefix;
$user_first_name = $res->user_first_name;
$user_last_name = $res->user_last_name;
$user_postcode = $res->user_postcode;
$user_pcode = $res->user_pcode;
$post_address = $res->post_address;
$user_house_number = $res->user_house_number;
$primary_address = $res->primary_address;
$secondary_address = $res->secondary_address;
$user_city = $res->user_city;
$user_county = $res->user_county;
$user_country = $res->user_country;
$user_phone = $res->user_phone;
$user_emailid = $res->user_emailid;
$u_id = $res->user_id;
$default_address = $res->default_address;
$checked_address = (($default_address == '1') ? 'checked' : '');
$_SESSION['default_email'] = $res->user_emailid;

if (isset($_POST['save'])) {
    unset($_POST['submit_val']);
    unset($_POST['save']);
    $post_url = API_PATH . 'addresses/' . $key . '';
    $post_data = $_POST;
    $request_data = $obj->httpPost($post_url, $post_data);
    if ($request_data->status == 'true') {
        if ($count_cart == 0) {
            $msg = '<font face="verdana" color="green">Record has been updated successfully</font>';
        } else {
            header("location:review.php");
        }
    } else {
        $msg = '<font face="verdana" color="red">Something went wrong please try again later</font>';
    }
}


//Account Detail
$ac_api = API_PATH . 'account-detail/' . $key . '';
$data_val = $obj->getCurl($ac_api);
$user_detail = $data_val->user_detail[0];
$user_emailID = $user_detail->user_email;
$userAccountPassword = $user_detail->user_password;
$enc_password = hash('sha256', $userAccountPassword);


if (isset($_POST['email_id']) and ($_POST['user_password'])) {
    $post_string = $_POST;
    $account_api = API_PATH . 'update-account/' . $key . '';
    $req = $obj->httpPost($account_api, $post_string);
    if ($req->status == 'true') {
        $msg = '<font face="verdana" color="green">Password change successfully</font>';
    } else {
        $msg = '<font face="verdana" color="red">Something went wrong please try again later</font>';
    }
}


//All Addresses
$all_add = $usrobj->get_all_billing_address($key, SITE_ID);
$address_val = json_decode($all_add);
$total = count($address_val);


//Order History
$order_history_api = API_PATH . 'order-history/' . $key . '';
$orderData = $obj->getCurl($order_history_api);
$order_history = $orderData->order_history;

//Payement History
//$payement_api = API_PATH.'payement-history/'.$user_id.'';
//$payement_details = $obj->getCurl($payement_api);
//$payement_history = $payement_details->payement_history;


if (isset($_POST['delete'])) {

    $delete_api = API_PATH . 'delete-billing-address/' . $key . '/' . $id . '';
    $res_val = $obj->getCurl($delete_api);
    if ($res_val->status == 'true') {
        header("location:my-account.php?msg=deleted");
    } else {
        $msg = '<font face="verdana" color="red">Something went wrong please try again later</font>';
    }
}

if (isset($_REQUEST['msg']) == 'deleted') {
    $msg = '<font face="verdana" color="green">successfully deleted</font>';
}

?>
<script src="postcodes/crafty_postcode.class.js"></script>
<script>
    var cp_obj = CraftyPostcodeCreate();
    cp_obj.set("access_token", "5a31d-5c42e-48f25-016e3"); // your token here
    cp_obj.set("result_elem_id", "crafty_postcode_result_display");
    cp_obj.set("form", "billing-frm");
    //cp_obj.set("elem_company"  , "companyname");
    cp_obj.set("elem_street1", "primary_address");
    cp_obj.set("elem_street2", "secondary_address");
    cp_obj.set("elem_town", "user_city");
    cp_obj.set("elem_county", "user_county");
    cp_obj.set("elem_country", "user_country");
    cp_obj.set("elem_postcode", "post_code");
</script>
<script src="validation/jquery.validate.min.js"></script>
<script src="validation/additional-methods.min.js"></script>
<script src="javascripts/user-form.js"></script>
<script>
    $(document).ready(function () {
        var rootUrl = '<?php echo API_PATH; ?>';
        $("#save").on('click', function (evt) {
            //evt.preventDefault();
            //var dataFrm =  JSON.stringify($('#billing-frm').serializeObject());
            //console.log(dataFrm); return false;
            $("#billing-frm").submit();
        });

        $("#billing_address").on('change', function () {
            var val = $(this).val();
            if (val != '') {
                window.location.href = "my-account.php?id=" + val + "";
            }
        });

        $("#default_address").on('change', function () {
            var chk = $(this).is(':checked');
            if (chk == true) {
                var chk_val = '1';
                $(".bill_address").val(chk_val);
            }
            if (chk == false) {
                var chkVal = '0';
                $(".bill_address").val(chkVal);
            }
        });

        //$(".smsg").delay(4000).hide();
        setTimeout('$(".smsg").hide()', 3000);

        /*$(".order-details").on('click', function(){
            var data_oids = $(this).data('orderid');
            if(data_oids!=''){
                $.ajax({
                    url:rootUrl+'all-order/'+data_oids,
                    type:'get',
                    contentType: 'application/json',
                    dataType: "json",
                    cache:false,
                    beforeSend:function(){},
                    complete:function(){},
                    success:function(responce){
                        var resp = JSON.parse(responce);
                        alert(resp);
                    }
                })
            }
        });*/

        $("#searchPin").on('click', function () {
            var pcode = $("#post_code").val();
            $(".user_pcode").val(pcode);
        });

    });
</script>

<section class="responsPnone">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-lg-6">
                <h2>My Account</h2>
                <?php if ($default_address == '0') { ?>
                    <div class="cust-msg" style="color:red;">Would you like this address to be your billing address for
                        this order ? If yes, mark it as default, save and continue.
                    </div>
                <?php } ?>
                <div class="smsg"><?php echo $msg; ?></div>
                <div class="responsive-tabs">
                    <h2>Billing Address</h2>
                    <div>
                        <div class="billingInformaion">
                            <div class="billingform checkoutsummary">
                                <form name="billing-frm" id="billing-frm" class="form-horizontal" action=""
                                      method="post">
                                    <input type="hidden" name="bill_address" id="bill_address" class="bill_address"
                                           value="">
                                    <input type="hidden" name="active_address" id="active_address"
                                           class="active_address" value="<?php echo $obj->EncryptClientId($id); ?>">
                                    <input type="hidden" name="submit_val" class="submit_val" value="submit_address">
                                    <input type="hidden" name="site_id" value="<?php echo SITE_ID; ?>"/>
                                    <div class="billInformation newFormDe">
                                        <div class="form-group">
                                            <label for="Billingaddress" class="col-sm-8 control-label">Select From
                                                Billing Addresses</label>
                                            <div class="col-sm-4">
                                                <select class="form-control" id="billing_address"
                                                        name="billing_address">
                                                    <option value="">Please select address</option>
                                                    <?php
                                                    $count = 1;
                                                    foreach ($address_val as $k => $val) {
                                                        $bill_id = $obj->EncryptClientId($val->id);
                                                        $selected = (($bill_id == $req_id) ? "selected" : (($bill_id == $obj->EncryptClientId($id)) ? "selected" : ""));
                                                        echo '<option ' . $selected . ' value="' . $bill_id . '">Address' . $count . '</option>';
                                                        $count++;
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php if ($default_address == '0') { ?>
                                                <label for="DefaultAddress" class="col-sm-12 control-label">Make This
                                                    Your Default Address
                                                    <input type="checkbox" value="1" id="default_address"
                                                           name="default_address"/>
                                                </label>
                                            <?php } else { ?>
                                                <label for="DefaultAddress" class="col-sm-12 control-label"> <strong>This
                                                        is Your Default Address</strong> </label>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="Name" class="col-sm-3 control-label">Name <span>*</span></label>
                                            <div class="col-sm-3">
                                                <select class="form-control" name="user_prefix" id="user_prefix">
                                                    <option value="">Select prefix</option>
                                                    <option value="Mr" <?php echo(($user_prefix == 'Mr') ? 'selected' : ''); ?>>
                                                        Mr.
                                                    </option>
                                                    <option value="Mrs" <?php echo(($user_prefix == 'Mrs') ? 'selected' : ''); ?>>
                                                        Mrs
                                                    </option>
                                                    <option value="Ms" <?php echo(($user_prefix == 'Ms') ? 'selected' : ''); ?>>
                                                        Ms.
                                                    </option>
                                                    <option value="Miss" <?php echo(($user_prefix == 'Miss') ? 'selected' : ''); ?>>
                                                        Miss
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" name="user_fname"
                                                       id="user_fname" value="<?php echo $user_first_name; ?>"
                                                       placeholder="First Name">
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" name="user_lname"
                                                       id="user_lname" value="<?php echo $user_last_name; ?>"
                                                       placeholder="Last Name">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="PostCode" class="col-sm-3 control-label">Search
                                                Address<span>*</span></label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" name="post_code" id="post_code"
                                                       value="<?php echo $user_postcode; ?>" placeholder="Post Code">
                                            </div>
                                            <div class="col-sm-6">
                                                <button type="button" id="searchPin" class="search"
                                                        onclick="cp_obj.doLookup()">Search
                                                </button>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="HouseNumber" class="col-sm-3 control-label">Select
                                                Address</label>
                                            <div class="col-sm-9"><span
                                                        id="crafty_postcode_result_display">&nbsp;</span></div>
                                        </div>

                                        <div class="form-group">
                                            <label for="Address1" class="col-sm-3 control-label">Address1 <span>*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="primary_address"
                                                       id="primary_address" value="<?php echo $primary_address; ?>"
                                                       placeholder="Address1">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Address2" class="col-sm-3 control-label">Address2</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="secondary_address"
                                                       id="secondary_address" value="<?php echo $secondary_address ?>"
                                                       placeholder="Address2">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="City" class="col-sm-3 control-label">City <span>*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="user_city" id="user_city"
                                                       value="<?php echo $user_city; ?>" placeholder="City">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="Country" class="col-sm-3 control-label">Country
                                                <span>*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="user_country"
                                                       id="user_country" value="<?php echo $user_country; ?>"
                                                       placeholder="Country">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="Country" class="col-sm-3 control-label">Postcode <span>*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control user_pcode" name="user_pcode"
                                                       id="user_pcode" value="<?php echo $user_pcode; ?>"
                                                       placeholder="Postcode" style="text-transform:uppercase"
                                                       maxlength="7">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="Phone" class="col-sm-3 control-label">Mobile/Telephone
                                                <span>*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="user_phone"
                                                       id="user_phone" value="<?php echo $user_phone; ?>"
                                                       placeholder="Phone">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Email" class="col-sm-3 control-label">Email
                                                <span>*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="user_emailid"
                                                       id="user_emailid" value="<?php echo $user_emailid; ?>"
                                                       placeholder="Email">
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="form-group lftBtn">
                            <?php if ($default_address == '1') { ?>
                                <a onclick="alert('Please mark an another address as your default billing address before deleting the current default billing address');"
                                   class="btn btn-col btnDirection" style="margin-right:10px;"><i class="fa fa-trash"
                                                                                                  aria-hidden="true"></i>
                                    Delete</a>
                            <?php } else { ?>
                                <button type="submit" name="delete" value="delete" class="btn btn-col btnDirection"
                                        style="margin-right:10px;"><i class="fa fa-trash" aria-hidden="true"></i> Delete
                                </button>
                            <?php } ?>
                            <?php
                            if ($total == 5) { ?>
                                <a onclick="alert('You already have 5 saved billing addresses in your account. To add a new billing address please delete one of the existing billing addresses from your account');"
                                   id="addMore" style="margin-right:10px;" class="btn btn-col btnDirection"><i
                                            class="fa fa-plus" aria-hidden="true"></i> Add More Address</a>
                            <?php } else { ?>
                                <a href="add-more-billing-address.php" id="addMore" class="btn btn-col btnDirection"
                                   style="margin-right:10px;"><i class="fa fa-plus" aria-hidden="true"></i> Add More
                                    Address</a>
                            <?php }
                            ?>
                            <button type="submit" name="save" id="save" class="btn btn-col ccr btnDirection colorBt"><i
                                        class="fa fa-floppy-o" aria-hidden="true"></i> Save & continue
                            </button>

                            <!--<a href="review.php" class="btn btn-col btnDirection ccr">Continue</a>-->
                        </div>
                        </form>
                    </div>
                    <h2>Change Password</h2>
                    <div>
                        <div class="billingInformaion">
                            <div class="billingform checkoutsummary">
                                <form name="change-pass" id="changepass" action="" method="post"
                                      class="form-horizontal">
                                    <input type="hidden" name="siteid" value="<?php echo SITE_ID; ?>"/>
                                    <div class="cardMassages formDee nowidth">
                                        <div class="form-group">
                                            <label for="Email" class="col-sm-3 control-label">Email ID
                                                <span>*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" name="email_id" class="form-control" id="Email"
                                                       value="<?php echo $user_emailID; ?>" placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="password" class="col-sm-3 control-label">New Password
                                                <span>*</span></label>
                                            <div class="col-sm-9">
                                                <input type="password" name="user_password" class="form-control"
                                                       value="" id="password" placeholder="Password">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="cnfpassword" class="col-sm-3 control-label">Confirm Password
                                                <span>*</span></label>
                                            <div class="col-sm-9">
                                                <input type="password" name="confirm_pass" class="form-control"
                                                       id="cnfpassword" placeholder="Confirm Password">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="cnfpassword" class="col-sm-3 control-label">&nbsp;</label>
                                            <div class="col-sm-9">
                                                <button type="submit" id="changePassword" class="RegisterBt">Submit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <h2>Order History</h2>
                    <div>
                        <div class="billingInformaion">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0"
                                   class="table table-bordered table-condensed table-responsive ftSize">
                                <tr style=" background:#f8f8f8;">
                                    <td align="right" width="10%" scope="col">Order Id</td>
                                    <td scope="col" width="20%">Order date (DD-MM-YY)</td>
                                    <td scope="col" width="10%">Delivery Status</td>
                                    <td align="right" scope="col" width="15%">Total</td>
                                    <td align="right" scope="col" width="15%">Payment Status</td>
                                </tr>
                                <?php
                                foreach ($order_history as $order_val) { //print_r($order_val);
                                    $user_order_id = $order_val->order_id;
                                    $payment_status = $order_val->payment_status;
                                    $order_status = $order_val->order_status;
                                    //$product_qty = $order_val->product_qty;
                                    //$gift_quantity = $order_val->gift_quantity;
                                    $total_orders = $order_val->total_orders;
                                    $order_date = $order_val->created_date;
                                    $date = date_create($order_date);
                                    echo '<tr>
						<td align="right"><a href="javascript:void(0);" class="order-details" data-toggle="" data-target="" data-orderid="' . $obj->EncryptClientId($user_order_id) . '">' . $user_order_id . '</a></td>
						<td>' . date_format($date, "Y/M/D H:i:s") . '</td>
						<td>' . $order_status . '</td>
						<td align="right">' . $total_orders . '</td>
						<td align="right">' . $payment_status . '</td>
					  </tr>';
                                }

                                ?>
                            </table>
                        </div>
                    </div>
                </div>

                <!--<button type="submit" class="btn btn-col btnDirection">Continue</button>-->
            </div>
            <div class="col-lg-3"></div>
        </div>
    </div>
</section>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
            </div>
            <div class="modal-body">
                <table cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tbody>
                    <tr style="background:#eee;">
                        <td style="width:10%;"><b>Sl. No.</b></td>
                        <td style="width:35%;"><b>Product</b></td>
                        <td style="width:15%;"><b>Quantity</b></td>
                        <td style="width:15%; padding:0;"><b>Product Rate</b></td>
                        <td style="width:15%; padding:0;"><b>Gift Rate</b></td>
                        <td style="width:10%;"><b>Total</b></td>
                    </tr>
                    </tbody>
                </table>
                <table style="margin:0;" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tbody>
                    <tr>
                        <td style="width:10%;">1</td>
                        <td style="width:35%; text-align:left; padding-left:10px;"><a target="_blank"
                                                                                      href="http://54.191.172.136:82/florist-windsor/product-detail.php?product_id=f85454e81a6">Santorini</a><br>
                            Size :
                        </td>
                        <td class="mono" style="width:15%;">1</td>
                        <td style="width:15%;" class="mono">£</td>
                        <td style="width:15%;" class="mono">£</td>
                        <td style="width:10%;" class="mono">£35.00</td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td>Shipping :</td>
                        <td class="mono">£4.95</td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td>Discount :</td>
                        <td class="mono">0</td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td>Total :</td>
                        <td class="mono">£39.95</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="javascripts/responsiveTabs.js"></script>
<script>
    $(document).ready(function () {
        RESPONSIVEUI.responsiveTabs();
    })
</script>
<?php include_once 'footer.php'; ?>
