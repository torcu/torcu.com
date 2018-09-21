<?php include(HEADER); ?>

<div class="container">
	<h1 style="font-size:38px">Barcodes</h1>

    <form class="form-horizontal" role="form" action="/barcode/generate" method="POST">
	<fieldset>
		<legend>Generador de códigos de barras</legend>

		<div class="form-group">
		    <label class="col-sm-2 control-label" for="pw">Página</label>
		    <div class="col-xs-3">
		    	<input type="text" class="form-control" name="pw" id="pw" placeholder="ancho (mm)">
		 	</div>
		    <div class="col-xs-3">
		    	<input type="text" class="form-control" name="ph" id="ph" placeholder="alto (mm)">
		 	</div>
		 </div>

 		<div class="form-group">
 		    <label class="col-sm-2 control-label" for="mt">Posición</label>
 		    <div class="col-xs-3">
 		    	<input type="text" class="form-control" name="mt" id="mt" placeholder="top (mm)">
 		 	</div>
 		    <div class="col-xs-3">
 		    	<input type="text" class="form-control" name="ml" id="ml" placeholder="left (mm)">
 		 	</div>
 		 </div>

 		<div class="form-group">
 		    <label class="col-sm-2 control-label" for="bw">Tamaño</label>
 		    <div class="col-xs-3">
 		    	<input type="text" class="form-control" name="bw" id="bw" placeholder="ancho(mm)">
 		 	</div>
 		    <div class="col-xs-3">
 		    	<input type="text" class="form-control" name="bh" id="bh" placeholder="alto (mm)">
 		 	</div>
 		 </div>

     <div class="form-group">
            <label class="col-sm-2 control-label" for="type">Tipo</label>
              <div class="col-xs-6">
              <SELECT  class="form-control" name="type">
              <option value="EAN13">EAN 13</option>
              <option value="C128">CODE 128</option>
              <option value="C39">CODE 39</option>
            </SELECT>

          </div>
        </div>
		
        <div class="form-group">
            <label class="col-sm-2 control-label" for="fsize">Tamaño de fuente</label>
              <div class="col-xs-6">
              <SELECT  class="form-control" name="fsize">
			  <option value="5">3</option>
			  <option value="5">4</option>
			  <option value="5">5</option>
			  <option value="6">6</option>
			  <option value="7">7</option>
              <option value="8" selected="selected">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
              <option value="11">11</option>
              <option value="12">12</option>
              <option value="13">13</option>
              <option value="14">14</option>
            </SELECT>
          </div>
        </div>

		<!-- div class="form-group">
            <label class="col-sm-2 control-label" for="bcw">Grosor de linea</label>
              <div class="col-xs-6">
              <SELECT  class="form-control" name="bcw">
              <option value="0.1">0.1</option>
              <option value="0.2">0.2</option>
              <option value="0.3">0.3</option>
              <option value="0.4" selected="selected">0.4</option>
              <option value="0.5">0.5</option>
              <option value="0.6">0.6</option>
              <option value="0.7">0.7</option>
			  <option value="0.8">0.8</option>
			  <option value="0.9">0.9</option>
			  <option value="1">1</option>
            </SELECT>
          </div>
        </div -->

  		<div class="form-group">
  		    <label class="col-sm-2 control-label" for="start">Secuencia</label>
  		    <div class="col-xs-3">
  		    	<input type="text" class="form-control" name="start" id="start" placeholder="inicio">
  		 	  </div>
  		    <div class="col-xs-3">
  		    	<input type="text" class="form-control" name="end" id="end" placeholder="fin">
  		 	  </div>
  		 </div>

		<div class="form-group">
          <label class="col-sm-2 control-label" for="list">Lista</label>
          <div class="col-xs-6">
            <textarea rows="4" class="form-control" name="list" id="list" placeholder="Un codigo por linea"></textarea>
          </div>
        </div>
		
		<div class="form-group">
          <label class="col-sm-2 control-label" for="drb">Dibujar borde</label>
          <div class="col-xs-6">
            <div class="checkbox">
                <input type="checkbox" name="drb" id="drb" checked="checked">Dibujar borde de página
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label" for="stretch">Ajustar texto</label>
          <div class="col-xs-6">
            <div class="checkbox">
                <input type="checkbox" name="stretch" id="stretch">Ajustar texto a código
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label" for="dwl">Descargar PDF</label>
          <div class="col-xs-6">
            <div class="checkbox">
                <input type="checkbox" name="dwl" id="dwl">descargar como archivo PDF
            </div>
          </div>
        </div>

	 </fieldset>

      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary">Generar PDF</button>
        </div>
      </div>
    </form>
</div>

<script>
//$(function() {
//    $('select').change(function() {
//        var val = $(this).val();
//        if (val) {
//            $('div:not(#div' + val + ')').css("display", "none");
//            $('#div' + val).css("display", "block")
//        } else {
//           $('#div' + val).css("display", "block")
//        }
//    });
//});
</script>
<?php include(APP_DIR.'views/barcode/footer.php'); ?>