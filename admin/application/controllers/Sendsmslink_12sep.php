<?php  
/**
 * 
 */
class Sendsmslink extends Admin_Controller
{
	public function index()
	{
		$result = $this->messages_model->getPendingSmsList();
		// $i = 1;
		if (!empty($result)) {
			foreach ($result as $list) {
				$jsonData = json_decode($list['user_list']);
				$contactNumber = $jsonData->mobileno;
	            $message = $list['message'];
	            $messageId = $list['id'];
				// echo "<pre>"; print_r($list); echo "</pre>";
				if (!empty($contactNumber) && $contactNumber != "") {
					$data = array(
						'sms_status' => 1,
					);
					$status = $this->messages_model->updateSmsStatus($data,$messageId);
					if ($status == "true") {
						$this->smsgateway->sendSMS($contactNumber, strip_tags($message));
					}
					# code...
				}
			}
			redirect('homework');
		}else{
			redirect('homework');
		}
		
	}
}
?>