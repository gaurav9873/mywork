<?php
include_once 'dbinfo.php';

class Login
{

    const SITE_NAME = SITE_URL;
    const IMAGE_PATH = IMG_PATH;

    public function __construct()
    {
        $db = ConnectDb::getInstance();
        $conn = $db->getConnection();
        return $this->conn = $conn;
    }


    public function chk_exist_email($emailID, $site_id)
    {
        $sql_query = "SELECT id, user_email, site_id, pwd_status, password_txt FROM op2mro9899_customers_login WHERE user_email = :emailID AND site_id = :site_id";
        try {
            $stmt_prepare = $this->conn->prepare($sql_query);
            $stmt_prepare->bindParam("emailID", $emailID);
            $stmt_prepare->bindParam("site_id", $site_id);
            $stmt_prepare->execute();
            $existemail = $stmt_prepare->fetch(PDO::FETCH_OBJ);
            return $existemail;

        } catch (PDOException $e) {
            echo '{"error":{"text": ' . $e->getMessage() . '}}';
            return false;
        }
    }

    public function doLogin($user_email, $user_password, $site_id)
    {
        $dbh = new ConnectDb();
        $obj = new CustomFunctions();

        $sql = "SELECT id, user_email, user_password, user_first_name, user_last_name, customer_id, unique_key, site_id, pwd_status FROM op2mro9899_customers_login WHERE 
				user_email = :user_email AND user_password = :user_password AND site_id = :site_id";
        try {

            $chk_user = $this->chk_exist_email($user_email, $site_id);
            if (empty($chk_user)) {
                $not_found = json_encode(array('status' => 'notfound'));
                return $not_found;
            } else if ($chk_user->pwd_status == 0) {
                $pass = $obj->randomPassword();
                $usalt = $obj->unique_salt();
                $pasword = hash('sha256', $pass);
                $unique_salt = $chk_user->id . $usalt;

                //Update customer information
                $cust_args = array('user_password' => trim($pasword), 'password_txt' => trim($pass), 'unique_key' => $unique_salt, 'pwd_status' => 1);
                $condition = array('id' => $chk_user->id);
                $update_stmt = $dbh->update_record('op2mro9899_customers_login', $cust_args, $condition);
                if ($update_stmt) {
                    $vouchers = $dbh->voucher_code($site_id);
                    $site_url = Login::SITE_NAME;
                    $image_path = Login::IMAGE_PATH;

                    $msg = '<!doctype html>
								<html>
								<head>
								<meta charset="utf-8">
								<title>Update Password</title>
								<style type="text/css">
								@font-face {
								font-family: latolight;
								src: url("' . $image_path . 'images/lato-light-webfont.woff2") format("woff2"),  
									 url("' . $image_path . 'images/lato-light-webfont.woff") format("woff");
								font-weight: normal;
								font-style: normal;
								}
								</style>
								</head>

								<body style="margin:0; padding:0;">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
								<tr>
								<td align="center" valign="top"><table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
								<tr>
								<td height="120" align="center" valign="middle"><a href="#" target="_blank"><img src="' . $site_url . 'images/logo.png" width="156" alt="" height="84"></a></td>
								</tr>
								<tr>
								<td><table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border-bottom:1px solid #ccc;">
								<tr align="center" valign="middle">
								<td height="30" width="20%"><a href="https://www.nicolaflorist.co.uk/" style="font-size:16px; line-height:55px; font-family: latolight, Arial, Helvetica, sans-serif; text-decoration:none; color:#000000;" target="_blank">Home</a></td>
								<td height="30" width="20%"><a href="https://www.nicolaflorist.co.uk/category-products?category_id=182be0c521" style="font-size:16px; line-height:55px; font-family: latolight, Arial, Helvetica, sans-serif; text-decoration:none; color:#000000;" target="_blank"><b>Autumn Flowers</b></a></td>
								<td height="30" width="20%"><a href="https://www.nicolaflorist.co.uk/categories?category_id=c4ca42381" style="font-size:16px; line-height:55px; font-family: latolight, Arial, Helvetica, sans-serif; text-decoration:none; color:#000000;" target="_blank">Core Collection</a></td>
								<td height="30" width="20%"><a href="https://www.nicolaflorist.co.uk/categories?category_id=38b3eff865" style="font-size:16px; line-height:55px; font-family: latolight, Arial, Helvetica, sans-serif; text-decoration:none; color:#000000;" target="_blank">Occasion</a></td>
								<td height="30" width="20%"><a href="https://www.nicolaflorist.co.uk/contact-us" style="font-size:16px; line-height:55px; font-family: latolight, Arial, Helvetica, sans-serif; text-decoration:none; color:#000000;" target="_blank">Contact Us</a></td>
								</tr>
								</table></td>
								</tr>
								<tr>
								<td align="center" valign="middle" style="padding:35px 0;">
								<p  style="font-size:24px; line-height:38px; font-family: latolight, Arial, Helvetica, sans-serif; letter-spacing:3px; margin-bottom:0;">Nicola Florist, Basingstoke are glad to launch their new website.</p>
								<a style="font-size:46px; font-family: latolight, Arial, Helvetica, sans-serif; font-weight:600; text-decoration:none; border-bottom:3px solid #E5437E; color:#E5437E; margin:20px 0 45px 0; display:inline-block;" href="' . $site_url . '" target="_blank">' . preg_replace('#^https?://#', '', rtrim($site_url, '/')) . '</a>
								<p  style="font-size:24px; line-height:38px; font-family: latolight, Arial, Helvetica, sans-serif; letter-spacing:3px; margin-bottom:0;">Get <b>20%</b> off your orders in November and December when you spend £30 or more (includes Christmas). For discounts, please use voucher code: ' . $vouchers . '</p>
								<p style="font-size:22px; line-height:35px; font-family: latolight, Arial, Helvetica, sans-serif; display:block; margin:0 25px 45px;">Please use this new password to Login: ' . trim($pass) . '</p>
								<a style="padding:10px 25px; font-size:14px; font-family: latolight, Arial, Helvetica, sans-serif; color:#fff; text-decoration:none; letter-spacing:3px; border-radius:5px; -moz-border-radius:5px; -webkit-border-radius:5px; text-transform:uppercase; background:#E5437E;" href="#" target="_blank">Login</a></td>
								</tr>
								<tr>
								<td height="361" align="center" valign="middle"><a href="#" target="_blank"><img src="' . $site_url . 'images/floristSite.jpg" alt="" width="590" height="361"></a></td>
								</tr>
								<tr>
								<td bgcolor="f3f3f3" align="center" valign="middle" style="padding:15px 15px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
								<td align="center" valign="middle" width="33.33%"><a href="#" target="_blank"  style="font-size:13px; line-height:15px; font-family: latolight, Arial, Helvetica, sans-serif; text-decoration:none; color:#000000;">SAME DAY DELIVERY WHEN<br>ORDER BEFORE 3PM</a></td>
								</tr>
								</table></td>
								</tr>
								<tr>
								<td align="center" valign="middle" style="padding:25px 0 0;"><a href="#." target="_blank" style="padding:5px 5px 0 5px; border:1px solid #fff;"><img src="' . $image_path . 'images/social-facebook.png" width="16" height="16"></a> <a href="#." target="_blank" style="padding:5px 5px 0 5px; border:1px solid #fff;"><img src="' . $image_path . 'images/twitter.png" width="16" height="16"></a> <a href="#." target="_blank" style="padding:5px 5px 0 5px; border:1px solid #fff;"><img src="' . $image_path . 'images/Google_Plus.png" width="16" height="16"></a> <a href="#." target="_blank" style="padding:5px 5px 0 5px; border:1px solid #fff;"><img src="' . $image_path . 'images/youtube.png" width="16" height="16"></a></td>
								</tr>
								<tr>
								<td align="center" valign="middle"><p style="font-size:15px; line-height:22px; font-family: latolight, Arial, Helvetica, sans-serif; text-decoration:none; color:#000000;">&copy; copyright 1972 Nicola Florist<br>
								Ecommerce SolutionPowered By :<a href="https://www.perceptive-solutions.com/">PCS</a></p></td>
								</tr>
								</table></td>
								</tr>
								</table>
								</body>
							</html>';
                    $subj = 'Update your password - Nicola';
                    $to = $user_email;
                    $from = 'info@nicolaflorist.co.uk';
                    $name = 'Nicola';
                    $obj->password_smtpmailer($to, $from, $name, $subj, $msg);
                    $mailval = json_encode(array('status' => 'update_password', 'pwd_status' => $chk_user->pwd_status));
                    return $mailval;
                }

            } else {
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam("user_email", $user_email);
                $stmt->bindParam("user_password", $user_password);
                $stmt->bindParam("site_id", $site_id);
                $stmt->execute();
                $userRow = $stmt->fetch(PDO::FETCH_OBJ);
                if ($stmt->rowCount() > 0) {
                    if ($user_password == $userRow->user_password) {
                        $_SESSION['user_id'] = $userRow->id;
                        $_SESSION['customer_id'] = $userRow->customer_id;
                        $_SESSION['email'] = $userRow->user_email;
                        $_SESSION['first_name'] = $userRow->user_first_name;
                        $_SESSION['last_name'] = $userRow->user_last_name;
                        $_SESSION['key'] = $userRow->unique_key;
                        $loginval = json_encode(array('status' => 'login'));
                        return $loginval;
                    } else {
                        $password_not_matched = json_encode(array('status' => 'password_not_matched'));
                        return $password_not_matched;
                    }
                    $loginfail = json_encode(array('status' => 'loginfail'));
                    return $loginfail;
                }
            }
        } catch (PDOException $e) {
            $notfound = json_encode(array('status' => 'notfound'));
            throw new Exception('Login error');
            echo '{"error":{"text": ' . $e->getMessage() . '}}';
            return $notfound;
        }
    }


    public function user_exist($usersalt, $site_id)
    {
        $sql = "SELECT id, customer_id, unique_key FROM op2mro9899_customers_login WHERE unique_key = :usersalt AND site_id = :site_id";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam("usersalt", $usersalt);
            $stmt->bindParam("site_id", $site_id);
            $stmt->execute();
            $record = $stmt->fetch(PDO::FETCH_OBJ);
            return $record;
        } catch (PDOException $e) {
            echo '{"error":{"text": ' . $e->getMessage() . '}}';
            return false;
        }
    }

    public function billing_address($usrsalt, $address_id, $site_id)
    {
        $chk_usr = $this->user_exist($usrsalt, $site_id);
        $chk_usr->unique_key;
        $unique_key = $chk_usr->unique_key;
        if ($unique_key == $usrsalt) {
            $uid = $chk_usr->id;
            $sql = "SELECT * FROM op2mro9899_customers_billing_address WHERE id = :address_id AND site_id = :site_id";
            try {
                $add_stmt = $this->conn->prepare($sql);
                $add_stmt->bindParam("address_id", $address_id);
                $add_stmt->bindParam("site_id", $site_id);
                $add_stmt->execute();
                $adress_detail = $add_stmt->fetchAll(PDO::FETCH_OBJ);
                return $adress_detail;
            } catch (PDOException $e) {
                echo '{"error":{"text": ' . $e->getMessage() . '}}';
                return false;
            }
        } else {
            throw new exception('Record not found');
        }
    }


    public function get_default_address($api_key, $site_id)
    {
        $auth = $this->user_exist($api_key, $site_id);
        $unique_key = $auth->unique_key;
        if ($unique_key == $api_key) {
            //$user_id = $auth->id;
            $user_id = $auth->customer_id;
            $sql = "SELECT * FROM op2mro9899_customers_billing_address WHERE customer_id = :user_id AND default_address = 1 AND site_id = :site_id";
            try {
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam('user_id', $user_id);
                $stmt->bindParam('site_id', $site_id);
                $stmt->execute();
                $user_data = $stmt->fetch(PDO::FETCH_OBJ);
                return json_encode($user_data);
            } catch (PDOException $e) {
                echo '{"error":{"text": ' . $e->getMessage() . '}}';
                return false;
            }
        } else {
            echo json_encode(array('status' => 'false', 'message' => 'authentication fail'));
        }
    }

    public function user_address_by_id($apiKey, $address_id, $site_id)
    {
        $auth_key = $this->user_exist($apiKey, $site_id);
        $unique_key = $auth_key->unique_key;
        if ($unique_key == $apiKey) {
            $sql = "SELECT * FROM op2mro9899_customers_billing_address WHERE id = :address_id AND site_id = :site_id";
            try {
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam('address_id', $address_id);
                $stmt->bindParam('site_id', $site_id);
                $stmt->execute();
                //$stmt->debugDumpParams();
                $billingAdd = $stmt->fetch(PDO::FETCH_OBJ);
                return json_encode($billingAdd);
            } catch (PDOException $e) {
                echo '{"error":{"text": ' . $e->getMessage() . '}}';
                return false;
            }
        } else {
            echo json_encode(array('status' => 'false', 'message' => 'authentication fail'));
        }
    }

    public function get_all_billing_address($user_key, $site_id)
    {
        $auth = $this->user_exist($user_key, $site_id);
        $unique_key = $auth->unique_key;
        if ($unique_key == $user_key) {
            //$uid = $auth->id;
            $uid = $auth->customer_id;
            $query = "SELECT id, user_id, site_id FROM op2mro9899_customers_billing_address WHERE customer_id = :uid AND site_id = :site_id";
            try {
                $query_stmt = $this->conn->prepare($query);
                $query_stmt->bindParam("uid", $uid);
                $query_stmt->bindParam("site_id", $site_id);
                $query_stmt->execute();
                $all_address = $query_stmt->fetchAll(PDO::FETCH_OBJ);
                return json_encode($all_address);
            } catch (PDOException $e) {
                echo '{"error":{"text": ' . $e->getMessage() . '}}';
                return false;
            }
        } else {
            return json_encode(array('status' => 'false', 'message' => 'Billing address not found'));
        }
    }


    /*public function user_address_detail($usalt,$uid, $siteid){

        $sql ="SELECT a.id, a.user_prefix, a.user_first_name, a.user_last_name, a.user_postcode, a.post_address, a.user_house_number, a.secondary_address, a.user_city, a.user_county,
               a.user_phone, a.user_emailid as billing_email, a.user_id, a.customer_id, a.site_id, a.default_address, a.reg_date, b.user_email, b.customer_id, b.unique_key, b.pwd_status
               FROM op2mro9899_customers_billing_address as a INNER JOIN op2mro9899_customers_login as b ON a.customer_id = b.customer_id
               WHERE b.unique_key = :usalt AND a.user_id = :uid AND a.site_id = :siteid AND a.default_address = 1";
        try{
            $stmts = $this->conn->prepare($sql);
            $stmts->bindParam('usalt', $usalt);
            $stmts->bindParam('uid', $uid);
            $stmts->bindParam('siteid', $siteid);
            $detail = $stmts->fetchAll(PDO::FETCH_OBJ);
            return $detail;
        }catch(PDOException $e){
                echo '{"error":{"text": '.$e->getMessage().'}}';
                return false;
            }

    }*/


}

?>
