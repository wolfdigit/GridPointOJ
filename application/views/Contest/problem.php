<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="zh_TW">
<head>
	<meta charset="utf-8">
	<title>P<?=$pnum?> - <?=$prob->title?></title>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 16px/26px normal Helvetica, Arial, sans-serif;
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

	h2 {
	    background-color: #EEE;
	    padding: 10px 5px 5px 5px;
	    border-radius: 5px 5px 0 0;
	}

	pre {
		background-color: #222;
		color: #6F6;
	}
	</style>
</head>
<body>
<?php $this->load->view('Contest/header'); ?>
<a href="<?=$backUrl?>">back to problem list</a>
<div id="container">
	<h1>P<?=$pnum?> - <?=$prob->title?></h1>
	<div id="body">
	<div style="float:right; border:1px solid #888; border-radius:5px; padding:5px; background-color:white">
	<p style="margin-top:6px"><a href="<?=$statusUrl?>">解題狀態</a></p>
	<form action="<?=$submitUrl?>">
	<p style="margin-bottom:10px">
	<button>送答案</button>
	</p>
	</form>
	</div>
	<p>limit: <?=$prob->time_limit?>sec , <?=$prob->memory_limit?>MB</p>

	<h2>題目敘述：</h2>
	<p><?=$prob->description?></p>
	<h2>輸入格式：</h2>
	<p><?=$prob->input?></p>
	<h2>輸出格式：</h2>
	<p><?=$prob->output?></p>
	<h2>範例輸入：</h2>
	<pre><?=$prob->sample_input?></pre>
	<h2>範例輸出：</h2>
	<pre><?=$prob->sample_output?></pre>
	<h2>範例說明：</h2>
	<?=$prob->hint?>
	<h2>子任務配分：</h2>
	<?php foreach ($percents as $tid=>$perc) { ?>
	<p><?=$tid?>: <?=$perc?>%</p>
	<?php } ?>
	<h2>題目來源：</h2>
	<p><?=$prob->source?></p>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>

</body>
</html>
