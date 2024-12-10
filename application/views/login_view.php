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
	<title>MEDION - Medical Solution</title>
	<!-- Bootstrap Core CSS -->
	<link href="<?= base_url('templates/backend/') ?>bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- animation CSS -->
	<link href="<?= base_url('templates/backend/') ?>css/animate.css" rel="stylesheet">
	<!-- Custom CSS -->
	<link href="<?= base_url('templates/backend/') ?>css/style.css" rel="stylesheet">
	<!-- color CSS -->
	<link href="<?= base_url('templates/backend/') ?>css/colors/blue.css" id="theme" rel="stylesheet">
	<!-- vegas (bg slide) CSS -->
	<link rel="stylesheet" href="<?= base_url('assets/') ?>bg-slide-vegas/vegas.min.css">

	<!-- load all Font Awesome v6 styles -->
	<link href="<?= base_url('templates/backend/') ?>plugins/fontawesome-6.4.2/css/all.min.css" rel="stylesheet">

	<!-- support v4 icon references/syntax -->
	<link href="<?= base_url('templates/backend/') ?>plugins/fontawesome-6.4.2/css/v4-shims.min.css" rel="stylesheet">
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	<!-- jQuery -->
	<script src="<?= base_url('templates/backend/') ?>plugins/jquery/jquery.min.js"></script>
	<script src="<?= base_url('assets/') ?>jsTimezoneDetect/jstz-1.0.4.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			var tz = jstz.determine();
			$('#timezone').val(tz.name());

			//menghapus localstorage dari datatable (statesave: true)
			var arr = []; // Array to hold the keys
			// Iterate over localStorage and insert the keys that meet the condition into arr
			for (var i = 0; i < localStorage.length; i++) {
				if (localStorage.key(i).substring(0, 20) == 'DataTables_dataTable') {
					arr.push(localStorage.key(i));
				}
			}

			// Iterate over arr and remove the items by key
			for (var i = 0; i < arr.length; i++) {
				localStorage.removeItem(arr[i]);
			}
		});
	</script>
</head>

<style>
	.login-box {
		width: 50%;
		margin-top: 0;
	}

	@media (max-width: 1200px) {
		/* .login-register {
			padding-top: 10% !important;
		} */

		.login-box {
			width: 75% !important;
		}

		.sub-title {
			width: 100% !important;
			/* font-size: 12px !important; */
		}
	}

	@media (max-width: 767px) {
		.login-register {
			padding: 10% 0 17% 0 !important;
			/* position: fixed; */
		}

		.login-box {
			width: 75% !important;
		}

		body {
			background: none !important;
		}
	}

	h4.text-responsive {
		font-size: calc(1rem + 0.3vw);
	}
</style>

<body>
	<!-- Preloader -->
	<!--
		<div class="preloader">
			<div class="cssload-speeding-wheel"></div>
		</div>
		-->
	<section id="wrapper" class="login-register" style="background-image: url() !important; display: flex;flex-direction: column;justify-content: center;">
		<div class="login-box" style="">
			<div class="">
				<div class="row" style="min-height: 450px; display: flex; align-items: center; flex-wrap: wrap">
					<div class="col-md-6 col-xs-12" style="display: flex; align-self: stretch; align-items: center; justify-content: center; background-color: #000">
						<div class="text-center" style="justify-content: center;">
							<hr style="border-color:#efefef" class="m-0">
							<h1 class=" text-white" style="letter-spacing:0.4em">MEDION<br>
								<img class="d-block" src="<?= base_url() ?>assets/images/logo.png" alt="Home" width="120" />
								<br>EKLAIM
							</h1>

							<!-- <h2 class="mb-2">[MEDICAL SOLUTION]</h2> -->
							<hr style="border-color:#efefef" class="m-0">
							<!-- <h4 class="text-responsive sub-title pt-2 text-white">Hospital Management Information System</h4> -->
							<!-- <h3 class="text-center text-white" style="align-self: center">
								SISTEM INFORMASI MANAJEMEN RUMAH SAKIT
							</h3> -->
						</div>
					</div>
					<div class="col-md-6 col-xs-12 p-20">
						<form class="form-horizontal form-material" id="loginform" action="<?= site_url() ?>" method="post">
							<div class="row m-t-40">
								<div class="col-md-12">
									<div class="error"></div>
									<?php if ($error_msg != '') { ?>
										<div class="alert alert-danger">
											<h4 class="m-b-0"><i class="fa fa-exclamation-triangle"></i> PERHATIAN!!!</h4><?= $error_msg ?>
										</div>
									<?php } ?>
								</div>
							</div>

							<div class="form-group">
								<div class="col-xs-12">
									<input class="form-control" type="text" name="id_pengguna" id="userID" required="" placeholder="Username">
								</div>
							</div>
							<div class="form-group">
								<div class="col-xs-12">
									<div class="input-group">
										<input class="form-control" type="password" id="password_pengguna" name="password_pengguna" required="" placeholder="Password">
										<span class="input-group-addon toggle-password"><i class="fa fa-eye"></i></span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-xs-12">
									<input class="form-control" type="text" name="captcha" id="captcha" required placeholder="Silakan tulis kode di bawah">
								</div>
								<div>
									<img src="<?= site_url('main/captcha/' . rand()) ?>" id='captcha_image'>
									<button class="btn btn-secondary" id="reload-captcha" type="button"><i class="fa fa-refresh"></i></button>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<!--
								<div class="checkbox checkbox-primary pull-left p-t-0">
									<input id="checkbox-signup" type="checkbox">
									<label for="checkbox-signup"> Remember me </label>
								</div>
								<a href="javascript:void(0)" id="to-recover" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> Forgot pwd?</a>
								-->
								</div>
							</div>
							<div class="form-group text-center m-t-20">
								<div class="col-xs-12">
									<input type="hidden" name="timezone" id="timezone">
									<input type="hidden" id="<?= $this->security->get_csrf_token_name(); ?>" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />

									<div class="w-100" style="display: flex;">
										<button id="preLogin" class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="button">Log In</button>
										<!-- <button id="to-recover" type="button" class="btn btn-lg btn-primary waves-effect waves-light">
											<i class="fa fa-desktop"></i>
										</button> -->

									</div>
									<button id="submitLogin" class="hidden" type="submit" name="submit_login" value="true">Log In</button>
								</div>
							</div>
						</form>

						<section id="recoverform">
							<div class="row m-b-10">
								<div class="col-md-12">
									<a href="<?= admin_url("antrean_online/pendaftaran") ?>" class="btn btn-lg btn-facebook btn-block m-b-10" id="btn-absen-masuk">
										KIOSK<br>ANTREAN
									</a>
								</div>
								<div class="col-md-6">
									<a href="<?= admin_url("antrean_online/dashboard/admisi") ?>" class="btn btn-lg btn-facebook btn-block" id="btn-absen-masuk">
										DASHBOARD<br>ANTREAN<br>ADMISI
									</a>
								</div>
								<div class="col-md-6">
									<a href="<?= admin_url("antrean_online/dashboard/klinik") ?>" class="btn btn-lg btn-facebook  btn-block" id="btn-absen-masuk">
										DASHBOARD<br>ANTREAN<br>KLINIK
									</a>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<a href="<?= admin_url("tempat_tidur/dashboard") ?>" class="btn btn-lg btn-googleplus btn-block" id="btn-absen-masuk">
										DASHBOARD<br>TEMPAT<br>TIDUR
									</a>
								</div>
								<div class="col-md-6">
									<a href="<?= admin_url("antrean_online/dashboard_jadwal_operasi") ?>" class="btn btn-lg btn-googleplus btn-block" id="btn-absen-masuk">
										DASHBOARD<br>JADWAL<br>OPERASI
									</a>
								</div>
							</div>
							<hr>
							<button id="to-login" class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="button"><i class="fa fa-chevron-left"></i> Log In</button>
						</section>
					</div>
				</div>

				<div class="text-center text-white" style="position:absolute;left:0;bottom:20px;width:100%">
					<hr style="width:90%">
					2017 © Medion Prima Solusindo - Husmin Fajrin Hulnggi
				</div>
			</div>
		</div>
	</section>
	<!-- Bootstrap Core JavaScript -->
	<script src="<?= base_url('templates/backend/') ?>bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- vegas (bg slide) -->
	<script src="<?= base_url('assets/') ?>bg-slide-vegas/vegas.min.js"></script>
	<!-- Custom Theme JavaScript -->
	<!--<script src="<?= base_url('templates/backend/') ?>js/custom.min.js"></script>-->
	<script type="text/javascript" src="<?php echo base_url() ?>assets/js/js.cookie.js"></script>
	<script>
		$(".toggle-password").click(function() {
			$(this).children('i').toggleClass("fa-eye fa-eye-slash");
			var input = $("#password_pengguna");
			if (input.attr("type") == "password") {
				input.attr("type", "text");
			} else {
				input.attr("type", "password");
			}
		});

		$("#preLogin").click(function() {
			$("#<?= $this->security->get_csrf_token_name(); ?>").val(Cookies.get('mdn_cookie'));
			$("#submitLogin").click();
		});

		$("#to-recover").on("click", function() {
			$("#loginform").slideUp(), $("#recoverform").fadeIn()
		})

		$("#to-login").on("click", function() {
			$("#recoverform").slideUp(), $("#loginform").fadeIn()
		})

		$("#reload-captcha").click(function() {
			var img = document.images['captcha_image'];
			img.src = img.src.substring(
				0, img.src.lastIndexOf("/")
			) + "/" + Math.random() * 1000;
		})


		$("#userID").focus();
		$("body").vegas({
			slides: [{
					src: "<?= base_url('assets/') ?>images/bg1.jpg"
				},
				{
					src: "<?= base_url('assets/') ?>images/bg2.jpg"
				},
				{
					src: "<?= base_url('assets/') ?>images/bg3.jpg"
				},
				{
					src: "<?= base_url('assets/') ?>images/bg4.jpg"
				},
				{
					src: "<?= base_url('assets/') ?>images/bg5.jpg"
				}
			],
			transition: 'blur',
			overlay: '<?= base_url('assets/') ?>bg-slide-vegas/overlays/07.png'
		});
	</script>
</body>

</html>