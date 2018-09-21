<?php
/*
 * PIP v0.5.3
 */

//Start the Session
session_start(); 

// Defines
define('ROOT_DIR', realpath(dirname(__FILE__)) .'/');
define('APP_DIR', ROOT_DIR .'app/');
define('LIB_DIR', APP_DIR.'lib/');
define('STATIC_DIR', ROOT_DIR.'static/');
define('PRIV_DIR', ROOT_DIR.'priv/');

define('HEADER',APP_DIR.'views/common/header.php');
define('FOOTER',APP_DIR.'views/common/footer.php');

// Includes
require(APP_DIR .'config/config.php');
require(ROOT_DIR .'system/model.php');
require(ROOT_DIR .'system/view.php');
require(ROOT_DIR .'system/controller.php');
require(ROOT_DIR .'system/pip.php');

// Define base URL
global $config;
define('BASE_URL', $config['base_url']);
define('STATIC_URL', BASE_URL.'static/');
define('PRIV_URL', BASE_URL.'priv/');
define('LOGIN', 'auth/login/'.substr($_SERVER['REQUEST_URI'],strlen($config['base_url'])));

pip();
