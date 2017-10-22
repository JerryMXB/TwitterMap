<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require "vendor/autoload.php";
require "TwitterStream.php";
require "const.php";
use Aws\Sqs\SqsClient;
$keyword = $_GET["keyword"];
$CONSUMER_KEY = AWSConst::access_token;
$CONSUMER_SECRET = AWSConst::access_token_secret;

$access_token = AWSConst::CONSUMER_KEY;
$access_token_secret = AWSConst::CONSUMER_SECRET;

$end_point = 'https://search-twittermap-qkhshjtekerke6c7rd244mua3i.us-east-1.es.amazonaws.com';
$index = '/tweet/tweetmap';
$index_url = $end_point . $index;

$client = SqsClient::factory(array(
				    'credentials' => array(
				        'key'    => AWSConst::AWS_KEY,
				        'secret' => AWSConst::AWS_SECRET,
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

$res = $stream->getStatuses(['track'=>$keyword,'locations'=>'-180.0,-90.0,180.0,90.0'], function($tweet) use($client, $queueUrl, $index_url, $keyword) {
	if($tweet != null && array_key_exists('coordinates', $tweet) && $tweet['coordinates'] != null) {
		if($tweet['coordinates']['type'] == 'Point' && sizeof($tweet['coordinates']['coordinates']) == 2) {
            if(stripos($tweet['text'], $keyword)!= false || !isset($keyword) || trim($keyword)==='') {
                try{
                    $client->sendMessage(array(
                        'QueueUrl'    => $queueUrl,
                        'MessageBody' => $tweet['coordinates']['coordinates'][0] . "$$" . $tweet['coordinates']['coordinates'][1] . "$$" . $tweet['text'],
                    ));
                    print("sent success");

                    $json = array(
                        'text' => $tweet['text'],
                        'locations' => array($tweet['coordinates']['coordinates'][0], $tweet['coordinates']['coordinates'][1])
                    );
                    $json_string = json_encode($json, True);
                    $ch = curl_init($index_url);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); 
                    curl_setopt($ch, CURLOPT_TIMEOUT, 200);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_FORBID_REUSE, 0);
                    //$json_string = json_encode($tweet, True);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_string);
                    $response = curl_exec($ch);
                    var_dump($response);
                } catch (AwsException $e) {
                    error_log($e->getMessage());
                }
            }
		}
	}
});

?>