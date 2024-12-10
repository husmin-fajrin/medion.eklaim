<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/**
	 * cek_session()
	 *
	 * Fungsi untuk mengecek session login pengguna
	 * query ke database melalui model pengguna untuk memastikan bahwa data session yang ada
	 * sesuai dengan data pengguna di database.
	 *
	 * @param	none
	 * @return	TRUE jika sudah login atau session belum berakhir
	 * FALSE jika belum login atau session sudah berakhir.
	 */
	function cek_session($ajax = false)
	{
		$CI = get_instance();
		if (!$CI->session->userdata('is_login')):
			$CI->session->sess_destroy();
			if ($ajax) {
				return FALSE;
			} else {
				redirect('1');
			}
		else :
			return true;
		endif;
	}

	/**
	 * admin_helper()
	 *
	 * Fungsi untuk membuat url halaman admin
	 *
	 * @return	url halaman admin
	 */
	function admin_url($subURL = '')
	{
		//$CI = get_instance();
		return site_url($subURL);
	}

	function generate_seo_url($string)
	{
		$url = str_replace('"', '', $string);
		$url = str_replace("'", '', $url);
		$url = strtolower(strip_tags($url));
		// Clean up by removing unwanted characters
		$url = preg_replace("/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s", " ", $url);
		 //Strip any unwanted characters
		$url = preg_replace("/[^a-z0-9_\s-]/", "", $url);
		//Clean multiple dashes or whitespaces
		$url = preg_replace("/[\s-]+/", " ", $url);
		//Convert whitespaces and underscore to dash
		$url = preg_replace("/[\s_]/", "-", $url);
		// remove space at the end
		$url = trim($url);
		// replace space with - character
		$url = str_replace(' ', '-', $url);
		return $url;
	}

	function get_age($bday, $date = '')
	{
		$bday = new DateTime($bday);
		$today = new DateTime(($date == '') ? '00:00:00' : $date); //- use this for the current date
		//$today = new DateTime('2010-08-01 00:00:00'); // for testing purposes
		return $today->diff($bday);
	}

	/**
	 * admin_helper()
	 *
	 * Fungsi untuk merubah bulan ke bahasa indonesia
	 *
	 * @return	bulan dalam bahasa indonesia
	 */
	function nama_bulan($bulan, $jenis = '')
	{
		$bulan_penuh_eng = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
		$bulan_penuh_ina = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
		return str_replace($bulan_penuh_eng, $bulan_penuh_ina, $bulan);
	}

	/**
	 * admin_helper()
	 *
	 * Fungsi untuk merubah bulan ke bahasa indonesia
	 *
	 * @return	bulan dalam bahasa indonesia
	 */
	function nama_hari($hari)
	{
		$hari_penuh_eng = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
		$hari_penuh_ina = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
		return str_replace($hari_penuh_eng, $hari_penuh_ina, $hari);
	}

	/**
	 * admin_helper()
	 *
	 * Fungsi untuk mendapatkan lamanya waktu berlalu
	 *
	 * @return	jumlah interval waktu
	 */

	function get_interval_time($dt1, $dt2 = '', $return = 'day') {
		$CI = get_instance();
		$now = new DateTime("now", new DateTimeZone($CI->session->userdata('timezone')));
		$dt2 = ($dt2 == '' || $dt2 == '0000-00-00' || $dt2 == '0000-00-00 00:00:00') ? $now->format('Y-m-d') : date("Y-m-d", strtotime($dt2));
		$datetime1 = new DateTime(date("Y-m-d", strtotime($dt1))); //time start
		$datetime2 = new DateTime($dt2);
		$interval = $datetime1->diff($datetime2); //lamanya waktu berlalu mulai dari diterima sampai jam sekarang
		//$datetime1->add(new DateInterval('P'.$interval->format('%d').'DT'.$interval->format('%h').'H'.$interval->format('%i').'M'.$interval->format('%s').'S'));
		//return $datetime1->format('Y-m-d').' '.$interval->format('%H:%I:%S');
		//$day = $interval->format('%d') * 24;
		//$hour = $day + $interval->format('%H');
		//return $interval->format('%d');
		if ($return == 'day') {
			return $interval->days;
		} else {
			$dt2 = ($dt2 == '') ? $now->format('Y-m-d H:i:s') : date("Y-m-d H:i:s", strtotime($dt2));
			$datetime1 = new DateTime(date("Y-m-d H:i:s", strtotime($dt1))); //time start
			$datetime2 = new DateTime($dt2);
			$interval = $datetime1->diff($datetime2); //lamanya waktu berlalu mulai dari diterima sampai jam sekarang
			$day = $interval->format('%d') * 24;
			$hour = $day + $interval->format('%H');
			return $hour . ':' . $interval->format('%I:%S');
			// return $interval->d . ' hari '.$interval->h . ' jam '.$interval->i.' menit ' . $interval->s . ' detik';
		}
	}

	function getRelativeTime($timeStart, $depth = 1, $timeEnd = '', $suffix = true)
	{
		$CI = get_instance();
		$units = array(
			"tahun" => 31104000,
			"bulan" => 2592000,
			"pekan" => 604800,
			"hari" => 86400,
			"jam" => 3600,
			"menit" => 60,
			"detik" => 1
		);

		// $plural = "s";
		$plural = "";
		// $conjugator = " and ";
		$conjugator = " ";
		$separator = ", ";
		// $suffix1 = " ago";
		$suffix1 = "";
		$suffix2 = " left";	
		$now = "now";
		$empty = "";

		# DO NOT EDIT BELOW

		//$timediff = strtotime("now")-strtotime($timeStart);
		$dt = new DateTime("now", new DateTimeZone($CI->session->userdata('timezone')));
		$timeEnd = ($timeEnd == '') ? $dt->format('H:i:s') : $timeEnd;
		$timediff = strtotime($timeEnd) - strtotime($timeStart);
		if ($timediff == 0) return $now;
		if ($depth < 1) return $empty;

		$max_depth = count($units);
		$remainder = abs($timediff);
		$output = "";
		$count_depth = 0;
		$fix_depth = true;

		foreach ($units as $unit => $value) {
			if ($remainder > $value && $depth-- > 0) {
				if ($fix_depth) {
					$max_depth -= ++$count_depth;
					if ($depth >= $max_depth) $depth = $max_depth;
					$fix_depth = false;
				}
				$u = (int)($remainder / $value);
				$remainder %= $value;
				$pluralise = $u > 1 ? $plural : $empty;
				$separate = $remainder == 0 || $depth == 0 ? $empty : ($depth == 1 ? $conjugator : $separator);
				$output .= "{$u} {$unit}{$pluralise}{$separate}";
			}
			$count_depth++;
		}
		$suffix = ($suffix == true) ? ($timediff < 0 ? $suffix2 : $suffix1) : '';
		return $output . $suffix;
	}

	/**
	 * admin_helper()
	 *
	 * Fungsi mengeja tanggal
	 *
	 * @return	ejaan tanggal
	 */
	function dateToWords($date, $type = 'full') {
		$ordinal = Array("satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan",
			"sepuluh", "sebelas", "dua belas", "tiga belas", "empat belas", "lima belas", "enam belas",
			"tujuh belas", "delapan belas", "sembilan belas", "dua puluh", "dua puluh satu", "dua puluh dua",
			"dua puluh tiga", "dua puluh empat", "dua puluh lima", "dua puluh enam", "dua puluh tujuh",
			"dua puluh delapan", "dua puluh sembilan", "tiga puluh", "tiga puluh satu");
		if ($type == 'date') {
			return $ordinal[date('d', strtotime($date))-1];
		} else {
			$cardinal = Array("", "se", "dua ", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas",
				"dua belas", "tiga belas", "empat belas", "lima belas", "enam belas", "tujuh belas", "delapan belas", "sembilan belas");
			$puluh = Array("", "satu", "dua ", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas",
				"dua belas", "tiga belas", "empat belas", "lima belas", "enam belas", "tujuh belas", "delapan belas", "sembilan belas");
			$puluhan = Array("dua puluh", "tiga puluh", "empat puluh", "lima puluh", "enam puluh", "tujuh puluh", "delapan puluh", "sembilan puluh");

			$tahun = date('Y', strtotime($date));
			$decades = str_split($tahun, 2);
			if ($decades[1] < 20 && $decades[1] >= 10) {
				$decade = $puluh[$decades[1]];
			} else if ($decades[1] > 20) {
				$decade = $puluhan[substr($decades[1], -2, 1)-2]." ".$puluh[substr($decades[1], -1)];
			} else {
				$decade = $puluh[substr($decades[1], -1)];
			}
			$ratus = (substr($decades[0], -1) > 0) ? $cardinal[substr($decades[0], -1)].' ratus ' : '';
			$ribu = $cardinal[substr($decades[0], 0, 1)].'ribu ';

			if ($type == 'full') {
				return trim($ordinal[date('d', strtotime($date))-1].' '.nama_bulan(date('F', strtotime($date))).' '.$ribu.$ratus.$decade);
			} else if ($type == 'year') {
				return $ribu.$ratus.$decade;
			}
		}
	}

	/**
	 * admin_helper()
	 *
	 * deretan Fungsi untuk mengeja angkat
	 *
	 * @return	ejaan angka
	 */
	function getSatuan($satuan){
		switch ($satuan) {
			case 1: return "satu ";
			case 2: return "dua ";
			case 3: return "tiga ";
			case 4: return "empat ";
			case 5: return "lima ";
			case 6: return "enam ";
			case 7: return "tujuh ";
			case 8: return "delapan ";
			case 9: return "sembilan ";
			default: return "";
		}
	}

	function getPuluhan($puluhan) {
		$res = '';
		if (substr($puluhan, 0, 1) == 1) {//If value between 10-19...
			switch ($puluhan) {
				case 10: return "sepuluh ";
				case 11: return "sebelas ";
				case 12: return "dua belas ";
				case 13: return "tiga belas ";
				case 14: return "empat belas ";
				case 15: return "lima belas ";
				case 16: return "enam belas ";
				case 17: return "tujuh belas ";
				case 18: return "delapan belas ";
				case 19: return "sembilan belas ";
			}
		} else {//If value between 20-99...
			switch (substr($puluhan, 0, 1)) {
				case 2: $res = "dua puluh "; break;
				case 3: $res = "tiga puluh "; break;
				case 4: $res = "empat puluh "; break;
				case 5: $res = "lima puluh "; break;
				case 6: $res = "enam puluh "; break;
				case 7: $res = "tujuh puluh "; break;
				case 8: $res = "delapan puluh "; break;
				case 9: $res = "sembilan puluh "; break;
			}
			$satuan = (substr($puluhan, -1) > 0) ? getSatuan(substr($puluhan, -1)) : '';
			return $res.$satuan;
		}
	}

	function getRatusan($ratusan) {
		$res = '';
		//$ratusan = settype($ratusan, "string");
		//if ($ratusan == 0) {return;}
		$ratusan = (substr($ratusan, 0, 1) == 0) ? substr($ratusan, -2) : $ratusan;
		//Convert the hundreds place.
		if ($ratusan >= 100 && substr($ratusan, 0, 1) != 0) {
			if (getSatuan(substr($ratusan, 0, 1)) == "satu ") {
				$res = "seratus ";
			} else {
				$res = getSatuan(substr($ratusan, 0, 1)) . "ratus ";
			}
			$ratusan = substr($ratusan, -2);
		}
		//Convert the tens and ones place.
		//exit($ratusan);
		//if (substr($ratusan, 1, 1) != 0) {
		if (strlen($ratusan) == 2) {
		   //$res = $res.getPuluhan(substr($ratusan, 1));
		   $res = $res.getPuluhan($ratusan);
		} else {
			//$res = $res.getSatuan(substr($ratusan, 2));
			$res = $res.getSatuan($ratusan);
		}
		return $res;
	}

	function numberToWords($number, $currency = '') {
		$Dollars = '';
		$Place[1] = " ";
		$Place[2] = "ribu ";
		$Place[3] = "juta ";
		$Place[4] = "milyar ";
		$Place[5] = "triliyun ";
		$count = 1;
		$number = explode('.', $number);
		while ($number[0] > 0) {
			$temp = getRatusan(substr($number[0], -3, 3));
			if ($temp != "") {
				if ($temp == "satu " && $count == 2) {
					$Dollars = "seribu " . $Dollars;
				} else {
					$Dollars = $temp. $Place[$count] . $Dollars;
				}
			}

			if (strlen($number[0]) > 3) {
				$number[0] = substr($number[0], 0, strlen($number[0]) - 3);
			} else {
				$number[0] = 0;
			}
			$count = $count + 1;
		}

		$decimal = '';
		if (isset($number[1]) && $number[1] > 0) {
			$count = 1;
			$Dollars .= ' koma ';
			
			while ($number[1] > 0) {
				$temp = getRatusan(substr($number[1], -3, 3));
				if ($temp != "") {
					if ($temp == "satu " && $count == 2) {
						$decimal = "seribu " . $decimal;
					} else {
						$decimal = $temp . $Place[$count] . $decimal;
					}
				}

				if (strlen($number[1]) > 3) {
					$number[1] = substr($number[1], 0, strlen($number[1]) - 3);
				} else {
					$number[1] = 0;
				}
				$count = $count + 1;
			}
		}

		switch ($Dollars) {
			Case "";
				$Dollars = "";
				break;
			default;
				$Dollars = $Dollars. $decimal . $currency;
		}
		return rtrim($Dollars);
	}

	function timeToWords($time) {
		list ($hour, $minute, $second) = explode (':', $time);
		$out = '';
		//$hour = (substr($hour,0,1) == 0) ? substr($hour,1,1) : $hour;
		$hour = ($hour < 10) ? substr($hour,1,1) : $hour;
		$hour = numberToWords($hour);
		//$minute = (substr($minute,0,1) == 0) ? substr($minute,1,1) : $minute;
		$minute = ($minute < 10) ? substr($minute,1,1) : $minute;
		$minute = ($minute > 0) ? ' lewat '.numberToWords($minute) : '';

		return $hour.$minute;
	}

	/*
	 * flatten()
	 *
	 * turn multidimensional array into single array
	 TURN THIS ARRAY
	 (
	 [0] => Array
			 (
			  [bahan_makanan] => Daging Ayam 100 gr
			 )

	 [1] => Array
			 (
				[bahan_makanan] => Telur Rebus
			 )
		)
	INTO THIS
	(
		 [0] => Daging Ayam 100 gr
		 [1] => Telur Rebus
	)
	 *
	 */
	function flatten(array $array) {
		$return = array();
		array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
		return $return;
	}

	function var_debugging($var, $exit = true)
	{
		echo '<pre>';
		print_r($var);
		echo '</pre>';
		if ($exit) {
			exit();
		}
	}
/* End of file admin_helper.php */
/* Location: ./system/helpers/admin_helper.php */
