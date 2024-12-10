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
	<title>MEDION x EKLAIM</title>
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

	<!-- load all Font Awesome v6 styles -->
	<link href="<?= base_url('templates/backend/') ?>plugins/fontawesome-6.4.2/css/all.min.css" rel="stylesheet">

	<!-- support v4 icon references/syntax -->
	<link href="<?= base_url('templates/backend/') ?>plugins/fontawesome-6.4.2/css/v4-shims.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>templates/backend/plugins/sweetalert/sweetalert2.min.css" />

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!-- jQuery -->
	<script src="<?= base_url('templates/backend/') ?>plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap Core JavaScript -->
	<script src="<?= base_url('templates/backend/') ?>bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="<?= base_url('assets/') ?>js/moment.min2.js"></script>
	<script type="text/javascript" src="<?php echo base_url('templates/backend/'); ?>plugins/blockUI/jquery.blockUI.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>assets/js/js.cookie.js"></script>

</head>

<body class="">
	<!-- Preloader -->
	<div class="preloader">
		<div class="cssload-speeding-wheel"></div>
	</div>
	<div id="wrapper">
		<!-- Navigation -->
		<nav class="navbar navbar-default navbar-static-top m-b-0">
			<div class="navbar-header"> <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse"><i class="ti-menu"></i></a>
				<div class="top-left-part" style="background: rgba(0,0,0,1);">
					<a class="logo" href="<?= admin_url() ?>">
						<b>
							<img src="<?= base_url('assets/images/logo.png') ?>" alt="home" width="50px" />
						</b>
						<span class="hidden-xs text-center m-t-30" style="vertical-align:middle">
							<!-- <img src="<?= base_url('assets/images/logo-name.png') ?>" alt="home" width="135px" /> -->
							<strong>MEDION.EKLAIM</strong>
						</span>
					</a>
				</div>
				<ul class="nav navbar-top-links navbar-left hidden-xs">
					<li><a href="javascript:void(0)" class="open-close hidden-xs waves-effect waves-light"><i class="icon-arrow-left-circle ti-menu"></i></a></li>
				</ul>
				<ul class="nav navbar-top-links navbar-right pull-right">

					<li id="notif_button" class="dropdown hide">
						<a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#"><i class="icon-bell"></i>
							<div id="notifyPoint" class="notify"></div>
						</a>
						<ul id="notifyList" class="dropdown-menu mailbox scale-up">
							<li>
								<div class="drop-title">Pesan notifikasi tidak tersedia</div>
							</li>
						</ul>
					</li>
					<li class="bg-danger"><a style="padding-top: 8px; height: 60px" class="waves-effect waves-light" href="<?= site_url('keluar') ?>"><i class="ti-power-off fa-2x"></i></a></li>
					<!-- /.dropdown -->
				</ul>
			</div>
			<!-- /.navbar-header -->
		</nav>
		<!-- Left navbar-header -->

		<?php
		// include_once('menu/' . str_replace(' ', '_', strtolower($this->session->userdata('grup_pengguna'))) . '.php');
		?>

		<!-- Left navbar-header end -->
		<!-- Page Content -->
		<div id="page-wrapper">
			<div id="main-content" class="container-fluid">
				<div class="row bg-title">
					<div class="col-md-8">
						<h4 class="m-0 page-title"><?= strtoupper($page_title) ?></h4>
						<?php if (isset($page_subtitle)) { ?>
							<h5 class="m-0 text-muted" style="font-weight:500"><?= strtoupper($page_subtitle) ?></h5>
						<?php } ?>
					</div>
					<div class="col-md-4 col-xs-12">
						<!-- <button class="btn btn-info pull-right m-l-10" type="button" title="Shortcut Menu"><i class="fa-solid fa-bars"></i></button> -->
						<?php
						if (isset($shortcut_menu)) {
							echo '<button class="btn btn-info pull-right m-l-10" onclick="' . $shortcut_menu . '" type="button" title="Shortcut Menu"><i class="fa-solid fa-bars"></i></button>';
						}
						if (isset($back_button)) {
							echo '<a href="javascript:void(0)" onClick="parent.history.back(); return false;" class="btn btn-danger pull-right m-l-20 btn-outline hidden-xs hidden-sm waves-effect waves-light"><i class=" icon-arrow-left "></i> Kembali</a>';
						} ?>
					</div>
				</div>
				<?php
				if (isset($page_view)) :
					include_once($page_view . '.php');
				endif;
				?>
			</div>
		</div>
		<!-- /.container-fluid -->
		<footer class="footer text-center"> 2017 &copy; Husmin Fajrin - <small>husmin.fajrin@gmail.com</small> </footer>

		<div class="modal fade" id="modalPenunjang">
			<div class="modal-dialog modal-fs">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"><i class="fa fa-check-square-o"></i> Hasil Pemeriksaan Penunjang</h4>
					</div>
					<div class="modal-body">

					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<?php if (($this->session->userdata('id_petugas_medis') != '' || $this->session->userdata('grup_pengguna') == 'Laboratorium' || $this->session->userdata('grup_pengguna') == 'Depo Farmasi') && $this->session->userdata('pin') == '' && $this->uri->segment(1) == 'dashboard' && $this->session->userdata('id_pengguna') != 'medion') { ?>
			<div class="modal fade in" id="modal-pin" style="">
				<div class="modal-dialog modal-md">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title"><i class="fa fa-key"></i> PIN Tanda Tangan</h4>
						</div>
						<div class="modal-body text-center">
							<p class="h4">Apabila dalam pencatatan rekam medis menggunakan teknologi informasi elektronik, kewajiban membubuhi tanda tangan dapat diganti dengan menggunakan nomor identitas pribadi (Personal Identification Number) <br> <span class="h5 text-info">UU No.29/2004 Ps. 46 ayat (3)</span></p>
							<p class="h4">Tanda tangan elektronik tidak tersertifikasi salah satunya berupa Karakter Unik (PIN) <br> <span class="h5 text-info">PP PSTE No.71/2019 Ps. 60 ayat (2)</span></p>
							<hr>
							<h4>Anda belum memiliki PIN sebagai bentuk pemberian Tanda Tangan Elektronik terhadap berkas rekam medis.
								<a href="<?= admin_url('pengguna/pin') ?>" class="btn btn-info btn-block m-t-10">Registrasi PIN</a>
							</h4>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			<script>
				$("#modal-pin").modal('show');
			</script>
		<?php } ?>

		<?php if (($this->session->userdata('grup_pengguna') == 'Depo Farmasi' || $this->session->userdata('grup_pengguna') == 'Apotek' || $this->session->userdata('grup_pengguna') == 'Laboratorium' || $this->session->userdata('grup_pengguna') == 'Radiologi') && $this->session->userdata('id_satusehat') == '' && $this->session->userdata('id_pengguna') != 'medion') { ?>
			<div class="modal fade in" id="modal-pin" style="">
				<div class="modal-dialog modal-md">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title"><i class="fa fa-key"></i> ID Satusehat</h4>
						</div>
						<div class="modal-body text-center">
							<h4>Anda belum memiliki ID Satusehat, silakan lakukan penarikan ID Satusehat berdasarkan NIK Anda pada halaman berikut
								<a href="<?= admin_url('pengguna/edit/' . $this->session->userdata('id_pengguna')) ?>" class="btn btn-info btn-block m-t-10">Pengaturan Akun</a>
								<br>atau jika Anda merupakan Tenaga Kesehatan (Perawat/Bidan/Dokter/Apoteker), bisa tanyakan ke pengelola SIMRS untuk melakukan penarikan ID Satusehat
							</h4>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			<script>
				$("#modal-pin").modal('show');
			</script>
		<?php } ?>
	</div>
	<!-- /#wrapper -->
	<!-- Menu Plugin JavaScript -->
	<script src="<?= base_url('templates/backend/') ?>plugins/sidebar-nav/dist/sidebar-nav.min.js"></script>
	<!--slimscroll JavaScript -->
	<script src="<?= base_url('templates/backend/') ?>js/jquery.slimscroll.js"></script>
	<!--Wave Effects -->
	<script src="<?= base_url('templates/backend/') ?>js/waves.js"></script>
	<!-- Custom Theme JavaScript -->
	<script src="<?= base_url('templates/backend/') ?>js/custom.js"></script>
	<!-- Date Picker Plugin JavaScript -->
	<script src="<?= base_url('templates/backend/') ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>templates/backend/plugins/sweetalert/sweetalert2.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/input_mask/jquery.inputmask.bundle.min.js"></script>
	<script>
		$(document).on('show.bs.modal', '.modal', function() {
			var zIndex = 1040 + (10 * $('.modal:visible').length);

			$(this).css('z-index', zIndex);
			setTimeout(function() {
				$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
			}, 0);
		});

		function load_page(url) {
			$(".preloader").show();
			// $('#main-content').html("<h3 class='text-center' style='margin: 15px; margin-top: 20%'><i class='fa fa-spinner fa-pulse'></i> Memuat halaman...</h3>");
			$('#main-content').load(url, function(responseTxt, statusTxt, xhr) {
				if (statusTxt == "error") {
					$('#main-content').html("<div	class='alert alert-danger' style='margin: 15px'><i class='fa fa-exclamation-circle fa-2x'></i> Terjadi kesalahan dalam mengambil data (" + xhr.status + ": " + xhr.statusText + ")</div>");
				} else {
					// window.history.pushState("object or string", "Title", url);
				}
			});
			$(".preloader").fadeOut();
		}

		$(document).on('click', '.dropdown-menu-right .load-page', function() {
			load_page($(this).attr('url'));
		});

		function roundNumber(num, scale) {
			if (!("" + num).includes("e")) {
				return +(Math.round(num + "e+" + scale) + "e-" + scale);
			} else {
				var arr = ("" + num).split("e");
				var sig = ""
				if (+arr[1] + scale > 0) {
					sig = "+";
				}
				return +(Math.round(+arr[0] + "e" + sig + (+arr[1] + scale)) + "e-" + scale);
			}
		}

		function shortcutMenu(rawatan, idRawat = '') {
			// Select all links inside the accordion menu
			let links = $('#jquery-accordion-menu-' + rawatan + ' a');
			// Loop through each link
			links.each(function() {
				// Get the corresponding data-url value
				let dataUrl = $(this).attr('href');
				if (dataUrl != "#") {
					dataUrl = dataUrl.replace('__idrawat__', idRawat);
					// Set the href attribute to the data-url value
					$(this).attr('href', dataUrl);
				}
			});

			var body = $("body");
			$("#sidebar-" + rawatan).slideDown(50).toggleClass("shw-rside");
			$(".fxhdr").on("click", function() {
				body.toggleClass("fix-header"); /* Fix Header JS */
			});
			$(".fxsdr").on("click", function() {
				body.toggleClass("fix-sidebar"); /* Fix Sidebar JS */
			});

			/* ===== Service Panel JS ===== */

			var fxhdr = $('.fxhdr');
			if (body.hasClass("fix-header")) {
				fxhdr.attr('checked', true);
			} else {
				fxhdr.attr('checked', false);
			}
			if (body.hasClass("fix-sidebar")) {
				fxhdr.attr('checked', true);
			} else {
				fxhdr.attr('checked', false);
			}
		}

		$(document).ready(function() {
			jQuery('#datepicker-inline').datepicker({
				todayHighlight: true
			});
		});

		function copyText(text) {
			var dummy = document.createElement("textarea");
			// to avoid breaking orgain page when copying more words
			// cant copy when adding below this code
			// dummy.style.display = 'none'
			document.body.appendChild(dummy);
			//Be careful if you use texarea. setAttribute('value', value), which works with "input" does not work with "textarea". – Eduard
			dummy.value = text;
			dummy.select();
			document.execCommand("copy");
			document.body.removeChild(dummy);
		}
	</script>
</body>

</html>