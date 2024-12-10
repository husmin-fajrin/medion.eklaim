<link href="<?= base_url('templates/backend/plugins/') ?>select2/css/select2.min.css" rel="stylesheet">
<link href="<?= base_url('templates/backend/plugins/') ?>select2/css/select2-bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url() ?>assets/daterangepicker/daterangepicker.min.css" rel="stylesheet">
<style>
	#table-diagnosa td,
	#table-diagnosa tr,
	#table-diagnosa-ig td,
	#table-diagnosa-ig tr {
		cursor: all-scroll;
	}
</style>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12 white-box block">
		<form method="post" onsubmit="return false" accept-charset="utf-8" action="" class="form-horizontal" id="formSearch">
			<input type="hidden" id="csrf_mdn" name="csrf_mdn">
			<input type="hidden" id="tipePencarian" name="pencarian" value="nomor">

			<div class="form-group">
				<label for="filterNoSep" class="col-sm-2 control-label">Nomor SEP</label>
				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" id="filterNoSep" name="no_sep" placeholder="Nomor SEP">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button id="btnSearchKunjungan" type="button" class="btn btn-info waves-effect waves-light" style="width:120px" name="submit" value="submit"><i class="fa fa-search"></i> Cari</button>
					<!-- <button id="btnResetFilter" type="reset" class="btn btn-danger waves-effect waves-light"><i class="fa fa-close"></i> Batal</button> -->
				</div>
			</div>
		</form>
	</div>
</div>

<div id="form-klaim">
</div>

<div id="modal-rme" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-fs">
		<div class="modal-content">
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<hr>
				<button type="button" class="btn btn-danger waves-effect" data-dismiss="modal"><i class="ti-close"></i> Tutup</button>
			</div>
		</div>
	</div>
</div>

<script src="<?= base_url('templates/backend/plugins/') ?>select2/js/select2.min.js"></script>
<script src="<?= base_url('templates/backend/plugins/') ?>select2/js/i18n/id.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/moment.min2.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/daterangepicker/daterangepicker.js"></script>

<script>
	$('#filterNoSep, #filterNoRM').keydown(function(e) {
		if (e.keyCode == 13) {
			$('#btnSearchKunjungan').click();
		}
	});

	$('button#btnSearchKunjungan').click(function() {
		if ($("#filterNoSep").val() == '') {
			swal("", 'Silakan masukan Nomor SEP', "warning");
			return false;
		}

		$("#<?= $this->security->get_csrf_token_name(); ?>").val(Cookies.get('mdn_cookie'));
		var formData = new FormData($('form#formSearch')[0]);

		$.ajax({
			url: '<?= admin_url('eklaim/get_kunjungan'); ?>',
			type: 'post',
			dataType: 'json',
			contentType: false,
			cache: false,
			processData: false,
			data: formData,
			beforeSend: function() {
				$('div.block').block({
					message: '<h4><i class="fa fa-spinner fa-pulse"></i> Sedang Memproses...</h4>',
					css: {
						border: '1px solid #fff'
					}
				});
			},
			complete: function() {
				$('div.block').unblock();
			},
			success: function(data) {
				if (data.success) {
					// $('input[name=nama_pasien]').val(data.data_klaim.pasien.nama_pasien);
					// $('input[name=no_rekam_medis]').val(data.data_klaim.pasien.no_rekam_medis);
					// $('input[name=tanggal_lahir').val(data.data_klaim.pasien.tanggal_lahir);
					// $('input[name=no_bpjs]').val(data.data_klaim.pasien.no_bpjs);
					// if (data.data_klaim.pasien.jenis_kelamin == 'Wanita') {
					// 	$('input[name=jenis_kelamin]').val(2);
					// } else {
					// 	$('input[name=jenis_kelamin]').val(1);
					// }

					// if (data.data_klaim.tagihan.jenis_rawat == 'Rawat Inap') {
					// 	$('input[name=jenis_rawat]').val(1);
					// } else if (data.data_klaim.tagihan.jenis_rawat == 'Rawat Jalan') {
					// 	$('input[name=jenis_rawat]').val(2);
					// } else if (data.data_klaim.tagihan.jenis_rawat == 'Rawat Darurat') {
					// 	$('input[name=jenis_rawat]').val(3);
					// }
					// $('#d_jenis_rawat').val(data.data_klaim.tagihan.jenis_rawat);

					// $('input[name=no_sep]').val(data.data_klaim.tagihan.no_sep);
					// $('input[name=id_tagihan_pasien]').val(data.data_klaim.tagihan.id_tagihan_pasien);
					// $('input[name=nama_dokter]').val(data.data_klaim.data_rawat[0].nama_petugas_medis);
					// $('#d_tanggal_masuk').val(moment(data.data_klaim.data_rawat[0].tanggal_masuk).format('DD MM YYYY HH:mm:ss'));
					// $('input[name=tanggal_masuk]').val(data.data_klaim.data_rawat[0].tanggal_masuk);
					// $('#d_tanggal_keluar').val(moment(data.data_klaim.data_rawat[(data.data_klaim.data_rawat.length - 1)].tanggal_keluar).format('DD MM YYYY HH:mm:ss'));
					// $('input[name=tanggal_keluar]').val(data.data_klaim.data_rawat[(data.data_klaim.data_rawat.length - 1)].tanggal_keluar);

					// if (data.data_klaim.data_rawat[0].dikirim_oleh == 'Puskesmas' || data.data_klaim.data_rawat[0].dikirim_oleh == 'Dokter') {
					// 	$('input[name=cara_masuk]').val('gp');
					// } else if (data.data_klaim.data_rawat[0].dikirim_oleh == 'Rumah Sakit Lain') {
					// 	$('input[name=cara_masuk]').val('hosp-trans');
					// } else if (data.data_klaim.data_rawat[0].dikirim_oleh == 'Dokter Spesialis') {
					// 	$('input[name=cara_masuk]').val('mp');
					// } else if (data.data_klaim.data_rawat[0].dikirim_oleh == 'Rawat Jalan') {
					// 	$('input[name=cara_masuk]').val('outp');
					// } else if (data.data_klaim.data_rawat[0].dikirim_oleh == 'Rawat Inap') {
					// 	$('input[name=cara_masuk]').val('inp');
					// } else if (data.data_klaim.data_rawat[0].dikirim_oleh == 'Rawat Darurat') {
					// 	$('input[name=cara_masuk]').val('emd');
					// } else if (data.data_klaim.data_rawat[0].dikirim_oleh == 'Lahir di RS') {
					// 	$('input[name=cara_masuk]').val('born');
					// } else if (data.data_klaim.data_rawat[0].dikirim_oleh == 'Panti Jompo') {
					// 	$('input[name=cara_masuk]').val('nursing');
					// } else if (data.data_klaim.data_rawat[0].dikirim_oleh == 'Rumah Sakit Jiwa') {
					// 	$('input[name=cara_masuk]').val('psych');
					// } else if (data.data_klaim.data_rawat[0].dikirim_oleh == 'Rujukan Fasilitas Rehab') {
					// 	$('input[name=cara_masuk]').val('rehab');
					// } else {
					// 	$('input[name=cara_masuk]').val('other');
					// }
					// $('#d_cara_masuk').val(data.data_klaim.data_rawat[0].dikirim_oleh);

					// if (data.data_inacbg != '') {
					// 	$('select[name=kelas_rawat]').val(data.data_inacbg.kelas_rawat).change();
					// }

					$('#form-klaim').html(data.form_eklaim);
					// $("#cariDiagnosa, #cariProcedure, #cariDiagnosaIG, #cariProcedureIG").select2();
					redeclare_select2();





					$('section').unblock();
				} else {
					swal("Gagal", data.message, "error");
					$('section').block({
						message: data.message
					});
				}
			},
			// jqXHR, textStatus, errorThrown
			error: function(x, e) {
				if (x.responseText != '') {
					swal('Gagal', x.responseText, 'error');
				} else {
					// swal('Gagal', e+' '+x.status+' - '+x.statusText);
					swal('Gagal', 'Please, check your internet connection', 'error');
				}
			}
		});
	});


	function onSuccessCallback() {
		$('#btnSubmit').attr("disabled", false).attr('role', 'update');
	}
</script>

<script>
	//---------------------------------------- start: load rme ----------------------------------------//
	var rme = false;

	//---------------------------------------- end: load rme ----------------------------------------//

	/*======================================= start select2: DIAGNOSA & PROCEDURE =========================================*/
	var row;

	function start() {
		row = event.target;
	}

	function dragover() {
		var e = event;
		e.preventDefault();

		let children = Array.from(e.target.parentNode.parentNode.children);
		if (children.indexOf(e.target.parentNode) > children.indexOf(row)) {
			e.target.parentNode.after(row);
		} else {
			e.target.parentNode.before(row);
		}
	}

	function dragend(el) {
		// if (el.rowIndex == 0) {
		// 	el.getElementsByTagName('td')[1].innerHTML = 'Primer';
		// } else {
		// 	el.getElementsByTagName('td')[1].innerHTML = 'Sekunder';
		// }

		$(el).parent('tbody').children('tr').each(function() {
			if (this.rowIndex > 0) {
				this.getElementsByTagName('td')[2].innerHTML = 'Sekunder';
			} else {
				this.getElementsByTagName('td')[2].innerHTML = 'Primer';
			}
		});
	}

	function formatRepo(repo) {
		if (repo.loading) return repo.description;
		var markup = "<div class='m-t-10 m-b-10'>" +
			"<h3 class='m-0'><small>[" + repo.id + "] " + repo.description + "</small></h3>" +
			"</div>";
		return markup;
	}

	function formatRepoSelection(repo) {
		if (repo.description) {
			repo.text = "[" + repo.id + "] " + repo.description;
		}
		return repo.text;
	}

	function redeclare_select2() {
		$("#cariDiagnosa").select2({
			ajax: {
				url: "<?= site_url('eklaim/referensi_eklaim') ?>",
				type: 'post',
				dataType: 'json',
				delay: 500,
				data: function(params) {
					return {
						keyword: params.term, // search term
						method: 'search_diagnosis',
						<?= $this->security->get_csrf_token_name(); ?>: Cookies.get('mdn_cookie')
					};
				},
				beforeSend: function() {
					$('#cariDiagnosa').parent().block({
						message: '<i class="fa fa-spinner fa-pulse"></i>'
					});
				},
				complete: function() {
					$('#cariDiagnosa').parent().unblock();
				},
				processResults: function(data, params) {
					if (data.success == true) {
						// parse the results into the format expected by Select2
						// since we are using custom formatting functions we do not need to
						// alter the remote JSON data, except to indicate that infinite
						// scrolling can be used
						params.page = params.page || 1;
						return {
							// results: data.items,
							results: data.items.data.map(function(item) {
								return {
									id: item[1],
									description: item[0]
								};
							}),
							pagination: {
								more: (params.page * 30) < data.total_count
							}
						};
					} else {
						swal('Gagal', data.message, 'error');
						return {
							results: [{
								id: '',
								description: data.message
							}]
						};
					}

				},
				cache: true
			},
			theme: "bootstrap",
			selectOnClose: true,
			placeholder: "<i class='fa fa-search'></i> Cari Diagnosa",
			allowClear: true,
			language: "id",
			escapeMarkup: function(markup) {
				return markup;
			}, // let our custom formatter work
			minimumInputLength: 3,
			templateResult: formatRepo, // omitted for brevity, see the source of this page
			templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
		}).on("select2:selecting", function(e) {
			if ($('#table-diagnosa tbody tr').length == 0) {
				// $("#inputDG").val(e.params.args.data.id);
				$('#table-diagnosa tbody').append('<tr draggable="true" ondragstart="start()" ondragover="dragover()" ondragend="dragend(this)"><td class=""><button type="button" class="btn btn-danger btn-xs del-diagnosa"><i class="fa-solid fa-close"></i></button></td><td class=""><input type="hidden" name="diagnosa[]" value="' + e.params.args.data.id + '">[' + e.params.args.data.id + '] ' + e.params.args.data.description + '</td><td class="">Primer</td></tr>');
			} else {
				// $("#inputDG").val($("#inputDG").val() + '#' + e.params.args.data.id);
				$('#table-diagnosa tbody').append('<tr draggable="true" ondragstart="start()" ondragover="dragover()" ondragend="dragend(this)"><td class=""><button type="button" class="btn btn-danger btn-xs del-diagnosa"><i class="fa-solid fa-close"></i></button></td><td class=""><input type="hidden" name="diagnosa[]" value="' + e.params.args.data.id + '">[' + e.params.args.data.id + '] ' + e.params.args.data.description + '</td><td class="">Sekunder</td></tr>');
			}
			$('#table-diagnosa').next('small').removeClass('hide');
		});

		$("#cariProcedure").select2({
			ajax: {
				url: "<?= site_url('eklaim/referensi_eklaim') ?>",
				type: 'post',
				dataType: 'json',
				delay: 500,
				data: function(params) {
					return {
						keyword: params.term, // search term
						method: 'search_procedures',
						<?= $this->security->get_csrf_token_name(); ?>: Cookies.get('mdn_cookie')
					};
				},
				beforeSend: function() {
					$('#cariProcedure').parent().block({
						message: '<i class="fa fa-spinner fa-pulse"></i>'
					});
				},
				complete: function() {
					$('#cariProcedure').parent().unblock();
				},
				processResults: function(data, params) {
					if (data.success == true) {
						// parse the results into the format expected by Select2
						// since we are using custom formatting functions we do not need to
						// alter the remote JSON data, except to indicate that infinite
						// scrolling can be used
						params.page = params.page || 1;
						return {
							// results: data.items,
							results: data.items.data.map(function(item) {
								return {
									id: item[1],
									description: item[0]
								};
							}),
							pagination: {
								more: (params.page * 30) < data.total_count
							}
						};
					} else {
						swal('Gagal', data.message, 'error');
						return {
							results: [{
								id: '',
								description: data.message
							}]
						};
					}

				},
				cache: true
			},
			theme: "bootstrap",
			selectOnClose: true,
			placeholder: "<i class='fa fa-search'></i> Cari Procedure",
			allowClear: true,
			language: "id",
			escapeMarkup: function(markup) {
				return markup;
			}, // let our custom formatter work
			minimumInputLength: 3,
			templateResult: formatRepo, // omitted for brevity, see the source of this page
			templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
		}).on("select2:selecting", function(e) {
			$('#table-procedure tbody').append('<tr><td><button type="button" class="btn btn-danger btn-xs del-procedure"><i class="fa-solid fa-close"></i></button></td><td class=""><input type="hidden" name="procedure[]" value="' + e.params.args.data.id + '">[' + e.params.args.data.id + '] ' + e.params.args.data.description + '</td></tr>');
		});

		$("#cariDiagnosaIG").select2({
			ajax: {
				url: "<?= site_url('eklaim/referensi_eklaim') ?>",
				type: 'post',
				dataType: 'json',
				delay: 500,
				data: function(params) {
					return {
						keyword: params.term, // search term
						method: 'search_diagnosis_inagrouper',
						<?= $this->security->get_csrf_token_name(); ?>: Cookies.get('mdn_cookie')
					};
				},
				beforeSend: function() {
					$('#cariDiagnosaIG').parent().block({
						message: '<i class="fa fa-spinner fa-pulse"></i>'
					});
				},
				complete: function() {
					$('#cariDiagnosaIG').parent().unblock();
				},
				processResults: function(data, params) {
					if (data.success == true) {
						params.page = params.page || 1;
						return {
							// results: data.items,
							results: data.items.data.map(function(item) {
								return {
									id: item.code,
									description: item.description
								};
							}),
							pagination: {
								more: (params.page * 30) < data.total_count
							}
						};
					} else {
						swal('Gagal', data.message, 'error');
						return {
							results: [{
								id: '',
								description: data.message
							}]
						};
					}

				},
				cache: true
			},
			theme: "bootstrap",
			selectOnClose: true,
			placeholder: "<i class='fa fa-search'></i> Cari Diagnosa Inagrouper",
			allowClear: true,
			language: "id",
			escapeMarkup: function(markup) {
				return markup;
			}, // let our custom formatter work
			minimumInputLength: 3,
			templateResult: formatRepo, // omitted for brevity, see the source of this page
			templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
		}).on("select2:selecting", function(e) {
			if ($('#table-diagnosa-ig tbody tr').length == 0) {
				$('#table-diagnosa-ig tbody').append('<tr draggable="true" ondragstart="start()" ondragover="dragover()" ondragend="dragend(this)"><td class=""><button type="button" class="btn btn-danger btn-xs del-diagnosa"><i class="fa-solid fa-close"></i></button></td><td class=""><input type="hidden" name="diagnosa_inagrouper[]" value="' + e.params.args.data.id + '">[' + e.params.args.data.id + '] ' + e.params.args.data.description + '</td><td class="">Primer</td></tr>');
			} else {
				$('#table-diagnosa-ig tbody').append('<tr draggable="true" ondragstart="start()" ondragover="dragover()" ondragend="dragend(this)"><td class=""><button type="button" class="btn btn-danger btn-xs del-diagnosa"><i class="fa-solid fa-close"></i></button></td><td class=""><input type="hidden" name="diagnosa_inagrouper[]" value="' + e.params.args.data.id + '">[' + e.params.args.data.id + '] ' + e.params.args.data.description + '</td><td class="">Sekunder</td></tr>');
			}
			$('#table-diagnosa-ig').next('small').removeClass('hide');
		});

		$("#cariProcedureIG").select2({
			ajax: {
				url: "<?= site_url('eklaim/referensi_eklaim') ?>",
				type: 'post',
				dataType: 'json',
				delay: 500,
				data: function(params) {
					return {
						keyword: params.term, // search term
						method: 'search_procedures_inagrouper',
						<?= $this->security->get_csrf_token_name(); ?>: Cookies.get('mdn_cookie')
					};
				},
				beforeSend: function() {
					$('#cariProcedureIG').parent().block({
						message: '<i class="fa fa-spinner fa-pulse"></i>'
					});
				},
				complete: function() {
					$('#cariProcedureIG').parent().unblock();
				},
				processResults: function(data, params) {
					if (data.success == true) {
						params.page = params.page || 1;
						return {
							// results: data.items,
							results: data.items.data.map(function(item) {
								return {
									id: item.code,
									description: item.description
								};
							}),
							pagination: {
								more: (params.page * 30) < data.total_count
							}
						};
					} else {
						swal('Gagal', data.message, 'error');
						return {
							results: [{
								id: '',
								description: data.message
							}]
						};
					}

				},
				cache: true
			},
			theme: "bootstrap",
			selectOnClose: true,
			placeholder: "<i class='fa fa-search'></i> Cari Procedure Inagrouper",
			allowClear: true,
			language: "id",
			escapeMarkup: function(markup) {
				return markup;
			}, // let our custom formatter work
			minimumInputLength: 3,
			templateResult: formatRepo, // omitted for brevity, see the source of this page
			templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
		}).on("select2:selecting", function(e) {
			$('#table-procedure-ig tbody').append('<tr><td><button type="button" class="btn btn-danger btn-xs del-procedure"><i class="fa-solid fa-close"></i></button></td><td class=""><input type="hidden" name="procedure_inagrouper[]" value="' + e.params.args.data.id + '">[' + e.params.args.data.id + '] ' + e.params.args.data.description + '</td><td><input type="text" class="form-control" name="multiplicity[]"></td></tr>');
		});
	}

	$(document).on('click', '#table-diagnosa tbody .del-diagnosa', function() {
		$(this).closest('tr').remove();
		dragend($(this).closest('tr'));
	});

	$(document).on('click', '#table-procedure tbody .del-procedure', function() {
		$(this).closest('tr').remove();
		dragend($(this).closest('tr'));
	});



	$(document).on('click', '#table-diagnosa-ig tbody .del-diagnosa', function() {
		$(this).closest('tr').remove();
		dragend($(this).closest('tr'));
	});

	$(document).on('click', '#table-procedure-ig tbody .del-procedure', function() {
		$(this).closest('tr').remove();
		dragend($(this).closest('tr'));
	});
	/*======================================= end select2: DIAGNOSA & PROCEDURE =========================================*/

	$(document).on('click', '#btnSubmitKlaim', function() {
		$("#formInput #<?= $this->security->get_csrf_token_name(); ?>").val(Cookies.get('mdn_cookie'));
		var formData = new FormData($('form#formInput')[0]);
		$.ajax({
			url: '<?= admin_url('eklaim/new_claim') ?>',
			type: 'post',
			dataType: 'json',
			contentType: false,
			cache: false,
			processData: false,
			data: formData,
			beforeSend: function() {
				$('div.block').block({
					message: '<h4><i class="fa fa-spinner fa-pulse"></i> Sedang Memproses...</h4>',
					css: {
						border: '1px solid #fff'
					}
				});
			},
			complete: function() {
				$('div.block').unblock();
			},
			success: function(data) {
				if (data.success) {
					swal("Berhasil", data.message, "success");
				} else {
					swal("Gagal", data.message, "error");
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				//cetak kesalahan seperti: error 404 - Not Found
				swal('Gagal', textStatus + ' ' + jqXHR.status + ' - ' + jqXHR.statusText, 'error');
			}
		});
	});

	$(document).on('click', '#btnUpdateKlaim', function() {
		$("#formInput #<?= $this->security->get_csrf_token_name(); ?>").val(Cookies.get('mdn_cookie'));
		var formData = new FormData($('form#formInput')[0]);
		$.ajax({
			url: '<?= admin_url('eklaim/set_claim') ?>',
			type: 'post',
			dataType: 'json',
			contentType: false,
			cache: false,
			processData: false,
			data: formData,
			beforeSend: function() {
				$('div.block').block({
					message: '<h4><i class="fa fa-spinner fa-pulse"></i> Sedang Memproses...</h4>',
					css: {
						border: '1px solid #fff'
					}
				});
			},
			complete: function() {
				$('div.block').unblock();
			},
			success: function(data) {
				if (data.success) {
					swal("Berhasil", data.message, "success");
				} else {
					swal("Gagal", data.message, "error");
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				//cetak kesalahan seperti: error 404 - Not Found
				swal('Gagal', textStatus + ' ' + jqXHR.status + ' - ' + jqXHR.statusText, 'error');
			}
		});
	});
	<?php $CI = get_instance(); ?>
	$(document).on('click', '.btn-klaim', function() {
		$("#formInput #<?= $this->security->get_csrf_token_name(); ?>").val(Cookies.get('mdn_cookie'));
		var formData = new FormData($('form#formInput')[0]);
		let url = $(this).data('url').replace('<?= $CI->config->item('medion_url') ?>', '<?= admin_url() ?>');
		let role = $(this).attr('role');
		$.ajax({
			url: url,
			type: 'post',
			dataType: 'json',
			contentType: false,
			cache: false,
			processData: false,
			data: formData,
			beforeSend: function() {
				$('div.block').block({
					message: '<h4><i class="fa fa-spinner fa-pulse"></i> Sedang Memproses...</h4>',
					css: {
						border: '1px solid #fff'
					}
				});
			},
			complete: function() {
				$('div.block').unblock();
			},
			success: function(data) {
				if (data !== undefined && data.success) {
					swal("Berhasil", data.message, "success");
					if (role == 'reedit') {

					} else if (role == 'grouper1') {
						$('#descGrouper').html(data.response_grouper.cbg.description);
						$('#codeGrouper').html(data.response_grouper.cbg.code);
						$('#tarifGrouper').html(data.response_grouper.cbg.base_tariff);

						$('#descSA, #codeSA').html('-');
						$('#tarifSA').html('0');
						if (typeof data.response_grouper.sub_acute !== 'undefined') {
							$('#descSA').html(data.response_grouper.sub_acute.description);
							$('#codeSA').html(data.response_grouper.sub_acute.code);
							$('#tarifSA').html(data.response_grouper.sub_acute.tariff);
						}

						$('#descCR, #codeCR').html('-');
						$('#tarifCR').html('0');
						if (typeof data.response_grouper.chronic !== 'undefined') {
							$('#descCR').html(data.response_grouper.chronic.description);
							$('#codeCR').html(data.response_grouper.chronic.code);
							$('#tarifCR').html(data.response_grouper.chronic.tariff);
						}

						$('#inputSPP, #inputSPPr, #inputSPI, #inputSPD').html('<option value="">-</option>');
						$('#codeSPP, #codeSPPr, #codeSPPI, #codeSPPD').html('-');
						$('#tarifSPP, #tarifSPPr, #tarifSPPI, #tarifSPPD').html('0');
						if (typeof data.special_cmg_option !== 'undefined' && data.special_cmg_option != '') {
							data.special_cmg_option.forEach(function(item, index) {
								if (item.type == "Special Procedure") {
									$('#inputSPP').append('<option value="' + item.code + '">' + item.description + '</option>');
								}

								if (item.type == "Special Prosthesis") {
									$('#inputSPPr').append('<option value="' + item.code + '">' + item.description + '</option>');
								}

								if (item.type == "Special Investigation") {
									$('#inputSPI').append('<option value="' + item.code + '">' + item.description + '</option>');
								}

								if (item.type == "Special Drug") {
									$('#inputSPD').append('<option value="' + item.code + '">' + item.description + '</option>');
								}
							});
						}
						$('#totalGrouper').html(data.response_grouper.cbg.tariff);
					}
				} else if (data !== undefined) {
					swal("Gagal", data.message, "error");
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				//cetak kesalahan seperti: error 404 - Not Found
				swal('Gagal', textStatus + ' ' + jqXHR.status + ' - ' + jqXHR.statusText, 'error');
			}
		});
	});

	$(document).on('click', '#btnDelKlaim', function() {
		swal({
			title: 'Apakah Anda yakin?',
			text: "Data yang dihapus tidak dapat dikembalikan",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, hapus!',
			cancelButtonText: 'Tidak!',
			confirmButtonClass: 'btn btn-danger',
			cancelButtonClass: 'btn btn-default'
		}).then(function() {
			$("#formInput #<?= $this->security->get_csrf_token_name(); ?>").val(Cookies.get('mdn_cookie'));
			var formData = new FormData($('form#formInput')[0]);
			$.ajax({
				url: '<?= admin_url('eklaim/delete_claim') ?>',
				type: 'post',
				dataType: 'json',
				contentType: false,
				cache: false,
				processData: false,
				data: formData,
				beforeSend: function() {
					$('div.block').block({
						message: '<h4><i class="fa fa-spinner fa-pulse"></i> Sedang Memproses...</h4>',
						css: {
							border: '1px solid #fff'
						}
					});
				},
				complete: function() {
					$('div.block').unblock();
				},
				success: function(data) {
					if (data.success) {
						swal("Berhasil", data.message, "success");
					} else {
						swal("Gagal", data.message, "error");
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					//cetak kesalahan seperti: error 404 - Not Found
					swal('Gagal', textStatus + ' ' + jqXHR.status + ' - ' + jqXHR.statusText, 'error');
				}
			});
		}, function(dismiss) {
			return false;
		})
	});

	function grouper2(value) {
		$("#formInput #<?= $this->security->get_csrf_token_name(); ?>").val(Cookies.get('mdn_cookie'));
		var formData = new FormData($('form#formInput')[0]);
		$.ajax({
			url: '<?= admin_url('eklaim/grouper/2') ?>',
			type: 'post',
			dataType: 'json',
			contentType: false,
			cache: false,
			processData: false,
			data: formData,
			beforeSend: function() {
				$('div.block').block({
					message: '<h4><i class="fa fa-spinner fa-pulse"></i> Sedang Memproses...</h4>',
					css: {
						border: '1px solid #fff'
					}
				});
			},
			complete: function() {
				$('div.block').unblock();
			},
			success: function(data) {
				if (data.success) {
					swal("Berhasil", data.message, "success");
					if (typeof data.response_grouper.special_cmg !== 'undefined') {
						data.response_grouper.special_cmg.forEach(function(item, index) {
							if (item.type == "Special Procedure") {
								$('#codeSPP').html(item.code);
								$('#tarifSPP').html(item.tariff);
							}

							if (item.type == "Special Prosthesis") {
								$('#codeSPPr').html(item.code);
								$('#tarifSPPr').html(item.tariff);
							}

							if (item.type == "Special Investigation") {
								$('#codeSPI').html(item.code);
								$('#tarifSPI').html(item.tariff);
							}

							if (item.type == "Special Drug") {
								$('#codeSPD').html(item.code);
								$('#tarifSPD').html(item.tariff);
							}
						});
						$('#totalGrouper').html(data.response_grouper.cbg.tariff);
					}
				} else {
					swal("Gagal", data.message, "error");
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				//cetak kesalahan seperti: error 404 - Not Found
				swal('Gagal', textStatus + ' ' + jqXHR.status + ' - ' + jqXHR.statusText, 'error');
			}
		});
	}

	function get_claim_status(no_sep) {
		$.ajax({
			url: '<?= base_url('eklaim/get_claim_status') ?>' + '/' + no_sep,
			type: 'GET',
			dataType: 'json',
			beforeSend: function() {
				$('div.block').block({
					message: '<h4><i class="fa fa-spinner fa-pulse"></i> Sedang Memproses...</h4>',
					css: {
						border: '1px solid #fff'
					}
				});
			},
			complete: function() {
				$('div.block').unblock();
			},
			success: function(data) {
				if (data.status == 'success') {
					swal("", data.response, "info");
				} else {
					swal("", data.message, "error");
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				//cetak kesalahan seperti: error 404 - Not Found
				swal('Gagal', textStatus + ' ' + jqXHR.status + ' - ' + jqXHR.statusText, 'error');
			}
		});
	}

	function validate_sitb(no_sep) {
		if ($('input[name=nomor_register_sitb]').val() != '') {
			$.ajax({
				url: '<?= base_url('eklaim/sitb_validate') ?>' + '/' + no_sep + '/' + $('input[name=nomor_register_sitb]').val(),
				type: 'GET',
				dataType: 'json',
				beforeSend: function() {
					$('div.block').block({
						message: '<h4><i class="fa fa-spinner fa-pulse"></i> Sedang Memproses...</h4>',
						css: {
							border: '1px solid #fff'
						}
					});
				},
				complete: function() {
					$('div.block').unblock();
				},
				success: function(data) {
					if (data.status == 'success') {
						swal("", data.response, "info");
						$('#respon_sitb').html(data.validation);
					} else {
						swal("", data.message, "error");
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					//cetak kesalahan seperti: error 404 - Not Found
					swal('Gagal', textStatus + ' ' + jqXHR.status + ' - ' + jqXHR.statusText, 'error');
				}
			});
		} else {
			swal('Perhatian', 'Silakan masukan Nomor Register SITB', 'warning');
		}
	}
</script>