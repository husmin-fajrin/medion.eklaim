<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	function bridging_medion_eklaim($service, $param = '', $method = 'GET', $debug = false)
	{
		$CI = get_instance();
		$base_url = $CI->config->item('ws_eklaim_url'). $service;
		// exit($base_url);
		if ($service == 'auth') {
			$headers = array(
				"md_username:" . $param['id_pengguna'],
				"md_password:" . $param['password_pengguna'],
				"Content-type: application/json; charset=utf-8"
			);
		} else {
			$headers = array(
				"md_token:" . $CI->session->userdata('md_token'),
				"Content-type: application/json; charset=utf-8",
				"Content-length: ".strlen($param)
			);
		}

		

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $base_url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		if ($method != 'GET') {
			// $param = http_build_query($param);
			// $headers[1] = "Content-type: Application/x-www-form-urlencoded";
			// $headers[2] = "Content-length: ".strlen($param);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
			// curl_setopt($curl, CURLOPT_POST, 1);
		}

		//Tell cURL that it should only spend 10 seconds
		//trying to connect to the URL in question.
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);

		//A given cURL operation should only take
		//30 seconds max.
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);

		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

		$data = curl_exec($curl);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		// curl_close($curl);

		if (curl_errno($curl)) {
			if ($debug) {
				echo $httpcode;
				echo '<pre>';
				echo curl_error($curl) . '<br>====================================<br>';
				print_r(curl_getinfo($curl));
				echo '</pre>';
				exit($base_url);
			}

			return array(
				'metadata' => array('code' => 201, 'message' => 'Timed Out')
			);
		} else {
			if ($debug) {
				echo $data;
				echo '<pre>';
				print_r(json_decode($data, TRUE));
				echo '<br>';
				print_r($headers);
				echo '<br>';
				if (!is_array($param)) {
					print_r(json_decode($param, TRUE));
				} else {
					print_r($param);
				}
				echo '</pre>';
				exit($base_url);
			}

			return json_decode($data, true);	
		}
	}

	function bridging_eklaim($param, $debug = false)
	{
		$CI = get_instance();
		// data yang akan dikirimkan dengan method POST adalah encrypted:
		$payload = inacbg_encrypt($param, $CI->session->userdata('encryption_key_eklaim'));
		// $payload = $param;

		// tentukan Content-Type pada http header
		$header = array("Content-Type: application/x-www-form-urlencoded");
		// setup curl
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $CI->session->userdata('url_eklaim'));
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);

		//Tell cURL that it should only spend 10 seconds
		//trying to connect to the URL in question.
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);

		//A given cURL operation should only take
		//30 seconds max.
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);

		// request dengan curl
		$response = curl_exec($curl);

		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if (curl_errno($curl)) {
			if ($debug) {
				echo '<pre>';
				echo curl_error($curl) . '<br>====================================<br>';
				print_r(curl_getinfo($curl));
				echo '</pre>';
				exit($CI->session->userdata('url_eklaim'));
			}

			return array(
				'metadata' => array('code' => 201, 'message' => 'Timed Out', 'error_no' => '')
			);
		} else {
			if ($debug) {
				echo $httpcode . '<br>';
				echo $response;
				echo '<pre>';
				print_r(json_decode($response, TRUE));
				echo '<br>';
				print_r($payload);
				echo '<br>';
				// print_r($param);
				echo json_encode(json_decode($param, TRUE), JSON_PRETTY_PRINT);
				echo '</pre>';
				exit($CI->session->userdata('url_eklaim'));
			}

			// terlebih dahulu hilangkan "----BEGIN ENCRYPTED DATA----\r\n"
			// dan hilangkan "----END ENCRYPTED DATA----\r\n" dari response
			$first = strpos($response, "\n") + 1;
			$last = strrpos($response, "\n") - 1;
			$response = substr($response, $first, strlen($response) - $first - $last);
			// decrypt dengan fungsi inacbg_decrypt
			$response = json_decode(inacbg_decrypt($response, $CI->session->userdata('encryption_key_eklaim')), true);
			return $response;
		}

		/*
		==================contoh untuk cetak klaim==================
		// hasil decrypt adalah format json, ditranslate kedalam array
		$msg = json_decode($response,true);
		// variable data adalah base64 dari file pdf
		$pdf = base64_decode($msg["data"]);
		// hasilnya adalah berupa binary string $pdf, untuk disimpan:
		file_put_contents("klaim.pdf",$pdf);
		// atau untuk ditampilkan dengan perintah:
		header("Content-type:application/pdf");
		header("Content-Disposition:attachment;filename='klaim.pdf'");
		echo $pdf;

		*/
	}

	// Encryption Function
	function inacbg_encrypt($data, $key) {
		/// make binary representasion of $key
		$key = hex2bin($key);
		/// check key length, must be 256 bit or 32 bytes
		if (mb_strlen($key, "8bit") !== 32) {
			throw new Exception("Needs a 256-bit key!");
		}
		/// create initialization vector
		$iv_size = openssl_cipher_iv_length("aes-256-cbc");
		$iv = openssl_random_pseudo_bytes($iv_size); // dengan catatan dibawah
		/// encrypt
		$encrypted = openssl_encrypt($data, "aes-256-cbc",
			$key,
			OPENSSL_RAW_DATA,
			$iv);
		/// create signature, against padding oracle attacks
		$signature = mb_substr(hash_hmac("sha256",
			$encrypted,
			$key,
			true), 0, 10, "8bit");
		/// combine all, encode, and format
		$encoded = chunk_split(base64_encode($signature.$iv.$encrypted));
		return $encoded;
	}

	// Decryption Function
	function inacbg_decrypt($str, $strkey) {
		/// make binary representation of $key
		$key = hex2bin($strkey);
		/// check key length, must be 256 bit or 32 bytes
		if (mb_strlen($key, "8bit") !== 32) {
			throw new Exception("Needs a 256-bit key!");
		}
		/// calculate iv size
		$iv_size = openssl_cipher_iv_length("aes-256-cbc");
		/// breakdown parts
		$decoded = base64_decode($str);
		$signature = mb_substr($decoded, 0, 10, "8bit");
		$iv = mb_substr($decoded, 10, $iv_size, "8bit");
		$encrypted = mb_substr($decoded, $iv_size + 10, NULL, "8bit");
		/// check signature, against padding oracle attack
		$calc_signature = mb_substr(hash_hmac("sha256",
			$encrypted,
			$key,
			true), 0, 10, "8bit");
		if (!inacbg_compare($signature, $calc_signature)) {
			return "SIGNATURE_NOT_MATCH"; /// signature doesn't match
		}
		$decrypted = openssl_decrypt($encrypted,
			"aes-256-cbc",
			$key,
			OPENSSL_RAW_DATA,
			$iv);
		return $decrypted;
	}

	function inacbg_compare($a, $b) {
		/// compare individually to prevent timing attacks

		/// compare length
		if (strlen($a) !== strlen($b)) return false;

		/// compare individual
		$result = 0;
		for ($i = 0; $i < strlen($a); $i++) {
			$result |= ord($a[$i]) ^ ord($b[$i]);
		}
		return $result == 0;
	}
/* End of file bpjs_helper.php */
/* Location: ./system/helpers/bpjs_helper.php */