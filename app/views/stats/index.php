<?php include(HEADER); ?>
<div class="container">
	<h3 style="font-size:38px">Chart</h3>
	<div id="chart_div" style="width:533px;height:327px;"></div>
	
	<hr/>
	<div class="panel panel-info">
      <div class="panel-heading">
        <div class="row">
          <div class="col-xs-6">
            <i class="icon-twitter icon-5x"></i>
          </div>
          <div class="col-xs-6 text-right">
            <p class="h1"><big>45,358</big></p>
            <p class="h3"><small><small>+123 / +0.5%</small></small></p>
          </div>
        </div>
      </div>
      <a href="#">
        <div class="panel-footer announcement-bottom">
          <div class="row">
            <div class="col-xs-6">
              View Mentions
            </div>
            <div class="col-xs-6 text-right">
              <i class="icon-circle-arrow-right icon-2x"></i>
            </div>
          </div>
        </div>
      </a>
    </div>
	
	<div class="panel panel-info">
      <div class="panel-heading">
        <div class="row">
          <div class="col-xs-6">
            <i class="icon-facebook icon-5x"></i>
          </div>
          <div class="col-xs-6 text-right">
            <p class="h1"><big>45,358</big></p>
            <p class="h3"><small><small>+123 / +0.5%</small></small></p>
          </div>
        </div>
      </div>
      
      <a href="#">
        <div class="panel-footer announcement-bottom">
          <div class="row">
            <div class="col-xs-6">
              View Mentions
            </div>
            <div class="col-xs-6 text-right">
              <i class="icon-circle-arrow-right icon-2x"></i>
            </div>
          </div>
        </div>
      </a>
    </div>
	
	<hr/>
	<pre><?php 
		echo "GA\n";
		print_r($ga)
	?></pre>
</div>
<script type='text/javascript'>

	google.load('visualization', '1', {'packages': ['geochart']});
	google.setOnLoadCallback(drawRegionsMap);
	
	function drawRegionsMap(region) {
		
<?php
		$a[] = "['Country', 'Visits']";
		foreach($ga->getResults() as $result) {
			$a[] = "['".$result->getCountry()."', ".$result->getVisits()."]";
		}
		//echo join(",",$a);
?>
		var region = 'world';
		var mode = 'regions'
		var a = [<?php echo join(",",$a); ?>];
		
		console.log(a);
		var data = google.visualization.arrayToDataTable(a);
	
		var options = {
			region: region,
			displayMode: mode,
			colorAxis: {colors: ['#99D4F2','#036EA3']},
			backgroundColor : "#f8f8f8", 			
		};
		
		var geochart = new google.visualization.GeoChart(document.getElementById('chart_div'));
		
		function viewRegion(){
	        var selection = geochart.getSelection();
			var country = selection[0].row+1;
			//console.log(selection[0].row);
	        //var message = '';
	        //for (var i = 0; i < selection.length; i++) {
	        //  var item = selection[i];
	        //  if (item.row != null && item.column != null) {
	        //      message += '{row:' + item.row + ',column:' + item.column + '}';
	        //  } else if (item.row != null) {
	        //      message += '{row:' + item.row + '}';
	        //  } else if (item.column != null) {
	        //      message += '{column:' + item.column + '}';
	        //  }
	        //}
	        //if (message == '') {
	        //    message = 'nothing';
	        //}
			//console.log(selection);
	        //alert('You selected ' + a[selection[0].row+1][0]);
			
			$.ajax({
				url: '/torcu.com/stats/geochartdata?country='+a[country][0],
				dataType: 'json',
				success: function(json) {
					var js_region = json['region'];
					var js_mode   = json['mode'];
					var js_options = {
						region: js_region,
						displayMode: js_mode,
						colorAxis: {colors: ['#99D4F2','#036EA3']},
						backgroundColor : "#f8f8f8", 			
					};
					var js_data = google.visualization.arrayToDataTable(json['data']);
					//google.visualization.events.addListener(geochart, 'select', viewCountry);
					geochart.draw(js_data, js_options);	
				}
			}); 
	    }
		google.visualization.events.addListener(geochart, 'select', viewRegion);
		geochart.draw(data, options);
	};
</script>
<?php include(FOOTER); ?>