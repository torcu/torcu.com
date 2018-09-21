<?php //include(HEADER); ?>
<?php include(APP_DIR.'views/concilia/header.php'); ?>	

<form class="form-horizontal" action="<?php echo BASE_URL ?>upload" role="form" id="form1" method="post" enctype="multipart/form-data">

	<legend><small>Seleccione los archivos a conciliar</small></legend>

    <div class="form-group">
      <label class="col-sm-2 control-label" for="csv1">Archivo CSV PRO</label>
      <div class="col-sm-10">
        <input type="file" class="form-control" name="csv1" id="csv1" placeholder="Archivo CSV 1" required>
        <div class="progress" id="progress1" style="display:none">
        	<div id="bar1" class="bar" style="display:none"></div >
       	 	<div id="percent1" class="percent" style="display:none">0% <div id="status1"></div></div >
    	</div>

      </div>
    </div>
</form>
<form class="form-horizontal" action="<?php echo BASE_URL ?>upload" role="form" id="form2" method="post" enctype="multipart/form-data">

    <div class="form-group">
      <label class="col-sm-2 control-label" for="csv2">Archivo CSV PRE</label> 
      <div class="col-sm-10">  
        <input type="file" class="form-control" name="csv2" id="csv2" placeholder="Archivo CSV 2" required>
        <div class="progress" id="progress2" style="display:none">
        	<div id="bar2" class="bar" style="display:none"></div>
       	 	<div id="percent2" class="percent" style="display:none">0%<div id="status2"></div></div >	
    	</div>
      </div>
    </div>

</form>
<form class="form-horizontal" role="form">

	<div class="form-group">
		<label class="col-sm-2 control-label" for="csv2">Filtrar archivo</label> 
		<div class="col-sm-5">  
			<select name="filter" id="filter" class="form-control">
				<option value="" selected>NO FILTRAR</option>
				<option value="C">FILTRAR CONTRATOS EN CARTERA OBJETIVO</option>
				<option value="F">FILTRAR FACILITIES EN CARTERA OBJETIVO</option>
			</select>
		</div>
		<div class="col-sm-5">  
			<input type="text" name="deal" id="deal" class="form-control" style="display:none">
		</div>
	</div>

    <div class="form-group">
      <label class="col-sm-2 control-label" for="csv1">Separador CSV</label> 
      <div class="col-sm-5">
         <select name="sep" id="sep" class="form-control">
           <option value="tab" selected>TAB</option>
           <option value=";">;</option>
           <option value=",">,</option>
           <option value="cust">Otro</option>
         </select>  
         </div>
         <div class="col-sm-5">
           <input type="text" name="cust" id="cust" class="form-control" style="display:none">
           <input type="hidden" id="debug" name="debug" value="<?php echo $debug ?>">
         </div>
    </div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
	  		<button type="button" id="uploadb" class="btn btn-primary">Cargar</button>
	  		<button type="button" id="compareb" class="btn btn-primary" style="display:none">Comparar</button>
		</div>
	</div>
</form>


<script type="text/javascript">
$(document).ready(function () {

	var comparefiles = '';
	var file1 = ''
	var file2 = '';
	var bar1 	= $('#bar1');
	var percent1 = $('#percent1');
	var bar2	= $('#bar2');
	var percent2 = $('#percent2');
	var status1 = false;
	var status2 = false;
//	var status 	= $('#status');
			   
	$('#form1').ajaxForm({
	    beforeSend: function() {
	        //status.empty();
	        file1=$('#csv1').val();
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
				compare();
			} else {
				$('#bar1').css('background-color','#EE4D4D');
				percent1.html(json.msg);
			}
		}
	});

	$('#form2').ajaxForm({
	    beforeSend: function() {
	        //status.empty();
	        file2=$('#csv2').val();
	        $('#progress2').css('display','block');
	        $('#bar2').css('display','block');
	        $('#percent2').css('display','block');
	        var percentVal2 = '0%';
	        bar2.width(percentVal2)
	        percent2.html(percentVal2);
	    },
	    uploadProgress: function(event, position, total, percentComplete) {
	        var percentVal2 = percentComplete + '%';
	        bar2.width(percentVal2)
	        percent2.html(percentVal2);
	    },
	    success: function() {
	        var percentVal2 = '100%';
	        bar2.width(percentVal2)
	        percent2.html(percentVal2);
	    },
		complete: function(xhr) {
			json = $.parseJSON(xhr.responseText);
			if(!json.error) {
				status2 = true;
				compare();
			} else {
				$('#bar2').css('background-color','#EE4D4D');
				percent2.html(json.msg);
			}
		}
	});

	compare = function() {
		if(status1 && status2) {
			console.log
			comparefiles = file1.replace(/^.*\\/, "")+','+file2.replace(/^.*\\/, "");
			$('#compareb').css('display','inline');
		}
	}

	$('#uploadb').on('click', function() {
		$('#form1').submit();
		$('#form2').submit();
	});

	$( "#compareb" ).click(function() {
		window.location = "<?php echo BASE_URL ?>concilia/compare?files="+comparefiles+"&sep="+ $('#sep').val()+"&cust="+$('#cust').val()+"&filter="+$('#filter').val()+"&deal="+$('#deal').val()+"&debug="+$('#debug').val();
	});

	$('#sep').change(function() {
		if($(this).val() == 'cust') {
			$('#cust').css('display', 'inline');
			$('#cust').prop('required',true);
		} else {
			$('#cust').css('display', 'none');
			$('#cust').prop('required',false);
		}
	});

	$('#filter').change(function() {
		if($(this).val() == '') {
			$('#deal').css('display', 'none');
			$('#deal').prop('required',false);
			$('#deal').val('');
		} else {
			$('#deal').css('display', 'inline');
			$('#deal').prop('required',true);
			if($(this).val() == 'C') {
				$('#deal').prop('placeholder','COLUMNA CONTRATO');
			} else {
				$('#deal').prop('placeholder','COLUMNA FACILITY');
			}
		}
	});

});
</script>

<?php //include(FOOTER); ?>
<?php include(APP_DIR.'views/concilia/footer.php'); ?>	