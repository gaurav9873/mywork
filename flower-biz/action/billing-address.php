<?php
session_start();
include_once '../include/class-library.php';
include_once '../include/init.php';
$obj = new CustomFunctions();
$dbobj = new ConnectDb();

if ($obj->is_ajax()) {



    $count_cart = $obj->countProduct();

    $user_id = $_SESSION['user_id'];
    $customer_id = $_SESSION['customer_id'];
    $user_key = $_SESSION['key'];
    $json = json_decode(file_get_contents("php://input"));
    $action = $json->action; //update_detail

    switch ($action) {
        case "update_detail":

            $bill_address = filter_var($json->bill_address, FILTER_SANITIZE_STRING);
            $active_address = filter_var($json->active_address, FILTER_SANITIZE_STRING);
            $submit_val = filter_var($json->submit_val, FILTER_SANITIZE_STRING);
            $site_id = intval($json->site_id);
            $billing_address = filter_var($json->billing_address, FILTER_SANITIZE_STRING);
            $user_prefix = filter_var($json->user_prefix, FILTER_SANITIZE_STRING);
            $user_fname = filter_var($json->user_fname, FILTER_SANITIZE_STRING);
            $user_lname = filter_var($json->user_lname, FILTER_SANITIZE_STRING);
            $post_code = filter_var($json->post_code, FILTER_SANITIZE_STRING);
            $primary_address = filter_var($json->primary_address, FILTER_SANITIZE_STRING);
            $secondary_address = filter_var($json->secondary_address, FILTER_SANITIZE_STRING);
            $user_city = filter_var($json->user_city, FILTER_SANITIZE_STRING);
            $user_country = filter_var($json->user_country, FILTER_SANITIZE_STRING);
            $user_pcode = filter_var($json->user_pcode, FILTER_SANITIZE_STRING);
            $user_phone = filter_var($json->user_phone,FILTER_SANITIZE_STRING);
            $user_emailid = filter_var($json->user_emailid, FILTER_VALIDATE_EMAIL);

            $addressID = $obj->DecryptClientId($active_address);

            $update_args = array( 'user_prefix' => $user_prefix, 'user_first_name' => $user_fname, 'user_last_name' => $user_lname, 'user_postcode' => $post_code, 'primary_address' => $primary_address,
                'secondary_address' => $secondary_address, 'user_city' => $user_city, 'user_country' => $user_country, 'user_pcode' => $user_pcode, 'user_phone' => $user_phone, 'user_emailid' => $user_emailid,
            );


            if(isset($json->default_address)){
                if($json->default_address == 1){
                    $change_staus = array('default_address' => 0);
                    $con = array('user_id' => $user_id);
                    $changeStmt = $dbobj->update_record('op2mro9899_customers_billing_address', $change_staus, $con);
                    if($changeStmt){
                        $update_args['default_address'] = 1;
                    }
                }
            }

            $condition = array('id' => $addressID);
            $updateStmt = $dbobj->update_record('op2mro9899_customers_billing_address', $update_args, $condition);
            if($updateStmt['status'] = 'true'){
                if($count_cart == 0){
                    echo json_encode(array('status' => 'true', 'cart_val' => 'null', 'message' => '<font face="verdana" color="green">Record has been updated successfully</font>'));
                }else{
                    echo json_encode(array('status' => 'true', 'cart_val' => 'true', 'message' => 'review'));
                }
            }else{
                echo json_encode(array('status' => 'false', 'message' => '<font face="verdana" color="red">Something went wrong please try again later</font>'));
            }

            break;

        case "change_pass":

            $siteid = $json->siteid;
            $email_id = $json->email_id;
            $user_password = hash('sha256', $json->user_password);
            $array_args = array('user_email' => $email_id, 'user_password' => $user_password, 'site_id' => SITE_ID);
            $condition = array('id' => $user_id);
            $upStmt = $dbobj->update_record('op2mro9899_customers_login', $array_args, $condition);
            if($upStmt['status'] == 'true'){
                echo json_encode(array('status' => 'true', 'message' => '<font face="verdana" color="green">Password change successfully</font>'));
            }else{
                echo json_encode(array('status' => 'false', 'message' => '<font face="verdana" color="red">Something went wrong please try again later</font>'));
            }
            break;


    }



}




