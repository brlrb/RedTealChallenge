<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Red Teal Challenge Question</title>

    <!-- Bootstrap core CSS -->
   <link href="includes/css/bootstrap.min.css" rel="stylesheet">

   <!-- jQuery components -->
  <script src="includes/js/jquery-3.1.1.min.js"></script>
  <script src="includes/js/jquery-ui.min.js"></script>

   <script src="includes/js/bootstrap.min.js"></script>
   <script src="includes/js/bootstrap.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.min.js"></script>

   <!-- Google Maps API Key -->
   <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBzMLf3SozB_lTnInhz6LPdzDMa2oWRXsg&libraries=places&callback=initMap"> </script>

   <!-- // Show/Hide Earthquake date range Div Box -->
  <script type="text/javascript">
    $(document).ready(function(){
      $('input[type="checkbox"]').click(function(){
        var inputValue = $(this).attr("value");
        $("." + inputValue).toggle();
      });
    });
  </script>



   <style>
   .box{
        color: #fff;
        padding: 5px;
        display: none;
    }

     #map{
         width: 100%;
         height: 400px;
         margin: 20px;
     }

     .active{ /* Query Highlight */
       background-color: yellow;
     }

     .eqBox{
       background: #90c0c3;
       width: 250px;
     }
   </style>

   <script type="text/javascript">
       $(document).ready(function(){

           $("#searchCityButton").click(function(){
                var data = "q="+$("#searchCity").val()+"&username=testbm";

                var searchQuery = $("#searchCity").val();
                console.log("Search Query: ", searchQuery);


                var eqData = $("#eqData").val();
                var weatherData = $("#weatherData").val();

                console.log("Earthquake data: ", eqData);
                console.log("Weather data: ", weatherData);

                   $.ajax({
                       type: 'GET',
                       url: 'http://api.geonames.org/wikipediaSearchJSON',
                       data: data,
                       beforeSend: function ()
                       {

                           if ($("#searchCity").val()==""){
                              alert("data is blank. enter a city");
                           };

                       },

                       success: function (response)
                       {

                         console.log("response: ",response);

                           initMap();

                           var map;
                           function initMap() {

                               // get all the data in object of the searched place
                               var places = response.geonames;
                               console.log("Places: ",places);

                               // Get the lat & and lng for the searched place // First Element [0]
                               var center = {lat:places[0].lat,lng:places[0].lng};
                               console.log("center: ",center);

                               // Google Maps default initilization
                               var map = new google.maps.Map(document.getElementById('map'), {
                                 zoom: 8,
                                 center:center
                               });


                               for (var i = places.length - 1; i >= 0; i--) { // default length = 10
                                 console.log("Places Length: ", places.length);
                                   getCity(map, {lat:places[i].lat, lng:places[i].lng}, places[i].title, eqData, weatherData);
                                   console.log("Getting city Data: ", i);
                               }
                           } // end of initMap()
                       }
                   });
           });


            function getCity(map, location, cityName, earthquakeBool, weatherBool){
                var lat=location.lat;
                var lng=location.lng;
                var data="north="+(lat+1)+"&south="+(lat-1)+"&east="+(lng+1)+"&west="+(lng-1)+"&lang=en&username=testbm";
                var content="";
                $.ajax({
                    type: 'GET',
                    url: "http://api.geonames.org/citiesJSON",
                    data:data,
                    success:function(response){
                        response = response.geonames;
                        for (var i = response.length - 1; i >= 0; i--) {
                            if(response[i].name === cityName){
                              console.log("getCity Name: ",cityName);



                                content = content + "<h4><b>City Information: </b></h4>"
                                                  + "<b>Country code: </b> "
                                                  + response[i].countrycode
                                                  + "<br/><b>Toponyn Name: </b>"
                                                  + " <a href='#' class = 'active' >"+response[i].toponymName + "</a>"
                                                  + "<br/><br/>";




                                if($('#eqData').is(':checked') &&  $('#weatherData').is(':checked')){
                                  console.log("both options selected.");
                                  getEarthquakes(content, map, location, 1);


                                } else if ($('#weatherData').is(':checked')){
                                  getWeather(data, content, map, location);
                                } else  if ($('#eqData').is(':checked')) {
                                  getEarthquakes(content, map, location);
                                }else {
                                  showMarker(map, content, location);

                                }

                            }

                        }

                    }
                });
            } // End of getCity function


            function getEarthquakes(content, map, location, both){
              var lat=location.lat;
              var lng=location.lng;

              var startdate = $("#startdate").val();
              var enddate = $("#enddate").val();
              console.log("Earthquake Start Date: ", startdate);
              console.log("Earthquake End Date: ", enddate);


              var data = "north="+(lat+1)+"&south="+(lat-1)+"&east="+(lng-1)+"&west="+(lng+1)+"&date="+enddate+"&username=testbm";
              console.log("eq data: ", data);



                $.ajax({
                        type: 'GET',
                        url: 'http://api.geonames.org/earthquakesJSON',
                        data: data,

                        beforeSend: function ()
                        {
                          if ($("#startdate").val()=="" || $("#enddate").val()==""){
                             alert("Please enter earthquakes date range");

                          };
                        },

                        success:function(response){

                            var response = response.earthquakes;
                            console.log("Earthquake Original Incoming Data: ",response);

                            // Using Lodash filter
                            var response = _.filter(response, function(o) {
                              return o.datetime >= startdate;
                            });

                            console.log("filtered Earthquake Data(Time Range): ",response);


                            if(response.length!=0){
                                content += "<br/><h4>Earthquake Information: </h5>";
                            } else {
                              content += "<br/><h4>Earthquake data not available</h5>";
                            }


                            for (var i = response.length - 1; i >= 0; i--) {
                                var e = response[i];

                                var eqContents = "<b>Date: </b> "
                                       + e.datetime
                                       + "<br/><b>Depth: </b>"
                                       + e.depth
                                       + "<br/><b>Source: </b>"
                                       + e.src
                                       + "<br/><b>Eqid: </b>"
                                       + e.eqid
                                       + "<br/><b>Maginitude: </b>"
                                       + e.magnitude
                                       + "<br/><br/>";

                                content += eqContents;
                            }
                            if (both == 1) {
                              getWeather(data, content, map, location)
                            }else {
                              showMarker(map, content, location);

                            }

                        }
                    });
            } // end of getEarthquakes function



            function getWeather(data, content, map, location){
                     $.ajax({
                         type: 'GET',
                         url: 'http://api.geonames.org/weatherJSON',
                         data: data,
                         success:function(response){
                             var response = response.weatherObservations;
                             if(response.length != 0){
                              content += "<br/><h4>Weather Information: </h4>";
                            } else {
                              content += "<br/><h4>Weather data not available</h5>";
                            }


                             for (var i = response.length - 1; i >= 0; i--) {
                                 var w=response[i];
                                 var weatherContent = "<b>Date: </b>"
                                                    + w.datetime
                                                    + "<br/><b>Clouds: </b>"
                                                    + w.clouds
                                                    + "<br/><b>Temparature: </b>"
                                                    + w.temperature
                                                    + "<br/><b>Humidity: </b>"
                                                    + w.humidity
                                                    + "<br/><b>Station Name: </b>"
                                                    + w.stationName
                                                    + "<br/><b>Weather Condition: </b>"
                                                    + w.weatherCondition
                                                    + "<br/><b>Wind Direction: </b>"
                                                    + w.windDirection
                                                    + "<br/><b>Wind Speed: </b>"
                                                    + w.windSpeed
                                                    + "<br/><br/>";
                                 content += weatherContent;
                             }
                             showMarker(map, content, location);
                         }
                     });
             } // end of getWeather function



           function showMarker(map, content , location){
               var infowindow = new google.maps.InfoWindow();
               var marker = new google.maps.Marker({
                 position:location,
                 map: map
               });
               map.setCenter(location);
               google.maps.event.addListener(marker,'click', (function(marker, content, infowindow){
                   return function() {
                       infowindow.setContent(content);
                       infowindow.open(map, marker);
                   };
               })(marker, content, infowindow));
           } // end of showMarker function



       });
   </script>

   <!-- // Google Map autocomplete place names -->
   <!-- <script type="text/javascript">
   function initGoogleMap() {
      var input = document.getElementById('searchCity');

      var autocomplete = new google.maps.places.Autocomplete(input);

      // Bind the map's bounds (viewport) property to the autocomplete object,
      // so that the autocomplete requests use the current map bounds for the
      // bounds option in the request.
      autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
          // User entered the name of a Place that was not suggested and
          // pressed the Enter key, or the Place Details request failed.
          window.alert("No details available for input: '" + place.name + "'");
          return;
        }

        var address = '';
        if (place.address_components) {
          address = [
            (place.address_components[0] && place.address_components[0].short_name || ''),
            (place.address_components[1] && place.address_components[1].short_name || ''),
            (place.address_components[2] && place.address_components[2].short_name || '')
          ].join(' ');
        }
      });

    }
   </script> -->

  </head>



  <!-- Fixed header navbar -->
 <nav class="navbar navbar-inverse navbar-fixed-top">
   <div class="container">
     <div class="navbar-header">
       <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
         <span class="sr-only">Toggle navigation</span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
       </button>
       <img src="images/RedTealLogo.png" width = "50px" alt="" />
     </div>

     <li><a class="navbar-brand" href="#">&nbsp;&nbsp; Redteal Challenge</a></li>

   </div>
 </nav>
  <body>
