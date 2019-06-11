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

		$this->load->view('ContestAdmin/ranking', array('data'=>$retv, 'cid'=>$cid));
	}

	public function visualize($cid) {
		$cinfo = $this->db->get_where('contest', array('contest_id'=>$cid))->result();
		//print_r($cinfo);
		$cinfo = $cinfo[0];
		$solutions = $this->db->get_where('solution', array('contest_id'=>$cid))->result();
		usort($solutions, "cmp_time");
		//echo json_encode($solutions);
		$this->load->model('NeoContestSolution', 'Solution');
		$users_raw = $this->Solution->allContestants($cid);
		$users = array();
		foreach ($users_raw as $u) {
			$users[] = (object)array('user_id'=>$u->user_id, 'email'=>$u->email, 'nick'=>$u->nick, 'school'=>$u->school);
		}
		//echo json_encode($users);
		$this->load->helper('url');
		$this->load->view('ContestAdmin/visualize', array('solutions'=>$solutions, 'users'=>$users, 'sourceUrl'=>site_url('ContestAdmin/showSource/'), 'probUrl'=>'http://oj.lssh.tp.edu.tw/Theogony/problem.php?id=', 'cinfo'=>$cinfo));
	}

	public function showSource($sid=null) {
		if (!$sid) return show_404();
		$this->load->model('NeoContestSolution', 'Solution');
		$result = $this->Solution->source($sid);
		//print_r($result);
		$this->load->view('Contest/showSource', array('source'=>$result->source, 'solution_id'=>$result->solution_id));
	}
}
	function cmp(&$a, &$b) {
		$x = $a['total'];
		$y = $b['total'];
		if ($x>$y) return -1;
		if ($x<$y) return 1;
		else       return 0;
	}

	function cmp_time(&$a, &$b) {
		if ($a->in_date < $b->in_date) return -1;
		if ($a->in_date > $b->in_date) return 1;
		if ($a->problem_id < $b->problem_id) return -1;
		if ($a->problem_id > $b->problem_id) return 1;
		return 0;
	}
