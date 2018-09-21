<?php include(APP_DIR.'views/concilia/header.php'); ?>

<?php

	$afiles1 = array('File1', 'File2', 'File3', 'File4', 'File5');
	$afiles2 = array('FileA', 'FileB', 'FileC', 'FileD', 'FileE');
?>

<form class="form-horizontal" action="<?php echo BASE_URL ?>upload" role="form" id="form1" method="post" enctype="multipart/form-data">

	<legend><small>Procesar archivos</small></legend>

  	<table class="table table-condensed table-bordered">
    	<thead>
      		<tr>
        		<th>PRO</th>
        		<th>PRE</th>
      		</tr>
    	</thead>
    	<tbody>
    		<tr>
    			<td>
    				<ol class='example'>
    	 			<?php foreach ($afiles1 as $f) { ?>
    	 				<li data-name="<?php echo $f ?>"><?php echo $f ?></li>
    	 			<?php } ?>
    	 			</ol>
    	 		</td>
    	 		<td>
    				<ol class='example'>
    	 			<?php foreach ($afiles2 as $f) { ?>
    	 				<li data-name="<?php echo $f ?>"><?php echo $f ?></li>
    	 			<?php } ?>
    	 			</ol>
    	 		</td>
    	 	</tr>
    	</tbody>
    </table>

    <div class="form-group">
      <div class="col-sm-12">
        <input type="file" class="form-control" name="xls" id="xls" placeholder="Cargar cartera objetivo" required>
        <div class="progress1" id="progress" style="display:none">
        	<div id="bar1" class="bar" style="display:none"></div >
       	 	<div id="percent1" class="percent" style="display:none">0% <div id="status1"></div></div >
    	</div>
      </div>
    </div>

  </form>
  <form class="form-horizontal" role="form">

    <div class="form-group">
		<div class="col-sm-12">
	  		<button type="button" id="uploadb" class="btn btn-primary">Cargar cartera</button>
	  		<button type="button" id="updateb" class="btn btn-primary" style="display:none">Actualizar cartera</button>
		</div>
	</div>

	<pre id="serialize_output"></pre>

    <input type="hidden" name="debug" value="<?php echo $debug ?>">

</form>

<script type="text/javascript">
$(document).ready(function () {

	var sorted;

	var group = $("ol.example").sortable({
		 onDrop: function (item, container, _super) {
    		var data = group.sortable("serialize").get();
    		_super(item, container);
    		console.log(data[0][1].name);
  		}
 	})	
	

	$('#uploadb').on('click', function() {
		$('#form1').submit();
	});

	$( "#updateb" ).click(function() {
		window.location = "<?php echo BASE_URL ?>concilia/cartera_objetivo?filename="+filename+"&load=1&debug="+$('#debug').val();
	});

});
</script>

<?php include(APP_DIR.'views/concilia/footer.php'); ?>