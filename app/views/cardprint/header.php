
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title ?></title>
	<meta name="description" content="cardprint">
	<meta name="keywords" content="cardprint"/>
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>static/webix/<?php echo $style ?>.css" type="text/css" media="screen" charset="utf-8">
	<script src="<?php echo BASE_URL; ?>static/webix/webix.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo BASE_URL; ?>static/webix/i18n/<?php echo $lang ?>.js" type="text/javascript" charset="utf-8"></script>

<?php foreach($css as $file) { ?>
		<link rel="stylesheet" href="<?php echo (strpos($file,'//')===0)?$file:BASE_URL.'static/css/'.$file.'.css' ?>" type="text/css" media="all" />
<?php } ?>
<?php foreach($js as $file) { ?>
		<script type="text/javascript" src="<?php echo (strpos($file,'//')===0)?$file:BASE_URL.'static/js/'.$file.'.js' ?>"></script>
<?php }	?>

</head>
<body>
	<style>
	body{
		background: #F2EFEA;
	}
	.transparent{
		background-color: transparent;
	}
	a.check_flight{
		color:  #367ddc;
	}
	.webix_el_box .webixtype_form {
		color: #fff;
	}
	.webix_el_counter .webix_inp_counter_prev, .webix_el_counter .webix_inp_counter_next {
		color: #fff;
	}
	.webix_el_counter .webix_inp_counter_next {
		line-height: 28px;
	}
	.webix_list_item{
		line-height: 25px;
	}
	.myheader{
		border:1px solid transparent;
		background: transparent;
		font-size:18px;
	}
	.balance {
		font-size:24px;
		padding-right:5px;
	}
</style>
<style type="text/css">
	.myheader{
		border:1px solid transparent;
		background: transparent;
		font-size:18px;
	}
	
	
</style>
