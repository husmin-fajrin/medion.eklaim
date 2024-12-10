<?php
$CI = &get_instance();
if (!isset($CI)) {
	$CI = new CI_Controller();
}
$CI->load->helper('url');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="robots" content="noindex">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" type="image/png" sizes="16x16" href="<?= base_url() ?>assets/images/logo-app.png">
	<title>MED.ION - Sistem Informasi Manajemen Rumah Sakit</title>
	<!-- Bootstrap Core CSS -->
	<link href="<?= base_url('templates/backend/') ?>bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Menu CSS -->
	<link href="<?= base_url('templates/backend/') ?>plugins/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
	<!-- animation CSS -->
	<link href="<?= base_url('templates/backend/') ?>css/animate.css" rel="stylesheet">
	<!-- Custom CSS -->
	<link href="<?= base_url('templates/backend/') ?>css/style.css" rel="stylesheet">
	<!-- color CSS -->
	<link href="<?= base_url('templates/backend/') ?>css/colors/megna-dark.css" id="theme" rel="stylesheet">
	<!-- custom CSS -->
	<link href="<?= base_url('templates/backend/') ?>css/custom.css" rel="stylesheet">
	<!-- Date picker plugins css -->
	<link href="<?= base_url('templates/backend/') ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body>
	<section id="wrapper" class="error-page">
		<div class="error-box" style="background-color: #f5f5f5 !important;">
			<div class="error-body text-center">
				<!-- <h1>404</h1>
				<h3 class="text-uppercase">Page Not Found !</h3> -->
				<img src="<?= base_url('assets/images/404.jpg') ?>" class="img-responsive" style="margin:0 auto">
				<h4 class="text-muted m-t-30 m-b-30">YOU SEEM TO BE TRYING TO FIND HIS WAY HOME</h4>
				<a href="<?= admin_url() ?>" class="btn btn-info btn-rounded waves-effect waves-light m-b-40">Back to home</a>
			</div>
			<footer class="footer text-center">2017 © Husmin Fajrin - husmin.fajrin@gmail.com</footer>
		</div>
	</section>

	<!-- jQuery -->
	<script src="<?= base_url('templates/backend/') ?>plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap Core JavaScript -->
	<script src="<?= base_url('templates/backend/') ?>bootstrap/dist/js/bootstrap.min.js"></script>
</body>

</html>