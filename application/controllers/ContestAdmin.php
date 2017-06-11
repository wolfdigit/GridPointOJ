<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ContestAdmin extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct() {
		parent::__construct();
		$this->load->model('User');
		if (!$this->User->isAdmin()) {
			$this->load->helper('url');
			return redirect('User/login');
		}
	}

	public function index()
	{
		$this->load->model('NeoContest', 'Contest');
		$this->load->helper('url');
		$neos = $this->Contest->listContests();
		$raws = $this->Contest->rawContests();

		$neoIds = array();
		foreach ($neos as $n) {
			$neoIds[] = $n->contest_id;
		}

		$oldContests = array();
		foreach ($raws as $r) {
			if (!in_array($r->contest_id, $neoIds)) {
				$oldContests[] = $r;
			}
		}
		$this->load->view('ContestAdmin/index', array('contests'=>$neos, 'oldContests'=>$oldContests, 'editUrl'=>site_url('ContestAdmin/edit/'), 'rankUrl'=>site_url('ContestAdmin/ranking/')));
	}

	public function edit($cid) {
		$this->load->model('NeoContest', 'Contest');
		$rawProbs = $this->Contest->rawProbsInfo($cid);
		//print_r($rawProbs);
		$neoProbs = $this->Contest->neoProbs($cid);
		//print_r($neoProbs);
		$retv = array();
		foreach ($neoProbs as $np) {
			$p = array();
			$p['neo'] = $np;
			$p['prob'] = $rawProbs[$np->problem_id];
			unset($rawProbs[$np->problem_id]);
			$retv[] = $p;
		}
		foreach ($rawProbs as $rp) {
			$p = array();
			$p['prob'] = $rp;
			$retv[] = $p;
		}
		//print_r($retv);

		$info = $this->Contest->info($cid);

		$this->load->helper('url');
		$this->load->view('ContestAdmin/edit', array('probs'=>$retv, 'info'=>$info, 'actionUrl'=>site_url('ContestAdmin/actionEdit/'.$cid), 'backUrl'=>site_url('ContestAdmin/index'), 'probUrl'=>'http://oj.lssh.tp.edu.tw/Theogony/problem.php?id=' ));
	}

	public function actionEdit($cid) {
		$keys = array_keys($_POST);
		$data = array();
		foreach ($keys as $key) {
			if (substr($key, 0, 3)==="num") {
				$pid = intval(substr($key, 3));
				$num = intval($this->input->post($key));
				if (!array_key_exists($pid, $data)) $data[$pid] = array();
				$data[$pid]['num'] = $num;
			}
			if (substr($key, 0, 4)==="perc") {
				$pid = intval(substr($key, 4));
				$perc = intval($this->input->post($key));
				if (!array_key_exists($pid, $data)) $data[$pid] = array();
				$data[$pid]['percentage'] = $perc;
			}
		}

		$res = array();
		foreach ($data as $pid=>$d) {
			if ($d['num']>=0)
				$res[] = array('contest_id'=>$cid, 'problem_id'=>$pid, 'num'=>$d['num'], 'percentage'=>$d['percentage']);
		}
		$this->db->delete('contest_neo', array('contest_id'=>$cid));
		if (count($res)>0)
			$this->db->insert_batch('contest_neo', $res);

		$this->load->helper('url');
		redirect(site_url('ContestAdmin/index'));
	}

	function cmp($a, $b) {
		$x = $a['total'];
		$y = $b['total'];
		if ($x>$y) return -1;
		if ($x<$y) return -1;
		else       return 0;
	}

	public function ranking($cid) {
		$this->load->model('NeoContestSolution', 'Solution');


		$retv = array();
		$users = $this->Solution->allContestants($cid);
		//print_r($users);
		foreach ($users as $u) {
			$retv[$u->user_id] = array('total'=>0, 'nick'=>$u->nick);
			$grpRuns = $this->Solution->grpRuns($cid, $u->user_id);
			//print_r($grpRuns);
			foreach ($grpRuns as $pnum=>$subs) {
				//echo $u->user_id." p$pnum: ".$subs['max']."<br>";
				$retv[$u->user_id][$pnum] = $subs['max'];
				$retv[$u->user_id]['total'] += $subs['max'];
			}
		}

		uasort($retv, "cmp");

		$this->load->view('ContestAdmin/ranking', array('data'=>$retv));
	}
}
	function cmp($a, $b) {
		$x = $a['total'];
		$y = $b['total'];
		if ($x>$y) return -1;
		if ($x<$y) return 1;
		else       return 0;
	}

