<?php
ob_start();
date_default_timezone_set('Europe/London');

// Singleton to connect db.
class ConnectDb
{

    // Hold the class instance.
    private static $instance = null;
    private $conn;

    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $name = 'fdl_live';

    // The db connection is established in the private constructor.
    public function __construct()
    {
        $this->conn = new PDO("mysql:host={$this->host};
    dbname={$this->name}", $this->user, $this->pass,
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        //return $this->conn;
    }


    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new ConnectDb();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }


    public function insert_records($table, $parameters)
    {

        $columns = implode("`,`", array_keys($parameters));
        $values = implode("','", $parameters);
        try {
            $stmt = $this->conn->prepare("INSERT INTO `" . $table . "` (`" . $columns . "`) VALUES ('" . $values . "')");
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }


    public function insertRecords($table, $data)
    {

        if (!empty($data) && is_array($data)) {
            $columns = '';
            $values = '';
            $i = 0;
            $columnString = implode(',', array_keys($data));
            $valueString = ":" . implode(',:', array_keys($data));
            $sql = "INSERT INTO $table ($columnString) VALUES ($valueString)";
            $query = $this->conn->prepare($sql);

            foreach ($data as $key => $val) {
                $query->bindValue(':' . $key, $val);
            }
            $insert = $query->execute();
            if ($insert) {
                $data = $this->conn->lastInsertId();
                return $data;
            } else {
                return false;
            }
        } else {
            throw new Exception('data variable not found');
            return false;
        }
    }


    public function update_row($table_name, $form_data, $where_clause = '')
    {
        try {
            $whereSQL = '';
            if (!empty($where_clause)) {
                if (substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE') {
                    $whereSQL = " WHERE " . $where_clause;
                } else {
                    $whereSQL = " " . trim($where_clause);
                }
            }
            $sql = "UPDATE `" . $table_name . "` SET ";

            $sets = array();
            foreach ($form_data as $column => $value) {
                $sets[] = "" . $column . " = '" . $value . "'";
            }

            $sql .= implode(', ', $sets);
            $sql .= $whereSQL;
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }

    }


    function update_record($table, $data, $conditions)
    {
        try {

            if (!empty($data) && is_array($data)) {
                $colvalSet = '';
                $whereSql = '';
                $i = 0;

                foreach ($data as $key => $val) {
                    $pre = ($i > 0) ? ', ' : '';
                    $val = htmlspecialchars(strip_tags($val));
                    $colvalSet .= $pre . $key . "='" . $val . "'";
                    $i++;
                }
                if (!empty($conditions) && is_array($conditions)) {
                    $whereSql .= ' WHERE ';
                    $i = 0;
                    foreach ($conditions as $key => $value) {
                        $pre = ($i > 0) ? ' AND ' : '';
                        $whereSql .= $pre . $key . " = '" . $value . "'";
                        $i++;
                    }
                }
                $sql = "UPDATE " . $table . " SET " . $colvalSet . $whereSql;
                $query = $this->conn->prepare($sql);
                $update = $query->execute();
                $api_error['status'] = 'true';
                $api_error['affected_rows'] = $query->rowCount();
            } else {
                $api_error['status'] = 'false';
                $api_error['affected_rows'] = 0;
                $api_error['message'] = "Record not found";
                throw new Exception($api_error);
            }
        } catch (PDOException $e) {
            throw new Exception($e);

        }
        return $api_error;
    }

    public function updateRow($table, $data, $conditions)
    {
        try {
            if (!empty($data) && is_array($data)) {
                $colvalSet = '';
                $whereSql = '';
                $i = 0;

                foreach ($data as $key => $val) {
                    $pre = ($i > 0) ? ', ' : '';
                    $val = $val;
                    $colvalSet .= $pre . $key . "='" . $val . "'";
                    $i++;
                }
                if (!empty($conditions) && is_array($conditions)) {
                    $whereSql .= ' WHERE ';
                    $i = 0;
                    foreach ($conditions as $key => $value) {
                        $pre = ($i > 0) ? ' AND ' : '';
                        $whereSql .= $pre . $key . " = '" . $value . "'";
                        $i++;
                    }
                }
                $sql = "UPDATE " . $table . " SET " . $colvalSet . $whereSql;
                $query = $this->conn->prepare($sql);
                $update = $query->execute();
                $api_error['status'] = 'true';
                $api_error['affected_rows'] = $query->rowCount();
            } else {
                $api_error['status'] = 'false';
                $api_error['affected_rows'] = 0;
                $api_error['message'] = "Record not found";
                throw new Exception($api_error);
            }
        } catch (PDOException $e) {
            throw new Exception($e);

        }
        return $api_error;
    }

    public function deleteRow($tbname, $column_name, $id)
    {
        try {
            $delete_stmt = $this->conn->prepare("DELETE FROM $tbname WHERE $column_name = '" . $id . "'");
            $delete_stmt->bindparam(":$column_name", $id);
            $delete_stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }


    public function voucher_code($site_id)
    {
        $sql = "SELECT id, coupon_code, site_id FROM `op2mro9899_coupons` WHERE site_id = '" . $site_id . "' ORDER BY id DESC LIMIT 0, 1";
        try {
            $stmt_prepare = $this->conn->query($sql);
            $stmt_prepare->execute();
            $voucher = $stmt_prepare->fetchAll(PDO::FETCH_ASSOC);
            return $voucher[0]['coupon_code'];
            $db = null;
        } catch (PDOException $e) {
            echo '{"error":{"text": ' . $e->getMessage() . '}}';
            return false;
        }
    }

    public function isEmailExists($email, $site_id)
    {
        $sql_query = "SELECT user_email, pwd_status FROM op2mro9899_customers_login WHERE user_email = :email AND site_id = :site_id";
        try {
            $stmt_prepare = $this->conn->prepare($sql_query);
            $stmt_prepare->bindParam("email", $email);
            $stmt_prepare->bindParam("site_id", $site_id);
            $stmt_prepare->execute();
            $row_count = $stmt_prepare->rowCount();
            return $row_count;
        } catch (PDOException $e) {
            throw new Exception('Something went wrong');
            echo '{"error":{"text": ' . $e->getMessage() . '}}';
            return false;
        }
    }

    public function login_customer($user_email, $user_password, $site_id)
    {
        $sql = "SELECT id, user_email, user_password, user_first_name, user_last_name, customer_id, unique_key, site_id, pwd_status FROM op2mro9899_customers_login WHERE 
				user_email = :user_email AND user_password = :user_password AND site_id = :site_id";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam("user_email", $user_email);
            $stmt->bindParam("user_password", $user_password);
            $stmt->bindParam("site_id", $site_id);
            $stmt->execute();
            $userRow = $stmt->fetch(PDO::FETCH_OBJ);
            return $userRow;
        } catch (PDOException $e) {
            throw new Exception('Login error');
            echo '{"error":{"text": ' . $e->getMessage() . '}}';
            return false;
        }
    }


    public function holiday_list()
    {
        $current_date = date("Y-m-d");
        try {
            $stmt = $this->conn->prepare("SELECT holiday_date FROM op2mro9899_holiday WHERE holiday_date >= CURDATE() AND allowed_date = 0");
            $stmt->execute();
            $all_records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $all_records;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }


    public function match_holiday_date($holiday_date)
    {
        $sql = "SELECT holiday_date, holidat_desc FROM op2mro9899_holiday WHERE holiday_date = :holiday_date";
        try {
            $holiday_stmts = $this->conn->prepare($sql);
            $holiday_stmts->bindParam('holiday_date', $holiday_date);
            $holiday_stmts->execute();
            $holiday_records = $holiday_stmts->fetchAll(PDO::FETCH_OBJ);
            return $holiday_records;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }


    public function checkOrderID($order_id)
    {
        $sql_query = "SELECT * FROM op2mro9899_tmp_order WHERE order_id = :order_id";
        try {
            $stmt_prepare = $this->conn->prepare($sql_query);
            $stmt_prepare->bindParam("order_id", $order_id);
            $stmt_prepare->execute();
            $order_ids = $stmt_prepare->fetchAll(PDO::FETCH_OBJ);
            return $order_ids;
        } catch (PDOException $e) {
            echo '{"error":{"text": ' . $e->getMessage() . '}}';
            return false;
        }
    }


    public function chk_usr_salt($usr_key)
    {
        $sql = "SELECT id, customer_id, unique_key FROM op2mro9899_customers_login WHERE unique_key = :usr_key";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam("usr_key", $usr_key);
            $stmt->execute();
            $record = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $record;
        } catch (PDOException $e) {
            echo '{"error":{"text": ' . $e->getMessage() . '}}';
            return false;
        }
    }


    public function product_detail_byid($pid)
    {
        $sql = "SELECT * FROM op2mro9899_products WHERE pid = :pid";
        try {
            $statemtnt = $this->conn->prepare($sql);
            $statemtnt->bindParam("pid", $pid);
            $statemtnt->execute();
            $product_records = $statemtnt->fetchAll(PDO::FETCH_OBJ);
            return $product_records;
        } catch (PDOException $e) {
            echo '{"error":{"text": ' . $e->getMessage() . '}}';
            return false;
        }
    }


    function product_detail_by_siteid($product_id, $site_id)
    {
        $sql = "SELECT product_id, price, site_id FROM op2mro9899_products_price WHERE product_id = :product_id AND site_id = :site_id";
        try {
            $stmts = $this->conn->prepare($sql);
            $stmts->bindParam('site_id', $site_id);
            $stmts->bindParam('product_id', $product_id);
            $stmts->execute();
            $productPrices = $stmts->fetchAll(PDO::FETCH_OBJ);
            return $productPrices;
        } catch (PDOException $e) {
            echo '{"error":{"text": ' . $e->getMessage() . '}}';
            return false;
        }
    }

    public function gift_item_byid($gift_id)
    {
        $sql = "SELECT * FROM op2mro9899_gifts_type WHERE id = :gift_id";
        try {
            $gift_statement = $this->conn->prepare($sql);
            $gift_statement->bindParam("gift_id", $gift_id);
            $gift_statement->execute();
            $gift_record = $gift_statement->fetchAll(PDO::FETCH_OBJ);
            return $gift_record;
        } catch (PDOException $e) {
            echo '{"error":{"text": ' . $e->getMessage() . '}}';
            return false;
        }

    }

    public function user_payment_details($oid)
    {
        $sql = "SELECT * FROM op2mro9899_payments WHERE order_id = :oid";
        try {
            $oid_stmt = $this->conn->prepare($sql);
            $oid_stmt->bindparam("oid", $oid);
            $oid_stmt->execute();
            $p_detail = $oid_stmt->fetchAll(PDO::FETCH_OBJ);
            return $p_detail;
        } catch (PDOException $e) {
            echo '{"error":{"text": ' . $e->getMessage() . '}}';
            return false;
        }
    }


    public function invoice_order_details($order_id)
    {
        $sql = "SELECT * FROM op2mro9899_ordered_product WHERE order_id = :order_id";
        try {
            $invoice_stmt = $this->conn->prepare($sql);
            $invoice_stmt->bindparam("order_id", $order_id);
            $invoice_stmt->execute();
            $invoice_order = $invoice_stmt->fetchAll(PDO::FETCH_OBJ);
            return $invoice_order;
        } catch (PDOException $e) {
            echo '{"error":{"text": ' . $e->getMessage() . '}}';
            return false;
        }
    }


    public function user_billing_addresss($user_id, $site_id)
    {
        $sql_stmt = "SELECT * FROM op2mro9899_customers_billing_address WHERE user_id = :user_id AND site_id = :site_id AND default_address = 1";
        try {
            $billing_stmt = $this->conn->prepare($sql_stmt);
            $billing_stmt->bindParam("user_id", $user_id);
            $billing_stmt->bindParam("site_id", $site_id);
            $billing_stmt->execute();
            $billing_address = $billing_stmt->fetchAll(PDO::FETCH_OBJ);
            return $billing_address;
        } catch (PDOException $e) {
            echo '{"error":{"text": ' . $e->getMessage() . '}}';
            return false;
        }
    }

    public function user_billing_addresss_custID($cust_id, $site_id)
    {
        $sql_stmt = "SELECT * FROM op2mro9899_customers_billing_address WHERE customer_id = :cust_id AND site_id = :site_id AND default_address = 1";
        try {
            $billing_stmt = $this->conn->prepare($sql_stmt);
            $billing_stmt->bindParam("cust_id", $cust_id);
            $billing_stmt->bindParam("site_id", $site_id);
            $billing_stmt->execute();
            $billing_address_cust = $billing_stmt->fetchAll(PDO::FETCH_OBJ);
            return $billing_address_cust;
        } catch (PDOException $e) {
            echo '{"error":{"text": ' . $e->getMessage() . '}}';
            return false;
        }
    }

    public function holiday_charges($delivery_date)
    {
        $sql = "SELECT id, holiday_date, special_charges, allowed_date FROM op2mro9899_holiday WHERE holiday_date = :delivery_date AND allowed_date = 1";
        try {
            $del_charges = $this->conn->prepare($sql);
            $del_charges->bindparam("delivery_date", $delivery_date);
            $del_charges->execute();
            $holoday_charge = $del_charges->fetchAll(PDO::FETCH_OBJ);
            return $holoday_charge;
        } catch (PDOException $e) {
            echo '{"error":{"text": ' . $e->getMessage() . '}}';
            return false;
        }
    }


    public function get_category_name_by_id($cat_id)
    {
        $sql = "SELECT cat_id, category_name FROM op2mro9899_category WHERE cat_id = :cat_id";
        try {
            $cat_stmt = $this->conn->prepare($sql);
            $cat_stmt->bindparam('cat_id', $cat_id);
            $cat_stmt->execute();
            $cat_name = $cat_stmt->fetchAll(PDO::FETCH_OBJ);
            return json_encode($cat_name);
        } catch (PDOException $e) {
            echo '{"error":{"text": ' . $e->getMessage() . '}}';
            return false;
        }
    }

    
    public function check_ordered_process($userid, $siteid, $orderdate)
    {

        $sql = "SELECT * FROM op2mro9899_tmp_order WHERE user_id = :userid AND site_id = :siteid AND order_date = :orderdate AND order_process = 0 AND submit_process = 0 ORDER BY id DESC";
        try {
            $dbstmt = $this->conn->prepare($sql);
            $dbstmt->bindparam('userid', $userid);
            $dbstmt->bindparam('siteid', $siteid);
            $dbstmt->bindparam('orderdate', $orderdate);
            $dbstmt->execute();
            $deladdress = $dbstmt->fetch(PDO::FETCH_OBJ);
            return $deladdress;
        } catch (PDOException $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
            return false;
        }

    }

    public function giftStatusByCategoryId($productid)
    {
        $sqlStmt = "SELECT t.cat_id, t.category_name, t.parent_category, t.cat_order, t.gift_status, t1.pid, t1.cat_id, t1.status
                    FROM op2mro9899_category AS t INNER JOIN op2mro9899_products_relation AS t1 ON t.cat_id = t1.cat_id
                    WHERE t1.pid = :productid LIMIT 0 , 1";
        try {
            $stmt = $this->conn->prepare($sqlStmt);
            $stmt->bindParam("productid", $productid);
            $stmt->execute();
            $record = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $record;
        } catch (PDOException $e) {
            echo '{"error":{"text": ' . $e->getMessage() . '}}';
            return false;
        }
    }

    public function prep_postcode($str)
    {
        $str = strtoupper($str);
        $str = trim($str);
        if (substr($str, -4, 1) != ' ')
            $str = substr($str, 0, strlen($str) - 3) . " " . substr($str, -3);
        return $str;
    }

    public function is_postcode($postcode)
    {
        $postcode = str_replace(' ', '', $postcode);
        return
            preg_match("/^[A-Z]{1,2}[0-9]{2,3}[A-Z]{2}$/", $postcode)
            || preg_match("/^[A-Z]{1,2}[0-9]{1}[A-Z]{1}[0-9]{1}[A-Z]{2}$/", $postcode)
            || preg_match("/^GIR0[A-Z]{2}$/", $postcode);
    }

    public function default_delivery_charge($site_id)
    {
        $sql = "SELECT location_name, outer_post_code, inner_post_code, delivery_charges, site_id 
				FROM op2mro9899_shipping WHERE outer_post_code = 'XXX' AND inner_post_code = 'XXX' AND site_id = '" . $site_id . "'";
        try {
            $dbstmt = $this->conn->query($sql);
            $dbstmt->execute();
            $shipping_cost = $dbstmt->fetchAll(PDO::FETCH_OBJ);
            return $shipping_cost;
        } catch (PDOException $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
            return false;
        }

    }


    public function setOuterShippingCharge($outerPostCode, $innerPostCode, $site_id)
    {
        $sql = "SELECT id, location_name, outer_post_code, inner_post_code, delivery_charges, holiday_charges, 
                holiday_date, site_id FROM `op2mro9899_shipping` WHERE site_id = '" . $site_id . "' 
                AND (outer_post_code = '" . $outerPostCode . "' OR inner_post_code = '" . $innerPostCode . "') 
                ORDER BY id DESC";
        try {
            $dbStmt = $this->conn->query($sql);
            $dbStmt->execute();
            $record = $dbStmt->fetch(PDO::FETCH_OBJ);
            return $record;
        } catch (PDOException $exception) {
            echo '{"error":{"text":' . $exception->getMessage() . '}}';
            return false;
        }
    }

    public function get_shipping_charge($post_code, $site_id)
    {
        $chkPcodes = $this->prep_postcode($post_code);
        $explode = explode(' ', $chkPcodes);
        $outerPostCode = $explode[0];
        $innerPostCode = $explode[1];

        $sql = "SELECT location_name, outer_post_code, inner_post_code, delivery_charges, holiday_charges, holiday_date, site_id  FROM `op2mro9899_shipping` 
                WHERE site_id = '" . $site_id . "' AND outer_post_code = '" . $outerPostCode . "' AND inner_post_code = '" . $innerPostCode . "'";
        try {
            $dbstmt = $this->conn->query($sql);
            $dbstmt->execute();
            $shipping_cost = $dbstmt->fetch(PDO::FETCH_OBJ);
            if (empty($shipping_cost)) {
                $potCode = $this->setOuterShippingCharge($outerPostCode, $innerPostCode, $site_id);
                if (empty($potCode)) {
                    $potCode = $this->default_delivery_charge($site_id);
                }
                return $potCode;
            } else {
                return $shipping_cost;
            }
        } catch (PDOException $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
            return false;
        }
    }

    public function getDefaultUser($customer_id, $site_id)
    {
        $sql = "SELECT id, user_email, site_id, customer_id FROM op2mro9899_customers_login WHRE customer_id = :customer_id AND site_id = :site_id";
        try {
            $dbstmt = $this->conn->prepare($sql);
            $dbstmt->bindParam("customer_id", $customer_id);
            $dbstmt->bindParam("site_id", $site_id);
            $dbstmt->execute();
            $record = $dbstmt->fetch(PDO::FETCH_OBJ);
            return $record;
        } catch (PDOException $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
            return false;
        }
    }
}
