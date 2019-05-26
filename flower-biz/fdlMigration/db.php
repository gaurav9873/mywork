<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Europe/London');

$DB_host = "localhost";
$DB_user = "fdl_live";
$DB_pass = "fdllive@#$1234";
$DB_name = "fdl_live";

try
{
    $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}",$DB_user,$DB_pass);
    $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
    echo $e->getMessage();
}


function userID($email){
    global $DB_con;
    $sql = "SELECT * FROM theflowercorner2_shippingaddress as t1 INNER JOIN theflowercorner2_custdatabase t2 ON t1.BillEmail = t2.Cust_Email WHERE t1.BillEmail = '$email' AND t1.site_id = '2' GROUP BY t1.BillEmail";
    $stmt = $DB_con->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $data;
}

function getUid($email){
    global $DB_con;
    $sql = "SELECT * FROM op2mro9899_customers_login WHERE user_email = '$email' AND site_id = '2'";
    $stmt = $DB_con->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $data;
}