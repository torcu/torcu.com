<?php include(HEADER); ?>
<p><button id="addpanel">Add panel</button></p>
<?php $load=0 ?>
<div class="column" id="column1">
<?php $item=0; ?>
<?php for ($i = 1; $i <= $load; $i++) { ?>
<?php $item++; ?>
	<div class="dragbox" id="item<?php echo $item ?>" >
		<h2><span class="configure" ><a href="" onclick="configure('item<?php echo $item ?>')">Configure</a></span>Handle <?php echo $item ?></h2>
		<div class="dragbox-content lazy" src="/panels/getcontent" id="content<?php echo $item ?>"></div>
	</div>

<?php } ?>
</div>
<div class="column" id="column2" >
<?php for ($i = 1; $i <= $load; $i++) { ?>
<?php $item++; ?>
	<div class="dragbox" id="item<?php echo $item ?>" >
		<h2><span class="configure" ><a href="" onclick="configure('item<?php echo $item ?>')">Configure</a></span>Handle <?php echo $item ?></h2>
		<div class="dragbox-content lazy" src="/panels/getcontent" id="content<?php echo $item ?>"></div>
	</div>
<?php } ?>
</div>
<script type="text/javascript" >
	var items=<?php echo $item ?>;
</script>
<?php include(FOOTER); ?>