<?php
include_once 'smtpmail/library.php';
include_once 'smtpmail/classes/class.phpmailer.php';

function smtpmailer($to, $from, $from_name = 'Fleur de Lis Florist, Maidenhead', $subject, $body, $is_gmail = true){
	global $error;
	$mail = new PHPMailer();
	$mail->CharSet = "UTF-8";
	$mail->IsSMTP();
	$mail->Debugoutput = 'html';
	$mail->SMTPAuth = true; 
	//$mail->SMTPSecure = "tls";
	$mail->Host = SMTP_HOST;
	$mail->Port = SMTP_PORT;  
	$mail->Username = SMTP_UNAME;  
	$mail->Password = SMTP_PWORD;   
	$mail->IsHTML(true);
	$mail->From="orders@fleurdelisflorist.co.uk";
	$mail->FromName="Fleur de Lis Florist, Maidenhead";
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


function password_smtpmailer($to, $from, $from_name = 'Fleur de Lis Florist, Maidenhead', $subject, $body, $is_gmail = true){
	global $error;
	$mail = new PHPMailer();
	$mail->CharSet = "UTF-8";
	$mail->IsSMTP();
	$mail->Debugoutput = 'html';
	$mail->SMTPAuth = true; 
	//$mail->SMTPSecure = "tls";
	$mail->Host = ADMIN_SMTP_HOST;
	$mail->Port = ADMIN_SMTP_PORT;  
	$mail->Username = ADMIN_SMTP_UNAME;  
	$mail->Password = ADMIN_SMTP_PWORD;   
	$mail->IsHTML(true);
	$mail->From="info@fleurdelisflorist.co.uk";
	$mail->FromName="Fleur de Lis Florist, Maidenhead";
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


function randomPassword(){
	$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	$pass = array(); //remember to declare $pass as an array
	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	for ($i = 0; $i < 8; $i++) {
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}
	return implode($pass); //turn the array into a string
}



function convert_number_to_wordss($number) {

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
			return $negative . $this->convert_number_to_words(abs($number));
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
					$string .= $conjunction . $this->convert_number_to_words($remainder);
				}
			break;
			default:
			$baseUnit = pow(1000, floor(log($number, 1000)));
			$numBaseUnits = (int) ($number / $baseUnit);
			$remainder = $number % $baseUnit;
			$string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
			if ($remainder) {
				$string .= $remainder < 100 ? $conjunction : $separator;
				$string .= $this->convert_number_to_words($remainder);
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


/*$msg  = 'Hello World';
$subj = 'test mail message';
$to   = 'gaurav.s@perceptive-solutions.com';
$from = 'cksingh.s@perceptive-solutions.com';
$name = 'My Name';

echo smtpmailer($to,$from, $name ,$subj, $msg);
*/
?>
