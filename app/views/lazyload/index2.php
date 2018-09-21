<?php include(HEADER); ?>

<h3>lazyload</h3>
<?php
  for ($i=0;$i<100;$i++) {
?>
  <div class="panel" id="panel_<?php echo $i; ?>">
      <b>panel <?php echo $i; ?></b>
  </div>
<?php
  }
?>
   
<script type="text/javascript">
<?php
  for ($i=0;$i<100;$i++) {
?>
  var lazyloader_<?php echo $i; ?> = $('#panel_<?php echo $i; ?>').lazyload({
    src:'/lazyload/getitem?item=<?php echo $i; ?>',   
  });
  lazyloader_<?php echo $i; ?>.settings.loadStart = function() {
     $('#panel_<?php echo $i; ?>').html('<i class="fa fa-refresh fa-spin"></i>');
  };
  lazyloader_<?php echo $i; ?>.settings.loadSuccess = function(data, textStatus, jqXHR) {
    if (data.noResults) {
        $('#panel_<?php echo $i; ?>').html('Could not load content')
        this.settings.noResults();
    }
    $('#panel_<?php echo $i; ?>').html(data);
    //$('#panel_<?php echo $i; ?>').unbind();
  };

<?php
  }
?>
$(document).trigger('scroll');
</script>
<?php include(FOOTER); ?>