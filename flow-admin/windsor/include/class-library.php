<?php

class CustomFunctions
{

    public function getCurl($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        //print_r($result);
        curl_close($curl);
        return json_decode($result);
    }


    public function getData($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        //print_r($result);
        curl_close($curl);
        return $result;
    }


    public function httpPost($url, $params)
    {

        $data_string = json_encode($params);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
        //print_r($result);
        curl_close($ch);
        return json_decode($result);

    }


    public function httpGet($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public function cleanData($str)
    {
        $str = urldecode($str);
        $str = filter_var($str, FILTER_SANITIZE_STRING);
        $str = filter_var($str, FILTER_SANITIZE_SPECIAL_CHARS);
        return $str;
    }

    public function createRandomVal($val)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789,-";
        srand((double)microtime() * 1000000);
        $i = 0;
        $pass = '';
        while ($i <= $val) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }
        return $pass;
    }


    public function EncryptClientId($id)
    {
        return substr(md5($id), 0, 8) . dechex($id);
    }

    public function DecryptClientId($id)
    {
        $md5_8 = substr($id, 0, 8);
        $real_id = hexdec(substr($id, 8));
        return ($md5_8 == substr(md5($real_id), 0, 8)) ? $real_id : 0;
    }

    public function is_ajax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }


    public function searchForId($id, $array)
    {
        foreach ($array as $key => $val) {
            if ($val['product_id'] === $id) {
                return $val;
            }
        }
        return null;
    }

    public function implodeArrayKeys($array)
    {
        return implode(", ", array_values($array));
    }

    public function array_implode($a)
    {
        // print detailed info if $a is not array
        if (!is_array($a)) {
            var_dump(debug_backtrace()); // where exactly was it called?
            exit;
        }
        return implode(',', $a);
    }

    public function product_quantity($selected)
    {
        $rand = range(1, 30);
        foreach ($rand as $val) {
            $sel = (($selected == $val) ? 'selected' : '');
            echo '<option ' . $sel . ' value="' . $val . '">' . $val . '</option>';
        }
    }

    public function array_search_key($needle_key, $array)
    {
        foreach ($array AS $key => $value) {
            if ($key == $needle_key) return $value;
            if (is_array($value)) {
                if (($result = array_search_key($needle_key, $value)) !== false)
                    return $result;
            }
        }
        return false;
    }


    public function array_find_deep($array, $search, $keys = array())
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $sub = array_find_deep($value, $search, array_merge($keys, array($key)));
                if (count($sub)) {
                    return $sub;
                }
            } elseif ($value === $search) {
                return array_merge($keys, array($key));
            }
        }
        return array();
    }


    /*public function calculatePercentage($oldFigure, $newFigure){
        $percentChange = (($oldFigure - $newFigure) / $oldFigure) * 100;
        return round(abs($percentChange));
    }*/


    public function calculatePercentage($cur, $prev)
    {
        if ($cur == 0) {
            if ($prev == 0) {
                return array('diff' => 0, 'trend' => '');
            }
            return array('diff' => -($prev * 100), 'trend' => 'down_arrow');
        }
        if ($prev == 0) {
            return array('diff' => $cur * 100, 'trend' => 'up_arrow');
        }
        $difference = ($cur - $prev) / $prev * 100;
        $trend = '';
        if ($cur > $prev) {
            $trend = 'up_arrow';
        } else if ($cur < $prev) {
            $trend = 'down_arrow';
        }
        return array('diff' => round($difference, 0), 'trend' => $trend);
    }


    public function xss_clean($data)
    {
        // Fix &entity\n;
        $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        } while ($old_data !== $data);
        return $data;
    }

    public function countProduct()
    {
        if (!empty($_SESSION['cart']['products'])) {
            $count = count($_SESSION['cart']['products']);
        }

        if (!empty($_SESSION['cart']['gift'])) {
            $gift_count = count($_SESSION['cart']['gift']);
        }

        $product_val = isset($count) ? $count : '0';
        $gift_val = isset($gift_count) ? $gift_count : '0';
        $val = $product_val + $gift_val;
        return $val;
    }

    /*public function CheckSession($string){
        $all_sessions_exist = true;
        $keys = explode(',', $string);
        foreach($keys as $key){
            if(isset($_SESSION[$key])) {
                if(!empty($_SESSION[$key]))
                echo '$_SESSION['.$key.'] is set and contains "'.$_SESSION[$key].'".';
                else echo '$_SESSION['.$key.'] is set and is empty.' ;
            }else {
                echo '$_SESSION['.$key.'] is not set.';
                $all_sessions_exist = false;
            }
            echo '<br />';
        }
        return $all_sessions_exist;
    }*/


}

?>
