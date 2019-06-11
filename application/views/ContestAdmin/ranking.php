<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="zh_TW">
<head>
	<meta charset="utf-8">
	<title>GridPointOJ - ranking</title>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 16px/22px normal Helvetica, Arial, sans-serif;
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

	td.total {
		font-size: large;
	}

	tr:nth-child(odd) {
		background-color: #EEE;
	}

	td, th {
		padding: 5px;
	}
	</style>
</head>
<body>
<?php $this->load->view('Contest/header'); ?>
<div id="container">
	<h1>ranking</h1>

	<div id="body">
		<a href="<?=site_url('ContestAdmin/visualize/'.$cid)?>">timeline visualize</a>
		<table>
			<tr><th>ID</th><th>Nick</th><th>total</th><?php
				$abc = range('A','Z');
				if (count($data)>0) {
					foreach (current($data) as $pnum=>$score) {
						if ($pnum==='total'||$pnum==='nick') continue;
						echo "<td>P".$abc[$pnum]."</td>";
					}
				}
			?></tr>
<?php foreach ($data as $user=>$scores) {
	$total = $scores['total'];
	$nick = $scores['nick']; ?>
			<tr>
			<th><?=$user?></th><td><?=$nick?></td><td class="total"><?=$scores['total']?></td>
<?php	foreach ($scores as $pnum=>$score) {
		if ($pnum==='total'||$pnum==='nick') continue;
		echo "<td>$score</td>";
	} ?>
			</tr>
<?php } ?>
		</table>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>

</body>
</html>

