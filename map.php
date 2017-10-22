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
    <input id="keyword" type="text"></input>
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
            alert(event.latLng);
            
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