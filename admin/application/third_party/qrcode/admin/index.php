<?php    

    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'../qrimages/'.DIRECTORY_SEPARATOR;

    //html PNG location prefix
    $PNG_WEB_DIR = '../qrimages/';
    include "../qrcode/qrlib.php";    

    //ofcourse we need rights to create temp dir
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    
    
 $filename = $PNG_TEMP_DIR.'test.png';
    
    //processing form input
    //remember to sanitize user input in real-life solution !!!
    $errorCorrectionLevel = 'L';
    if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
        $errorCorrectionLevel = "H";    

    $matrixPointSize = 4;
      //  if(isset($_REQUEST['size']))
        $matrixPointSize = min(max((int)500, 1), 10);


	
	    //it's very important!
       // if(trim($_REQUEST['data']) == '')
         //   die('data cannot be empty! <a href="?">back</a>');            
        // user data
		$data="9";
        $filename = $PNG_TEMP_DIR.'chefkay'.md5($data.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
        QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);        
  echo $PNG_WEB_DIR.basename($filename);

  //  echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" /><hr/>';  


    