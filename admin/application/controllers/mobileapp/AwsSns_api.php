<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With,Content-Type');
header('Access-Control-Allow-Methods: POST,GET,OPTIONS');


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class AwsSns_api extends CI_Controller {
  private $config_aws;
  private $s3obj;
  public function __construct() {
        parent::__construct(); 
        //  Path to simple_html_dom
        include APPPATH . 'third_party/vendor/autoload.php';
        include APPPATH . 'third_party/initialize.php';
  }

  /*public function test()
  {
    
    $sdk = new Aws\Sns\SnsClient([
      'region'  => 'ap-south-1',
      'version' => 'latest',
      'credentials' => ['key' => 'AKIARHYMAGYFZLZESJ6U', 'secret' => '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']
    ]);

    $result = $sdk->publish([
      'Message' => 'This is a test message.',
      'PhoneNumber' => '+919303333433',
      'MessageAttributes' => ['AWS.SNS.SMS.SenderID' => [
          'DataType' => 'String',
          'StringValue' => 'CASJBP'
      ]
    ]]);

    print_r( $result );

  }
*/
  public function test2()
  {
    $params = array(
        'credentials' => array(
            'key' => 'AKIARHYMAGYFZLZESJ6U',
            'secret' => 'rzLEBtFshlqZzF87JtwXOujG7Whary81RPwpIfc1',
        ),
        'region' => 'ap-south-1', // < your aws from SNS Topic region
        'version' => 'latest'
    );
    $sns = new \Aws\Sns\SnsClient($params);

    $args = array(
        "MessageAttributes" => [
                    'AWS.SNS.SMS.SenderID' => [
                        'DataType' => 'String',
                        'StringValue' => 'CASJBP'
                    ],
                    'AWS.SNS.SMS.SMSType' => [
                        'DataType' => 'String',
                        'StringValue' => 'Transactional'
                    ]
                ],
        "Message" => "HELLO CENTRAL",
        "PhoneNumber" => "7566866565"
    );


    $result = $sns->publish($args);
    echo "<pre>";
    var_dump($result);
    echo "</pre>";

  }

// ---------------------------------end class-----------------------------//
}



?>

