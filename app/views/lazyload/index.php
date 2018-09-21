<?php include(HEADER); ?>
<h3>lazyload</h3>
<?php
  for ($i=0;$i<100;$i++) {
?>
  <div class="panel lazy" src="/lazyload/getitem/<?php echo $i; ?>" id="panel_<?php echo $i; ?>">
      <b>panel <?php echo $i; ?></b>
  </div>
<?php
  }
?>
<?php include(FOOTER); ?>