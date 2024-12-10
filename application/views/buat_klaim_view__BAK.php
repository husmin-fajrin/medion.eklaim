<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>templates/backend/plugins/sweetalert/sweetalert2.min.css" />
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
<?php
if ($tagihan['jenis_rawat'] != 'Rawat Inap') {
	$data_rawat[0]['tanggal_masuk'] = $data_rawat[0]['tanggal_kunjungan'];
	$data_rawat[0]['lama_rawat_intensif'] = 0;
}

if ($data_klaim_lokal != '') {
	$data_klaim = json_decode($data_klaim_lokal['data_klaim'], true);
}

?>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12 white-box block">
		<?php
		if (!is_array($data_rawat)) {
			echo '<div class="alert alert-danger text-center m-b-0">
				<h2 class="text-white"><i class="fa fa-2x  fa-warning"></i></h2>
				<h3 class="text-white m-b-0">' . $message . '</h3>
				<h4 class="m-t-0">- Periksa Kembali Nomor SEP Pasien -</h4>
			</div>';
		} else {
		?>
			<?php if (isset($message)) { ?>
				<div class="alert alert-info">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<?= $message ?>
				</div>
			<?php } ?>

			<div class="media bg-secondary">
				<div class="media-body">
					<div class="row">
						<div class="col-xs-12">
							<h2 class="m-b-0 fw-normal">[<?= $pasien['no_rekam_medis'] ?>] <?= $pasien['nama_pasien'] ?></h2>
						</div>
						<div class="col-sm-6">
							<h5 class="m-5">No. Registrasi: <?= $data_rawat[0]['nomor_registrasi'] ?></h5>
							<h5 class="m-5">DPJP: <?= $data_rawat[0]['nama_petugas_medis'] ?></h5>
							<h5 class="m-5">Jenis Pasien: <?= $data_rawat[0]['nama_asuransi'] ?></h5>
							<h5 class="m-5">Umur: <?= $data_rawat[0]['umur_tahun_pasien'] . ' Thn ' . $data_rawat[0]['umur_bulan_pasien'] . ' Bln ' . $data_rawat[0]['umur_hari_pasien'] . ' Hri' ?></h5>
						</div>
						<div class="col-sm-6">
							<h5 class="m-5">Dikirim Oleh: <?= $data_rawat[0]['dikirim_oleh'] ?></h5>
							<?php if ($tagihan['jenis_rawat'] == 'Rawat Inap') { ?>
								<h5 class="m-5">Ruangan: <?= '[' . $data_rawat[0]['jenis_kamar'] . '] ' . $data_rawat[0]['nama_kamar'] ?></h5>
								<h5 class="m-5">Tanggal Masuk: <?= nama_bulan(date('d F Y', strtotime($data_rawat[0]['tanggal_masuk']))) ?></h5>
								<h5 class="m-5">Tanggal Keluar: <?= ($data_rawat[0]['tanggal_keluar'] == '0000-00-00 00:00:00' || $data_rawat[0]['tanggal_keluar'] == '') ? '-' : nama_bulan(date('d F Y', strtotime($data_rawat[0]['tanggal_keluar']))) ?></h5>
							<?php } else { ?>
								<h5 class="m-5">Ruangan: <?= $data_rawat[0]['nama_poliklinik'] ?></h5>
								<h5 class="m-5">Tanggal Masuk: <?= nama_bulan(date('d F Y', strtotime($data_rawat[0]['tanggal_kunjungan']))) ?></h5>
								<h5 class="m-5">Tanggal Keluar: <?= ($data_rawat[0]['tanggal_keluar'] == '0000-00-00 00:00:00' || $data_rawat[0]['tanggal_keluar'] == '') ? '-' : nama_bulan(date('d F Y', strtotime($data_rawat[0]['tanggal_keluar']))) ?></h5>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>

			<!-- <form method="post" accept-charset="utf-8" action="<?= ($url_eklaim != '') ? '' : admin_url('eklaim/klaim/download') ?>" class="" id="formInput"> -->
			<form method="post" accept-charset="utf-8" action="" class="" id="formInput">
				<input type="hidden" id="<?= $this->security->get_csrf_token_name(); ?>" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
				<input type="hidden" class="form-control" id="nama_pasien" name="nama_pasien" readonly placeholder="Nama Pasien" value="<?= $pasien['nama_pasien'] ?>">
				<input type="hidden" class="form-control" id="inputRM" name="no_rekam_medis" readonly placeholder="Nomor Rekam Medis Pasien" value="<?= $pasien['no_rekam_medis'] ?>">
				<input type="hidden" name="tanggal_lahir" value="<?= $pasien['tanggal_lahir'] ?>">
				<input type="hidden" class="form-control" id="inputNoka" name="no_kartu" readonly placeholder="Nomor Kepesertaan BPJS" value="<?= $pasien['no_bpjs'] ?>">
				<input type="hidden" name="jenis_kelamin" value="<?= ($pasien['jenis_kelamin'] == 'Wanita') ? 2 : 1 ?>">
				<input type="hidden" name="jenis_rawat_klaim" value="<?= $tagihan['jenis_rawat'] ?>">

				<ul class="nav customtab2 nav-tabs hidden-print" role="tablist">
					<li role="presentation" class="active"><a href="#rawat" role="tab" data-toggle="tab" aria-expanded="false"><i class="sticon fa fa-stethoscope"></i> <span class="hidden-xs">Data Perawatan</span></a></li>
					<li role="presentation" class="hide"><a href="#covid" role="tab" data-toggle="tab" aria-expanded="false"><i class="sticon icon-shield"></i> <span class="hidden-xs">Covid-19</span></a></li>
					<li role="presentation" class=""><a href="#tarif" role="tab" data-toggle="tab" aria-expanded="false"><i class="sticon icon-calculator"></i> <span class="hidden-xs">Tarif Rumah Sakit</span></a></li>
					<li role="presentation" class=""><a href="#grouper" role="tab" data-toggle="tab" aria-expanded="false"><i class="sticon fa-solid fa-notes-medical"></i> <span class="hidden-xs">Hasil Grouper</span></a></li>
					<li role="presentation" class=""><a href="#sitb" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa-solid fa-lungs-virus"></i></i> <span class="hidden-xs">Validasi SITB</span></a></li>
					<li role="presentation" class=""><a href="#lain" role="tab" data-toggle="tab" aria-expanded="false"><i class="sticon fa-solid fa-list"></i> <span class="hidden-xs">Lainnya</span></a></li>
					<li role="presentation" class=""><a href="#" data-toggle="modal" data-target="#modal-rme" class="btn-load-rme"><i class="sticon fa-solid fa-book-medical"></i> <span>Berkas RME</span> </a></li>
					<li role="presentation" class="text-right"><a href="#dataINACBG" role="tab" data-toggle="tab" aria-expanded="false"><i class="sticon fa-solid fa-code"></i> <span>Data INACBG</span> </a></li>
				</ul>
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade active in" id="rawat" aria-labelledby="rawat-tab">
						<div class="row">
							<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 inbox-panel">
								<ul class="nav tabs-vertical">
									<li class="tab nav-item active"><a href="#dataKunjungan" aria-controls="dataKunjungan" role="tab" data-toggle="tab" aria-expanded="true">Data Kunjungan</a></li>
									<li class="tab nav-item"><a href="#dataKlinis" aria-controls="dataKunjungan" role="tab" data-toggle="tab" aria-expanded="true">Data Klinis</a></li>
									<li class="tab nav-item"><a href="#dataPersalinan" aria-controls="dataPersalinan" role="tab" data-toggle="tab" aria-expanded="true">Data Persalinan</a></li>
									<?php if ($url_eklaim != '') { ?>
										<li class="tab nav-item"><a href="#dataDiagnosa" aria-controls="dataKunjungan" role="tab" data-toggle="tab" aria-expanded="true">Diagnosa & Procedure</a></li>
									<?php } ?>
									<li class="tab nav-item"><a href="#kamarRawat" aria-controls="kamarRawat" role="tab" data-toggle="tab" aria-expanded="true">Kamar Rawat</a></li>
									<li class="tab nav-item"><a href="#dataADL" aria-controls="dataADL" role="tab" data-toggle="tab" aria-expanded="true">Activity Of Daily</a></li>
								</ul>
								<hr class="visible-xs">
							</div>

							<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 mail_listing">
								<div class="tab-content m-0">
									<div role="tabpanel" class="tab-pane fade active in" id="dataKunjungan" aria-labelledby="dataKunjungan-tab">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">Jenis Rawat</label>
													<input type="text" class="form-control" readonly placeholder="Jenis Rawat" value="<?= $tagihan['jenis_rawat'] ?>">
													<?php
													$jenis_rawat = 3;
													if ($tagihan['jenis_rawat'] == 'Rawat Inap') {
														$jenis_rawat = 1;
													} else if ($tagihan['jenis_rawat'] == 'Rawat Jalan') {
														$jenis_rawat = 2;
													} else if ($tagihan['jenis_rawat'] == 'Rawat Darurat') {
														$jenis_rawat = 3;
													}
													?>
													<input type="hidden" name="jenis_rawat" value="<?= $jenis_rawat ?>">
												</div>
												<div class="form-group">
													<label for="inputNosep" class="control-label">Nomor SEP</label>
													<input type="text" class="form-control" id="inputNosep" name="no_sep" readonly placeholder="Nomor SEP" value="<?= $tagihan['no_sep'] ?>">
												</div>
												<div class="form-group">
													<label for="idTagihan" class="control-label">Nomor Tagihan</label>
													<input type="text" class="form-control" id="idTagihan" name="id_tagihan_pasien" readonly placeholder="Nomor SEP" value="<?= $tagihan['id_tagihan_pasien'] ?>">
												</div>
												<div class="form-group">
													<label for="nama_dokter" class="control-label">Nama Dokter</label>
													<input type="text" class="form-control" id="nama_dokter" readonly name="nama_dokter" placeholder="Nama Dokter" value="<?= $data_rawat[0]['nama_petugas_medis'] ?>">
												</div>
												<?php if ($tagihan['jenis_rawat'] == 'Rawat Jalan') { ?>
													<div class="form-group">
														<label class="control-label">Poli Reguler/Eksekutif</label>
														<select class="form-control" name="kelas_rawat" placeholder="Poli Reguler/Eksekutif">
															<!-- <option value="">--Silakan Pilih Poli Reguler/Eksekutif--</option> -->
															<option <?= (isset($data_klaim['data']['kelas_rawat']) && $data_klaim['data']['kelas_rawat'] == 3) ? 'selected' : '' ?> value="3">Reguler</option>
															<option <?= (isset($data_klaim['data']['kelas_rawat']) && $data_klaim['data']['kelas_rawat'] == 1) ? 'selected' : '' ?> value="1">Eksekutif</option>
														</select>
													</div>
												<?php } ?>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">Tanggal Masuk</label>
													<input type="text" class="form-control" readonly placeholder="Tanggal Masuk" value="<?= nama_bulan(date('d F Y H:i:s', strtotime($data_rawat[0]['tanggal_masuk']))) ?>">
													<input type="hidden" name="tanggal_masuk" value="<?= $data_rawat[0]['tanggal_masuk'] ?>">
												</div>
												<div class="form-group">
													<label class="control-label">Tanggal Pulang</label>
													<input type="text" class="form-control" readonly placeholder="Tanggal Keluar" value="<?= nama_bulan(date('d F Y H:i:s', strtotime($data_rawat[count($data_rawat) - 1]['tanggal_keluar']))) ?>">
													<input type="hidden" name="tanggal_keluar" value="<?= $data_rawat[count($data_rawat) - 1]['tanggal_keluar'] ?>">
												</div>
												<div class="form-group">
													<label class="control-label">Cara Masuk</label>
													<input type="text" class="form-control" readonly placeholder="Cara Masuk" value="<?= $data_rawat[0]['dikirim_oleh'] ?>">
													<?php
													$cara_masuk = 'other';
													if ($data_rawat[0]['dikirim_oleh'] == 'Puskesmas' || $data_rawat[0]['dikirim_oleh'] == 'Dokter') {
														$cara_masuk = 'gp';
													} else if ($data_rawat[0]['dikirim_oleh'] == 'Rumah Sakit Lain') {
														$cara_masuk = 'hosp-trans';
													} else if ($data_rawat[0]['dikirim_oleh'] == 'Dokter Spesialis') {
														$cara_masuk = 'mp';
													} else if ($data_rawat[0]['dikirim_oleh'] == 'Rawat Jalan') {
														$cara_masuk = 'outp';
													} else if ($data_rawat[0]['dikirim_oleh'] == 'Rawat Inap') {
														$cara_masuk = 'inp';
													} else if ($data_rawat[0]['dikirim_oleh'] == 'Rawat Darurat') {
														$cara_masuk = 'emd';
													} else if ($data_rawat[0]['dikirim_oleh'] == 'Lahir di RS') {
														$cara_masuk = 'born';
													} else if ($data_rawat[0]['dikirim_oleh'] == 'Panti Jompo') {
														$cara_masuk = 'nursing';
													} else if ($data_rawat[0]['dikirim_oleh'] == 'Rumah Sakit Jiwa') {
														$cara_masuk = 'psych';
													} else if ($data_rawat[0]['dikirim_oleh'] == 'Rujukan Fasilitas Rehab') {
														$cara_masuk = 'rehab';
													}
													?>
													<input type="hidden" name="cara_masuk" value="<?= $cara_masuk ?>">
												</div>
												<div class="form-group">
													<label class="control-label">Cara Pulang</label>
													<?php
													if ($data_rawat[count($data_rawat) - 1]['keadaan_keluar'] == 'Mati Saat Tiba' || $data_rawat[count($data_rawat) - 1]['keadaan_keluar'] == 'Mati < 48 Jam' || $data_rawat[count($data_rawat) - 1]['keadaan_keluar'] == 'Mati > 48 Jam') {
														$discharge_status = 4;
														$data_rawat[count($data_rawat) - 1]['cara_keluar'] = 'Meninggal';
													} else if ($data_rawat[count($data_rawat) - 1]['cara_keluar'] == 'Dibolehkan Pulang' || $data_rawat[count($data_rawat) - 1]['cara_keluar'] == 'Pulang') {
														$discharge_status = 1;
														$data_rawat[count($data_rawat) - 1]['cara_keluar'] = 'Atas Persetujuan Dokter';
													} else if ($data_rawat[count($data_rawat) - 1]['cara_keluar'] == 'Pulang Paksa') {
														$discharge_status = 3;
														$data_rawat[count($data_rawat) - 1]['cara_keluar'] = 'Atas Permintaan Sendiri';
													} else if ($data_rawat[count($data_rawat) - 1]['cara_keluar'] == 'Rujuk Balik' || $data_rawat[count($data_rawat) - 1]['cara_keluar'] == 'Dirujuk Ke Rumah Sakit Lain') {
														$discharge_status = 2;
														$data_rawat[count($data_rawat) - 1]['cara_keluar'] = 'Dirujuk';
													} else {
														$discharge_status = 5;
														$data_rawat[count($data_rawat) - 1]['cara_keluar'] = 'Lain-Lain';
													}
													?>
													<input type="text" class="form-control" readonly placeholder="Cara Pulang Pasien" value="<?= $data_rawat[count($data_rawat) - 1]['cara_keluar'] ?>">
													<input type="hidden" name="discharge_status" value="<?= $discharge_status ?>">
												</div>
											</div>
										</div>
									</div>

									<div role="tabpanel" class="tab-pane fade in" id="dataKlinis" aria-labelledby="dataKlinis-tab">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">Sistole/Diastole</label>
													<div class="input-group">
														<input type="text" class="form-control" name="sistole" placeholder="Sistole" value="<?= $data_rawat[count($data_rawat) - 1]['tensi_sitolik'] ?>">
														<div class="input-group-addon">/</div>
														<input type="text" class="form-control" name="diastole" placeholder="Diastole" value="<?= $data_rawat[count($data_rawat) - 1]['tensi_diastolik'] ?>">
														<div class="input-group-addon">mmhg</div>
													</div>

												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label for="birth_weight" class="control-label">Berat Bayi Lahir</label>
													<div class="input-group">
														<input type="text" class="form-control" id="birth_weight" name="birth_weight" placeholder="Berat Bayi Lahir" value="<?= (isset($data_bayi['berat_badan'])) ? $data_bayi['berat_badan'] : 0 ?>">
														<div class="input-group-addon">gram</div>
													</div>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label for="kantong_darah" class="control-label">Jumlah Kantong Darah</label>
													<div class="input-group">
														<input type="text" class="form-control" id="kantong_darah" name="kantong_darah" placeholder="Kantong Darah" value="<?= (isset($data_klaim['data']['kantong_darah'])) ? $data_klaim['data']['kantong_darah'] : 0 ?>">
														<div class="input-group-addon">Kantong</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="alteplase_ind" class="control-label">Pemberian Alteplase</label>
													<select id="alteplase_ind" class="form-control" name="alteplase_ind">
														<option value="">-- Silakan Pilih --</option>
														<option <?= (isset($data_klaim['data']['alteplase_ind']) && $data_klaim['data']['alteplase_ind'] == 0) ? 'selected' : '' ?> value="0">Tidak</option>
														<option <?= (isset($data_klaim['data']['alteplase_ind']) && $data_klaim['data']['alteplase_ind'] == 1) ? 'selected' : '' ?> value="1">Ya</option>
													</select>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label for="dializer_single_use" class="control-label">Dializer</label>
													<select id="dializer_single_use" class="form-control" name="dializer_single_use">
														<option value="">-- Silakan Pilih --</option>
														<option <?= (isset($data_klaim['data']['dializer_single_use']) && $data_klaim['data']['dializer_single_use'] == 1) ? 'selected' : '' ?> value="1">Single Use</option>
														<option <?= (isset($data_klaim['data']['dializer_single_use']) && $data_klaim['data']['dializer_single_use'] == 0) ? 'selected' : '' ?> value="0">Multiple Use</option>
													</select>
												</div>
											</div>
										</div>

										<?php if (isset($data_bayi) && $data_bayi != '') { ?>
											<h4 class="box-title m-b-0 m-t-20">APGAR SCORE</h4>
											<div class="table-responsive b-t">
												<table class="table">
													<thead>
														<tr>
															<th class="text-center" style="width: 200px;">Tanda</th>
															<th class="text-center">1 Menit</th>
															<th class="text-center">5 Menit</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td class="bg-secondary"><b>Warna Kulit<br>(Appearance)</b></td>
															<td>
																<?= $data_bayi['n_a_1'] ?>
																<input type="hidden" class="form-control" name="apgar[m1][appearance]" value="<?= preg_match('/\[(\d+)\]/', $data_bayi['n_a_1'], $match) ? $match[1] : '' ?>">
															</td>
															<td>
																<?= $data_bayi['n_a_5'] ?>
																<input type="hidden" class="form-control" name="apgar[m5][appearance]" value="<?= preg_match('/\[(\d+)\]/', $data_bayi['n_a_5'], $match) ? $match[1] : '' ?>">
															</td>
														</tr>
														<tr>
															<td class="bg-secondary"><b>Frekuensi Jantung<br>(Pulse)</b></td>
															<td>
																<?= $data_bayi['n_p_1'] ?>
																<input type="hidden" class="form-control" name="apgar[m1][pulse]" value="<?= preg_match('/\[(\d+)\]/', $data_bayi['n_p_1'], $match) ? $match[1] : '' ?>">
															</td>
															<td>
																<?= $data_bayi['n_p_5'] ?>
																<input type="hidden" class="form-control" name="apgar[m5][pulse]" value="<?= preg_match('/\[(\d+)\]/', $data_bayi['n_p_5'], $match) ? $match[1] : '' ?>">
															</td>
														</tr>
														<tr>
															<td class="bg-secondary"><b>Iritabilitas Reflex<br>(Grimace)</b></td>
															<td>
																<?= $data_bayi['n_g_1'] ?>
																<input type="hidden" class="form-control" name="apgar[m1][grimace]" value="<?= preg_match('/\[(\d+)\]/', $data_bayi['n_g_1'], $match) ? $match[1] : '' ?>">
															</td>
															<td>
																<?= $data_bayi['n_g_5'] ?>
																<input type="hidden" class="form-control" name="apgar[m5][grimace]" value="<?= preg_match('/\[(\d+)\]/', $data_bayi['n_g_5'], $match) ? $match[1] : '' ?>">
															</td>
														</tr>
														<tr>
															<td class="bg-secondary"><b>Tonus Otot<br>(Activity)</b></td>
															<td>
																<?= $data_bayi['n_ac_1'] ?>
																<input type="hidden" class="form-control" name="apgar[m1][activity]" value="<?= preg_match('/\[(\d+)\]/', $data_bayi['n_ac_1'], $match) ? $match[1] : '' ?>">
															</td>
															<td>
																<?= $data_bayi['n_ac_5'] ?>
																<input type="hidden" class="form-control" name="apgar[m5][activity]" value="<?= preg_match('/\[(\d+)\]/', $data_bayi['n_ac_5'], $match) ? $match[1] : '' ?>">
															</td>
														</tr>
														<tr>
															<td class="bg-secondary"><b>Usaha Bernafas<br>(Respiration)</b></td>
															<td>
																<?= $data_bayi['n_r_1'] ?>
																<input type="hidden" class="form-control" name="apgar[m1][respiration]" value="<?= preg_match('/\[(\d+)\]/', $data_bayi['n_r_1'], $match) ? $match[1] : '' ?>">
															</td>
															<td>
																<?= $data_bayi['n_r_5'] ?>
																<input type="hidden" class="form-control" name="apgar[m5][respiration]" value="<?= preg_match('/\[(\d+)\]/', $data_bayi['n_r_5'], $match) ? $match[1] : '' ?>">
															</td>
														</tr>
													</tbody>
													<tbody>
														<tr>
															<td class="bg-secondary"><b>TOTAL APGAR SKOR</b></td>
															<td>
																<h2 class="m-0 font-bold"><?= $data_bayi['apgar_skor_1'] ?></h2>
															</td>
															<td>
																<h2 class="m-0 font-bold"><?= $data_bayi['apgar_skor_5'] ?></h2>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										<?php } ?>
									</div>

									<div role="tabpanel" class="tab-pane fade in" id="dataPersalinan" aria-labelledby="dataPersalinan-tab">
										<?php
										if (is_array($data_persalinan)) {
											if ($pasien['riwayat_kebidanan'] != '') {
												$riwayat_kebidanan = json_decode($pasien['riwayat_kebidanan'], true);
											} else {
												$riwayat_kebidanan['gravida'] = $riwayat_kebidanan['para'] = $riwayat_kebidanan['abortus'] = '';
											}
										?>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label" for="GPA">Gravida</label>
														<input type="text" class="form-control" placeholder="Gravida" name="gravida" value="<?= $riwayat_kebidanan['gravida'] ?>">
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label" for="GPA">Para</label>
														<input type="text" class="form-control" placeholder="Para" name="para" value="<?= $riwayat_kebidanan['para'] ?>">
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label" for="GPA">Abortus</label>
														<input type="text" class="form-control" placeholder="Para" name="abortus" value="<?= $riwayat_kebidanan['abortus'] ?>">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="usia_kehamilan" class="control-label">Usia Kehamilan</label>
														<div class="input-group">
															<input type="text" class="form-control" placeholder="Usia Kehamilan" name="usia_kehamilan" value="<?= ($data_persalinan[0]['usia_kehamilan_ibu'] != '') ? $data_persalinan[0]['usia_kehamilan_ibu'] : '' ?>">
															<div class="input-group-addon">Minggu</div>
														</div>

													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="onset_kontraksi" class="control-label">Onset Kontraksi</label>
														<select class="form-control" name="onset_kontraksi">
															<option value="">-- Silakan Pilih --</option>
															<option value="spontan" <?= ($data_persalinan[0]['onset_kontraksi'] == 'spontan') ? 'selected' : '' ?>>Spontan</option>
															<option value="induksi" <?= ($data_persalinan[0]['onset_kontraksi'] == 'induksi') ? 'selected' : '' ?>>Induksi</option>
															<option value="non_spontan_non_induksi" <?= ($data_persalinan[0]['onset_kontraksi'] == 'non_spontan_non_induksi') ? 'selected' : '' ?>>Non Spontan Non Induksi</option>
														</select>
													</div>
												</div>
											</div>
										<?php
											foreach ($data_persalinan as $k => $v) {
												$dm = ($v['proses_lahir'] == 'Normal') ? 'vaginal' : 'sc';
												$kondisi = ($v['keadaan_lahir'] == 'Hidup') ? 'livebirth' : 'stillbirth';
												echo '<hr><h4 class="box-title text-center">-- PERSALINAN ANAK KE ' . ($k + 1) . ' --</h4>
												<div class="row">
													<div class="col-md-4">
														<div class="form-group">
															<label for="delivery_method" class="control-label">Proses Lahir</label>
															<input type="text" class="form-control" readonly name="delivery[delivery_method][]" placeholder="Delivery Method" value="' . $dm . '">
														</div>

														<div class="form-group">
															<label for="delivery_dttm" class="control-label">Tanggal & Jam Lahir</label>
															<input type="text" class="form-control" readonly placeholder="Delivery Datetime" value="' . date('d-m-Y', strtotime($v['tanggal_lahir'])) . ' ' . $v['jam_lahir'] . '">
															<input type="hidden" class="form-control" name="delivery[delivery_dttm][]" placeholder="Delivery Datetime" value="' . $v['tanggal_lahir'] . ' ' . $v['jam_lahir'] . '">
														</div>

														<div class="form-group">
															<label for="letak_janin" class="control-label">Letak Janin</label>
															<input type="text" class="form-control" readonly name="delivery[letak_janin][]" placeholder="Letak Janin" value="' . $v['letak_janin'] . '">
														</div>

														<div class="form-group">
															<label for="letak_janin" class="control-label">Kondisi Bayi</label>
															<input type="text" class="form-control" readonly name="delivery[kondisi][]" placeholder="Kondisi Bayi" value="' . $kondisi . '">
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label for="use_manual" class="control-label">Bantuan Manual</label>
															<input type="text" class="form-control" readonly name="delivery[use_manual][]" placeholder="Bantuan Manual" value="' . $v['bantuan_manual'] . '">
														</div>

														<div class="form-group">
															<label for="use_forcep" class="control-label">Pakai Forcep</label>
															<input type="text" class="form-control" readonly name="delivery[use_forcep][]" placeholder="Pakai Forcep" value="' . $v['pakai_forcep'] . '">
														</div>

														<div class="form-group">
															<label for="use_vacuum" class="control-label">Pakai Vacuum</label>
															<input type="text" class="form-control" readonly name="delivery[use_vacuum][]" placeholder="Pakai Vacuum" value="' . $v['pakai_vacuum'] . '">
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label for="use_manual" class="control-label">Pengambilan Spesimen SHK</label>
															<select class="form-control" name="delivery[shk_spesimen_ambil][]" placeholder="Pengambilan Spesimen SHK">
																<option value="ya">Ya</option>
																<option value="tidak">Tidak</option>
															</select>
														</div>
														<div class="form-group">
															<label for="use_vacuum" class="control-label">Alasan Tidak Mengambil SHK</label>
															<select class="form-control" name="delivery[shk_alasan][]" placeholder="Alasan Tidak Mengambil SHK">
																<option value="">-- Silakan Pilih --</option>
																<option value="tidak-dapat">Tidak Dapat</option>
																<option value="akses-sulit">Akses Sulit</option>
															</select>
														</div>
														<div class="form-group">
															<label for="use_forcep" class="control-label">Lokasi Spesimen SHK</label>
															<select class="form-control" name="delivery[shk_lokasi][]" placeholder="Lokasi Spesimen SHK">
																<option value="tumit">Tumit</option>
																<option value="vena">Vena</option>
															</select>
														</div>
														<div class="form-group">
															<label for="use_vacuum" class="control-label">Waktu Pengambilan Spesimen SHK</label>
															<input class="form-control inputWaktuSHKMask" id="inputTanggalLahir" type="text" name="delivery[shk_spesimen_dttm][]">
														</div>
													</div>
												</div>
												';
											}
										} else {
											echo 'data persalinan tidak ditemukan';
										}
										?>
									</div>

									<div role="tabpanel" class="tab-pane fade in" id="dataDiagnosa" aria-labelledby="dataDiagnosa-tab">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">Diagnosa</label>
													<!-- <input type="text" class="form-control" name="diagnosa" placeholder="Diagnosa" value="<?= $data_rawat[count($data_rawat) - 1]['diagnosa'] ?>"> -->
													<select id="cariDiagnosa" tabindex="-1" aria-hidden="true" style="width:100%"></select>

													<table id="table-diagnosa" class="table b-b">
														<tbody>
															<?php
															if (isset($data_klaim['data']['diagnosa']) && $data_klaim['data']['diagnosa'] != '' && $data_klaim['data']['diagnosa'] != '#') {
																$diagnosa_klaim = explode('#', $data_klaim['data']['diagnosa']);
																foreach ($diagnosa_klaim as $k => $v) {
																	echo '<tr draggable="true" ondragstart="start()" ondragover="dragover()" ondragend="dragend(this)">
																		<td width="100px"><button type="button" class="btn btn-danger btn-xs del-diagnosa"><i class="fa-solid fa-close"></i></button></td>
																		<td><input type="hidden" name="diagnosa[]" value="' . $v . '">' . $v . '</td>
																		<td class="">' . (($k == 0) ? 'Primer' : 'Sekunder') . '</td>
																	</tr>';
																}
															}
															?>
														</tbody>
													</table>
													<small class="text-info hide">Klik, tahan & geser untuk menyesuaikan urutan diagnosa</small>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">Prosedur</label>
													<select id="cariProcedure" tabindex="-1" aria-hidden="true" style="width:100%"></select>
													<table id="table-procedure" class="table b-b">
														<tbody>
															<?php
															if (isset($data_klaim['data']['procedure']) && ($data_klaim['data']['procedure'] != '' && $data_klaim['data']['procedure'] != '#')) {
																$procedure_klaim = explode('#', $data_klaim['data']['procedure']);
																foreach ($procedure_klaim as $k => $v) {
																	echo '<tr>
																		<td width="100px"><button type="button" class="btn btn-danger btn-xs del-procedure"><i class="fa-solid fa-close"></i></button></td>
																		<td><input type="hidden" name="procedure[]" value="' . $v . '">' . $v . '</td>
																	</tr>';
																}
															}
															?>
														</tbody>
													</table>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">Diagnosa Inagrouper</label>
													<select id="cariDiagnosaIG" tabindex="-1" aria-hidden="true" style="width:100%"></select>
													<table id="table-diagnosa-ig" class="table b-b">
														<tbody>
															<?php
															if (isset($data_klaim['data']['diagnosa_inagrouper']) && $data_klaim['data']['diagnosa_inagrouper'] != '' && $data_klaim['data']['diagnosa_inagrouper'] != '#') {
																$diagnosa_ig_klaim = explode('#', $data_klaim['data']['diagnosa_inagrouper']);
																foreach ($diagnosa_ig_klaim as $k => $v) {
																	echo '<tr draggable="true" ondragstart="start()" ondragover="dragover()" ondragend="dragend(this)">
																		<td width="100px"><button type="button" class="btn btn-danger btn-xs del-diagnosa"><i class="fa-solid fa-close"></i></button></td>
																		<td class=""><input type="hidden" name="diagnosa_inagrouper[]" value="' . $v . '">' . $v . '</td>
																		<td class="">' . (($k == 0) ? 'Primer' : 'Sekunder') . '</td>
																	</tr>';
																}
															}
															?>
														</tbody>
													</table>
													<small class="text-info hide">Klik, tahan & geser untuk menyesuaikan urutan diagnosa</small>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">Prosedur Inagrouper</label>
													<select id="cariProcedureIG" tabindex="-1" aria-hidden="true" style="width:100%"></select>

													<table id="table-procedure-ig" class="table b-b">
														<tbody>
															<?php
															if (isset($data_klaim['data']['procedure_inagrouper']) && $data_klaim['data']['procedure_inagrouper'] != '' && $data_klaim['data']['procedure_inagrouper'] != '#') {
																$procedure_ig_klaim = explode('#', $data_klaim['data']['procedure_inagrouper']);
																foreach ($procedure_ig_klaim as $k => $v) {
																	echo '<tr>
																		<td width="100px"><button type="button" class="btn btn-danger btn-xs del-procedure"><i class="fa-solid fa-close"></i></button></td>
																		<td><input type="hidden" name="procedure[]" value="' . $v . '">' . $v . '</td>
																	</tr>';
																}
															}
															?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>

									<div role="tabpanel" class="tab-pane fade in data-ranap" id="kamarRawat" aria-labelledby="kamarRawat-tab">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">Kelas Rawat</label>
													<?php if ($tagihan['jenis_rawat'] == 'Rawat Inap') { ?>
														<select class="form-control" name="kelas_rawat">
															<option value="">-- Silakan Pilih --</option>
															<option value="1">Kelas 1</option>
															<option value="2">Kelas 2</option>
															<option value="3">Kelas 3</option>
														</select>
														<!-- <input type="text" class="form-control" readonly name="kelas_rawat" placeholder="Kelas Rawat BPJS" value="<?= $tagihan['hak_kelas_bpjs'] ?>"> -->
													<?php } ?>
												</div>

												<div class="form-group">
													<label for="naikKelas" class="control-label">Naik Kelas</label>
													<select class="form-control" id="naikKelas" name="upgrade_class_class">
														<option value="">--Silakan Pilih Jika Pasien Naik Kelas Kamar--</option>
														<?php if ($tagihan['pembayaran_pasien_bpjs'] == 'Selisih') { ?>
															<option <?= ($data_rawat[0]['id_kelas_siranap'] == '004') ? 'selected' : '' ?> value="kelas_2">Naik Kelas 2</option>
															<option <?= ($data_rawat[0]['id_kelas_siranap'] == '003') ? 'selected' : '' ?> value="kelas_1">Naik Kelas 1</option>
															<option <?= ($data_rawat[0]['id_kelas_siranap'] == '002') ? 'selected' : '' ?> value="vip">Naik Kelas VIP</option>
															<option <?= ($data_rawat[0]['id_kelas_siranap'] == '001') ? 'selected' : '' ?> value="vvip">Naik Kelas VVIP</option>
														<?php } ?>
													</select>
													<input type="hidden" id="indNaikKelas" name="upgrade_class_ind" value="<?= ($tagihan['pembayaran_pasien_bpjs'] == 'Selisih') ? 1 : 0 ?>">
												</div>
												<div class="form-group">
													<label for="naikKelasLOS" class="control-label">Lama Rawat Di Kelas Kamar Yang Naik</label>
													<div class="input-group">
														<input type="text" class="form-control naik-kelas" id="naikKelasLOS" <?= ($tagihan['pembayaran_pasien_bpjs'] == 'Selisih') ? '' : 'readonly' ?> name="upgrade_class_los" placeholder="Lama Rawat Di Kelas Kamar Yang Naik" value="<?= ($tagihan['pembayaran_pasien_bpjs'] == 'Selisih') ? get_interval_time($data_rawat[0]['tanggal_masuk'], $data_rawat[0]['tanggal_keluar']) : 0 ?>">
														<div class="input-group-addon">Hari</div>
													</div>
												</div>
												<div class="form-group">
													<label for="payor" class="control-label">Upgrade Class Payor</label>
													<select class="form-control" id="payor" name="upgrade_class_payor">
														<option value="">--Silakan Pilih Jika Pasien Naik Kelas Kamar--</option>
														<?php if ($tagihan['pembayaran_pasien_bpjs'] == 'Selisih') { ?>
															<option value="peserta">Peserta</option>
															<option value="pemberi_kerja">Pemberi Kerja</option>
															<option value="asuransi_tambahan">Asuransi Tambahan</option>
														<?php } ?>
													</select>
												</div>
												<div class="form-group">
													<label class="control-label">Add Payment PCT</label>
													<input type="text" class="form-control naik-kelas" name="add_payment_pct" <?= ($tagihan['pembayaran_pasien_bpjs'] == 'Selisih') ? '' : 'readonly' ?> placeholder="Koefisien Tambahan Biaya Khusus Naik Kelas" value="">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">Lama Rawat Intensif</label>
													<div class="input-group">
														<input type="text" class="form-control" readonly name="icu_los" placeholder="Jenis Kelamin Pasien" value="<?= $data_rawat[0]['lama_rawat_intensif'] ?>">
														<input type="hidden" name="icu_indikator" value="<?= ($data_rawat[0]['lama_rawat_intensif'] > 0) ? 1 : 0 ?>">
														<div class="input-group-addon">Hari</div>
													</div>
												</div>
												<div class="form-group">
													<label for="inputNosep" class="control-label">Pemakaian Ventilator</label>
													<div class="input-group">
														<input type="text" class="form-control" name="ventilator_hour" placeholder="Jumlah Jam Pemakaian Ventilator Di ICU" value="<?= (isset($data_klaim['data']['ventilator_hour'])) ? $data_klaim['data']['ventilator_hour'] : '' ?>">
														<div class="input-group-addon">Jam</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label">Waktu Pemakaian Ventilator</label>
													<input class="form-control" id="inputWaktuMask" placeholder="Waktu Pemakaian Ventilator" type="text" style="cursor:pointer">

													<input id="inputWaktuMulai" name="ventilator_start_dttm" type="hidden" value="">
													<input id="inputWaktuSelesai" name="ventilator_stop_dttm" type="hidden" value="">
												</div>
											</div>
										</div>



									</div>

									<div role="tabpanel" class="tab-pane fade in" id="dataADL" aria-labelledby="dataADL-tab">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">ADL Sub Acute <small class="text-info">(Nilainya 12 s/d 60)</small></label>
													<input type="text" class="form-control" name="adl_sub_acute" placeholder="Activities of Daily Living Score Untuk Pasien Sub Acute" value="">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="inputNosep" class="control-label">ADL Chronic <small class="text-info">(Nilainya 12 s/d 60)</small></label>
													<input type="text" class="form-control" name="adl_chronic" placeholder="Activities of Daily Living Score Untuk Pasien Chronic" value="">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="tarif" aria-labelledby="tarif-tab">
						<?php
						$total_tarif = $tarif['tarif_non_bedah'] + $tarif['tarif_bedah'] + $tarif['tarif_konsultasi'] + $tarif['tarif_ahli'] +
							$tarif['tarif_keperawatan'] + $tarif['tarif_penunjang'] + $tarif['tarif_radiologi'] + $tarif['tarif_laboratorium'] +
							$tarif['tarif_darah'] + $tarif['tarif_rehabilitasi'] + $tarif['tarif_kamar'] + $tarif['tarif_intensif'] +
							$tarif['tarif_obat'] + $tarif['tarif_alkes'] + $tarif['tarif_bmhp'] + $tarif['tarif_alat'] +
							$tarif['tarif_obat_kronis'] + $tarif['tarif_obat_kemoterapi'];

						if ($tagihan['total'] != $total_tarif) {
							echo '<div class="alert alert-danger"><h3 class="text-center m-0 text-white"><i class="fa-2x fa-solid fa-triangle-exclamation fa-beat"></i><br>Total Tagihan tidak sama dengan Total Tarif Rumah Sakit<br><b>Total Tagihan: Rp. ' . number_format($tagihan['total'], 0, ',', '.') . '</b></h3></div><hr>';
						}
						?>
						<div class="row rincian-tarif">
							<div class="col-md-4">
								<div class="form-group">
									<label for="prosedur_non_bedah" class="control-label">Prosedur Non Bedah</label>
									<div class="input-group">
										<div class="input-group-addon">Rp.</div>
										<input type="text" class="form-control inputmask" id="prosedur_non_bedah" name="prosedur_non_bedah" placeholder="Prosedur Non Bedah" value="<?= $tarif['tarif_non_bedah'] ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="konsultasi" class="control-label">Konsultasi</label>
									<div class="input-group">
										<div class="input-group-addon">Rp.</div>
										<input type="text" class="form-control inputmask" id="konsultasi" name="konsultasi" placeholder="Konsultasi" value="<?= $tarif['tarif_konsultasi'] ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="pelayanan_darah" class="control-label">Pelayanan Darah</label>
									<div class="input-group">
										<div class="input-group-addon">Rp.</div>
										<input type="text" class="form-control inputmask" id="pelayanan_darah" name="pelayanan_darah" placeholder="Pelayanan Darah" value="<?= $tarif['tarif_darah'] ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="kamar" class="control-label">Kamar</label>
									<div class="input-group">
										<div class="input-group-addon">Rp.</div>
										<input type="text" class="form-control inputmask" id="kamar" name="kamar" placeholder="Kamar" value="<?= $tarif['tarif_kamar'] ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="rawat_intensif" class="control-label">Rawat Intensif</label>
									<div class="input-group">
										<div class="input-group-addon">Rp.</div>
										<input type="text" class="form-control inputmask" id="rawat_intensif" name="rawat_intensif" placeholder="Rawat Intensif" value="<?= $tarif['tarif_intensif'] ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="bmhp" class="control-label">BMHP</label>
									<div class="input-group">
										<div class="input-group-addon">Rp.</div>
										<input type="text" class="form-control inputmask" id="bmhp" name="bmhp" placeholder="BMHP" value="<?= $tarif['tarif_bmhp'] ?>">
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="prosedur_bedah" class="control-label">Prosedur Bedah</label>
									<div class="input-group">
										<div class="input-group-addon">Rp.</div>
										<input type="text" class="form-control inputmask" id="prosedur_bedah" name="prosedur_bedah" placeholder="Prosedur Bedah" value="<?= $tarif['tarif_bedah'] ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="keperawatan" class="control-label">Keperawatan</label>
									<div class="input-group">
										<div class="input-group-addon">Rp.</div>
										<input type="text" class="form-control inputmask" id="keperawatan" name="keperawatan" placeholder="Keperawatan" value="<?= $tarif['tarif_keperawatan'] ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="tenaga_ahli" class="control-label">Tenaga Ahli</label>
									<div class="input-group">
										<div class="input-group-addon">Rp.</div>
										<input type="text" class="form-control inputmask" id="tenaga_ahli" name="tenaga_ahli" placeholder="Tenaga Ahli" value="<?= $tarif['tarif_ahli'] ?>">
									</div>
								</div>

								<div class="form-group">
									<label for="rehabilitasi" class="control-label">Rehabilitasi</label>
									<div class="input-group">
										<div class="input-group-addon">Rp.</div>
										<input type="text" class="form-control inputmask" id="rehabilitasi" name="rehabilitasi" placeholder="Rehabilitasi" value="<?= $tarif['tarif_rehabilitasi'] ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="alkes" class="control-label">ALKES</label>
									<div class="input-group">
										<div class="input-group-addon">Rp.</div>
										<input type="text" class="form-control inputmask" id="alkes" name="alkes" placeholder="ALKES" value="<?= $tarif['tarif_alkes'] ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="sewa_alat" class="control-label">Sewa Alat</label>
									<div class="input-group">
										<div class="input-group-addon">Rp.</div>
										<input type="text" class="form-control inputmask" id="sewa_alat" name="sewa_alat" placeholder="Sewa ALat" value="<?= $tarif['tarif_alat'] ?>">
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="penunjang" class="control-label">Penunjang</label>
									<div class="input-group">
										<div class="input-group-addon">Rp.</div>
										<input type="text" class="form-control inputmask" id="penunjang" name="penunjang" placeholder="Penunjang" value="<?= $tarif['tarif_penunjang'] ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="laboratorium" class="control-label">Laboratorium</label>
									<div class="input-group">
										<div class="input-group-addon">Rp.</div>
										<input type="text" class="form-control inputmask" id="laboratorium" name="laboratorium" placeholder="Laboratorium" value="<?= $tarif['tarif_laboratorium'] ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="radiologi" class="control-label">Radiologi</label>
									<div class="input-group">
										<div class="input-group-addon">Rp.</div>
										<input type="text" class="form-control inputmask" id="radiologi" name="radiologi" placeholder="Radiologi" value="<?= $tarif['tarif_radiologi'] ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="obat" class="control-label">Obat</label>
									<div class="input-group">
										<div class="input-group-addon">Rp.</div>
										<input type="text" class="form-control inputmask" id="obat" name="obat" placeholder="Obat" value="<?= $tarif['tarif_obat'] ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="obat_kemoterapi" class="control-label">Obat Kemoterapi</label>
									<div class="input-group">
										<div class="input-group-addon">Rp.</div>
										<input type="text" class="form-control inputmask" id="obat_kemoterapi" name="obat_kemoterapi" placeholder="Obat Kemoterapi" value="<?= $tarif['tarif_obat_kemoterapi'] ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="obat_kronis" class="control-label">Obat Kronis</label>
									<div class="input-group">
										<div class="input-group-addon">Rp.</div>
										<input type="text" class="form-control inputmask" id="obat_kronis" name="obat_kronis" placeholder="Obat Kronis" value="<?= $tarif['tarif_obat_kronis'] ?> ">
									</div>
								</div>
							</div>

							<div class="col-md-9 m-t-30"></div>
							<div class="col-md-3 m-t-30 b-t text-right p-t-20">
								<h5 class="text-right fw-normal m-0"><i>TOTAL</i></h5>
								<h2 class="text-right fw-normal m-t-0">Rp. <?= number_format($total_tarif, 0, ',', '.') ?></h2>
							</div>
						</div>
					</div>

					<div role="tabpanel" class="tab-pane fade" id="grouper" aria-labelledby="grouper-tab">
						<table id="table_grouper" class="table table-hover table-form">
							<tbody>
								<tr>
									<th class="text-right" width="250px">Group</th>
									<td id="descGrouper"><?= (isset($data_inacbg['grouper']['response']['cbg'])) ? $data_inacbg['grouper']['response']['cbg']['description'] : '-' ?></td>
									<td id="codeGrouper"><?= (isset($data_inacbg['grouper']['response']['cbg'])) ? $data_inacbg['grouper']['response']['cbg']['code'] : '-' ?></td>
									<td class="text-right">Rp.</td>
									<td class="text-right" id="tarifGrouper"><?= (isset($data_inacbg['grouper']['response']['cbg'])) ? number_format($data_inacbg['grouper']['response']['cbg']['base_tariff'], 0, ',', '.') : 0 ?></td>
								</tr>
								<tr>
									<th class="text-right">Sub Acute</th>
									<td id="descSA"><?= (isset($data_inacbg['grouper']['response']['sub_acute'])) ? $data_inacbg['grouper']['response']['sub_acute']['description'] : '-' ?></td>
									<td id="codeSA"><?= (isset($data_inacbg['grouper']['response']['sub_acute'])) ? $data_inacbg['grouper']['response']['sub_acute']['code'] : '-' ?></td>
									<td class="text-right">Rp.</td>
									<td class="text-right" id="tarifSA"><?= (isset($data_inacbg['grouper']['response']['sub_acute'])) ? number_format($data_inacbg['grouper']['response']['sub_acute']['tariff'], 0, ',', '.') : 0 ?></td>
								</tr>
								<tr>
									<th class="text-right">Chronic</th>
									<td id="descCR"><?= (isset($data_inacbg['grouper']['response']['chronic'])) ? $data_inacbg['grouper']['response']['chronic']['description'] : '-' ?></td>
									<td id="codeCR"><?= (isset($data_inacbg['grouper']['response']['chronic'])) ? $data_inacbg['grouper']['response']['chronic']['code'] : '-' ?></td>
									<td class="text-right">Rp.</td>
									<td class="text-right" id="tarifCR"><?= (isset($data_inacbg['grouper']['response']['chronic'])) ? number_format($data_inacbg['grouper']['response']['chronic']['tariff'], 0, ',', '.') : 0 ?></td>
								</tr>
								<?php
								$SPPOpt = $SPPROpt = $SPPIOpt = $SPPDOpt = '';
								$SPPCode = $SPPRCode = $SPPICode = $SPPDCode = '-';
								$SPPTarif = $SPPRTarif = $SPPITarif = $SPPDTarif = '0';
								if (isset($data_inacbg['grouper']['response']['special_cmg'])) {
									foreach ($data_inacbg['grouper']['response']['special_cmg'] as $key => $value) {
										if ($value['type'] == 'Special Procedure') {
											$SPPOpt .= '<option selected value="' . $value['code'] . '">' . $value['description'] . '</option>';
											$SPPCode = $value['code'];
											$SPPTarif = number_format($value['tariff'], 0, ',', '.');
										}

										if ($value['type'] == 'Special Prosthesis') {
											$SPPROpt .= '<option selected value="' . $value['code'] . '">' . $value['description'] . '</option>';
											$SPPRCode = $value['code'];
											$SPPRTarif = number_format($value['tariff'], 0, ',', '.');
										}

										if ($value['type'] == 'Special Investigation') {
											$SPPIOpt .= '<option selected value="' . $value['code'] . '">' . $value['description'] . '</option>';
											$SPPICode = $value['code'];
											$SPPITarif = number_format($value['tariff'], 0, ',', '.');
										}

										if ($value['type'] == 'Special Drug') {
											$SPPDOpt .= '<option selected value="' . $value['code'] . '">' . $value['description'] . '</option>';
											$SPPDCode = $value['code'];
											$SPPDTarif = number_format($value['tariff'], 0, ',', '.');
										}
									}
								}
								?>
								<tr>
									<th class="text-right">Special Procedure</th>
									<td>
										<select onchange="grouper2()" class="form-control" id="inputSPP" name="special[procedure]">
											<option value="">-</option>
											<?= $SPPOpt ?>
										</select>
									</td>
									<td id="codeSPP"><?= $SPPCode ?></td>
									<td class="text-right">Rp.</td>
									<td class="text-right" id="tarifSPP"><?= $SPPTarif ?></td>
								</tr>
								<tr>
									<th class="text-right">Special Prosthesis</th>
									<td>
										<select onchange="grouper2()" class="form-control" id="inputSPPr" name="special[prosthesis]">
											<option value="">-</option>
											<?= $SPPROpt ?>
										</select>
									</td>
									<td id="codeSPPr"><?= $SPPRCode ?></td>
									<td class="text-right">Rp.</td>
									<td class="text-right" id="tarifSPPr"><?= $SPPRTarif ?></td>
								</tr>
								<tr>
									<th class="text-right">Special Investigation</th>
									<td>
										<select onchange="grouper2()" class="form-control" id="inputSPI" name="special[investigation]">
											<option value="">-</option>
											<?= $SPPIOpt ?>
										</select>
									</td>
									<td id="codeSPI"><?= $SPPICode ?></td>
									<td class="text-right">Rp.</td>
									<td class="text-right" id="tarifSPI"><?= $SPPITarif ?></td>
								</tr>
								<tr>
									<th class="text-right">Special Drug</th>
									<td>
										<select onchange="grouper2()" class="form-control" id="inputSPD" name="special[drug]">
											<option value="">-</option>
											<?= $SPPDOpt ?>
										</select>
									</td>
									<td id="codeSPD"><?= $SPPDCode ?></td>
									<td class="text-right">Rp.</td>
									<td class="text-right" id="tarifSPD"><?= $SPPDTarif ?></td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="3"></td>
									<td class="text-right fw-normal">Total Rp.</td>
									<td class="text-right fw-normal" id="totalGrouper"><?= (isset($data_inacbg['grouper']['response']['cbg'])) ? number_format($data_inacbg['grouper']['response']['cbg']['tariff'], 0, ',', '.') : 0 ?></td>
								</tr>
							</tfoot>
						</table>
					</div>

					<div role="tabpanel" class="tab-pane fade" id="covid" aria-labelledby="covid-tab">
						<div class="row">
							<div class="col-md-12 text-center">
								<div class="clearfix">
									<ul class="icheck-list" style="width:100%">
										<li>
											<input type="radio" class="check pelayanan" id="TML" name="pelayanan" data-radio="iradio_square-red" value="false" checked>
											<label for="TML">Pasien Jaminan Covid-19</label>
										</li>
										<li>
											<input type="radio" class="check pelayanan" id="TL" name="pelayanan" data-radio="iradio_square-red" value="true">
											<label for="TL">Bukan Pasien Jaminan Covid-19</label>
										</li>
									</ul>
								</div>
								<hr>
							</div>
						</div>
						<div class="row jaminan-covid">
							<div class="col-md-6">
								<div class="form-group">
									<label for="covid19_status_cd" class="control-label">Status Pasien</label>
									<select class="form-control" id="covid19_status_cd" name="covid19_status_cd">
										<option value="1">Suspek</option>
										<option value="2">Probabel</option>
										<option value="3">Terkonfirmasi Positif COVID-19</option>
									</select>

								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="inputNosep" class="control-label">Pemulasaran Jenazah</label>
									<input type="text" class="form-control covid-meninggal" name="pemulasaraan_jenazah" placeholder="Pemulasaran Jenazah" value="">
								</div>
								<div class="form-group">
									<label class="control-label">Kantong Jenazah</label>
									<input type="text" class="form-control covid-meninggal" name="kantong_jenazah" placeholder="Kantong Jenazah" value="">
								</div>
								<div class="form-group">
									<label class="control-label">Peti Jenazah</label>
									<input type="text" class="form-control covid-meninggal" name="peti_jenazah" placeholder="Peti Jenazah" value="">
								</div>
								<div class="form-group">
									<label class="control-label">Plastik Erat</label>
									<input type="text" class="form-control covid-meninggal" name="plastik_erat" placeholder="Plastik Erat" value="">
								</div>
								<div class="form-group">
									<label class="control-label">Desinfektan Jenazah</label>
									<input type="text" class="form-control covid-meninggal" name="desinfektan_jenazah" placeholder="Desinfektan Jenazah" value="">
								</div>
								<div class="form-group">
									<label class="control-label">Mobil Jenazah</label>
									<input type="text" class="form-control covid-meninggal" name="mobil_jenazah" placeholder="Mobil Jenazah" value="">
								</div>
								<div class="form-group">
									<label class="control-label">Desinfektan Mobil Jenazah</label>
									<input type="text" class="form-control covid-meninggal" name="desinfektan_mobil_jenazah" placeholder="Desinfektan Mobil Jenazah" value="">
								</div>
							</div>
						</div>
					</div>

					<div role="tabpanel" class="tab-pane fade in" id="sitb" aria-labelledby="sitb-tab">
						<div class="form-group">
							<label class="control-label">Nomor Register SITB</label>
							<input type="text" class="form-control m-b-10" name="nomor_register_sitb" placeholder="Nomor Register SITB" value="">
							<button onclick="validate_sitb('<?= $tagihan['no_sep'] ?>')" role="hapus" type="button" class="btn btn-info"><i class="fa-solid fa-search"></i> Validasi Nomor Register SITB</button>
						</div>
						<section id="respon_sitb"></section>
					</div>

					<div role="tabpanel" class="tab-pane fade" id="lain" aria-labelledby="lain-tab">
						<div class="row">
							<div class="col-md-6">
								<?php
								$payor = array(
									0 => array('payplan_id' => 3, 'payplan_name' => 'JKN', 'payplan_code' => 'JKN'),
									1 => array('payplan_id' => 71, 'payplan_name' => 'JAMINAN COVID-19', 'payplan_code' => 'COVID-19'),
									2 => array('payplan_id' => 72, 'payplan_name' => 'JAMINAN KIPI', 'payplan_code' => 'KIPI'),
									3 => array('payplan_id' => 73, 'payplan_name' => 'JAMINAN BAYI BARU LAHIR', 'payplan_code' => 'BBL'),
									4 => array('payplan_id' => 74, 'payplan_name' => 'JAMINAN PERPANJANGAN MASA RAWAT', 'payplan_code' => 'PMR'),
									5 => array('payplan_id' => 75, 'payplan_name' => 'JAMINAN CO-INSIDENSE', 'payplan_code' => 'CO-INS'),
									6 => array('payplan_id' => 76, 'payplan_name' => 'JAMPERSAL', 'payplan_code' => 'JPS'),
									7 => array('payplan_id' => 77, 'payplan_name' => 'JAMINAN PEMULIHAN KESEHATAN PRIORITAS', 'payplan_code' => 'JPKP')
								);
								?>
								<div class="form-group">
									<label class="control-label">Payment Plan ID</label>
									<select class="form-control" name="payor_id" id="payor_id">
										<?php
										foreach ($payor as $v) {
											echo '<option ' . ((array_key_exists('payor_id', $data_klaim['data']) && $data_klaim['data']['payor_id'] == $v['payplan_id']) ? 'selected' : '') . ' value="' . $v['payplan_id'] . '" data-payplan-code="' . $v['payplan_code'] . '">' . $v['payplan_name'] . '</option>';
										}
										?>
									</select>
									<input type="hidden" class="form-control" id="payor_cd" name="payor_cd" placeholder="Payment Plan Code" value="<?= $payor[0]['payplan_code'] ?>">
								</div>
								<div class="form-group">
									<label for="kode_tarif" class="control-label">Kode Tarif</label>
									<select class="form-control" id="kode_tarif" name="kode_tarif">
										<option <?= $kode_tarif_eklaim == 'AP' ? 'selected' : '' ?> value="AP">TARIF RS KELAS A PEMERINTAH</option>
										<option <?= $kode_tarif_eklaim == 'AS' ? 'selected' : '' ?> value="AS">TARIF RS KELAS A SWASTA</option>
										<option <?= $kode_tarif_eklaim == 'BP' ? 'selected' : '' ?> value="BP">TARIF RS KELAS B PEMERINTAH</option>
										<option <?= $kode_tarif_eklaim == 'BS' ? 'selected' : '' ?> value="BS">TARIF RS KELAS B SWASTA</option>
										<option <?= $kode_tarif_eklaim == 'CP' ? 'selected' : '' ?> value="CP">TARIF RS KELAS C PEMERINTAH</option>
										<option <?= $kode_tarif_eklaim == 'CS' ? 'selected' : '' ?> value="CS">TARIF RS KELAS C SWASTA</option>
										<option <?= $kode_tarif_eklaim == 'DP' ? 'selected' : '' ?> value="DP">TARIF RS KELAS D PEMERINTAH</option>
										<option <?= $kode_tarif_eklaim == 'DS' ? 'selected' : '' ?> value="DS">TARIF RS KELAS D SWASTA</option>
										<option <?= $kode_tarif_eklaim == 'RSCM' ? 'selected' : '' ?> value="RSCM">TARIF RSUPN CIPTO MANGUNKUSUMO</option>
										<option <?= $kode_tarif_eklaim == 'RSJP' ? 'selected' : '' ?> value="RSJP">TARIF RSJPD HARAPAN KITA</option>
										<option <?= $kode_tarif_eklaim == 'RSD' ? 'selected' : '' ?> value="RSD">TARIF RS KANKER DHARMAIS</option>
										<option <?= $kode_tarif_eklaim == 'RSAB' ? 'selected' : '' ?> value="RSAB">TARIF RSAB HARAPAN KITA</option>
									</select>
								</div>
								<div class="form-group">
									<label for="tarif_poli_eks" class="control-label">Tarif Poli Eksklusif</label>
									<div class="input-group">
										<div class="input-group-addon">Rp.</div>
										<input type="text" class="form-control inputmask" id="tarif_poli_eks" name="tarif_poli_eks" placeholder="Tarif Poli Eksklusif" value="">
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="bayi_lahir_status_cd" class="control-label">Jaminan Bayi Baru Lahir</label>
									<select class="form-control" id="bayi_lahir_status_cd" name="bayi_lahir_status_cd">
										<option value="">-- Silakan Pilih --</option>
										<option value="1">Tanpa Kelainan</option>
										<option value="2">Dengan Kelainan</option>
									</select>
								</div>
								<div class="form-group">
									<label class="control-label">COB</label>
									<select class="form-control" name="cob_cd" placeholder="Coordination Of Benefit">
										<option value="" class="option selected">-- Silakan Pilih --</option>
										<option value="1" class="option">MANDIRI INHEALTH</option>
										<option value="5" class="option">ASURANSI SINAR MAS</option>
										<option value="6" class="option">ASURANSI TUGU MANDIRI</option>
										<option value="7" class="option">ASURANSI MITRA MAPARYA</option>
										<option value="8" class="option">ASURANSI AXA MANDIRI FINANSIAL SERVICE</option>
										<option value="9" class="option">ASURANSI AXA FINANSIAL INDONESIA</option>
										<option value="10" class="option">LIPPO GENERAL INSURANCE</option>
										<option value="11" class="option">ARTHAGRAHA GENERAL INSURANSE</option>
										<option value="12" class="option">TUGU PRATAMA INDONESIA</option>
										<option value="13" class="option">ASURANSI BINA DANA ARTA</option>
										<option value="14" class="option">ASURANSI JIWA SINAR MAS MSIG</option>
										<option value="15" class="option">AVRIST ASSURANCE</option>
										<option value="16" class="option">ASURANSI JIWA SRAYA</option>
										<option value="17" class="option">ASURANSI JIWA CENTRAL ASIA RAYA</option>
										<option value="18" class="option">ASURANSI TAKAFUL KELUARGA</option>
										<option value="19" class="option">ASURANSI JIWA GENERALI INDONESIA</option>
										<option value="20" class="option">ASURANSI ASTRA BUANA</option>
										<option value="21" class="option">ASURANSI UMUM MEGA</option>
										<option value="22" class="option">ASURANSI MULTI ARTHA GUNA</option>
										<option value="23" class="option">ASURANSI AIA INDONESIA</option>
										<option value="24" class="option">ASURANSI JIWA EQUITY LIFE INDONESIA</option>
										<option value="25" class="option">ASURANSI JIWA RECAPITAL</option>
										<option value="26" class="option">GREAT EASTERN LIFE INDONESIA</option>
										<option value="27" class="option">ASURANSI ADISARANA WANAARTHA</option>
										<option value="28" class="option">ASURANSI JIWA BRINGIN JIWA SEJAHTERA</option>
										<option value="29" class="option">BOSOWA ASURANSI</option>
										<option value="30" class="option">MNC LIFE ASSURANCE</option>
										<option value="31" class="option">ASURANSI AVIVA INDONESIA</option>
										<option value="32" class="option">ASURANSI CENTRAL ASIA RAYA</option>
										<option value="33" class="option">ASURANSI ALLIANZ LIFE INDONESIA</option>
										<option value="34" class="option">ASURANSI BINTANG</option>
										<option value="35" class="option">TOKIO MARINE LIFE INSURANCE INDONESIA</option>
										<option value="36" class="option">MALACCA TRUST WUWUNGAN</option>
										<option value="37" class="option">ASURANSI JASA INDONESIA</option>
										<option value="38" class="option">ASURANSI JIWA MANULIFE INDONESIA</option>
										<option value="39" class="option">ASURANSI BANGUN ASKRIDA</option>
										<option value="40" class="option">ASURANSI JIWA SEQUIS FINANCIAL</option>
										<option value="41" class="option">ASURANSI AXA INDONESIA</option>
										<option value="42" class="option">BNI LIFE</option>
										<option value="43" class="option">ACE LIFE INSURANCE</option>
										<option value="44" class="option">CITRA INTERNATIONAL UNDERWRITERS</option>
										<option value="45" class="option">ASURANSI RELIANCE INDONESIA</option>
										<option value="46" class="option">HANWHA LIFE INSURANCE INDONESIA</option>
										<option value="47" class="option">ASURANSI DAYIN MITRA</option>
										<option value="48" class="option">ASURANSI ADIRA DINAMIKA</option>
										<option value="49" class="option">PAN PASIFIC INSURANCE</option>
										<option value="50" class="option">ASURANSI SAMSUNG TUGU</option>
										<option value="51" class="option">ASURANSI UMUM BUMI PUTERA MUDA 1967</option>
										<option value="52" class="option">ASURANSI JIWA KRESNA</option>
										<option value="53" class="option">ASURANSI RAMAYANA</option>
										<option value="54" class="option">VICTORIA INSURANCE</option>
										<option value="55" class="option">ASURANSI JIWA BERSAMA BUMIPUTERA 1912</option>
										<option value="56" class="option">FWD LIFE INDONESIA</option>
										<option value="57" class="option">ASURANSI TAKAFUL KELUARGA</option>
										<option value="58" class="option">ASURANSI TUGU KRESNA PRATAMA</option>
										<option value="59" class="option">SOMPO INSURANCE</option>
									</select>
								</div>
								<div class="form-group">
									<label for="inputNIKCoder" class="control-label">NIK Coder</label>
									<input type="text" class="form-control" readonly id="inputNIKCoder" name="coder_nik" placeholder="NIK Coder" value="<?= ($nik_coder != '') ? $nik_coder : $this->session->userdata('nik_pengguna') ?>">
								</div>
							</div>
						</div>
					</div>

					<div role="tabpanel" class="tab-pane fade" id="dataINACBG" aria-labelledby="dataINACBG-tab">
						<?= var_debugging($data_inacbg, false); ?>
					</div>
				</div>
				<div class="form-group text-center m-t-40">
					<hr>
					<?php
					if ($url_eklaim != '') {
					?>
						<button id="btnSubmitKlaim" role="input" type="button" class="btn btn-primary <?= ($data_inacbg != '') ? 'hidden' : '' ?>"><i class="fa fa-paper-plane"></i> Buat & Set Data Klaim</button>
						<button id="btnUpdateKlaim" role="input" type="button" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Update Data Klaim</button>

						<button role="grouper1" type="button" class="btn btn-primary btn-klaim" data-url="<?= admin_url('eklaim/klaim/grouper/1') ?>"><i class="fa fa-save"></i> Grouper</button>
						<button role="finalisasi" type="button" class="btn btn-primary btn-klaim" data-url="<?= admin_url('eklaim/klaim/finalisasi') ?>"><i class="fa fa-save"></i> Finalisasi</button>

						<button role="cetak" type="button" class="btn btn-primary btn-klaim" data-url="<?= admin_url('eklaim/klaim/claim_print') ?>"><i class="fa fa-print"></i> Cetak Klaim</button>
						<button onclick="get_claim_status('<?= $tagihan['no_sep'] ?>')" role="hapus" type="button" class="btn btn-info"><i class="fa-solid fa-check-to-slot"></i> Status Klaim BPJS</button>
						<button role="reedit" type="button" class="btn btn-warning btn-klaim text-dark" data-url="<?=admin_url('eklaim/klaim/reedit_claim')?>"><i class="fa-solid fa-pen-to-square"></i> Edit Ulang</button>
						<button id="btnDelKlaim" role="hapus" type="button" class="btn btn-danger" data-url="<?= admin_url('eklaim/klaim/hapus_klaim') ?>"><i class="fa fa-close"></i> Hapus Klaim</button>

						<!-- <button id="btnReset" type="reset" class="btn btn-danger"><i class="fa fa-close"></i> Batal</button> -->
					<?php } else { ?>
						<!-- <button role="input" type="submit" class="btn btn-primary"><i class="fa fa-download"></i> Download Berkas</button> -->
						<!-- <button id="btnSubmit" role="<?= ($data_klaim_lokal != '') ? 'update' : 'input' ?>" type="button" class="btn btn-info"><i class="fa fa-save"></i> Simpan</button> -->
					<?php } ?>
				</div>
			</form>
		<?php } ?>
	</div>
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

<?php include_once(APPPATH . '/views/include/admin_js.php'); ?>
<script type="text/javascript" src="<?php echo base_url() ?>templates/backend/plugins/sweetalert/sweetalert2.min.js"></script>
<script src="<?= base_url('templates/backend/plugins/') ?>select2/js/select2.min.js"></script>
<script src="<?= base_url('templates/backend/plugins/') ?>select2/js/i18n/id.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/moment.min2.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/daterangepicker/daterangepicker.js"></script>

<script>
	function onSuccessCallback() {
		$('#btnSubmit').attr("disabled", false).attr('role', 'update');
	}

	<?php if ($tagihan['jenis_rawat'] != 'Rawat Inap') { ?>
		$('div.data-ranap').block({
			message: '',
			css: {
				border: '1px solid #fff'
			},
			overlayCSS: {
				cursor: "auto"
			}
		});
	<?php } ?>

	// $('#payor_id').select2();
	$('#payor_id').change(function() {
		$('#payor_cd').val($('#payor_id option:selected').data('payplan-code'));
	});
	$('#payor_id').change();

	$('.inputmask').inputmask({
		alias: "decimal",
		groupSeparator: ".",
		radixPoint: ",",
		rightAlign: false,
		autoGroup: true,
		digitsOptional: true,
		digits: 2,
		allowPlus: false,
		allowMinus: false,
		removeMaskOnSubmit: true
	});

	$('#inputWaktuMask').daterangepicker({
		"drops": "up",
		"timePicker": true,
		"timePicker24Hour": true,
		startDate: <?= (isset($data_klaim['ventilator_start_dttm']) && $data_klaim['ventilator_start_dttm'] != '') ? "'" . nama_bulan(date('d F Y h:i:s', strtotime($data_klaim['ventilator_start_dttm']))) . "'" : 'moment()' ?>,
		endDate: <?= (isset($data_klaim['ventilator_stop_dttm']) && $data_klaim['ventilator_stop_dttm'] != '') ? "'" . nama_bulan(date('d F Y h:i:s', strtotime($data_klaim['ventilator_start_dttm']))) . "'" : 'moment()' ?>,
		maxDate: moment(),
		locale: {
			format: 'DD MMMM YYYY (HH:mm:ss)'
		},
	}, function(start, end, label) {
		$('#inputWaktuMulai').val(start.format('YYYY-MM-DD HH:mm:ss'));
		$('#inputWaktuSelesai').val(end.format('YYYY-MM-DD HH:mm:ss'));
	});
	$('#inputWaktuMulai').val(<?= (isset($data_klaim['ventilator_start_dttm']) && $data_klaim['ventilator_start_dttm'] != '') ? "'" . nama_bulan(date('d F Y h:i:s', strtotime($data_klaim['ventilator_start_dttm']))) . "'" : 'moment().format("YYYY-MM-DD HH:mm:ss")' ?>);
	$('#inputWaktuSelesai').val(<?= (isset($data_klaim['ventilator_stop_dttm']) && $data_klaim['ventilator_stop_dttm'] != '') ? "'" . nama_bulan(date('d F Y h:i:s', strtotime($data_klaim['ventilator_start_dttm']))) . "'" : 'moment().format("YYYY-MM-DD HH:mm:ss")' ?>);

	$('.inputWaktuSHKMask').daterangepicker({
		"drops": "up",
		"timePicker": true,
		"timePicker24Hour": true,
		singleDatePicker: true,
		showDropdowns: true,
		startDate: moment(),
		maxDate: moment(),
		locale: {
			format: 'DD-MM-YYYY HH:mm:ss'
		}
	});

	//---------------------------------------- start: load rme ----------------------------------------//
	var rme = false;
	<?php
	if ($tagihan['jenis_rawat'] == 'Rawat Inap') {
		$url = admin_url('rm/verifikasi_klaim/rawat_inap/detail/' . $data_rawat[0]['id_rawat_inap'] . '/' . $data_rawat[0]['verifikasi_klaim']);
	} else if ($tagihan['jenis_rawat'] == 'Rawat Jalan') {
		$url = admin_url('rm/verifikasi_klaim/rawat_jalan/detail/' . $data_rawat[0]['id_rawat_jalan'] . '/' . $data_rawat[0]['verifikasi_klaim']);
	} else {
		$url = admin_url('rm/verifikasi_klaim/rawat_darurat/detail/' . $data_rawat[0]['id_rawat_jalan'] . '/' . $data_rawat[0]['verifikasi_klaim']);
	}
	?>
	// $('a[data-toggle="tab"][href=#rme]').on('shown.bs.tab', function(e) {
	// 	if (rme == false) {
	// 		$('#rme').html("<div class='text-center' style='margin: 15px'><i class='fa fa-spinner fa-pulse'></i> Memuat data...</div>");
	// 		$('#rme').load(encodeURI('<?= $url ?>'), function(responseTxt, statusTxt, xhr) {
	// 			if (statusTxt == "success")
	// 				rme = true;
	// 			$('#rme #btnSubmitVerifikasi, #rme #btnBatalVerifikasi, #rme #printKlaim, #rme .checkbox, #rme .input-group .input-group-addon:first-child').remove();
	// 			if (statusTxt == "error")
	// 				$('#rme').html("<div	class='alert alert-danger' style='margin: 15px'><i class='fa fa-exclamation-circle fa-2x'></i> Terjadi kesalahan dalam mengambil data (" + xhr.status + ": " + xhr.statusText + ")</div>");
	// 		});
	// 	}
	// });

	$('.btn-load-rme').click(function() {
		if (rme == false) {
			$('#modal-rme .modal-body').html("<div class='text-center' style='margin: 15px'><i class='fa fa-spinner fa-pulse'></i> Memuat data...</div>");
			$('#modal-rme .modal-body').load(encodeURI('<?= $url ?>'), function(responseTxt, statusTxt, xhr) {
				if (statusTxt == "success")
					rme = true;
				$('#modal-rme #btnSubmitVerifikasi, #modal-rme #btnBatalVerifikasi, #modal-rme #printKlaim, #modal-rme .checkbox, #modal-rme .input-group .input-group-addon:first-child').remove();
				if (statusTxt == "error")
					$('#modal-rme').html("<div	class='alert alert-danger' style='margin: 15px'><i class='fa fa-exclamation-circle fa-2x'></i> Terjadi kesalahan dalam mengambil data (" + xhr.status + ": " + xhr.statusText + ")</div>");
			});
		}
	});
	//---------------------------------------- end: load rme ----------------------------------------//

	// $('#tagihan').load("<?= admin_url('tagihan_pasien/detail/' . $tagihan['id_tagihan_pasien']) ?>", function(responseTxt, statusTxt, xhr) {
	// 	if (statusTxt == "error")
	// 		$('#tagihan').html("<div class='alert alert-danger' style='margin: 15px'><i class='fa fa-exclamation-circle fa-2x'></i> Terjadi kesalahan dalam mengambil data (" + xhr.status + ": " + xhr.statusText + ")</div>");
	// });

	$("#naikKelas").change(function() {
		if (this.value == '') {
			$("#indNaikKelas").val(0);
			$(".naik-kelas").attr("readonly", true).val('');
		} else {
			$("#indNaikKelas").val(1);
			$(".naik-kelas").attr("readonly", false).val('');
		}
	})

	$('.jaminan-covid').block({
		message: ''
	});
	$('.pelayanan').on('ifChecked', function(event) {
		if (this.value == 'true') {
			$(".jaminan-covid").unblock();
		} else {
			$('.jaminan-covid').block({
				message: ''
			});
		}
	});

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

	$("#cariDiagnosa").select2({
		ajax: {
			url: "<?= site_url('eklaim/referensi_eklaim/pencarian') ?>",
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
					generate_notification('error', data.message);
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
			url: "<?= site_url('eklaim/referensi_eklaim/pencarian') ?>",
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
					generate_notification('error', data.message);
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

	$('#table-diagnosa tbody').on('click', '.del-diagnosa', function() {
		$(this).closest('tr').remove();
		dragend($(this).closest('tr'));
	});

	$('#table-procedure tbody').on('click', '.del-procedure', function() {
		$(this).closest('tr').remove();
		dragend($(this).closest('tr'));
	});

	$("#cariDiagnosaIG").select2({
		ajax: {
			url: "<?= site_url('eklaim/referensi_eklaim/pencarian') ?>",
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
					generate_notification('error', data.message);
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
			url: "<?= site_url('eklaim/referensi_eklaim/pencarian') ?>",
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
					generate_notification('error', data.message);
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

	$('#table-diagnosa-ig tbody').on('click', '.del-diagnosa', function() {
		$(this).closest('tr').remove();
		dragend($(this).closest('tr'));
	});

	$('#table-procedure-ig tbody').on('click', '.del-procedure', function() {
		$(this).closest('tr').remove();
		dragend($(this).closest('tr'));
	});
	/*======================================= end select2: DIAGNOSA & PROCEDURE =========================================*/

	$("#btnSubmitKlaim").click(function() {
		$("#formInput #<?= $this->security->get_csrf_token_name(); ?>").val(Cookies.get('mdn_cookie'));
		var formData = new FormData($('form#formInput')[0]);
		$.ajax({
			url: '<?= admin_url('eklaim/klaim/new_claim') ?>',
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
				generate_notification('error', textStatus + ' ' + jqXHR.status + ' - ' + jqXHR.statusText);
			}
		});
	});

	$("#btnUpdateKlaim").click(function() {
		$("#formInput #<?= $this->security->get_csrf_token_name(); ?>").val(Cookies.get('mdn_cookie'));
		var formData = new FormData($('form#formInput')[0]);
		$.ajax({
			url: '<?= admin_url('eklaim/klaim/set_claim') ?>',
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
				generate_notification('error', textStatus + ' ' + jqXHR.status + ' - ' + jqXHR.statusText);
			}
		});
	});

	$(".btn-klaim").click(function() {
		$("#formInput #<?= $this->security->get_csrf_token_name(); ?>").val(Cookies.get('mdn_cookie'));
		var formData = new FormData($('form#formInput')[0]);
		let url = $(this).data('url');
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
				generate_notification('error', textStatus + ' ' + jqXHR.status + ' - ' + jqXHR.statusText);
			}
		});
	});

	$("#btnDelKlaim").click(function() {
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
				url: '<?= admin_url('eklaim/klaim/delete_claim') ?>',
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
					generate_notification('error', textStatus + ' ' + jqXHR.status + ' - ' + jqXHR.statusText);
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
			url: '<?= admin_url('eklaim/klaim/grouper/2') ?>',
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
				generate_notification('error', textStatus + ' ' + jqXHR.status + ' - ' + jqXHR.statusText);
			}
		});
	}

	function get_claim_status(no_sep) {
		$.ajax({
			url: '<?= base_url('eklaim/klaim/get_claim_status') ?>' + '/' + no_sep,
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
				generate_notification('error', textStatus + ' ' + jqXHR.status + ' - ' + jqXHR.statusText);
			}
		});
	}

	function validate_sitb(no_sep) {
		if ($('input[name=nomor_register_sitb]').val() != '') {
			$.ajax({
				url: '<?= base_url('eklaim/klaim/sitb_validate') ?>' + '/' + no_sep + '/' + $('input[name=nomor_register_sitb]').val(),
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
					generate_notification('error', textStatus + ' ' + jqXHR.status + ' - ' + jqXHR.statusText);
				}
			});
		} else {
			generate_notification('warning', 'Silakan masukan Nomor Register SITB');
		}
	}

	// $('#btnDownloadKlaim').click(function() {
	// 	$("#formInput #<?= $this->security->get_csrf_token_name(); ?>").val(Cookies.get('mdn_cookie'));
	// 	var formData = new FormData($('form#formInput')[0]);
	// 	return $.ajax({
	// 		url: '<?= admin_url('eklaim/klaim/download') ?>',
	// 		// timeout: requestTimeout,
	// 		global: false,
	// 		cache: false,
	// 		type: "POST",
	// 		data: formData,
	// 		dataType: "json",
	// 		success: function(data) { // note 'data' here
	// 			var blob = new Blob([data], {
	// 				type: 'application/json'
	// 			});
	// 			var link = document.createElement('a');
	// 			link.href = window.URL.createObjectURL(blob);
	// 			link.download = "export.json";
	// 			link.click();
	// 		}
	// 	});
	// });
</script>