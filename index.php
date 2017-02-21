<?php include "includes/header.php" ?>


<br><br><br>
<div class="row">
    <div class="container">
      <div class="col-sm-8">


            <input id="searchCity" type="text" class="form-control" name="search" placeholder="Enter a City/Location">


            <span class="btn btn-info" id="searchCityButton"> <i class="glyphicon glyphicon-search"></i> Find </span>


            <div class="checkbox">
              <label class="checkbox-inline"><input type="checkbox"  id="eqData"  value="eqBox">Add Earthquake Data</label>
              <label  class="checkbox-inline"><input type="checkbox"  id="weatherData" value="weather">Add Weather Data</label>
            </div>

            <div class="eqBox box" value="eqBox">
              Start <input type="date" id="startdate" class="form-control"  value="" placeholder="Start Date. eg: yyyy-mm-dd" style="width: 200px">
              <br>
              To end <input type="date" id="enddate" name="enddate" class="form-control"  value="" placeholder="End Date. eg: yyyy-mm-dd" style="width: 200px">
            </div>




      <br/>

      <div class="container">

          <div class="col-sm-12">
              <div id="map"></div>
          </div>
    </div>


    </div>
</div>


<?php include "includes/footer.php" ?>
