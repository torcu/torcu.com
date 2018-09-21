<?php include(HEADER); ?>	

<h4>&nbsp;</h4>

<div class="row">
    <div class="col-md-5">
		
		<h4>Call events</h4>
		
		<form class="form-horizontal" role="form">
		
	
			<div class="form-group">
				<label for="event" class="col-sm-2 col-form-label">Event</label>
				<div class="col-sm-10">  
					<select class="form-control" id="event">
						<option value="incoming">Incoming</option>
						<option value="pickup">Pick up</option>
						<option value="released">Hang up</option>
						<option value="decline">Declined</option>
					</select>
				</div>
			</div>
			
			<div class="form-group">
				<label for="ani" class="col-sm-2 col-form-label">User ID</label>
				<div class="col-sm-10">  
					<input type="text" class="form-control" id="uid" placeholder="User ID" value="1">   
				</div>
			</div>
	
			<div class="form-group">
				<label for="callid" class="col-sm-2 col-form-label">Call ID</label>
				<div class="col-sm-10">
					<div class="input-group">
						<input type="text" class="form-control" id="callid" placeholder="Call ID">
						<span class="input-group-btn">
							<button class="form-control btn btn-primary" id="refreshid" type="button"><i class="fa fa-refresh"></i></button>
						</span>
					</div>
				</div>
			</div>
					
			<div class="form-group">
				<label for="ani" class="col-sm-2 col-form-label">Number</label>
				<div class="col-sm-10">  
					<input type="text" class="form-control" id="ani" placeholder="ANI">   
				</div>
			</div>
	
			<div class="form-group">
				<label for="dni" class="col-sm-2 col-form-label">DNI</label>
				<div class="col-sm-10">  
					<input type="text" class="form-control" id="dni" placeholder="DNI">   
				</div>
			</div>
		
			<div class="form-group">
				<div class="col-sm-2"></div>
				<div class="col-sm-5">  
					<input type="button" class="form-control btn btn-primary" id="generate" value="Create call" style="visibility:hidden;margin-bottom:10px;">   
				</div>
				<div class="col-sm-5">  
					<input type="button" class="form-control btn btn-success" id="send" value="Send" style="margin-bottom:10px;">   
				</div>
			</div>
		
		
		</form>
		
	</div>
	<div class="col-md-7">
      <h4>Event log</h4>
      <div class="row well" style="-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;overflow:hidden;border:0px;height:100%;font-size:0.9em;">
          <div id="msgcontent"></div>
      </div>
	</div>
</div>

  <!-- p>
    <form action="" method="get" onsubmit="comet.doRequest($('word').value);$('word').value='';return false;">
      <input type="text" name="word" id="word" value="" />
      <input type="submit" name="submit" value="Send" />
    </form>
  </p -->
   
  <script type="text/javascript">
  var Comet = Class.create();
  Comet.prototype = {
 
    timestamp: 0,
    url: '/static/comet/server.php',
    noerror: true,
 
    initialize: function() { },
 
    connect: function()
    {
      this.ajax = new Ajax.Request(this.url, {
        method: 'get',
        parameters: { 'timestamp' : this.timestamp },
        onSuccess: function(transport) {
          // handle the server response
          var response = transport.responseText.evalJSON();
          this.comet.timestamp = response['timestamp'];
          this.comet.handleResponse(response);
          this.comet.noerror = true;
        },
        onComplete: function(transport) {
          // send a new ajax request when this request is finished
          if (!this.comet.noerror)
            // if a connection problem occurs, try to reconnect each 5 seconds
            setTimeout(function(){ comet.connect() }, 5000); 
          else
            this.comet.connect();
          this.comet.noerror = false;
        }
      });
      this.ajax.comet = this;
    },
 
    disconnect: function()
    {
    },
 
    handleResponse: function(response)
    {
	  HandleMsg(response['msg']);
      $('msgcontent').innerHTML += '<div>' + response['msg'] + '</div>';
    },
 
    doRequest: function(request)
    {
      new Ajax.Request(this.url, {
        method: 'get',
        parameters: { 'msg' : request }
      });
    }
  }
	
	var comet = new Comet();
	comet.connect();
  
	jQuery('#send').click(function() {
		var msg = '{"callid":"' + jQuery('#callid').val() + '","uid":"' + jQuery('#uid').val() + '","event":"' + jQuery('#event').val() +'","ani":"' + jQuery('#ani').val() +'","dni":"' + jQuery('#dni').val() +'"}';
		comet.doRequest(msg);
		return false;
	});
	
	jQuery('#refreshid').click(function() {
		var id = Math.floor((Math.random() * 10000000000000) + 1);
		jQuery('#callid').val(id);
	});
	
	function HandleMsg(msg) {
		
		var obj = jQuery.parseJSON(msg);
		if(typeof(obj.callid) !== undefined) {
			jQuery('#callid').val(obj.callid);
		}
		if(typeof(obj.ani) !== undefined) {
			jQuery('#ani').val(obj.ani);
		}
		if(typeof(obj.dni) !== undefined) {
			jQuery('#dni').val(obj.dni);
		}
	
		
		//jQuery('#msgcontent').innerHTML += '<div>' + response['msg'] + '</div>';
	}
  
  
  </script>
  
  
  <script type="text/javascript">
  
	
  
  </script>
  
  

<?php include(FOOTER); ?>