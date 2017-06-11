<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function currentUserId() {
		if(!isset($_SESSION)){session_start();}
		if (isset($_SESSION['user_id'])) return $_SESSION['user_id'];
		else                             return FALSE;
	}

	function currentUserInContest($cid) {
		if(!isset($_SESSION)){session_start();}
		if (isset($_SESSION['c'.$cid])) return $_SESSION['c'.$cid];
		return 0;
	}

	function isAdmin() {
		if(!isset($_SESSION)){session_start();}
		if ($_SESSION['administrator']==1) return true;
		else                               return false;
	}
}
