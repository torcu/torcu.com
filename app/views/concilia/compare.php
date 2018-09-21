<?php //include(HEADER); ?>
<?php include(APP_DIR.'views/concilia/header.php'); ?>	
<?php

//  $data['tables'] = Array ('PRO___UAT_UPGRADE_SANTANDER___DISPUESTO_POR_CONTRATO_V1_24','PRE___UAT_UPGRADE_SANTANDER___DISPUESTO_POR_CONTRATO_V1_24');
//  $data['files'] = Array('PRO - UAT Upgrade Santander - Dispuesto por contrato v1.24.csv','PRE - UAT Upgrade Santander - Dispuesto por contrato v1.24.csv');
//
//  $data['columns'] = Array
//      (
//          0  => 'DEAL_EXPENSE',
//          1  => 'DEAL',
//          2  => 'MIGRACION',
//          3  => 'DEAL_CURRENCY_CODE',
//          4  => 'AGENTE',
//          5  => 'FACILITY',
//          6  => 'FAC_CONTRATO',
//          7  => 'FAC_CURRENCY_CODE',
//          8  => 'SUCURSAL',
//          9  => 'CONTRATO',
//          10 => 'LMITE',
//          11 => 'DISPONIBLE',
//          12 => 'DISPUESTO'
//      );

?>

<div class="container">
	<form class="form-horizontal" role="form">
	<fieldset> 

		    <legend>Comparando Archivos: <?php echo join(", ", $data['files']) ?></legend>

		    <?php
		    	if(!empty($data['errors'])) {
		    		foreach($data['errors'] as $error) {
		    ?>
		    	<div class="alert alert-danger"><?php echo $error ?></div>
		    <?php
		     		}
		    	} else {
		    ?>
				<div class="alert alert-success">Los archivos se han importado correctamente</div>
		    <?php 
		    	}
		    ?>

		   <?php if(empty($data['errors'])) { ?>

				<?php foreach($data['tables'] as $table) { ?>
					<input type="hidden" name="tables[]" value="<?php echo $table ?>">
				<?php } ?>
					<input type="hidden" name="compare" value="1">
				
				<p>Filtrar contratos:</p>
				<div class="form-group">
					<div class="col-sm-6">  
						<select name="filter" id="filter" class="form-control">
							<option value="">NO FILTRAR</option>
							<option value="C" selected="selected">CONTRATOS EN CARTERA OBJETIVO</option>
							<option value="F">FACILITIES EN CARTERA OBJETIVO</option>
						</select>
					</div>
					<div class="col-sm-6">  
						<select name="deal" id="deal" class="form-control"<?php echo($filter=='')?' required style="display:inline"':' style="display:none'; ?>>
							<option value="" disabled selected></option>
						<?php foreach($data['columns'][0] as $column) { ?>
							<option value="<?php echo $column ?>"><?php echo $column ?></option>
						<?php } ?>
						</select>  
					</div>
				</div>

				<p></p>

				<div class="form-group">
					<div class="col-sm-6"> 
						<p> Selecciona qué columnas forman un identificador único de fila:</p>
						<div class="checkbox">
							<label><input type="checkbox" class="check_all" id="check_all" name="dummy" value="1" checked="checked">TODOS</label>
						</div>

						<?php foreach($data['columns'][0] as $column) { ?>
							<input type="hidden" name="headers[]" value="<?php echo $column ?>">
							<div class="checkbox">
		 						<label><input type="checkbox" class="check_c" name="columns[]" value="<?php echo $column ?>" checked="checked"><?php echo $column ?></label>
							</div>
						<?php } ?>
					</div>

					<div class="col-sm-6"> 
						<p> Opciones:</p>
						<div class="checkbox">
							<label><input type="checkbox" id="ignore_trail" name="ignore_hours" value="1" checked="checked">Ignorar espacios extra</label>
						</div>
						<div class="checkbox">
							<label><input type="checkbox" id="ignore_format" name="ignore_format" value="1" checked="checked">Ignorar errores de formatos de número</label>
						</div>
						<div class="checkbox">
							<label><input type="checkbox" id="ignore_hours" name="ignore_hours" value="1" checked="checked">Ignorar hora en fechas</label>
						</div>

						<p>&nbsp;</p>

						<p> Ignorar columnas:</p>
						<select name="ignore_cols[]" id="ignore_cols" class="form-control" multiple="multiple">
							<?php foreach($data['columns'][0] as $column) { ?>
								<option value="<?php echo $column ?>"><?php echo $column ?></option>
							<?php } ?>
						</select>  
					</div>

				</div>
				
				<div class="form-group">
					<div class="col-sm-12">
						<legend></legend>
						<button type="submit" class="btn btn-primary">Comparar</button>
						<div class="checkbox">
							<label><input type="checkbox" name="debug" value="1"<?php echo ($debug=='1')?' checked':''; ?>>debug</label>
						</div>
					</div>
				</div>
			<?php } ?>

		</fieldset>
	</form>
</div>

<?php if($debug=='1') { ?>
<div class="container">
	<pre><?php print_r($data) ?></pre>
</div>
<?php } ?>

<script type="text/javascript">
$(document).ready(function () {

	$('#filter').change(function() {
		if($(this).val() == '') {
			$('#deal').css('display', 'none');
			$('#deal').prop('required',false);
			$('#deal').val('');
		} else {
			$('#deal').css('display', 'inline');
			$('#deal').prop('required',true);

		}
	});
	$('.check_c').change(function(){ 
    	if($(".check_c:checked").length>0 && $(".check_c:checked").length<<?php echo count($data['columns'][0]) ?>) {
	        $('#check_all').prop('checked', false);
	    } else {
	        $('#check_all').prop('checked', true);
	        $('.check_c').prop('checked',true);
	    }
	});
    $('#check_all').click(function() {
    	$('.check_c').prop('checked',this.checked);
	});

	$('#ignore_cols').multiselect({
		buttonWidth:"100%"
	});

});

</script>

<?php //include(FOOTER); ?>
<?php include(APP_DIR.'views/concilia/footer.php'); ?>	