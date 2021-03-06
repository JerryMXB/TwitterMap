<!DOCTYPE html>
<html>
  <head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 90%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  </head>
  <body>
    <select id="keyword">
        <option value=""></option>>
        <option value="today">Today</option>
        <option value="birthday">Birthday</option>
        <option value="sport">Sport</option>
        <option value="hello">Hello</option>
        <option value="nba">NBA</option>
        <option value="coffee">Coffee</option>
        <option value="nba">NBA</option>
        <option value="song">Song</option>
        <option value="it">It</option>
        <option value="liverpool">Liverpool</option>
    </select>
    <button id="search" type="button">Search</button>
    <button id="stop" type="button">Stop</button>
    <div id="div1"></div>
    <div id="map"></div>
    <script>
      var map;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 34.397, lng: 150.644},
          zoom: 2
        });
        google.maps.event.addListener(map, 'click', function(event) {
            var cordinates = String(event.latLng).replace('(','').replace(')','').split(', ');
            //alert(cordinates[0] + cordinates[1]);
            xhr = $.ajax({url: "search.php?lat=" + cordinates[0] + "&lon=" + cordinates[1], success: function(result){
              alert("Showing nearby tweets from ES");
              var tweets = result.split('&&');
              tweets.forEach(function(entry) {
                var contents = entry.split('$$');
                console.log(contents[0]);
                var myLatLng = new google.maps.LatLng(Number(contents[1]), Number(contents[2]));
                var image = 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png';
                var marker = new google.maps.Marker({
                    position: myLatLng,
                    animation: google.maps.Animation.DROP,
                    title: contents[0],
                    icon: image
                });
                marker.addListener('click', function() {
                  new google.maps.InfoWindow({content: contents[0]}).open(map, marker);
                });
                // To add the marker to the map, call setMap();
                marker.setMap(map);

                });

            }});
        });
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBxcSct_LGtpjAoXRmb56TcEL7SjrrMYeM&callback=initMap"
    async defer></script>
    <script type="text/javascript">
      var t;
      var xhr;
      function stream() {
        var keyword = $("#keyword").val();
        $("#div1").html('start streaming');
        xhr = $.ajax({url: "stream.php?keyword=" + keyword, success: function(result){}});
      }
      function plot() {
        t = setInterval(function(){
        $.ajax({url: "points.php", success: function(result){
             var cors = result.split('$$');
             $("#div1").html(cors[1] + cors[0]);
             var myLatLng = new google.maps.LatLng(Number(cors[1]), Number(cors[0]));
             var marker = new google.maps.Marker({
                  position: myLatLng,
                  animation: google.maps.Animation.DROP,
                  title: cors[2]
              });
              marker.addListener('click', function() {
                new google.maps.InfoWindow({content: cors[2]}).open(map, marker);
              });
              // To add the marker to the map, call setMap();
              marker.setMap(map);
          }});},500);
      }
      $("#search").click(function(){
        stream();
        plot();});
      $("#stop").click(function(){
        clearInterval(t);
        xhr.abort();});

    </script>
  </body>
</html>