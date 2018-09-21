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
    	<script src="<?php echo BASE_URL; ?>static/js/jquery.min.js"></script>
    	<!-- script src="<?php echo BASE_URL; ?>static/js/jquery.form.js"></script -->
		<!-- script src="//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script -->
		<!-- script src="//code.jquery.com/jquery-git.js"></script -->
<?php foreach($js as $file) { ?>
		<script type="text/javascript" src="<?php echo (strpos($file,'//')===0)?$file:BASE_URL.'static/js/'.$file.'.js' ?>"></script>
<?php }	?>
<?php if(0) { ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-53702211-2', 'auto');
  ga('send', 'pageview');
</script>
<?php } ?>
		<style>
			.progress 	{ position:relative; width:100%; border: 1px solid #ddd; padding: 1px; border-radius: 3px; margin-top:5px; margin-bottom:0px; }
			.bar 		{ background-color: #B4F5B4; width:20%; height:20px; border-radius: 3px; }
			.percent 	{ position:absolute; display:inline-block; top:0px; left:48%; }
		</style>
	</head>
<body>
<!-- Fixed navbar -->
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand" href="<?php echo BASE_URL ?>concilia">conciliador</a>
    </div>
  </div>
</div>
<!-- Begin page content -->
<div class="container">