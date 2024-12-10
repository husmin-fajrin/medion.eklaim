<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eklaim_ctrl extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	public function buat_klaim()
	{
		cek_session();
		$data_view['page_view'] = 'buat_klaim_view';
		$data_view['page_title'] = 'MEDION-EKLAIM';
		$data_view['page_subtitle'] = 'Bridging Data Klaim';

		/*
		$this->load->model('settings_mdl');
		$inacbg = $this->settings_mdl->pengaturan_bridging('get');
		$data_view['kode_tarif_eklaim'] = $inacbg['kode_tarif_eklaim'];
		$data_view['url_eklaim'] = $inacbg['url_eklaim'];
		$data_view['nik_coder'] = $inacbg['nik_coder'];

		$data_view['tagihan'] = $data_view['data_rawat'] = $data_view['data_persalinan'] = $data_view['data_klaim_lokal'] = '';
		if ($noSep != '') {
			$this->load->model('eklaim/eklaim_mdl');
			$data_view['data_klaim_lokal'] = $this->eklaim_mdl->get_data_klaim_lokal($noSep, $noRm);
			$data_view['tagihan'] = $this->eklaim_mdl->get_tagihan($noSep, $noRm);
			if ($data_view['tagihan'] == '') {
				show_404();
			}
			$data_view['data_rawat'] = $this->eklaim_mdl->get_data_rawat($data_view['tagihan']['jenis_rawat'], $data_view['tagihan']['id_tagihan_pasien']);

			$this->load->model('pasien_mdl');
			$data_view['pasien'] = $this->pasien_mdl->get_detail($noRm);
			
			if ($data_view['tagihan']['jenis_rawat'] == 'Rawat Inap') {
				//get data persalinan ibu (id ranap indeks 0) untuk dimasukan ke dalam parameter persalinan
				$data_view['data_persalinan'] = $this->eklaim_mdl->get_data_persalinan($data_view['data_rawat'][0]['id_rawat_inap'], $noRm);
				//get data bayi untuk pasien bayi
				$data_view['data_bayi'] = $this->eklaim_mdl->get_data_bayi($data_view['data_rawat'][0]['id_rawat_inap'], $noRm);
			}

			$data_view['tarif'] = $this->eklaim_mdl->get_data_tarif($noSep, $noRm);
			$data_view['data_inacbg'] = '';
			if ($inacbg['url_eklaim'] != '') {
				$this->load->helper('bridging');
				//cari data klaim
				$data_inacbg = bridging_eklaim(
					json_encode(
						array(
							"metadata" => array(
								"method" => "get_claim_data"
							),
							"data" => array(
								"nomor_sep" => $noSep
							)
						)
					)
				);

				//jika klaim pernah dibuat
				if ($data_inacbg['metadata']['code'] == 200) {
					$data_view['data_inacbg'] = $data_inacbg['response']['data'];
				}
			}
		}
			*/

		$this->load->view('main_view.php',$data_view);
	}

	public function get_kunjungan()
	{
		$response = bridging_medion_eklaim(
			'data_kunjungan',
			json_encode(
				array(
					'no_sep' => $this->input->post('no_sep')
				)
			),
			'POST'
		);
		// var_debugging($response);
		if ($response['metadata']['code'] === 200) {
			$return = $response;
			$return['success'] = true;

			$return['data_inacbg'] = '';
			$this->load->helper('bridging');
			//cari data klaim
			$data_inacbg = bridging_eklaim(
				json_encode(
					array(
						"metadata" => array(
							"method" => "get_claim_data"
						),
						"data" => array(
							"nomor_sep" => $this->input->post('no_sep')
						)
					)
				)
			);

			//jika klaim pernah dibuat
			if ($data_inacbg['metadata']['code'] == 200) {
				$return['data_inacbg'] = $data_inacbg['response']['data'];
			}

			$return['form_eklaim'] = file_get_contents($this->config->item('medion_url').'eklaim/klaim/buat_klaim/'. $this->input->post('no_sep').'/'. $response['data_klaim']['tagihan']['no_rekam_medis'].'/true');
		} else {
			$return['success'] = false;
			$return['message'] = $response['metadata']['message'].'<br>Code: '. $response['metadata']['code'];
		}
		echo json_encode($return);
	}

	public function new_claim()
	{
		if (cek_session(true)) :
			$param = $this->generate_param_klaim('new_claim');
			$this->load->helper('bridging');
			$result = bridging_eklaim($param);
			// var_debugging($result);
			if ($result['metadata']['code'] == '200') {
				$ret_set_klaim = $this->set_claim($result['response']['admission_id'] . '-' . $result['response']['hospital_admission_id']);
				$return['message'] = 'Klaim baru berhasil dikirim<hr>Set Data Klaim: ' . $ret_set_klaim;
				$return['success'] = true;
			} else {
				$return['message'] = $result['metadata']['message'] . '<hr>Code: ' . $result['metadata']['code'] . '<br>Error No: ' . $result['metadata']['error_no'];
				$return['success'] = false;
			}
		else:
			$return['message'] = 'Session habis, silakan login kembali';
			$return['success'] = false;
		endif;
		echo json_encode($return);
	}

	public function set_claim($id_klaim_inacbg = '')
	{
		if (cek_session(true)){
			$param = $this->generate_param_klaim('set_claim');
			$this->load->helper('bridging');
			$result = bridging_eklaim($param);
			if ($result['metadata']['code'] == '200') {
				$return['message'] = 'Klaim Berhasil Diupdate';
				$return['success'] = true;
			} else {
				$return['message'] = $result['metadata']['message'].'<br>Code: '.$result['metadata']['error_no'];
				$return['param'] = json_decode($param, true);
				$return['success'] = false;
			}

			if ($id_klaim_inacbg != '') {
				return $return['message'];
			}
			
		} else{
			$return['message'] = 'Session habis, silakan login kembali';
			$return['success'] = false;
		}
		echo json_encode($return);
	}

	public function claim_print()
	{
		if (cek_session(true)) :
			$param = json_encode(
				array(
					"metadata" => array(
						"method" => "claim_print"
					),
					"data" => array(
						"nomor_sep" => $this->input->post('no_sep')
					)
				)
			);

			$this->load->helper('bridging');
			$result = bridging_eklaim($param);
			if ($result['metadata']['code'] == '200') {
				$pdf = base64_decode($result["data"]);
				header("Content-type:application/pdf");
				header("Content-Disposition:attachment;filename='klaim-". $this->input->post('no_sep').".pdf'");
				echo $pdf;
			} else {
				$return['message'] = $result['metadata']['message'] . '<br>Code: ' . $result['metadata']['code'] . '<br>Error No: ' . $result['metadata']['error_no'];
				$return['success'] = false;
			}
		else:
			$return['message'] = 'Session habis, silakan login kembali';
			$return['success'] = false;
		endif;
		echo json_encode($return);
	}

	public function grouper($stage)
	{
		if (cek_session(true)) :
			if ($stage === '1') {
				$param = json_encode(
					array(
						"metadata" => array(
							"method" => "grouper",
							"stage" => $stage
						),
						"data" => array(
							"nomor_sep" => $this->input->post('no_sep')
						)
					)
				);
			} else {
				$special = '';
				foreach ($this->input->post('special') as $v) {
					if ($special == '') {
						$special .= $v;
					} else {
						$special .= '#' . $v;
					}
				}

				$param = json_encode(
					array(
						"metadata" => array(
							"method" => "grouper",
							"stage" => $stage
						),
						"data" => array(
							"nomor_sep" => $this->input->post('no_sep'),
							"special_cmg" => $special
						)
					)
				);
			}
			
			$this->load->helper('bridging');
			$result = bridging_eklaim($param);
			
			if ($result['metadata']['code'] == '200') {
				$return['message'] = $result['metadata']['message'] . '<br>Code: ' . $result['metadata']['code'];
				$return['success'] = true;
				$return['response_grouper'] = $result['response'];
				$return['response_inagrouper'] = $result['response_inagrouper'];
				$return['special_cmg_option'] = (isset($result['special_cmg_option']) ? $result['special_cmg_option'] : '');
				$return['tarif_alt'] = $result['tarif_alt'];
			} else {
				$return['message'] = $result['metadata']['message'] . '<br>Code: ' . $result['metadata']['code'] . '<br>Error No: ' . $result['metadata']['error_no'];
				$return['success'] = false;
			}
		else:
			$return['message'] = 'Session habis, silakan login kembali';
			$return['success'] = false;
		endif;
		echo json_encode($return);
	}

	public function finalisasi()
	{
		if (cek_session(true)) :
			$param = json_encode(
				array(
					"metadata" => array(
						"method" => "claim_final"
					),
					"data" => array(
						"nomor_sep" => $this->input->post('no_sep'),
						"coder_nik"=> $this->input->post('coder_nik')
					)
				)
			);

			$this->load->helper('bridging');
			$result = bridging_eklaim($param);
			if ($result['metadata']['code'] == '200') {
				$return['message'] = $result['metadata']['message'] . '<br>Code: ' . $result['metadata']['code'];
				$return['success'] = true;
			} else {
				$return['message'] = $result['metadata']['message'] . '<br>Code: ' . $result['metadata']['code'] . '<br>Error No: ' . $result['metadata']['error_no'];
				$return['success'] = false;
			}
		else:
			$return['message'] = 'Session habis, silakan login kembali';
			$return['success'] = false;
		endif;
		echo json_encode($return);
	}

	public function reedit_claim()
	{
		if (cek_session(true)) :
			$param = json_encode(
				array(
					"metadata" => array(
						"method" => "reedit_claim"
					),
					"data" => array(
						"nomor_sep" => $this->input->post('no_sep')
					)
				)
			);

			$this->load->helper('bridging');
			$result = bridging_eklaim($param);
			if ($result['metadata']['code'] == '200') {
				$return['message'] = $result['metadata']['message'] . '<br>Code: ' . $result['metadata']['code'];
				$return['success'] = true;
			} else {
				$return['message'] = $result['metadata']['message'] . '<br>Code: ' . $result['metadata']['code'] . '<br>Error No: ' . $result['metadata']['error_no'];
				$return['success'] = false;
			}
		else:
			$return['message'] = 'Session habis, silakan login kembali';
			$return['success'] = false;
		endif;
		echo json_encode($return);
	}

	public function get_claim_status($no_sep)
	{
		if (cek_session(true)) :
			$param = json_encode(
				array(
					"metadata" => array(
						"method" => "get_claim_status"
					),
					"data" => array(
						"nomor_sep" => $no_sep
					)
				)
			);

			$this->load->helper('bridging');
			$result = bridging_eklaim($param);

			if ($result['metadata']['code'] == '200') {
				
				$return['message'] = $result['metadata']['message'] . '<br>Code: ' . $result['metadata']['code'];
				$return['success'] = true;
				$return['response'] = $result['response'];
			} else {
				$return['message'] = $result['metadata']['message'] . '<br>Code: ' . $result['metadata']['code'] . '<br>Error No: ' . $result['metadata']['error_no'];
				$return['success'] = false;
			}
		else:
			$return['message'] = 'Session habis, silakan login kembali';
			$return['success'] = false;
		endif;
		echo json_encode($return);
	}

	public function sitb_validate($no_sep, $no_sitb)
	{
		if (cek_session(true)) :
			$param = json_encode(
				array(
					"metadata" => array(
						"method" => "sitb_validate"
					),
					"data" => array(
						"nomor_sep" => $no_sep,
						"nomor_register_sitb" => $no_sitb
					)
				)
			);

			$this->load->helper('bridging');
			$result = bridging_eklaim($param);

			if ($result['metadata']['code'] == '200') {
				$return['message'] = 'Status: '.$result['response']['status'] . '<br>' . $result['response']['detail'];
				$return['success'] = true;
				$return['validation'] = $result['response']['validation'];
			} else {
				$return['message'] = $result['metadata']['message'] . '<br>Code: ' . $result['metadata']['code'] . '<br>Error No: ' . $result['metadata']['error_no'];
				$return['success'] = false;
			}
		else:
			$return['message'] = 'Session habis, silakan login kembali';
			$return['success'] = false;
		endif;
		echo json_encode($return);
	}

	public function download()
	{
		// $json = file_get_contents("eklaim-param.json");
		// if ($json === false) {
		// 	die('Error reading the JSON file');
		// }
		// $json = json_decode($json, true);
		// if ($json === null) {
		// 	die('Error decoding the JSON file');
		// }
		// var_debugging($json, false);
		// return 'asdad';
		if (cek_session(true)) :
			$param['new_claim'] = $this->generate_param_klaim('new_claim', 'array');
			$param['set_claim'] = $this->generate_param_klaim('set_claim', 'array');
			
			// Force download .json file with JSON in it
			header("Content-type: application/vnd.ms-excel");
			header("Content-Type: application/force-download");
			header("Content-Type: application/download");
			header("Content-disposition: " . $this->input->post('no_sep') . ".json");
			header("Content-disposition: filename=" . $this->input->post('no_sep') . ".json");

			print json_encode($param);
			exit;
		endif;
	}

	public function delete_claim()
	{
		if (cek_session(true)) :
			$param = json_encode(
				array(
					"metadata" => array(
						"method" => "delete_claim"
					),
					"data" => array(
						"nomor_sep" => $this->input->post('no_sep'),
						"coder_nik" => $this->input->post('coder_nik')
					)
				)
			);
			$this->load->helper('bridging');
			$result = bridging_eklaim($param);

			if ($result['metadata']['code'] == '200') {
				$return['message'] = 'Klaim Berhasil Dihapus';
				$return['success'] = true;
			} else {
				$return['message'] = 'Data Klaim lokal berhasil dihapus, tapi gagal menghapus data klaim di INACBG.<hr>' . $result['metadata']['message'] . '<br>Code: ' . $result['metadata']['error_no'];
				$return['success'] = false;
			}
		else:
			$return['message'] = 'Session habis, silakan login kembali';
			$return['success'] = false;
		endif;
		echo json_encode($return);
	}

	public function referensi_eklaim()
	{
		$param = json_encode(
			array(
				"metadata" => array(
					"method" => $this->input->post('method')
				),
				"data" => array(
					"keyword" => $this->input->post('keyword')
				)
			)
		);

		$this->load->helper('bridging');
		$result = bridging_eklaim($param);

		if ($result['metadata']['code'] == '200') {
			$return['items'] = $result['response'];
			$return['message'] = $result['metadata']['message'] . '<br>Code: ' . $result['metadata']['code'];
			$return['success'] = true;
		} else {
			$return['message'] = $result['metadata']['message'] . '<br>Code: ' . $result['metadata']['code'] . '<br>Error No: ' . $result['metadata']['error_no'];
			$return['success'] = false;
		}
		echo json_encode($return);
	}

	private function generate_param_klaim($jenis_param, $format = 'json')
	{
		if ($jenis_param == 'new_claim') {
			$param = json_encode(
				array(
					"metadata" => array(
						"method" => "new_claim"
					),
					"data" => array(
						"nomor_kartu" => $this->input->post('no_kartu'),
						"nomor_sep" => $this->input->post('no_sep'),
						"nomor_rm" => $this->input->post('no_rekam_medis'),
						"nama_pasien" => $this->input->post('nama-pasien'),
						"tgl_lahir" => $this->input->post('tanggal_lahir'),
						"gender" => $this->input->post('jenis_kelamin')
					)
				)
			);
		} else if ($jenis_param == 'set_claim') {
			$persalinan = '';
			if ($this->input->post('delivery') !== null) {
				if (isset($this->input->post('delivery')['delivery_method'][1])) {
					if (($this->input->post('delivery')['shk_spesimen_ambil'][0] == 'ya')) {
						$shk1 = '"shk_spesimen_ambil": "' . $this->input->post('delivery')['shk_spesimen_ambil'][0] . '",
									"shk_lokasi": "' . $this->input->post('delivery')['shk_lokasi'][0] . '",
									"shk_spesimen_dttm": "' . date('Y-m-d H:i:s', strtotime($this->input->post('delivery')['shk_spesimen_dttm'][0])) . '"';
					} else {
						$shk1 = '"shk_spesimen_ambil": "' . $this->input->post('delivery')['shk_spesimen_ambil'][0] . '",
									"shk_alasan": "' . $this->input->post('delivery')['shk_alasan'][0] . '"';
					}

					if (($this->input->post('delivery')['shk_spesimen_ambil'][1] == 'ya')) {
						$shk2 = '"shk_spesimen_ambil": "' . $this->input->post('delivery')['shk_spesimen_ambil'][1] . '",
									"shk_lokasi": "' . $this->input->post('delivery')['shk_lokasi'][1] . '",
									"shk_spesimen_dttm": "' . date('Y-m-d H:i:s', strtotime($this->input->post('delivery')['shk_spesimen_dttm'][1])) . '"';
					} else {
						$shk2 = '"shk_spesimen_ambil": "' . $this->input->post('delivery')['shk_spesimen_ambil'][1] . '",
									"shk_alasan": "' . $this->input->post('delivery')['shk_alasan'][1] . '"';
					}

					$persalinan = '"persalinan" : {
						"usia_kehamilan" : "' . $this->input->post('usia_kehamilan') . '",
						"gravida" : "' . $this->input->post('gravida') . '",
						"partus" : "' . $this->input->post('partus') . '",
						"abortus" : "' . $this->input->post('abortus') . '",
						"onset_kontraksi" : "' . $this->input->post('onset_kontraksi') . '",
						"delivery" : [
							{
								"delivery_sequence" : "1",
								"delivery_method" : "' . $this->input->post('delivery')['delivery_method'][0] . '",
								"delivery_dttm" : "' . $this->input->post('delivery')['delivery_dttm'][0] . '",
								"letak_janin" : "' . $this->input->post('delivery')['letak_janin'][0] . '",
								"kondisi" : "' . $this->input->post('delivery')['kondisi'][0] . '",
								"use_manual" : "' . (($this->input->post('delivery')['use_manual'][0] == 'Ya') ? 1 : 0) . '",
								"use_forcep" : "' . (($this->input->post('delivery')['use_forcep'][0] == 'Ya') ? 1 : 0) . '",
								"use_vacuum" : "' . (($this->input->post('delivery')['use_vacuum'][0] == 'Ya') ? 1 : 0) . '",
								' . $shk1 . '
							}, {
								"delivery_sequence" : "2",
								"delivery_method" : "' . $this->input->post('delivery')['delivery_method'][1] . '",
								"delivery_dttm" : "' . $this->input->post('delivery')['delivery_dttm'][1] . '",
								"letak_janin" : "' . $this->input->post('delivery')['letak_janin'][1] . '",
								"kondisi" : "' . $this->input->post('delivery')['kondisi'][1] . '",
								"use_manual" : "' . (($this->input->post('delivery')['use_manual'][1] == 'Ya') ? 1 : 0) . '",
								"use_forcep" : "' . (($this->input->post('delivery')['use_forcep'][1] == 'Ya') ? 1 : 0) . '",
								"use_vacuum" : "' . (($this->input->post('delivery')['use_vacuum'][1] == 'Ya') ? 1 : 0) . '",
								' . $shk2 . '
							}
						]
					},';
				} else {
					if (($this->input->post('delivery')['shk_spesimen_ambil'][0] == 'ya')) {
						$shk1 = '"shk_spesimen_ambil": "' . $this->input->post('delivery')['shk_spesimen_ambil'][0] . '",
									"shk_lokasi": "' . $this->input->post('delivery')['shk_lokasi'][0] . '",
									"shk_spesimen_dttm": "' . date('Y-m-d H:i:s', strtotime($this->input->post('delivery')['shk_spesimen_dttm'][0])) . '"';
					} else {
						$shk1 = '"shk_spesimen_ambil": "' . $this->input->post('delivery')['shk_spesimen_ambil'][0] . '",
									"shk_alasan": "' . $this->input->post('delivery')['shk_alasan'][0] . '"';
					}
					$persalinan = '"persalinan" : {
						"usia_kehamilan" : "' . $this->input->post('usia_kehamilan') . '",
						"gravida" : "' . $this->input->post('gravida') . '",
						"partus" : "' . $this->input->post('partus') . '",
						"abortus" : "' . $this->input->post('abortus') . '",
						"onset_kontraksi" : "' . $this->input->post('onset_kontraksi') . '",
						"delivery" : [
							{
								"delivery_sequence" : "1",
								"delivery_method" : "' . $this->input->post('delivery')['delivery_method'][0] . '",
								"delivery_dttm" : "' . $this->input->post('delivery')['delivery_dttm'][0] . '",
								"letak_janin" : "' . $this->input->post('delivery')['letak_janin'][0] . '",
								"kondisi" : "' . $this->input->post('delivery')['kondisi'][0] . '",
								"use_manual" : "' . (($this->input->post('delivery')['use_manual'][0] == 'Ya') ? 1 : 0) . '",
								"use_forcep" : "' . (($this->input->post('delivery')['use_forcep'][0] == 'Ya') ? 1 : 0) . '",
								"use_vacuum" : "' . (($this->input->post('delivery')['use_vacuum'][0] == 'Ya') ? 1 : 0) . '",
								' . $shk1 . '
							}
						]
					},';
				}
			}

			$apgar = '';
			if ($this->input->post('apgar') !== null) {
				$apgar = '"apgar": {
					"menit_1": {
						"appearance": ' . $this->input->post('apgar')['m1']['appearance'] . ',
						"pulse": ' . $this->input->post('apgar')['m1']['pulse'] . ',
						"grimace": ' . $this->input->post('apgar')['m1']['grimace'] . ',
						"activity": ' . $this->input->post('apgar')['m1']['activity'] . ',
						"respiration": ' . $this->input->post('apgar')['m1']['respiration'] . '
					},
						"menit_5": {
						"appearance": ' . $this->input->post('apgar')['m5']['appearance'] . ',
						"pulse": ' . $this->input->post('apgar')['m5']['pulse'] . ',
						"grimace": ' . $this->input->post('apgar')['m5']['grimace'] . ',
						"activity": ' . $this->input->post('apgar')['m5']['activity'] . ',
						"respiration": ' . $this->input->post('apgar')['m5']['respiration'] . '
					}
				},';
			}

			$diagnosa = '';
			if ($this->input->post('diagnosa') !== null) {
				foreach ($this->input->post('diagnosa') as $v) {
					if ($diagnosa == '') {
						$diagnosa .= $v;
					} else {
						$diagnosa .= '#' . $v;
					}
				}
			} else {
				$diagnosa = '#';
			}

			$procedure = '';
			if ($this->input->post('procedure') !== null) {
				foreach ($this->input->post('procedure') as $v) {
					if ($procedure == '') {
						$procedure .= $v;
					} else {
						$procedure .= '#' . $v;
					}
				}
			} else {
				$procedure = '#';
			}

			$diagnosaINA = '';
			if ($this->input->post('diagnosa_inagrouper') !== null) {
				foreach ($this->input->post('diagnosa_inagrouper') as $v) {
					if ($diagnosaINA == '') {
						$diagnosaINA .= $v;
					} else {
						$diagnosaINA .= '#' . $v;
					}
				}
			} else {
				$diagnosaINA = '#';
			}

			$procedureINA = '';
			if ($this->input->post('procedure_inagrouper') !== null) {
				foreach ($this->input->post('procedure_inagrouper') as $k => $v) {
					$multi = ($this->input->post('multiplicity')[$k] != '') ? '+' . $this->input->post('multiplicity')[$k] : '';
					if ($procedureINA == '') {
						$procedureINA .= $v . $multi;
					} else {
						$procedureINA .= '#' . $v . $multi;
					}
				}
			} else {
				$procedureINA = '#';
			}

			$chrFind = array('.', ',');
			$chrRep = array('', '.');

			$param = '{
				"metadata": {
					"method": "set_claim_data",
					"nomor_sep": "' . $this->input->post('no_sep') . '"
				},
				"data": {
					"nomor_sep": "' . $this->input->post('no_sep') . '",
					"nomor_kartu": "' . $this->input->post('no_kartu') . '",
					"tgl_masuk": "' . $this->input->post('tanggal_masuk') . '",
					"tgl_pulang": "' . $this->input->post('tanggal_keluar') . '",
					"cara_masuk": "' . $this->input->post('cara_masuk') . '",
					"jenis_rawat": "' . $this->input->post('jenis_rawat') . '",
					"kelas_rawat": "' . $this->input->post('kelas_rawat') . '",
					"adl_sub_acute": "' . $this->input->post('adl_sub_acute') . '",
					"adl_chronic": "' . $this->input->post('adl_chronic') . '",
					"icu_indikator": "' . $this->input->post('icu_indikator') . '",
					"icu_los": "' . $this->input->post('icu_los') . '",
					"ventilator_hour": "' . $this->input->post('ventilator_hour') . '",
					"ventilator": {
						"use_ind": "' . (($this->input->post('ventilator_hour') > 0) ? 1 : 0) . '",
						"start_dttm": "' . (($this->input->post('ventilator_hour') > 0) ? $this->input->post('ventilator_start_dttm') : '') . '",
						"stop_dttm": "' . (($this->input->post('ventilator_hour') > 0) ? $this->input->post('ventilator_stop_dttm') : '') . '"
					},
					"upgrade_class_ind": "' . $this->input->post('upgrade_class_ind') . '",
					"upgrade_class_class": "' . $this->input->post('upgrade_class_class') . '",
					"upgrade_class_los": "' . $this->input->post('upgrade_class_los') . '",
					"upgrade_class_payor": "' . $this->input->post('upgrade_class_payor') . '",
					"add_payment_pct": "' . $this->input->post('add_payment_pct') . '",
					"sistole": "' . $this->input->post('sistole') . '",
					"diastole": "' . $this->input->post('diastole') . '",
					"birth_weight": "' . $this->input->post('birth_weight') . '",
					"discharge_status": "' . $this->input->post('discharge_status') . '",
					"diagnosa": "' . $diagnosa . '",
					"procedure": "' . $procedure . '",
					"diagnosa_inagrouper": "' . $diagnosaINA . '",
					"procedure_inagrouper": "' . $procedureINA . '",
					"tarif_rs": {
						"prosedur_non_bedah": "' . str_replace($chrFind, $chrRep, $this->input->post('prosedur_non_bedah')) . '",
						"prosedur_bedah": "' . str_replace($chrFind, $chrRep, $this->input->post('prosedur_bedah')) . '",
						"konsultasi": "' . str_replace($chrFind, $chrRep, $this->input->post('konsultasi')) . '",
						"tenaga_ahli": "' . str_replace($chrFind, $chrRep, $this->input->post('tenaga_ahli')) . '",
						"keperawatan": "' . str_replace($chrFind, $chrRep, $this->input->post('keperawatan')) . '",
						"penunjang": "' . str_replace($chrFind, $chrRep, $this->input->post('penunjang')) . '",
						"radiologi": "' . str_replace($chrFind, $chrRep, $this->input->post('radiologi')) . '",
						"laboratorium": "' . str_replace($chrFind, $chrRep, $this->input->post('laboratorium')) . '",
						"pelayanan_darah": "' . str_replace($chrFind, $chrRep, $this->input->post('pelayanan_darah')) . '",
						"rehabilitasi": "' . str_replace($chrFind, $chrRep, $this->input->post('rehabilitasi')) . '",
						"kamar": "' . str_replace($chrFind, $chrRep, $this->input->post('kamar')) . '",
						"rawat_intensif": "' . str_replace($chrFind, $chrRep, $this->input->post('rawat_intensif')) . '",
						"obat": "' . str_replace($chrFind, $chrRep, $this->input->post('obat')) . '",
						"obat_kronis": "' . str_replace($chrFind, $chrRep, $this->input->post('obat_kronis')) . '",
						"obat_kemoterapi": "' . str_replace($chrFind, $chrRep, $this->input->post('obat_kemoterapi')) . '",
						"alkes": "' . str_replace($chrFind, $chrRep, $this->input->post('alkes')) . '",
						"bmhp": "' . str_replace($chrFind, $chrRep, $this->input->post('bmhp')) . '",
						"sewa_alat": "' . str_replace($chrFind, $chrRep, $this->input->post('sewa_alat')) . '"
					},
					"bayi_lahir_status_cd": "' . $this->input->post('bayi_lahir_status_cd') . '",
					' . $apgar . '
					' . $persalinan . '
					"tarif_poli_eks": "' . $this->input->post('tarif_poli_eks') . '",
					"nama_dokter": "' . $this->input->post('nama_dokter') . '",
					"kode_tarif": "' . $this->input->post('kode_tarif') . '",
					"payor_id": "' . $this->input->post('payor_id') . '",
					"payor_cd": "' . $this->input->post('payor_cd') . '",
					"cob_cd": "' . (($this->input->post('cob_cd') == '') ? '#' : $this->input->post('cob_cd')) . '",
					"coder_nik": "' . $this->input->post('coder_nik') . '"
				}
			}';

			/*
			"pemulasaraan_jenazah": "1",
			"kantong_jenazah": "1",
			"peti_jenazah": "1",
			"plastik_erat": "1",
			"desinfektan_jenazah": "1",
			"mobil_jenazah": "0",
			"desinfektan_mobil_jenazah": "0",
			"covid19_status_cd": "1",
			"nomor_kartu_t": "nik",
			"episodes": "1;12#2;3#6;5",
			"covid19_cc_ind": "1",
			"covid19_rs_darurat_ind": "1",
			"covid19_co_insidense_ind": "1",
			"covid19_penunjang_pengurang"=> array (
				"lab_asam_laktat": "1",
				"lab_procalcitonin": "1",
				"lab_crp": "1",
				"lab_kultur": "1",
				"lab_d_dimer": "1",
				"lab_pt": "1",
				"lab_aptt": "1",
				"lab_waktu_pendarahan": "1",
				"lab_anti_hiv": "1",
				"lab_analisa_gas": "1",
				"lab_albumin": "1",
				"rad_thorax_ap_pa": "0"
			),
			"terapi_konvalesen": "1000000",
			"akses_naat": "C",
			"isoman_ind": "0",
			*/
		}

		// var_debugging($param);
		if ($format == 'json') {
			return $param;
		} else {
			return json_decode($param, true);
		}
	}
}