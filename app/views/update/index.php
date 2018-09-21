<?php include(HEADER); ?>
<!-- http://jhollingworth.github.io/bootstrap-wysihtml5/ -->
<div class="container">
	<h1 style="font-size:58px"></h1>
	<hr/>
	<textarea class="textarea" placeholder="Enter text ..." style="width: 810px; height: 200px"></textarea>
</div>
<style type="text/css" media="screen">
	.btn.jumbo {
		font-size: 20px;
		font-weight: normal;
		padding: 14px 24px;
		margin-right: 10px;
		-webkit-border-radius: 6px;
		-moz-border-radius: 6px;
		border-radius: 6px;
	}
</style>
<script>
	$('.textarea').wysihtml5();
</script>
<script type="text/javascript" charset="utf-8">
	$(prettyPrint);
</script>
<?php include(FOOTER); ?>
