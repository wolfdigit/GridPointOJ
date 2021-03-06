<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(__DIR__.'/../judgeResult.inc.php');
?><!DOCTYPE html>
<html lang="zh_TW">
<head>
	<meta charset="utf-8">
	<meta http-equiv='refresh' content='60'>
	<title>GridPointOJ - <?=$info->title?></title>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 14px/22px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}

	tr:nth-child(odd) {
		background-color: #EEE;
	}

	span.result, span.score {
		font-weight: bold;
		font-size: larger;
	}

	span.PD { color: gray }
	span.ING { color: orange }
	span.AC { color: green }
	span.PR, span.WA, span.TLE, span.MLE, span.OLE, span.RE, span.CE { color: red }
	span.XD { color: navy }
	</style>
</head>
<body>
<?php $this->load->view('Contest/header'); ?>
<a href="<?=$backUrl?>">back to problem statement</a>
<div id="container">
	<h1><?=$info->contest_id?> - <?=$info->title?></h1>

	<div id="body">
<?php //print_r($data) ?>
<?php foreach ($data as $num=>$pr) { ?>
		P<?=$PNUM[$num]?> - <?=$pr['title']?>: <span class="score">(<?=$pr['max']?>/<?=$pr['full']?>分)</span>
<?php 	unset($pr['max']); ?>
		<table>
			<tr><th>上傳時間</th><th>得分</th><th>程式碼</th><th>詳細資料</th></tr>
<?php 	foreach ($pr as $in_date=>$sr) { ?>
<?php 		if ($in_date=='max' || $in_date=='full' || $in_date=='title') continue; ?>
			<tr>
				<td><?=$in_date?></td>
				<td><span class="score"><?=$sr['sum']?></span></td>
				<td><a href="<?=$sourceUrl.$sr['firstSid']?>">source</a></td>
				<td>
<?php 		$case = 0;
		foreach ($sr as $pid=>$r) {
			if ($pid=='sum'||$pid=='firstSid') continue;
			$case++;
			$infoLinkTag = '';
			if (isset($r['run'])) {
				if ($r['run']->result==11) $infoLinkTag = '<a href="'.$sInfoUrl.$r['run']->solution_id.'">';
				if ($r['run']->result==10) $infoLinkTag = '<a href="'.$sInfoUrl.$r['run']->solution_id.'">';
			}
?>
					<p>
					<!--<span>小題號: <?=$pid?> (<?=$r['percent']?>%)</span>--><span>第<?=$case?>筆測資(<?=$r['percent']?>%): </span>
<?php 			if (isset($r['run'])) { ?>
					<?=$infoLinkTag?><span class="result <?=$judge_class[$r['run']->result]?>"><?=$judge_result[$r['run']->result]?></span><?=($infoLinkTag)?'</a>':''?>
					<!--<span>(#<?=$r['run']->solution_id?>)</span>-->
					<small>耗時：</small><?=$r['run']->time?> <small>記憶體：</small><?=$r['run']->memory?>
<?php 			} ?>
					</p>
<?php 		} ?>
				</td>
			</tr>
<?php 	} ?>
		</table>
<?php } ?>
		<p>
			<span class="PD">Pending</span>: 等待評測
			<span class="AC">AC</span>: Accepted 通過
			<span class="CE">CE</span>: Compile Error 編譯錯誤
			<span class="RE">RE</span>: Runtime Error 執行錯誤
			<span class="WA">WA</span>: Wrong Answer 答案錯誤
			<span class="TLE">TLE</span>: Time Limit Exceeded 時間超限
			<span class="MLE">MLE</span>: Memory Limit Exceeded 記憶體超限
			<span class="OLE">OLE</span>: Output Limit Exceeded 輸出超限
		</p>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>

</body>
</html>
