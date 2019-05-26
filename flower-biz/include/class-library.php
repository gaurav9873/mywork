<?php
include_once 'jwt_helper.php';
include_once 'smtpmail/library.php';
include_once 'smtpmail/classes/class.phpmailer.php';
class CustomFunctions{
	
	const HOST = SMTP_HOST;
	const PORT = SMTP_PORT;
	const UNAME = SMTP_UNAME;
	const PASS = SMTP_PWORD;
	
	public function getCurl($url){
		$jwthelper = new JWT();
		$key = "pjuJ-Xp;/t0y<.X:#06.]7&M[YWLly.sOa0h|t!{yRnG,B!RF`r}CfNQ{)#w*f";
		$token = array("user_id"=> '8302e8318c2ed9cc976c54f45dfcebd3');
		$jwthelper = JWT::encode($token, $key);
		$curl = curl_init();
		$headers = array(
             'Authorization: '.$jwthelper,
        );
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($curl);
		//print_r($result);
		curl_close($curl);
		return json_decode($result);
	}
	
	
	public function getData($url){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		//print_r($result);
		curl_close($curl);
		return $result;
	}
	
	
	public function httpPost($url,$params){
		$data_string = json_encode($params); 
		$jwthelper = new JWT();
		$key = "pjuJ-Xp;/t0y<.X:#06.]7&M[YWLly.sOa0h|t!{yRnG,B!RF`r}CfNQ{)#w*f";
		$token = array("user_id"=> '8302e8318c2ed9cc976c54f45dfcebd3');
		$jwthelper = JWT::encode($token, $key);
		$data_string = json_encode($params);                                                                                   
		$ch = curl_init($url);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                         
		'Content-Type: application/json',
		'Authorization: '.$jwthelper,                                                                             
		'Content-Length: ' . strlen($data_string))                                                                       
		);                                                                                                                   
								 
		$result = curl_exec($ch);
		//print_r($result);
		curl_close($ch);
		return json_decode($result);

	}
	
	

	public function httpGet($url){
		$ch = curl_init();  
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_HEADER, false); 
		$output=curl_exec($ch);
		curl_close($ch);
		return $output;
	}

	public function cleanData($str) {
		$str = urldecode ($str );
		$str = filter_var($str, FILTER_SANITIZE_STRING);
		$str = filter_var($str, FILTER_SANITIZE_SPECIAL_CHARS);
		return $str ;
	}
	
	public function baseImage($f){
		$image=file_get_contents($f);
		$encrypted=base64_encode($image);
		$url="data:image/png;base64,".$encrypted;
		return $url;
	}

	public function createRandomVal($val){
		$chars="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789,-";
		srand((double)microtime()*1000000);
		$i = 0;
		$pass = '' ;
		while ($i<=$val) 
		{
			$num  = rand() % 33;
			$tmp  = substr($chars, $num, 1);
			$pass = $pass . $tmp;
			$i++;
		}
		return $pass;
	}
	
	
	public function randomPassword(){
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}
	
	
	public function delivery_date_time(){
		$current_time = date('G');
		$optionsStr = '';
		if($current_time >= 15){
			$date = new DateTime();
			$date->add(new DateInterval('P1D'));
			//echo $date->format('l-d-F');
			$optionsStr = $date->format('l').' '. $date->format('d').' '.$date->format('F');
		}else{
			$optionsStr = date("l").' '.date("d").' '.date("F");
		}
		
		
		return $optionsStr;
	}
	
	
	public function getUserIP(){
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];

		if(filter_var($client, FILTER_VALIDATE_IP)){
			$ip = $client;
		}
		elseif(filter_var($forward, FILTER_VALIDATE_IP)){
			$ip = $forward;
		} else {
			$ip = $remote;
		}
		return $ip;
	}
	
	
	public function unique_salt() {
		return substr(sha1(mt_rand()), 0, 22);
	}
	
	public function EncryptClientId($id){
		return substr(md5($id) , 0, 8) . dechex($id);
	}

	public function DecryptClientId($id){
		$md5_8 = substr($id, 0, 8);
		$real_id = hexdec(substr($id, 8));
		return ($md5_8 == substr(md5($real_id) , 0, 8)) ? $real_id : 0;
	}
	
	public function is_ajax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}
	
	
	public function searchForId($id, $array) {
		foreach ($array as $key => $val) {
			if ($val['product_id'] === $id) {
				return $val;
			}
		}
		return null;
	}
	
	public function implodeArrayKeys($array) {
		return implode(", ",array_values($array));
	}
	
	public function array_implode($a) {
		// print detailed info if $a is not array
		if(!is_array($a)) {
			var_dump(debug_backtrace()); // where exactly was it called?
			exit;
		}
		return implode(',', $a);
	}
	
	public function product_quantity($selected){
		$rand = range(1,30);
		foreach($rand as $val){
			$sel = (($selected == $val) ? 'selected' : '');
			echo '<option '.$sel.' value="'.$val.'">'.$val.'</option>';
		}
	}
	
	public function array_search_key( $needle_key, $array ) {
		foreach($array AS $key=>$value){
			if($key == $needle_key) return $value;
			if(is_array($value)){
				if( ($result = array_search_key($needle_key,$value)) !== false)
					return $result;
			}
		}
		return false;
	} 
	
	
	public function array_find_deep($array, $search, $keys = array()){
	foreach($array as $key => $value) {
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
	
	
	public function calculatePercentage($cur, $prev) {
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
	
	
	
	public function xss_clean($data){
		// Fix &entity\n;
		$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
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

		do
		{
			// Remove really unwanted tags
			$old_data = $data;
			$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
		}
		while ($old_data !== $data);
		return $data;
	}
	
	public function countProduct(){
		if(!empty($_SESSION['cart']['products'])){
			$standard_count = isset($_SESSION['cart']['products']['Standard']) ? count($_SESSION['cart']['products']['Standard']):'0';
			$large_count = isset($_SESSION['cart']['products']['Large']) ? count($_SESSION['cart']['products']['Large']): '0';
			$count = $standard_count+$large_count;
		}
		
		if(!empty($_SESSION['cart']['gift'])){
			$gift_count = count($_SESSION['cart']['gift']);
		}
		
		$product_val = isset($count) ? $count : '0';
		$gift_val = isset($gift_count) ? $gift_count : '0';
		$val = $product_val + $gift_val;
		return $val;
	}
	
	
	public function smtpmailer($to, $from, $from_name = 'Nicola Florist', $subject, $body, $is_gmail = true){
		global $error;
		$mail = new PHPMailer();
		$mail->CharSet = "UTF-8";
		$mail->IsSMTP();
		$mail->Debugoutput = 'html';
		$mail->SMTPAuth = true; 
		$mail->SMTPSecure = "tls";
		$mail->Host = CustomFunctions::HOST;
		$mail->Port = CustomFunctions::PORT;
		$mail->Username = CustomFunctions::UNAME;
		$mail->Password = CustomFunctions::PASS;
		$mail->IsHTML(true);
		$mail->From="orders@nicolaflorist.co.uk";
		$mail->FromName="Flower Corner";
		$mail->Sender=$from; // indicates ReturnPath header
		$mail->AddReplyTo($from, $from_name); // indicates ReplyTo headers
		//$mail->AddCC('cc@site.com.com', 'CC: to site.com');
		$mail->Subject = $subject;
		$mail->Body = $body;
		$mail->AddAddress($to);
		if(!$mail->Send()){
			$error = 'Mail error: '.$mail->ErrorInfo;
			return true;
		}else{
			$error = 'Message sent Successfully!';
			return false;
		}
	}
	
	public function password_smtpmailer($to, $from, $from_name = 'Nicola Florist', $subject, $body, $is_gmail = true){
		global $error;
		$mail = new PHPMailer();
		$mail->CharSet = "UTF-8";
		$mail->IsSMTP();
		$mail->Debugoutput = 'html';
		$mail->SMTPAuth = true; 
		$mail->SMTPDebug = 1;
		$mail->SMTPSecure = "tls";
		$mail->Host = CustomFunctions::HOST;
		$mail->Port = CustomFunctions::PORT;  
		$mail->Username = CustomFunctions::UNAME;  
		$mail->Password = CustomFunctions::PASS; 
		$mail->IsHTML(true);
		$mail->From="info@nicolaflorist.co.uk";
		$mail->FromName="Nicola Florist";
		$mail->Sender=$from; // indicates ReturnPath header
		$mail->AddReplyTo($from, $from_name); // indicates ReplyTo headers
		$mail->Subject = $subject;
		$mail->Body = $body;
		$mail->AddAddress($to);
		if(!$mail->Send()){
			$error = 'Mail error: '.$mail->ErrorInfo;
			return $error;
		}else{
			$error = 'Message sent Successfully!';
			return $error;
		}
	}
	
	
	public function convert_number_to_wordss($number) {

		$hyphen      = '-';
		$conjunction = ' and ';
		$separator   = ', ';
		$negative    = 'negative ';
		$decimal     = ' point ';
		$dictionary  = array(
		0                   => 'zero',
		1                   => 'one',
		2                   => 'two',
		3                   => 'three',
		4                   => 'four',
		5                   => 'five',
		6                   => 'six',
		7                   => 'seven',
		8                   => 'eight',
		9                   => 'nine',
		10                  => 'ten',
		11                  => 'eleven',
		12                  => 'twelve',
		13                  => 'thirteen',
		14                  => 'fourteen',
		15                  => 'fifteen',
		16                  => 'sixteen',
		17                  => 'seventeen',
		18                  => 'eighteen',
		19                  => 'nineteen',
		20                  => 'twenty',
		30                  => 'thirty',
		40                  => 'fourty',
		50                  => 'fifty',
		60                  => 'sixty',
		70                  => 'seventy',
		80                  => 'eighty',
		90                  => 'ninety',
		100                 => 'hundred',
		1000                => 'thousand',
		1000000             => 'million',
		1000000000          => 'billion',
		1000000000000       => 'trillion',
		1000000000000000    => 'quadrillion',
		1000000000000000000 => 'quintillion'
		);

		if (!is_numeric($number)) {
			return false;
		}

		if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
			// overflow
			trigger_error(
			'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
			E_USER_WARNING
			);
			return false;
		}

		if ($number < 0) {
			return $negative . $this->convert_number_to_wordss(abs($number));
		}

		$string = $fraction = null;

		if (strpos($number, '.') !== false) {
			list($number, $fraction) = explode('.', $number);
		}

		switch (true) {
			case $number < 21:
				$string = $dictionary[$number];
			break;
			case $number < 100:
				$tens   = ((int) ($number / 10)) * 10;
				$units  = $number % 10;
				$string = $dictionary[$tens];
				if ($units) {
					$string .= $hyphen . $dictionary[$units];
				}
			break;
			case $number < 1000:
				$hundreds  = $number / 100;
				$remainder = $number % 100;
				$string = $dictionary[$hundreds] . ' ' . $dictionary[100];
				if ($remainder) {
					$string .= $conjunction . $this->convert_number_to_wordss($remainder);
				}
			break;
			default:
			$baseUnit = pow(1000, floor(log($number, 1000)));
			$numBaseUnits = (int) ($number / $baseUnit);
			$remainder = $number % $baseUnit;
			$string = $this->convert_number_to_wordss($numBaseUnits) . ' ' . $dictionary[$baseUnit];
			if ($remainder) {
				$string .= $remainder < 100 ? $conjunction : $separator;
				$string .= $this->convert_number_to_wordss($remainder);
			}
			break;
		}

		if (null !== $fraction && is_numeric($fraction)) {
			$string .= $decimal;
			$words = array();
			foreach (str_split((string) $fraction) as $number) {
				$words[] = $dictionary[$number];
			}
			$string .= implode(' ', $words);
		}

		return $string;
	}
	

}