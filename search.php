<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require "vendor/autoload.php";
use Aws\Sqs\SqsClient;

$end_point = 'https://search-twittermap-qkhshjtekerke6c7rd244mua3i.us-east-1.es.amazonaws.com';
$index = '/tweet/_search';
$index_url = $end_point . $index;
$lat = $_GET['lat'];
$lon = $_GET['lon'];
//$lat = '44';
//$lon = '-117';
$query = '{"query": {"bool": {"filter": {"geo_distance": {"distance": "500km", "locations": { "lat":' . $lat . ',"lon":' . $lon .'}}}}}}';
$ch = curl_init($index_url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); 
curl_setopt($ch, CURLOPT_TIMEOUT, 200);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 0);
curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
$response = curl_exec($ch);
$result = json_decode($response, True);
if (sizeof($result['hits']['hits']) > 0) { 
	foreach ($result['hits']['hits'] as $location) {
		echo $location['_source']['text'] . "$$" . $location['_source']['locations'][1] . "$$" . $location['_source']['locations'][0] . "&&";
	}
}
?>