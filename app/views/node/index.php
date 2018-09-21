<?php include(HEADER); ?>
<h3>Node test</h3>
  <div id="msgcontent"></div>
  <p>
    <time></time>
    <div id="container">Try to change your xml data to update this content</div>
  </p>
  <script src="socket.io/socket.io.js"></script>
  <script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
  <script>
    // creating a new websocket
    var socket = io.connect('http://node.torcu.com');
    // on every message recived we print the new datas inside the #container div
    socket.on('notification', function (data) {
      // convert the json string into a valid javascript object
      var _data = JSON.parse(data);
      $('#container').html(_data.test.sample);
      $('time').html('Last Update:' + _data.time);
    });
  </script>
<?php include(FOOTER); ?>