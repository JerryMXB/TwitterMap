# TwitterMap

This is a web app of streaming real-time tweets and provide relational application. 
<p align="center">
  <img src='https://raw.githubusercontent.com/JerryMXB/TwitterMap/master/map1.png'/>
</p>

## Installation

### Build dependencies using composer
```
composer install
```
### And That's it!

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
### Google Map API
Provide the map UI for ploting streaming tweet.
### Twitter API
Streaming realtime twitter.
### AWS SQS Service
Queue up the feed-in tweet streaming and waiting for pulling.
### AWS ElasticSearch
Elastic Search organizes tweets data and make it easily accessible to users. User can search nearby tweet using geospatial feature that shows tweets that are within a certain distance from the point the user clicks on the map. 
