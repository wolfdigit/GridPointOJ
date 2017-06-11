<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contest extends CI_Controller {

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
	public function index($cid='')
	{
		$this->load->model('NeoContest', 'Contest');
		$this->load->model('Problem');
		$this->load->helper('url');
		if (!$cid) {
			$res = $this->Contest->listContests();
			$this->load->view('Contest/index', array('contests'=>$res, 'contestUrl'=>site_url('Contest/index/')));
			return;
		}

		$info = $this->Contest->info($cid);
		//print_r($info);
		if (!$this->Contest->started($cid)) return $this->load->view('Contest/err', array('msg'=>'notStart'));

		$res = $this->Contest->notInvited($cid);
		if ($res>0) {
			if ($res==2) {
				return $this->load->view('Contest/err', array('msg'=>'login', 'actionUrl'=>site_url('User/login')));
			}
			if ($res==1) {
				return $this->load->view('Contest/err', array('msg'=>'invite'));
			}
			return;
		}

		$pids = $this->Contest->mainProbs($cid);
		$probs = $this->Problem->getProbs($pids);

		$this->load->model('User');
		$user_id = $this->User->currentUserId();
		if ($user_id) {
			$this->load->model('NeoContestSolution', 'Solution');
			$runs = $this->Solution->grpRuns($cid, $user_id);
			foreach ($probs as $pnum=>$p) {
				if (isset($runs[$pnum])) {
					$probs[$pnum]->max = $runs[$pnum]['max'];
					$probs[$pnum]->full = $runs[$pnum]['full'];
				}
			}
		}
		else {
			$percs = $this->Contest->subtaskPercents($cid);
			foreach ($probs as $pnum=>$p) {
				$full = 0;
				foreach ($percs[$pnum] as $perc) {
					$full += $perc;
				}
				$probs[$pnum]->full = $full;
				$probs[$pnum]->max = '-';
			}
		}

		$abc = range('A', 'Z');
		$data = array();
		foreach ($probs as $i=>$p) {
			$data[$abc[$i]] = array('title'=>$p->title, 'problem_id'=>$p->problem_id, 'max'=>(isset($p->max))?$p->max:'0', 'full'=>$p->full);
		}
		$this->load->view('Contest/contest_index', array('cid'=>$cid, 'title'=>$info->title, 'start_time'=>$info->start_time, 'end_time'=>$info->end_time, 'probs'=>$data, 'probUrl'=>site_url("Contest/problem/$cid/"), 'indexUrl'=>site_url('Contest')) );
	}

	public function problem($cid, $pnum) {
		$pnum = ord($pnum) - ord('A');
		$this->load->model('NeoContest', 'Contest');
		$this->load->model('Problem');
		$this->load->helper('url');

		if (!$this->Contest->started($cid)) return $this->load->view('Contest/err', array('msg'=>'notStart'));
		if ($this->Contest->notInvited($cid)>0) return $this->load->view('Contest/err', array('msg'=>'invite'));

		$pids = $this->Contest->mainProbs($cid);
		$pid = $pids[$pnum];

		$probs = $this->Problem->getProbs(array($pid));
		$prob = $probs[0];

		$percents = $this->Contest->subtaskPercents($cid);
		$percents = $percents[$pnum];

		$abc = range('A', 'Z');
		$pnum = $abc[$pnum];
		$this->load->view('Contest/problem', array('prob'=>$prob, 'pnum'=>$pnum, 'percents'=>$percents, 'backUrl'=>site_url('Contest/index/'.$cid), 'submitUrl'=>site_url('Contest/submit/'.$cid.'/'.$pnum), 'statusUrl'=>site_url('Contest/status/'.$cid.'/'.$pnum)));
	}

	public function submit($cid, $pnum) {
		$pnum = ord($pnum) - ord('A');
		$this->load->model('NeoContest', 'Contest');
		$this->load->model('Problem');
		$this->load->helper('url');
		$this->load->model('User');
		$user_id = $this->User->currentUserId();
		if ($user_id==FALSE) return $this->load->view('Contest/err', array('msg'=>'login', 'actionUrl'=>site_url('User/login')));

		if (!$this->Contest->started($cid)) return $this->load->view('Contest/err', array('msg'=>'notStart'));
		if ($this->Contest->notInvited($cid)>0) return $this->load->view('Contest/err', array('msg'=>'invite'));
		if ($this->Contest->ended($cid)) return $this->load->view('Contest/err', array('msg'=>'ended'));

		$cinfo = $this->Contest->info($cid);

		$pids = $this->Contest->mainProbs($cid);
		$pid = $pids[$pnum];

		$probs = $this->Problem->getProbs(array($pid));
		$prob = $probs[0];

		$abc = range('A', 'Z');
		$pnum = $abc[$pnum];
		$this->load->view('Contest/submit', array('pnum'=>$pnum, 'prob'=>$prob, 'cid'=>$cid, 'cinfo'=>$cinfo, 'backUrl'=>site_url('Contest/problem/'.$cid.'/'.$pnum), 'doSubmitUrl'=>site_url('Contest/doSubmit/'.$cid.'/'.$pnum)));
	}

	public function doSubmit($cid, $pnum) {
		$this->load->model('NeoContest', 'Contest');
		if (!$this->Contest->started($cid)) return $this->load->view('Contest/err', array('msg'=>'notStart'));
		if ($this->Contest->notInvited($cid)>0) return $this->load->view('Contest/err', array('msg'=>'invite'));
		if ($this->Contest->ended($cid)) return $this->load->view('Contest/err', array('msg'=>'ended'));

		chdir('/var/www/Theogony/');
		require_once('GridPointOJ_submit.php');
		$this->load->helper('url');
		//header("Location: /GridPointOJ/Contest/stauts/$cid");
		redirect('Contest/status/'.$cid.'/'.$pnum);
	}

	public function status($cid, $pnum=false) {
		$this->load->model('NeoContest', 'Contest');
		$this->load->model('NeoContestSolution', 'Solution');
		$this->load->model('User', 'User');
		$this->load->helper('url');
		if (!$this->Contest->started($cid)) return $this->load->view('Contest/err', array('msg'=>'notStart'));
		if ($this->Contest->notInvited($cid)>0) return $this->load->view('Contest/err', array('msg'=>'invite'));
		$user_id = $this->User->currentUserId();
		if ($user_id==FALSE) return $this->load->view('Contest/err', array('msg'=>'login', 'actionUrl'=>site_url('User/login')));
		if (isset($pnum)&&ctype_upper($pnum)) $pnum = ord($pnum) - ord('A');
		else                                  $pnum = -1;

		$abc = range('A', 'Z');

		$grpRuns = $this->Solution->grpRuns($cid, $user_id);
		if ($pnum>=0) {
			$nums = array_keys($grpRuns);
			foreach ($nums as $k) {
				if ($k==='max' || $k==='full') continue;
				if ($k!=$pnum) {
					$grpRuns[$k] = null;
					unset($grpRuns[$k]);
				}
			}
		}


		$this->load->model('Problem');
		$pids = $this->Contest->mainProbs($cid);
		$probs = $this->Problem->getProbs($pids);
		$pidToTitle = array();
		foreach ($probs as $p) {
			$pidToTitle[$p->problem_id] = $p->title;
		}

		$numToPids = $this->Contest->mainProbs($cid);
		foreach ($grpRuns as $num=>$r) {
			$grpRuns[$num]['title'] = $pidToTitle[$numToPids[$num]];
		}

		$cinfo = $this->Contest->info($cid);

		if ($pnum>=0) {
			$backUrl = site_url('Contest/problem/'.$cid.'/'.$abc[$pnum]);
		}
		else {
			$backUrl = site_url('Contest/problem/'.$cid);
		}
		$this->load->view('Contest/status', array('data'=>$grpRuns, 'info'=>$cinfo, 'backUrl'=>$backUrl, 'sInfoUrl'=>site_url('Contest/resultInfo/'), 'sourceUrl'=>site_url('Contest/showSource/')));
	}

	public function resultInfo($sid=null) {
		if (!$sid) return show_404();
		$this->load->model('User');
		$user_id = $this->User->currentUserId();
		$this->load->helper('url');
		if (!$user_id) redirect('User/login');
		$this->load->model('NeoContestSolution', 'Solution');
		$result = $this->Solution->info($sid);
		if ($result->user_id!=$user_id) return show_404();
		$this->load->model('NeoContest', 'Contest');
		$probInfo = $this->Contest->rawProbsInfo($result->contest_id);
		$probInfo = $probInfo[$result->problem_id];
		//print_r($probInfo);
		$title = $probInfo->title;
		if ($result->result==11) {
			$info = $this->Solution->ceinfo($sid);
			//print_r($info);
			$this->load->view('Contest/resultInfo', array('info'=>$info, 'title'=>$title, 'msg'=>'編譯錯誤'));
		}
		else if ($result->result==10) {
			$info = $this->Solution->reinfo($sid);
			//print_r($info);
			$this->load->view('Contest/resultInfo', array('info'=>$info, 'title'=>$title, 'msg'=>'執行錯誤'));
		}
		else {
			show_404();
		}
		
	}

	
	public function showSource($sid=null) {
		if (!$sid) return show_404();
		$this->load->model('User');
		$user_id = $this->User->currentUserId();
		$this->load->helper('url');
		if (!$user_id) redirect('User/login');
		$this->load->model('NeoContestSolution', 'Solution');
		$result = $this->Solution->source($sid);
		if ($result->user_id!=$user_id) return show_404();
		//print_r($result);
		$this->load->view('Contest/showSource', array('source'=>$result->source, 'solution_id'=>$result->solution_id));
	}
}
