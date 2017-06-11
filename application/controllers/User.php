<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	public function login() {
		$this->load->helper('url');
		redirect('../Theogony/loginpage.php');
	}

	public function logout() {
		session_start();
		unset($_SESSION['user_id']);
		session_destroy();
		$this->load->helper('url');
		redirect('');
	}
}
