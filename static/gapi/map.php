<?php
define('ga_email','mail@torcu.com');
define('ga_password','fox mulder');
define('ga_profile_id','77425084');

require 'gapi.class.php';

$ga = new gapi(ga_email,ga_password);

$ga->requestReportData(ga_profile_id,array('Country'), array('visits'));
?>
<html>
  <head>
    <script type='text/javascript' src='https://www.google.com/jsapi'></script>
    <script type='text/javascript'>
     google.load('visualization', '1', {'packages': ['geochart']});
     google.setOnLoadCallback(drawRegionsMap);

      function drawRegionsMap() {
        var data = google.visualization.arrayToDataTable([
<?php
$a[] = "['Country', 'Visits']";
foreach($ga->getResults() as $result) {
	$a[] = "['".$result."', ".$result->getVisits()."]";
}
echo join(",",$a);
?>
        ]);

        var options = {};

        var chart = new google.visualization.GeoChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    };
    </script>
  </head>
  <body>
    <div id="chart_div" style="width: 900px; height: 500px;"></div>
  </body>
</html>
