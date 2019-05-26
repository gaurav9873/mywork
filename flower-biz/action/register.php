<?php
session_start();
include_once '../include/init.php';
$dbobj = new ConnectDb();
$cobj = new CustomFunctions();
$user = new Login();

if ($cobj->is_ajax()) {

    $json = json_decode(file_get_contents("php://input"));

    $user_prefix = filter_var($json->user_prefix, FILTER_SANITIZE_STRING);
    $user_fname = filter_var($json->user_fname, FILTER_SANITIZE_STRING);
    $user_lname = filter_var($json->user_lname, FILTER_SANITIZE_STRING);
    $post_code = $json->post_code;
    $primary_address = filter_var($json->primary_address, FILTER_SANITIZE_STRING);
    $secondary_address = filter_var($json->secondary_address, FILTER_SANITIZE_STRING);
    $user_city = filter_var($json->user_city, FILTER_SANITIZE_STRING);
    //$user_county = filter_var($_POST['user_county'], FILTER_SANITIZE_STRING);
    $user_country = filter_var($json->user_country, FILTER_SANITIZE_STRING);
    $user_pcode = filter_var($json->user_pcode, FILTER_SANITIZE_STRING);
    $user_phone = $json->user_phone;
    $user_emailid = filter_var($json->user_emailid, FILTER_VALIDATE_EMAIL);
    $user_password = hash('sha256', $json->user_password);
    $enc_password = hash('sha256', $json->user_password);
    $reg_date = date("Y-m-d");
    $created_date = date("Y-m-d H:i:s");
    $created_ip = $cobj->getUserIP();
    $rand = sprintf("%06d", rand(1, 92));
    $rand_num = rand(100, 29);
    $customerID = $rand + $rand_num;

    $chk_email = $dbobj->isEmailExists($user_emailid, SITE_ID);
    if ($chk_email > 0) {
        echo json_encode(array('email_status' => 'true', 'message' => 'This email id is already registered. Please login with your UID & Password. <a href="' . SITE_URL . 'login">Click here</a>'));
    } else {

        $login_args = array(
            'user_email' => $user_emailid, 'user_password' => trim($user_password), 'password_txt' => $json->user_password, 'user_first_name' => $user_fname, 'user_last_name' => $user_lname, 'reg_date' => $reg_date,
            'site_id' => SITE_ID, 'pwd_status' => '1', 'created_date' => $created_date, 'created_ip' => $created_ip
        );
        $insertStmt = $dbobj->insertRecords('op2mro9899_customers_login', $login_args);
        if ($insertStmt) {

            $cust_id = $customerID . $insertStmt;
            $usalt = $insert_stmt . $cobj->unique_salt() . SITE_ID;
            $cust_args = array('customer_id' => $cust_id, 'unique_key' => $usalt);
            $condition = array('id' => $insertStmt);
            $updateStmt = $dbobj->update_record('op2mro9899_customers_login', $cust_args, $condition);
            if ($updateStmt) {

                $address_args = array(
                    'user_prefix' => $user_prefix, 'user_first_name' => $user_fname, 'user_last_name' => $user_lname, 'user_postcode' => $post_code, 'primary_address' => $primary_address,
                    'secondary_address' => $secondary_address, 'user_city' => $user_city, 'user_country' => $user_country, 'user_pcode' => $user_pcode, 'user_phone' => $user_phone,
                    'user_emailid' => $user_emailid, 'user_id' => $insertStmt, 'customer_id' => $cust_id, 'site_id' => SITE_ID, 'default_address' => '1', 'reg_date' => $reg_date, 'created_ip' => $created_ip
                );
                $newUser = $dbobj->insert_records('op2mro9899_customers_billing_address', $address_args);
                if ($newUser) {
                    $loginStmt = $dbobj->login_customer($user_emailid, $enc_password, SITE_ID);
                    if ($loginStmt) {
                        $_SESSION['user_id'] = $loginStmt->id;
                        $_SESSION['customer_id'] = $loginStmt->customer_id;
                        $_SESSION['email'] = $loginStmt->user_email;
                        $_SESSION['first_name'] = $loginStmt->user_first_name;
                        $_SESSION['last_name'] = $loginStmt->user_last_name;
                        $_SESSION['key'] = $loginStmt->unique_key;
                        echo json_encode(array('login_status' => 'true', 'message' => 'Please wait while we redirect.'));
                    } else {
                        echo json_encode(array('login_status' => 'false', 'message' => 'Something went wrong. Please try again later'));
                    }
                }
            }

        }

    }
}
