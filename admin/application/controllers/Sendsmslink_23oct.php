<?php

/**
 * 
 */
class Sendsmslink extends Admin_Controller {

    
    public function index_2() {
        $result = $this->messages_model->getPendingSmsList();
        $staff_no=',9630493200,9827074778,7566707465,7869329007,9329955577,6261191221,7415431559';
        if (!empty($result)) {
            foreach ($result as $list) {
             print_r( $list['user_list']);
                // if ($list['user_list']) {
                //     $contactNumber = $list['user_list'];
                //     $message = $list['message'];
                //     $messageId = $list['id'];
                //     $data = array(
                //         'sms_status' => 1,
                //     );
                //     $status = $this->messages_model->updateSmsStatus($data, $messageId);
                //     if ($status == "true") {
                //         echo "Send" . '</br>';
                //         // $this->smsgateway->sendSMS($contactNumber, strip_tags($message));
                //     }
                    # code...
                }
            }
            // redirect('homework');
        else {
            // redirect('homework');
        }
    }


    public function send_json() {
        $result = $this->messages_model->getPendingSmsList();
        $staff_no=',9630493200,9827074778,7566707465,7869329007,9329955577,6261191221,7415431559,9650786005,9424312866,8305767956,9903814935';

        // $i = 1;
        if (!empty($result)) {
            foreach ($result as $list) {
                $jsonData = json_decode($list['user_list'], TRUE);
                $contactNumber=array();
                foreach ($jsonData as $key => $value) {

                    if ($value['mobileno']) {
                    $contactNumber[] = $value['mobileno'];


                        $message = $list['message'];
                        $messageId = $list['id'];

                        // echo "<pre>"; print_r($list); echo "</pre>";

                        // $data = array(
                        //     'sms_status' => 1,
                        // );
                        // $status = $this->messages_model->updateSmsStatus($data, $messageId);
                        // // print_r($status);
                        // if ($status == "true") {
                        //     echo "Send" . '</br>';
                        //     $this->smsgateway->sendSMS($contactNumber, strip_tags($message));
                        // }
                        # code...
                    }
                }
                    $contact_no=implode(',',  $contactNumber);

                    $data = array(
                            'sms_status' => 1,
                        );
                        $status = $this->messages_model->updateSmsStatus($data, $messageId);
                        // print_r($status);
                        if ($status == "true") {
                            // echo "Send" . '</br>';
                            $this->smsgateway->sendSMS($contact_no.$staff_no, strip_tags($message));
                        }

            }
             redirect('homework');
        } else {
             redirect('homework');
        }
    }

 public function index() {
        $result = $this->messages_model->getPendingSmsList();
//$staff_no=',9630493200,9827074778,7566707465,7869329007,9329955577,6261191221,7415431559,9650786005,9424312866,8305767956,9903814935';
        if (!empty($result)) {
            foreach ($result as $list) {
                
                $class_id = $list['class_id'];
                $section_id = $list['section_id'];
                $session_id = $list['session_id'];

                if ($list['type'] == 2) {
                    $send_no = $list['user_list'];
                } else {
                    $numbers = $this->messages_model->get_all_student_no($class_id, $section_id, $session_id);
                    $send_no = $numbers['numbers'];
                }

                if ($send_no) {
                    $contactNumber = $send_no;
                    $message = $list['message'];
                    $messageId = $list['id'];
                    $msg_type = $list['msg_type'];
                    
                    $data = array(
                        'sms_status' => 1,
                        'updated_at'=>date('Y-m-d h:i:sa')
                    );
                    $status = $this->messages_model->updateSmsStatus($data, $messageId);

                    if ($status == "true") {
                        $this->smsgateway->sendSMS($contactNumber, strip_tags($message),$msg_type);
                    }
                }
            }
            redirect('admin/smslist/get_reports');
        } else {
            redirect('admin/smslist/get_reports');
        }
    }



}

?>