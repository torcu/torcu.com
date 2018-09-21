<?php include(APP_DIR.'views/concilia/header.php'); ?>
<?php print_r($cartera) ?>
<form class="form-horizontal" action="<?php echo BASE_URL ?>upload" role="form" id="form1" method="post" enctype="multipart/form-data">

	<legend><small>Cartera Objetivo</small></legend>

  	<table class="table table-condensed table-bordered">
    	<thead>
      		<tr>
        		<th>BRANCH</th>
        		<th>OPERACION</th>
        		<th>RID</th>
        		<th>EXPENSE</th>
      		</tr>
    	</thead>
    	<tbody>
    	 	
    	 	<?php foreach ($cartera as $row) { ?>
    	 		<tr>
    	 			<?php foreach (array('BRANCH','OPERACION','RID','EXPENSE') as $header) { ?>
    	 			<td><?php echo $row[$header] ?></td>
    	 			<?php } ?>
    	 		</tr>
    	 	<?php } ?>
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

    <input type="hidden" name="debug" value="<?php echo $debug ?>">

</form>


<script type="text/javascript">
$(document).ready(function () {

	var filename = '';
	var file1 = ''
	var bar1 	= $('#bar1');
	var percent1 = $('#percent1');
	var status1 = false;
	
//	var status 	= $('#status');

	$('#form1').ajaxForm({
	    beforeSend: function() {
	        //status.empty();
	        file1=$('#xls').val();
	        $('#progress1').css('display','block');
	        $('#bar1').css('display','block');
	        $('#percent1').css('display','block');
	        var percentVal1 = '0%';
	        bar1.width(percentVal1)
	        percent1.html(percentVal1);
	    },
	    uploadProgress: function(event, position, total, percentComplete) {
	        var percentVal1 = percentComplete + '%';
	        bar1.width(percentVal1)
	        percent1.html(percentVal1);
	    },
	    success: function() {
	        var percentVal1 = '100%';
	        bar1.width(percentVal1)
	        percent1.html(percentVal1);
	    },
		complete: function(xhr) {
			json = $.parseJSON(xhr.responseText);
			if(!json.error) {
				status1 = true;
				filename = file1.replace(/^.*\\/, "");
				$('#updateb').css('display','inline');
			} else {
				$('#bar1').css('background-color','#EE4D4D');
				percent1.html(json.msg);
			}
		}
	});

	$('#uploadb').on('click', function() {
		$('#form1').submit();
	});

	$( "#updateb" ).click(function() {
		window.location = "<?php echo BASE_URL ?>concilia/cartera_objetivo?filename="+filename+"&load=1&debug="+$('#debug').val();
	});

});
</script>

<?php include(APP_DIR.'views/concilia/footer.php'); ?>