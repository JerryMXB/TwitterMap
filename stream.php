<?php
require "vendor/autoload.php";
require "TwitterStream.php";
use Aws\Sqs\SqsClient;
$keyword = $_GET["keyword"];
$access_token = "2802092556-4CTFLexYEfYCj5MYospMixSWxsS2Rv72FTwgDVa";
$access_token_secret = "mfq08S8y8MzKPJYIVlKCCeFCU1jpNTj8hOd0Ouu0nxGBd";

$CONSUMER_KEY = "eSZ45OWMPbHBVaGVBsHbgxsH2";
$CONSUMER_SECRET = "RqvPYjkn0Xr3I42u7zc5nPJlBF0FYbH0YOfDiWWTndGhJOGLmY";

$client = SqsClient::factory(array(
				    'credentials' => array(
				        'key'    => 'AKIAJKSJDXO6SKBQIYFQ',
				        'secret' => '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',
				    ),
				    'region'  => 'us-east-1',
				    'version' => '2012-11-05'
				));

$queueUrl = "https://sqs.us-east-1.amazonaws.com/842132808572/twitts";

$stream = new TwitterStream(array(
	    'consumer_key'    => $CONSUMER_KEY,
	    'consumer_secret' => $CONSUMER_SECRET,
	    'token'           => $access_token,
	    'token_secret'    => $access_token_secret
	));

$res = $stream->getStatuses(['track'=>$keyword,'locations'=>'-180.0,-90.0,180.0,90.0'], function($tweet) use($client, $queueUrl) {
	if($tweet != null && array_key_exists('coordinates', $tweet) && $tweet['coordinates'] != null) {
		if($tweet['coordinates']['type'] == 'Point' && sizeof($tweet['coordinates']['coordinates']) == 2) {
			try{
				$client->sendMessage(array(
				    'QueueUrl'    => $queueUrl,
				    'MessageBody' => $tweet['coordinates']['coordinates'][0] . "$$" . $tweet['coordinates']['coordinates'][1] . "$$" . $tweet['text'],
				));
				print("sent success");
			} catch (AwsException $e) {
			    error_log($e->getMessage());
			}
		}
	}
});

?>