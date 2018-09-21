<?php include(HEADER); ?>	



  <div id="ex1" style="display:none;width:300;height:200;">
    <p>Thanks for clicking.  That felt good.  <a href="#" rel="modal:close">Close</a> or press ESC</p>
  </div>

  <!-- Link to open the modal -->
  <p><a href="/modal/feedme/modal1/test1" class="mod-dialog" rel="modal:open">Open Modal 1</a></p>
  <p><a href="/modal/feedme/modal2/test2" class="mod-dialog" rel="modal:open">Open Modal 2</a></p>

  <script type="text/javascript">
	$('.mod-dialog').click(function(event) {
		event.preventDefault();
		$.get(this.href, function(html) {
			$(html).appendTo('body').modal();
			});
		});
  </script>

<?php include(FOOTER); ?>