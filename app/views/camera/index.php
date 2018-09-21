<?php include(HEADER); ?>
<div align="center">
	<p>
		<IMG APPLET CODE="YawApplet.class" ARCHIVE="YawApplet.jar" CODEBASE="http://_Public_Address_:8081" WIDTH="320" HEIGHT="240">
			<param name="Host" value="_Public_Address_">
			<param name="Port" value="8081">
			<param name="Zoom" value="true">
		</APPLET>
	</p>
	<p>
		<input type="button" id="left"  name="left"  value=" < left "  onmousedown='$("#left").move("left");$("#stop").attr("disabled", "disabled");$("#auto").removeAttr("disabled");'   onmouseup='$("#left").move("stop");'>
		<input type="button" id="right" name="right" value=" right > " onmousedown='$("#right").move("right");$("#stop").attr("disabled", "disabled");$("#auto").removeAttr("disabled");' onmouseup='$("#right").move("stop");'>
		<input type="button" id="auto"  name="auto"  value=" auto "    onclick='$("#auto").move("auto");$("#auto").attr("disabled", "disabled");$("#stop").removeAttr("disabled");'>
		<input type="button" id="stop"  name="stop"  value=" stop "    onclick='$("#stop").move("stop");$("#auto").removeAttr("disabled");$("#stop").attr("disabled", "disabled");' disabled="disabled" >
		<div id="result"></div>
	</p>
</div>
<script type="application/javascript">
	$.fn.move = function (d) {
	   var values="dir="+d;
		$.ajax({
		  url: "/camera/move/"+d,
		  success: function(res){
			  $("#result").html(res);
		  },
		  error:function(){
			  $("#result").html("fail");
		  }
		});
	}
</script>
<?php include(FOOTER); ?>>
