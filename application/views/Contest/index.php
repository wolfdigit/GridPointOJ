<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="zh_TW">
<head>
	<meta charset="utf-8">
	<title>GridPointOJ - Contests</title>

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

	td.now {
		color: red;
		font-weight: bold;
	}

	td.future {
		color: green;
		font-weight: bold;
	}
	</style>
</head>
<body>
<?php $this->load->view('Contest/header'); ?>
<div id="container">
	<h1>最新最潮der競賽系統唷~</h1>

	<div id="body">
<table>
<?php
foreach ($contests as $c) {
	$linkTag = '<a href="'.$contestUrl.$c->contest_id.'">';
	if ($c->state=='future') $linkTag = false;
	$privTag = '<small>(需事先核可)</small>';
	if ($c->private!==1) '';
?>
	<tr>
		<th><?=$c->contest_id?></th>
		<td><?=$linkTag?><?=$c->title?><?=($linkTag!==false)?'</a>':''?><?=($c->private)?'<small>(需事先核可)</small>':''?></td>
		<td class="<?=$c->state?>"><?=$c->start_time?> ~ <?=$c->end_time?></td>
	</tr>
<?php } ?>
</table>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>

</body>
</html>
