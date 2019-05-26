<?php
include_once '../include/class-library.php';
include_once '../include/init.php';
$obj = new CustomFunctions();
$dbobj = new ConnectDb();
session_start();
if ($obj->is_ajax()) {
    $json = json_decode(file_get_contents("php://input"));
    $site_id = SITE_ID;
    $pcode = strtoupper($json->user_pcode);
    $addressid = $obj->DecryptClientId($json->addressid);
    $shipping_cahrge = $dbobj->get_shipping_charge($pcode, $site_id);
    //if (empty($shipping_cahrge)) {
        //echo json_encode(array('status' => 'false', 'msg' => 'Location not serviceable please check post code.'));  die;
    //} else {
        $delivery_args = array('post_code' => strtoupper($json->post_code),
            'user_prefix' => $json->user_prefix, 'user_fname' => $json->user_fname, 'user_lname' => $json->user_lname, 'user_mobile' => isset($json->user_mobile) ? $json->user_mobile : '', 'user_telephone' => isset($json->user_mobile) ? $json->user_mobile : '',
            'user_fax' => isset($json->user_mobile) ? $json->user_mobile : '', 'delivery_email' => isset($json->delivery_email) ? $json->delivery_email : '', 'user_city' => $json->user_city, 'primary_address' => $json->user_address1,
            'secondary_address' => $json->user_address2, 'user_county' => 'United Kingdom', 'country' => 'United Kingdom', 'delivery_date' => $json->delivery_date, 'user_card_msg' => $json->user_card_msg,
            'user_notes' => isset($json->user_notes) ? $json->user_notes : '', 'user_id' => $_SESSION['user_id'], 'customer_id' => $_SESSION['customer_id'], 'email' => $_SESSION['email'], 'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'], 'key' => $_SESSION['key']
        );

        if($addressid){
            //Update delivery address
            $order_args = array('delivery_address' => json_encode($delivery_args), 'delivery_date' => $json->delivery_date);
            $condition = array('id' => $addressid);
            $update_stmt = $dbobj->updateRow('op2mro9899_tmp_order', $order_args, $condition);
            if ($update_stmt['status'] == true) {
                echo json_encode(array('status' => 'true', 'message' => 'redirect review page'));
            }else{
                echo json_encode(array('status' => 'false', 'message' => 'OOPS!! something went wrong'));
            }

        }else{
            //Insert new address
            $tab_args = array('delivery_address' => json_encode($delivery_args), 'site_id' => $site_id, 'user_id' => $_SESSION['user_id'], 'customer_id' => $_SESSION['customer_id'], 'order_date' => date("Y-m-d"),
                'created_date' => date("Y-m-d H:i:s"), 'created_ip' => $obj->getUserIP(), 'delivery_date' => $json->delivery_date
            );
            $insStmt = $dbobj->insertRecords('op2mro9899_tmp_order', $tab_args);
            if($insStmt){
                $uorder_id = rand(10,999);
                $user_orderID = $uorder_id.$insStmt;
                $orderIDargs = array('order_id' => $user_orderID);
                $rowID = array('id' => $insStmt);
                $update_stmt = $dbobj->update_record('op2mro9899_tmp_order', $orderIDargs, $rowID);
                if($update_stmt['status'] == true) {
                    echo json_encode(array('status' => 'true', 'message' => 'redirect review page'));
                }
            }else{
                echo json_encode(array('status' => 'false', 'message' => 'OOPS!! something went wrong.'));
            }

        }
    //}

}

