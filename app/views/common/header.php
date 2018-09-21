<!DOCTYPE html>
<html lang="en">
	<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
		<title><?php echo $title ?></title>
		<meta name="description" content="torcu.com">
		<meta name="author" content="torcu.com">

		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->	
    
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/style.css" type="text/css" media="all" />
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/bootstrap.min.css" type="text/css" media="all" >
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/sticky-footer-navbar.css" type="text/css" media="all" >
<?php foreach($css as $file) { ?> 
		<link rel="stylesheet" href="<?php echo (strpos($file,'//')===0)?$file:BASE_URL.'static/css/'.$file.'.css' ?>" type="text/css" media="all" />
<?php } ?>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<!-- script src="//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script -->
		<!-- script src="//code.jquery.com/jquery-git.js"></script -->
<?php foreach($js as $file) { ?>
		<script type="text/javascript" src="<?php echo (strpos($file,'//')===0)?$file:BASE_URL.'static/js/'.$file.'.js' ?>"></script>
<?php }	?>
	</head>
<body>
<!-- Fixed navbar -->
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <!-- button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button -->
      <a class="navbar-brand" href="#">torcu.com</a>
    </div>
    <div class="nav-collapse collapse">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#contact">Contact</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li class="divider"></li>
            <li class="nav-header">Nav header</li>
            <li><a href="#">Separated link</a></li>
            <li><a href="#">One more separated link</a></li>
          </ul>
        </li>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</div>
<!-- Begin page content -->
<div class="container">
<?php //print_r($_SERVER); ?>
