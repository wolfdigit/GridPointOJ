<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="zh_TW">
<head>
	<meta charset="utf-8">
	<title>GridPointOJ - <?=$info->contest_id?></title>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
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
	</style>
</head>
<body>
<?php $this->load->view('Contest/header'); ?>
<a href="<?=$backUrl?>">back</a>
<div id="container">
	<h1><?=$info->contest_id?> - <?=$info->title?></h1>

	<div id="body">
<form action="<?=$actionUrl?>" method="POST">
<table>
<tr><th>pid</th><th>title</th><th>題組編號</th><th>佔分%</th></tr>
<?php
foreach ($probs as $pb) {
	$p = $pb['prob'];
	if (array_key_exists('neo', $pb)) {
		$n = $pb['neo'];
	}
	else {
		$n = (object)array('num'=>-1, 'percentage'=>0);
	}?>
	
	<tr>
		<th><?=$p->problem_id?></th>
		<td><a href="<?=$probUrl.$p->problem_id?>"><?=$p->title?></a></td>
		<td><input type="number" name="num<?=$p->problem_id?>" value="<?=$n->num?>"></input></td>
		<td><input type="number" name="perc<?=$p->problem_id?>" value="<?=$n->percentage?>"></input></td>
	</tr>
<?php } ?>
</table>
<button type="submit">submit</button>
<button type="reset">reset</button>
</form>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>

</body>
</html>
