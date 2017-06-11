<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NeoContestSolution extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function rawRuns($cid, $user_id) {
		$res = $this->db->get_where('solution', array('contest_id'=>$cid, 'user_id'=>$user_id))->result();
		return $res;
	}

	function grpRuns($cid, $user_id) {
		$res = $this->db->order_by('num')->order_by('problem_id')->get_where('contest_neo', array('contest_id'=>$cid))->result();
		$numPids = array();
		$full = array();
		foreach ($res as $r) {
			if (!isset($numPids[$r->num])) $numPids[$r->num] = array();
			$numPids[$r->num][$r->problem_id] = array('percent'=>$r->percentage);
			if (!isset($full[$r->num])) $full[$r->num] = 0;
			$full[$r->num] += $r->percentage; 
		}

                $retv = array();        // (contest_id , user_id), num , in_date , problem_id=> percent, solution_id , result, ...
		foreach ($numPids as $num=>$dummy) {
			$retv[$num] = array();
		}

		$res = $this->db->join('contest_neo', 'contest_neo.contest_id=solution.contest_id AND contest_neo.problem_id=solution.problem_id')->get_where('solution', array('solution.contest_id'=>$cid, 'user_id'=>$user_id))->result();
                foreach ($res as $r) {
                        if (!isset($retv[$r->num][$r->in_date])) {
                                $retv[$r->num][$r->in_date] = $numPids[$r->num];
                        }
                        $retv[$r->num][$r->in_date][$r->problem_id]['run'] = $r; //array('solution_id'=>$r->solution_id, 'result'=>$r->result, 'detail'=>$run);
                }

                foreach ($retv as $num=>$retvnum) {
                        foreach ($retv[$num] as $in_date=>$prob) {
                                $sum = 0;
                                foreach ($retv[$num][$in_date] as $pid=>$run) {
                                        if (!isset($run['run'])) continue;
                                        if (!isset($retv[$num][$in_date]['firstSid'])) $retv[$num][$in_date]['firstSid'] = $run['run']->solution_id;
                                        if ($run['run']->result==4) $sum += $run['run']->percentage;
                                }
                                $retv[$num][$in_date]['sum'] = $sum;
                        }
                        krsort($retv[$num]);
                        $max = 0;
                        foreach ($retv[$num] as $in_date=>$runs) {
                                if ($runs['sum']>$max) $max = $runs['sum'];
                        }
                        $retv[$num]['max'] = $max;
			$retv[$num]['full'] = $full[$num];
                }

		//print_r($retv);
		return $retv;
	}

	function allContestants($cid) {
		$res = $this->db->select('users.*')->distinct()->join('users', 'users.user_id=solution.user_id')->get_where('solution', array('contest_id'=>$cid))->result();
		return $res;
	}

	function info($sid) {
		$res = $this->db->get_where('solution', array('solution_id'=>$sid))->result();
		return $res[0];
	}

	function ceinfo($sid) {
		$res = $this->db->get_where('compileinfo', array('solution_id'=>$sid))->result();
		return $res[0];
	}

	function reinfo($sid) {
		$res = $this->db->get_where('runtimeinfo', array('solution_id'=>$sid))->result();
		return $res[0];
	}

	function source($sid) {
		$res = $this->db->join('source_code', 'source_code.solution_id=solution.solution_id')->get_where('solution', array('solution.solution_id'=>$sid))->result();
		return $res[0];
	}
}
