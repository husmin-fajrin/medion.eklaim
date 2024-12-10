<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_ctrl extends CI_Controller {

	public function index()
	{
		$data_view['error_msg'] = '';
		$submit_login = $this->input->post('submit_login');
		if ($submit_login) :
			// Validation: Checking entered captcha code with the generated captcha code
			if (strcmp($this->session->userdata('captcha'), $this->input->post('captcha')) != 0) {
				// Note: the captcha code is compared case insensitively.
				// if you want case sensitive match, check above with strcmp()
				$data_view['error_msg'] = "Kode yang Anda masukkan salah, silakan ulangi kembali.";
				$this->load->view('login_view', $data_view);
			} else {
				$this->login();
			}
			
		else :
			//$data_view = '';
			$session_login['id_pengguna'] = $this->session->userdata('id_pengguna');
			$session_login['password_pengguna'] = $this->session->userdata('password_pengguna');
			if ($session_login['id_pengguna'] && $session_login['password_pengguna']) : //jika session sudah ada
				cek_session(); //cek apakah data session masih sama
				redirect(admin_url() . 'eklaim/buat_klaim');
			else : //jika session belum ada atau sudah habis
				
				if ($this->uri->segment(1) == 1) {
					$data_view['error_msg'] = "Jika Anda belum login, maka login terlebih dahulu untuk masuk ke halaman administrator<br>Jika Anda sudah login, maka session login Anda telah berakhir. Silakan login kembali";
				} elseif ($this->uri->segment(1) == 2) {
					$data_view['error_msg'] = "Data Session Anda berubah. Hal ini bisa dikarenakan Anda mengganti Password. Silakan login kembali";
				}

				$this->load->view('login_view', $data_view);
			endif;
		endif;
	}

	/**
	 * login()
	 *
	 * Fungsi untuk mengecek id pengguna beserta password yang dimasukkan
	 * pada halaman login, dan kemudian membuat session jika data
	 * yang dimasukkan sesuai dengan yang ada di database.
	 *
	 * @param	none
	 * @return	jika proses login berhasil, maka halaman beranda akan dibuka
	 * jika tidak berhasil, maka halaman login akan dibuka kembali
	 * dengan menampilkan pesan kesalahan.
	 */
	function login()
	{
		$id_pengguna = $this->input->post('id_pengguna');
		$password_pengguna = $this->input->post('password_pengguna');
		$data_view = array();

		if ($id_pengguna <> '' && $password_pengguna <> '') {
			$response = bridging_medion_eklaim('auth', array('id_pengguna' => $id_pengguna, 'password_pengguna' => $password_pengguna), 'GET', false);
			if ($response['metadata']['code'] === 200) {
				$user_data = array(
					'is_login' => true,
					'md_token' => $response['response']['token'],
					'encryption_key_eklaim' => $response['response']['inacbg']['encryption_key_eklaim'],
					'url_eklaim' => $response['response']['inacbg']['url_eklaim'],
					'kode_tarif_eklaim' => $response['response']['inacbg']['kode_tarif_eklaim'],
					'nik_coder' => $response['response']['inacbg']['nik_coder']
				);
				$this->session->set_userdata($user_data);
				session_write_close();
				redirect(admin_url() . 'eklaim/buat_klaim');
			} else {
				$data_view['error_msg'] = 'Akun yang Anda masukkan <strong>TIDAK TERDAFTAR DI MEDION.SIMRS</strong>, silakan cek kembali id dan password akun Anda.';
			}
		} else {
			$data_view['error_msg'] = 'Masukan Username dan Password Anda';
		}

		$this->load->view('login_view', $data_view);
	}

	public function logout()
	{
		$this->session->sess_destroy();
		header('location:'.site_url());
	}

	public function captcha()
	{
		$captcha_code = '';
		$captcha_image_height = 50;
		$captcha_image_width = 130;
		$total_characters_on_image = 4;

		//The characters that can be used in the CAPTCHA code.
		//avoid all confusing characters and numbers (For example: l, 1 and i)
		$possible_captcha_letters = 'bcdfghjkmnpqrstvwxyz23456789';
		$captcha_font = APPPATH . '../assets/monofont.ttf';

		$random_captcha_dots = 25;
		$random_captcha_lines = 25;
		$captcha_text_color = "0x142864";
		$captcha_noise_color = "0x142864";


		$count = 0;
		while ($count < $total_characters_on_image) {
			$captcha_code .= substr(
				$possible_captcha_letters,
				mt_rand(0, strlen($possible_captcha_letters) - 1),
				1
			);
			$count++;
		}

		$captcha_font_size = $captcha_image_height * 0.85;
		$captcha_image = @imagecreate(
			$captcha_image_width,
			$captcha_image_height
		);

		/* setting the background, text and noise colours here */
		$background_color = imagecolorallocate(
			$captcha_image,
			255,
			255,
			255
		);

		$array_text_color = $this->captcha_hextorgb($captcha_text_color);
		$captcha_text_color = imagecolorallocate(
			$captcha_image,
			$array_text_color['red'],
			$array_text_color['green'],
			$array_text_color['blue']
		);

		$array_noise_color = $this->captcha_hextorgb($captcha_noise_color);
		$image_noise_color = imagecolorallocate(
			$captcha_image,
			$array_noise_color['red'],
			$array_noise_color['green'],
			$array_noise_color['blue']
		);

		/* Generate random dots in background of the captcha image */
		for ($count = 0; $count < $random_captcha_dots; $count++) {
			imagefilledellipse(
				$captcha_image,
				mt_rand(0, $captcha_image_width),
				mt_rand(0, $captcha_image_height),
				2,
				3,
				$image_noise_color
			);
		}

		/* Generate random lines in background of the captcha image */
		for ($count = 0; $count < $random_captcha_lines; $count++) {
			imageline(
				$captcha_image,
				mt_rand(0, $captcha_image_width),
				mt_rand(0, $captcha_image_height),
				mt_rand(0, $captcha_image_width),
				mt_rand(0, $captcha_image_height),
				$image_noise_color
			);
		}

		/* Create a text box and add 6 captcha letters code in it */
		$text_box = imagettfbbox(
			$captcha_font_size,
			0,
			$captcha_font,
			$captcha_code
		);
		$x = ($captcha_image_width - $text_box[4]) / 2;
		$y = ($captcha_image_height - $text_box[5]) / 2;
		imagettftext(
			$captcha_image,
			$captcha_font_size,
			0,
			$x,
			$y,
			$captcha_text_color,
			$captcha_font,
			$captcha_code
		);

		/* Show captcha image in the html page */
		// defining the image type to be shown in browser widow
		header('Content-Type: image/jpeg');
		imagejpeg($captcha_image); //showing the image
		imagedestroy($captcha_image); //destroying the image instance

		$capt = array(
			'captcha' => $captcha_code
		);
		$this->session->set_userdata($capt);
		session_write_close();
	}

	function captcha_hextorgb($hexstring)
	{
		$integar = hexdec($hexstring);
		return array(
			"red" => 0xFF & ($integar >> 0x10),
			"green" => 0xFF & ($integar >> 0x8),
			"blue" => 0xFF & $integar
		);
	}

}
