<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With,Content-Type');
header('Access-Control-Allow-Methods: POST,GET,OPTIONS');

/**

 * 

 */

class Sendsmscron extends CI_Controller {

 public function cron_sms() {

        $result = $this->messages_model->getPendingSmsList();

//$staff_no=',9630493200,9827074778,7566707465,7869329007,9329955577,6261191221,7415431559,9650786005,9424312866,8305767956,9903814935';

        if (!empty($result)) {

            foreach ($result as $list) {

                

                $class_id = $list['class_id'];

                $section_id = $list['section_id'];

                $session_id = $list['session_id'];



                if ($list['type'] == 2) {

                    $send_no = $list['user_list'].','.$list['other_number'];

                } else {

                    //$numbers = $this->messages_model->get_all_student_no($class_id, $section_id, $session_id);

                    $send_no = $list['user_list'].','.$list['other_number'];

                }



                if ($send_no) {

                    $contactNumber = $send_no;

                    $message = $list['message'];

                    $messageId = $list['id'];

                    $msg_type = $list['msg_type'];

                    $response_sms='';

                    $data = array(

                        'sms_status' => 1,

                        'updated_at'=>date('Y-m-d h:i:sa')

                    );

                    $status = $this->messages_model->updateSmsStatus($data, $messageId);



                    if (1) {

                       $response_sms=  $this->smsgateway->sendSMS($contactNumber, strip_tags($message),$msg_type);

					  if($response_sms!=''){
					  
					   $arr=unserialize(trim($response_sms));

					   //$status = $this->messages_model->updateSmsStatus($data, $messageId);

					 if($arr['billcredit']>0){
					  $data = array(
                        'sms_status' => 1,
                        'updated_at'=>date('Y-m-d h:i:sa'),
						'msg_id'=>$arr['msg_id']
                      );
					  
					   $status = $this->messages_model->updateSmsStatus($data, $messageId);
                     } 
                     elseif($arr['billcredit']<1){
                        $data = array(
                            'sms_status' => 55,
                            'updated_at'=>date('Y-m-d h:i:sa'),
                            'msg_id'=>$arr['msg_id']
                          );
                          
                           $status = $this->messages_model->updateSmsStatus($data, $messageId);
                     }

					  

					  }

					   //print_r($response_sms);die;

                    }

                }

            }

            //redirect('admin/smslist/get_reports');

        } /*else {

            redirect('admin/smslist/get_reports');

        }*/

    }

 public function cron_sms2() {

        $result = $this->messages_model->getPendingSmsList2();

//$staff_no=',9630493200,9827074778,7566707465,7869329007,9329955577,6261191221,7415431559,9650786005,9424312866,8305767956,9903814935';

        if (!empty($result)) {

            foreach ($result as $list) {

                

                $class_id = $list['class_id'];

                $section_id = $list['section_id'];

                $session_id = $list['session_id'];



                if ($list['type'] == 2) {

                    $send_no = $list['user_list'].','.$list['other_number'];

                } else {

                    //$numbers = $this->messages_model->get_all_student_no($class_id, $section_id, $session_id);

                    $send_no = $list['user_list'].','.$list['other_number'];

                }



                if ($send_no) {

                    $contactNumber = $send_no;

                    $message = $list['message'];

                    $messageId = $list['id'];

                    $msg_type = $list['msg_type'];

                    $response_sms='';

                    $data = array(

                        'sms_status' => 1,

                        'updated_at'=>date('Y-m-d h:i:sa')

                    );

                    //$status = $this->messages_model->updateSmsStatus($data, $messageId);



                    if (1) {

                       $response_sms=  $this->smsgateway->sendSMS2($contactNumber, strip_tags($message),$msg_type);

					  if($response_sms!=''){

					   $status = $this->messages_model->updateSmsStatus($data, $messageId);

					 

					  

					  }

					   //print_r($response_sms);die;

                    }

                }

            }

            //redirect('admin/smslist/get_reports');

        } /*else {

            redirect('admin/smslist/get_reports');

        }*/

    }
    
 public function cron_sms3() {

        $result = $this->messages_model->getPendingSmsList();

//$staff_no=',9630493200,9827074778,7566707465,7869329007,9329955577,6261191221,7415431559,9650786005,9424312866,8305767956,9903814935';

        if (!empty($result)) {

            foreach ($result as $list) {

                

                $class_id = $list['class_id'];

                $section_id = $list['section_id'];

                $session_id = $list['session_id'];



                if ($list['type'] == 2) {

                    $send_no = $list['user_list'].','.$list['other_number'];

                } else {

                    //$numbers = $this->messages_model->get_all_student_no($class_id, $section_id, $session_id);

                    $send_no = $list['user_list'].','.$list['other_number'];

                }



                if ($send_no) {

                    $contactNumber = $send_no;

                    $message = $list['message'];

                    $messageId = $list['id'];

                    $msg_type = $list['msg_type'];

                    $response_sms='';

                    $data = array(

                        'sms_status' => 1,

                        'updated_at'=>date('Y-m-d h:i:sa')

                    );

                    //$status = $this->messages_model->updateSmsStatus($data, $messageId);



                    if (1) {

                       $response_sms=  $this->smsgateway->sendSMS($contactNumber, strip_tags($message),$msg_type);

					  if($response_sms!=''){

					   $status = $this->messages_model->updateSmsStatus($data, $messageId);

					 

					  

					  }

					   //print_r($response_sms);die;

                    }

                }

            }

            //redirect('admin/smslist/get_reports');

        } /*else {

            redirect('admin/smslist/get_reports');

        }*/

    } 
	
	public function cron_notification(){
	$result = $this->messages_model->getPendingNotiList();
	if (!empty($result)){
		foreach ($result as $list) {
			$student_id = $list['student_id'];
			$message = $list['message'];
			$messageId = $list['id'];
			if($student_id!=0){
				$device_token=$this->db->get_where('students',array('id'=>$student_id))->row()->device_token;
				if($device_token!=''){
					$top['to'] = $device_token ;
					$top['data'] = array('data' => array('type' => 'Request', 'title' => 'LKS Notification', 'is_background' => false, 'message' => $message, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
					$result=   $this->sendPushNotificationTwo($top);
					if($result != FALSE){
						$data = array(
						'noti_status' => 1,
						'updated_at'=>date('Y-m-d h:i:sa')
						);
						$status = $this->messages_model->updateSmsStatus($data, $messageId);	
					}
					echo 'chala';
				}
			}
		}
	}
	
}	
 
public function sendPushNotificationTwo($fields) { 

$FIREBASE_API_KEY_TWO = 'AIzaSyCO04Q-DB0SX5JXrsz3gqj4oFAbv3m8MwQ';
//this key2 is a legacy key so always use legacy key not a 
//$FIREBASE_API_KEY_TWO = 'AIzaSyD4dJqnFRb-5ka75wH3HKdNcd7XEm7jLc0';
// Set POST variables
$url = 'https://fcm.googleapis.com/fcm/send'; 
$headers = array(
'Authorization: key=' . $FIREBASE_API_KEY_TWO,
'Content-Type: application/json'
);
// Open connection
$ch = curl_init();

// echo json_encode($fields);
// Set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Disabling SSL Certificate support temporarly
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

// Execute post
$result = curl_exec($ch);
/*if ($result === FALSE) {
die('Curl failed: ' . curl_error($ch));
}*/

// Close connection
curl_close($ch);

return $result;
}



public function cron_sms_dlr() {
 $this->load->library('customsms');
	$result = $this->messages_model->getPendingSmsList_dlr();
	if (!empty($result)) {
		foreach ($result as $list) {
			$messageId = $list['id'];
			$msg_id = $list['msg_id'];
			$response_sms='';
			  $response_sms=  $this->customsms->sendSMS_dlr($msg_id);
			if($response_sms!='' && trim($response_sms)!='s:0:"";'){
					$arr=array();
					$arr=unserialize(trim($response_sms));
					//print_r($arr[0]);  Invalid Number
					if($arr[0]['messageStatus']== 'DELIVRD'){
						$data = array(
						'delivery_status' => 1,
						'delivery_date'=>$arr[0]['sendondate']
						);
						$status = $this->messages_model->updateSmsStatus($data, $messageId);
				}elseif($arr[0]['messageStatus']== 'Invalid Number'){

                    $data = array(
                        'sms_status' => 55,
                        'delivery_status' => 55,
						'updated_at'=>date('Y-m-d h:i:sa')
						);
						$status = $this->messages_model->updateSmsStatus($data, $messageId);
                }
			}
		
		}
	}
}	    

}



?>