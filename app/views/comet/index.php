<?php include(HEADER); ?>	
<h3>Comet</h3>

  <div id="msgcontent"></div>

  <p>
    <form action="" method="get" onsubmit="comet.doRequest($('word').value);$('word').value='';return false;">
      <input type="text" name="word" id="word" value="" />
      <input type="submit" name="submit" value="Send" />
    </form>
  </p>
   
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
  </script>

<?php include(FOOTER); ?>