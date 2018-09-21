<?php include(HEADER); ?>
<style>
 #log {width:440px; height:200px; border:1px solid #7F9DB9; overflow:auto;}
 #msg {width:330px;}
</style>

<h3>WebSocket v2.00</h3>

  <div id="log"></div>
  <input id="msg" type="textbox" onkeypress="onkey(event)"/>
  <button onclick="send()">Send</button>
  <button onclick="quit()">Quit</button>
  <div>Commands: hello, hi, name, age, date, time, thanks, bye</div>

<?php include(FOOTER); ?>