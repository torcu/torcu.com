<?php

class Ws extends Controller {
  
  function index()
  {
    
    $template = $this->loadView('ws/index');
    $template->set('title', 'web sockets');
    $template->loadJS('websocket');
    $template->render();
  }

  function start()
  {
    $out = shell_exec('sudo service phpbot start');
    $out .=  'chatbot started with pid '.file_get_contents('/var/run/phpbot/phpbot.pid'); 
    die($out);
  }

  function stop()
  {
    $out = shell_exec('sudo service phpbot stop');
    die($out);
  }

  function status()
  {
    if (file_exists('/var/run/phpbot/phpbot.pid'))
      die('1');
    else
      die('0');
  }
    
}
