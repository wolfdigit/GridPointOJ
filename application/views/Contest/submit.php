<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="zh_TW">
<head>
	<title>Submit Code</title>
	<meta charset="utf-8">
	<base href="<?=site_url('/')?>">
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
	</style>
</head>
<body>
<?php $this->load->view('Contest/header'); ?>
<a href="<?=$backUrl?>">back to problem statement</a>
<div id="container">
<center>
	<script language="Javascript" type="text/javascript" src="edit_area/edit_area_full.js"></script>
	<script language="Javascript" type="text/javascript">
		editAreaLoader.init({
			id: "source"            
			,start_highlight: true 
			,allow_resize: "both"
			,allow_toggle: true
			,word_wrap: true
			,language: "en"
			,syntax: "cpp"  
			,font_size: "8"
			,syntax_selection_allow: "basic,c,cpp,java,pas,perl,php,python,ruby"
			,toolbar: "search, go_to_line, fullscreen, |, undo, redo, |, select_font,syntax_selection,|, change_smooth_selection, highlight, reset_highlight, word_wrap, |, help"          
		});
	</script>
	<h2>Contest <?=$cid?> - <?=$cinfo->title?></h2>
	<h1><a href="<?=$backUrl?>">P<?=$pnum?> - <?=$prob->title?></a></h1>
	<form action="<?=$doSubmitUrl?>" method="post">
		<input type='hidden' value='<?=$cid?>' name="cid">
		<input type='hidden' value='<?=$pnum?>' name="pid">
		Language:
		<select id="language" name="language">
			<option value=0 >C</option>
			<option value=1 selected>C++</option>
		</select>
		<br>
		<textarea cols=80 rows=20 id="source" name="source"></textarea><br>
		
		<input type=submit value="Submit">
		<input type=reset value="Reset">
	</form>
</center>
</div><!--end main-->
</body>
</html>
