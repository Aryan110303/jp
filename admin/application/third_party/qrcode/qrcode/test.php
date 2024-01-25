<?php 
function google_qr($url,$size ='150',$EC_level='L',$margin='0') {

$url = urlencode($url);
echo '<img src="chart.apis.google.com/chart?chs='.$size.'x'.$size.'&cht=qr&chld='.$EC_level.'|'.$margin.'&chl='.$url.'" alt="QR code" width="'.$size.'" height="'.$size.'"/>';
}

google_qr('www.google.com',452523452345);
?> 