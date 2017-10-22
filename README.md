# TwitterMap

This is a web app of streaming real-time tweets and provide relational application. 
<p align="center">
  <img src='https://raw.githubusercontent.com/JerryMXB/TwitterMap/master/map1.png'/>
</p>

## Function
1. Plot the real-time tweets on the Google map with the selected keyword.
<p align="center">
  <img src='https://raw.githubusercontent.com/JerryMXB/TwitterMap/master/map2.png'/>
</p>

2. Search the tweets within a distance of user's click from ElasticSearch.
<p align="center">
  <img src='https://raw.githubusercontent.com/JerryMXB/TwitterMap/master/map3.png'/>
</p>

## Involved External Service
Google Map API: Provide the map UI for ploting streaming tweet.
Twitter API: Streaming realtime twitter.
AWS SQS Service: Queue up the feed-in tweet streaming and waiting for pulling.
AWS ElasticSearch:

## Installation
1. Set up the ElasticSearch, and replace the information in the code.
2. Set up the SQS queue, and replace the information in the code.
3. Put the codes on your server, fill in the AWS key and Twitter developer token. 
Run "your web directory/map.php"
