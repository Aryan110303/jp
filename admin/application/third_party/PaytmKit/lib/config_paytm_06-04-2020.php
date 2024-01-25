<?php
/*
- Use PAYTM_ENVIRONMENT as 'PROD' if you wanted to do transaction in production environment else 'TEST' for doing transaction in testing environment.
- Change the value of PAYTM_MERCHANT_KEY constant with details received from Paytm.
- Change the value of PAYTM_MERCHANT_MID constant with details received from Paytm.
- Change the value of PAYTM_MERCHANT_WEBSITE constant with details received from Paytm.
- Above details will be different for testing and production environment.
*/

define('PAYTM_ENVIRONMENT', 'TEST'); // PROD
//define('PAYTM_MERCHANT_KEY', 'NkDzsQs!mLSK5jnX');
//define('PAYTM_MERCHANT_KEY', 'NkDzsQs!mLSK5jnX');
define('PAYTM_MERCHANT_KEY', 'NkDzsQs!mLSK5jnX');
 //Change this constant's value with Merchant key received from Paytm.
// define('PAYTM_MERCHANT_KEY', '7sj0&YXIZtMh4O%l'); //Change this constant's value with Merchant key received from Paytm.
// define('PAYTM_MERCHANT_KEY', 'mZ&ugiN8AxNc169v'); //Change this constant's value with Merchant key received from Paytm.
//define('PAYTM_MERCHANT_MID', 'Centra96677453879384'); //Change this constant's value with MID (Merchant ID) received from Paytm.
//define('PAYTM_MERCHANT_MID', 'jZyrKp88742992158056'); //Change this constant's value with MID (Merchant ID) received from Paytm.
define('PAYTM_MERCHANT_MID', 'Centra96677453879384'); //Change this constant's value with MID (Merchant ID) received from Paytm.
// define('PAYTM_MERCHANT_MID', 'CuZmON72900584729742'); //Change this constant's value with MID (Merchant ID) received from Paytm.
// define('PAYTM_MERCHANT_MID', 'rLnTMz40578791060171'); //Change this constant's value with MID (Merchant ID) received from Paytm.
define('PAYTM_MERCHANT_WEBSITE', 'APPSTAGING'); //Change this constant's value with Website name received from Paytm.


$PAYTM_STATUS_QUERY_NEW_URL='https://securegw-stage.paytm.in/merchant-status/getTxnStatus';
$PAYTM_TXN_URL='https://securegw-stage.paytm.in/theia/processTransaction';
if (PAYTM_ENVIRONMENT == 'PROD') {
	$PAYTM_STATUS_QUERY_NEW_URL='https://securegw.paytm.in/merchant-status/getTxnStatus';
	$PAYTM_TXN_URL='https://securegw.paytm.in/theia/processTransaction';
}

define('PAYTM_REFUND_URL', '');
define('PAYTM_STATUS_QUERY_URL', $PAYTM_STATUS_QUERY_NEW_URL);
define('PAYTM_STATUS_QUERY_NEW_URL', $PAYTM_STATUS_QUERY_NEW_URL);
define('PAYTM_TXN_URL', $PAYTM_TXN_URL);
?>
