<?php include(HEADER); ?>

<style type='text/css'>
	#modalUIWrap {
	   padding: 10px;
	}
	#modalUIWrap table {
	    width: 100%;
	}
	#modalUIWrap td {
	    vertical-align: middle;
	}
	#modalUIWrap td {
	    padding-top: 3px;
	    padding-bottom: 3px;
	    color: #555;
	} 
	#modalUIWrap .modal-close {
	   color:red;
	   position:relative;
	   float:right;
	   cursor:pointer;
	} 
	.ui-widget-overlay {
	    position: fixed;
	}
	.ui-widget-overlay {
	    background: #000;
	    opacity: .7;
	    -moz-opacity: 0.7;
	    filter: alpha(opacity=70);
	}
	.ui-dialog-titlebar {
	  display: none;
	}
</style>

<script type='text/javascript'>
//<![CDATA[ 
	$(window).load(function(){
		$('#modalUIWrap').dialog({
			modal: true,
			resizable: false,
			autoOpen: false,
			width: 500,
			buttons: {
				"Save": function () {
					alert('save here!');
				}
			}
			//close: function(event, ui) {
			//	$(this).dialog('destroy');
			//	$('#modalEventEditUIWrap').remove();
			//} 
		});
		$('#startDate').datepicker();
		$('#showMe').click(function () {
			$('#modalUIWrap').dialog('open');
		});
		$('.modal-close').click(function () {
			$('#modalUIWrap').dialog('close');
		});

	});
//]]>
</script>

<input type="button" id="showMe" value="Show Me" /> 
<div id="modalUIWrap" style="display: none">
	<div class="modal-close" onclick="">X</div>
    <table> 
        <tr> 
            <td style="width: 150px">Name</td> 
            <td> 
                <input type="text" id="name" name="name" style="width: 250px" /> 
            </td> 
        </tr> 
        <tr> 
            <td>Category</td> 
            <td> 
                <input type="text" id="category" name="category" style="width: 150px" /> 
            </td> 
        </tr> 
        <tr> 
            <td>Short Description:</td> 
            <td> 
                <input type="text" id="shortDescription" name="shortDescription" style="width: 250px" /> 
            </td> 
        </tr> 
        <tr> 
            <td>Long Description:</td> 
            <td> 
                <textarea id="longDescription" name="longDescription" style="width: 300px; height: 80px"></textarea> 
            </td> 
        </tr> 
        <tr> 
            <td>Price:</td> 
            <td> 
                <input type="text" id="price" name="price" style="width: 85px" /> 
            </td> 
        </tr> 
        <tr> 
            <td>Start Date:</td> 
            <td> 
                <input type="text" id="startDate" name="startDate" style="width: 85px" /> 
            </td> 
        </tr> 
    </table> 
</div> 

<?php include(FOOTER); ?>