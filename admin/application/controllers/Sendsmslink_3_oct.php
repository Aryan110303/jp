<?php

/**
 * 
 */
class Sendsmslink extends Admin_Controller {

    

    public function index_1() {
        $result = $this->messages_model->getPendingSmsList();
        if (!empty($result)) {
            foreach ($result as $list) {
//              print_r( $list['user_list']);
                if ($list['user_list']) {
                    $contactNumber = $list['user_list'];
                    $message = $list['message'];
                    $messageId = $list['id'];
                    $data = array(
                        'sms_status' => 1,
                    );
                    $status = $this->messages_model->updateSmsStatus($data, $messageId);
                    if ($status == "true") {
//                        echo "Send" . '</br>';
                        $this->smsgateway->sendSMS($contactNumber, strip_tags($message));
                    }
                    # code...
                }
            }
            redirect('homework');
        } else {
            redirect('homework');
        }
    }

 public function index() {
        $result = $this->messages_model->getPendingSmsList();
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
                    $data = array(
                        'sms_status' => 1,
                    );
                    $status = $this->messages_model->updateSmsStatus($data, $messageId);

                    if ($status == "true") {
                        $this->smsgateway->sendSMS($contactNumber, strip_tags($message));
                    }
                }
            }
            redirect('homework');
        } else {
            redirect('homework');
        }
    }



}

?>