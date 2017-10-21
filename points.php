<?php
require "vendor/autoload.php";
use Aws\Sqs\SqsClient;

$queueUrl = "https://sqs.us-east-1.amazonaws.com/842132808572/twitts";

$client = SqsClient::factory(array(
				    'profile' => 'default',
				    'region'  => 'us-east-1',
				    'version' => '2012-11-05'
));

try{
	$result = $client->receiveMessage(array(
					'QueueUrl' => $queueUrl,
				));
	print($result["Messages"][0]["Body"]);
	$client->deleteMessageBatch(array(
				    // QueueUrl is required
				    'QueueUrl' => $queueUrl,
				    // Entries is required
				    'Entries' => array(
					        array(
					            // Id is required
					            'Id' => $result["Messages"][0]['MessageId'],
					            // ReceiptHandle is required
					            'ReceiptHandle' => $result["Messages"][0]['ReceiptHandle'],
					        ),
					        // ... repeated
					    ),
					));
} catch (AwsException $e) {
    error_log($e->getMessage());
}
?>