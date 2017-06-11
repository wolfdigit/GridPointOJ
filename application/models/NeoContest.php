<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NeoContest extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function listContests() {
		$res = $this->db->select('contest.*')->distinct()->order_by('end_time', 'desc')->from('contest')->join('contest_neo', 'contest_neo.contest_id=contest.contest_id')->where('contest.defunct', 'N')->get()->result();
		foreach ($res as $i=>$c) {
			$now = time();
			if ($now<strtotime($res[$i]->start_time)) {
				$res[$i]->state = 'future';
			}
			else {
				if ($now<=strtotime($res[$i]->end_time)) {
					$res[$i]->state = 'now';
				}
				else {
					$res[$i]->state = 'past';
				}
			}
		}
		return $res;
	}
/*
	function rawPids($cid) {
		$retv = array();
		$res = $this->db->select('problem_id')->order_by('num')->get_where('contest_problem', array('contest_id'=>$cid))->result();
		foreach ($res as $p) {
			$retv[] = $p->problem_id;
		}
		return $retv;
	}
*/
	function rawContests() {
		$res = $this->db->order_by('end_time', 'desc')->from('contest')->get()->result();
		return $res;
	}

	function rawProbsInfo($cid) {
		$res = $this->db->select('problem.*')->join('problem', 'problem.problem_id=contest_problem.problem_id')->order_by('num')->get_where('contest_problem', array('contest_id'=>$cid))->result();
		$retv = array();
		foreach ($res as $p) {
			$retv[$p->problem_id] = $p;
		}
		return $retv;
	}

	function neoProbs($cid) {
		$neoInfos = $this->db->order_by('num')->order_by('problem_id')->get_where('contest_neo', array('contest_id'=>$cid))->result();
		return $neoInfos;
	}

	function mainProbs($cid) {
		$neoInfos = $this->db->order_by('num')->order_by('problem_id', 'desc')->get_where('contest_neo', array('contest_id'=>$cid))->result();
		$retv = array();
		foreach ($neoInfos as $info) {
			$retv[$info->num] = $info->problem_id;
		}
		return $retv;
	}

	function info($cid) {
		$res = $this->db->get_where('contest', array('contest_id'=>$cid))->result();
		return $res[0];
	}

	function subtaskPercents($cid) {
		$neoInfos = $this->db->order_by('num')->order_by('problem_id')->get_where('contest_neo', array('contest_id'=>$cid))->result();
		$retv = array();
		foreach ($neoInfos as $info) {
			if (!array_key_exists($info->num, $retv)) $retv[$info->num] = array();
			$retv[$info->num][$info->problem_id] = $info->percentage;
		}
		return $retv;
	}

	function started($cid) {
		$res = $this->db->get_where('contest', array('contest_id'=>$cid))->result();
		$info = $res[0];

                return time()>=strtotime($info->start_time);
	}

	function ended($cid) {
		$res = $this->db->get_where('contest', array('contest_id'=>$cid))->result();
		$info = $res[0];

                return time()>=strtotime($info->end_time);
	}

	function notInvited($cid) {	// 0: public, 1: not invited, 2: not logined
		$res = $this->db->get_where('contest', array('contest_id'=>$cid))->result();
		$info = $res[0];
                if ($info->private) {
                        $this->load->model('User');
                        $user_id = $this->User->currentUserId();
                        if (!$user_id) return 2;
                        if (!$this->User->currentUserInContest($cid)) return 1;
                }
		return 0;
	}

	function pidToPnum($cid, $pid) {
		$res = $this->db->get_where('contest_neo', array('contest_id'=>$cid, 'problem_id'=>$pid))->result();
		if (isset($res[0])) return $res[0]->num;
		else                return -1;
	}
}
