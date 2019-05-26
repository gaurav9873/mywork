<?php
date_default_timezone_set('Europe/London');
// include autoloader
require_once 'dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

$url = $_REQUEST['invoice'];
$html = file_get_contents($url);
$htms = $html;
//print_r($htms); die;
//print_r($html); die;
$dompdf = new Dompdf();
$dompdf->set_option('enable_remote', true);
$dompdf->set_option('enable_css_float', true);
$dompdf->set_option('enable_html5_parser', true);
//$dompdf->set_option('defaultFont', 'times');
//$dompdf->set_option("fontHeightRatio",0.90);
//$dompdf->set_option('enable_unicode', true);
$dompdf->set_option('enable_font_subsetting', true); 
$dompdf->set_option('enable_php', true);
$dompdf->set_option('debug_layout_inline', true);
$dompdf->set_option( 'dpi' , '300' );
$dompdf->setPaper('A4', 'potrait'); 
$dompdf->load_html($htms);
$dompdf->render();
// Output the generated PDF (1 = download and 0 = preview)
$dompdf->stream("invoice",array("Attachment"=>0));
?>
