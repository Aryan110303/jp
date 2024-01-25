<?php

$this->config_aws=[
  's3-access' => [
    'key' => 'AKIAJ5RDBMCE3S4EYYQQ',
    'secret' => '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',
    'bucket' => 'schooleye-bucket',
    'region' => 'ap-south-1',
    'version' => 'latest',
    'acl' => 'public-read',
    'private-acl' => 'private'
  ]
];

$this->s3obj=Aws\S3\S3Client::factory([
  'credentials' => [
    'key' => $this->config_aws['s3-access']['key'],
    'secret' => $this->config_aws['s3-access']['secret']
  ],
  'version' => $this->config_aws['s3-access']['version'],
  'region' => $this->config_aws['s3-access']['region']
]);

?>