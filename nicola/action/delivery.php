<?php
include_once '../include/init.php';

if($obj->is_ajax()){
    $json = json_decode(file_get_contents("php://input"));

    $bill_address='';
    $active_address='';
    $submit_val='';
    $site_id='';
    $billing_address = '';
    $user_prefix = '';
    $user_fname = '';
    $user_lname = '';
    $post_code = '';
    $primary_address = '';
    $secondary_address = '';
    $user_city = '';
    $user_country = '';
    $user_pcode = '';
    $user_phone = '';
    $user_emailid = '';

}