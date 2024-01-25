<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customsms {
//http://login.heightsconsultancy.com/API/WebSMS/Http/v1.0a/index.php?username=casjbp&password=password&sender=CASJBP&to=9302864646&message=Hello&reqid=1&format=%7bjson%7Ctext%7d&route_id=113
    private $_CI;
    var $AUTH_KEY = ""; //your AUTH_KEY here
    var $senderId = "CASJBP"; //your senderId here
    var $routeId = "113"; //your routeId here
    var $smsContentType = "";
    var $username = "casjbp";
    var $password = "password"; //your smsContentType here

    function __construct() {
        $this->_CI = & get_instance();
        $this->session_name = $this->_CI->setting_model->getCurrentSessionName();
    }

    // function sendSMS($to, $message) {
    //     $content = 'AUTH_KEY=' . rawurlencode($this->AUTH_KEY) .
    //             '&message=' . rawurlencode($message) .
    //             '&senderId=' . rawurlencode($this->senderId) .
    //             '&routeId=' . rawurlencode($this->routeId) .
    //             '&mobileNos=' . rawurlencode($to) .
    //             '&smsContentType=' . rawurlencode($this->smsContentType);
    //     $ch = curl_init('http://login.heightsconsultancy.com/API/WebSMS/Http/v1.0a/index.php?' . $content);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     $response = curl_exec($ch);
    //     curl_close($ch);
    //     return $response;
    // }

    function sendSMS($to,$message,$msg_type) {
       $message=  str_replace("&nbsp;","%0A", $message);
        $content ='username='.rawurlencode($this->username).
                '&password='.rawurlencode($this->password).
                '&sender='.rawurlencode($this->senderId).
                '&to='.rawurlencode($to).
                '&message='.$message.
                '&reqid='.rawurlencode(1).
                '&format={json|text}'.
                '&route_id='.rawurlencode($this->routeId);
       
        if($msg_type==1){
                $content1 ='username='.rawurlencode($this->username).
                '&password='.rawurlencode($this->password).
                '&sender='.rawurlencode($this->senderId).
                '&to='.rawurlencode($to).
                '&message='.rawurlencode($message).
                '&reqid='.rawurlencode(1).
                '&format={json|text}'.
                '&route_id='.rawurlencode($this->routeId).
                '&msgtype=unicode';
              	$test=  str_replace("%250A","%0A", $content1);
                $test=  str_replace("%C2%A0","%0A", $content1);
             }else{
                 
              $content1 ='username='.rawurlencode($this->username).
                '&password='.rawurlencode($this->password).
                '&sender='.rawurlencode($this->senderId).
                '&to='.rawurlencode($to).
                '&message='.rawurlencode($message).
                '&reqid='.rawurlencode(1).
                '&format={json|text}'.
                '&route_id='.rawurlencode($this->routeId);
              	$test=  str_replace("%250A","%0A", $content1);
                $test=  str_replace("%C2%A0","%0A", $content1);   
                 
                 
             } 
        $ch = curl_init('http://login.heightsconsultancy.com/API/WebSMS/Http/v1.0a/index.php?'.$test);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    
    /*function sendSMS($to, $message) {
        $message=str_replace("&nbsp;","%0D%0A", $message);
        $content ='username='.urlencode($this->username).
                '&password='.urlencode($this->password).
                '&sender='.urlencode($this->senderId).
                '&to='.urlencode($to).
                '&message='.urlencode($message).
                '&reqid='.urlencode(1).
                '&format={json|text}'.
                '&route_id='.urlencode($this->routeId);
                
        $ch = curl_init('http://login.heightsconsultancy.com/API/WebSMS/Http/v1.0a/index.php?'.$content);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }*/

}
?>