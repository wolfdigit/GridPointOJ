<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Problem extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function getProbs($pids) {
		$res = $this->db->where_in('problem_id', $pids)->get('problem')->result();
		$retv = $pids;
		foreach ($pids as $num=>$pid) {
			foreach ($res as $p) {	
				if ($p->problem_id==$pid) {
					$retv[$num] = $p;
					break;
				}
			}
		}
		return $retv;
	}
}
