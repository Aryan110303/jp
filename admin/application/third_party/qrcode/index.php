<?php    
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'../qrimg/'.DIRECTORY_SEPARATOR;
    $PNG_WEB_DIR = '../qrimg/';
    include "qrcode/qrlib.php";    
    if (!file_exists($PNG_TEMP_DIR))
    mkdir($PNG_TEMP_DIR);
	$errorCorrectionLevel = 'H';    
    $matrixPointSize = 8;
	$data="localhost:123123.php";
    $filename = $PNG_TEMP_DIR.md5($data.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
    QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);        
    //echo $PNG_WEB_DIR.basename($filename);
	//echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" /><hr/>';	       
?>	