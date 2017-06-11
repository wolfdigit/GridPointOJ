<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="zh_TW">
<head>
	<meta charset="utf-8">
	<meta http-equiv='refresh' content='300'>
	<title>GridPointOJ - <?=$cid?></title>

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

	td.full {
		color: LimeGreen;
	}
	td.zero {
		color: red;
	}

	span.total {
		font-size: large;
	}
	#countDown.final {
		color: red;
	}
	</style>
</head>
<body>
<?php $this->load->view('Contest/header'); ?>
<a href="<?=$indexUrl?>">back to contests list</a>
<div id="container">
	<h1><?=$title?></h1>

	<div id="body">
	<p>競賽時間：<span><?=$start_time?> ~ <?=$end_time?></span></p>
	<p>現在時間: <span id="nowdate">______</span>  <span style="font-size:larger; margin-left:3em">剩下：<span id="countDown">(這邊要加個倒數計時)</span></span></p>
<table>
<tr><th>得分</th><th>題號</th><th>標題</th></tr>
<?php
$sum = 0;
$fullsum = 0;
foreach ($probs as $num => $prob) {
	if ($prob['max']==$prob['full']) $state = 'full';
	else if ($prob['max']==0)        $state = 'zero';
	else                             $state = '';
	$sum += $prob['max'];
	$fullsum += $prob['full'];
?>
	<tr><td class="<?=$state?>"><?=$prob['max']?>/<?=$prob['full']?></td><th>P<?=$num?></th><td><a href="<?=$probUrl.$num?>"><?=$prob['title']?></a></td></tr>
<?php } ?>
</table>
總分： <span class="total"><?=$sum?></span> / <?=$fullsum?>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>

<script>
var user_end_time = new Date('<?=$end_time?>').getTime();
var diff=new Date("<?php echo date("Y/m/d H:i:s")?>").getTime()-new Date().getTime();
//alert(diff);
function clock()
    {
      var x,h,m,s,n,xingqi,y,mon,d;
      var x = new Date(new Date().getTime()+diff);
      y = x.getYear()+1900;
      if (y>3000) y-=1900;
      mon = x.getMonth()+1;
      d = x.getDate();
      xingqi = x.getDay();
      h=x.getHours();
      m=x.getMinutes();
      s=x.getSeconds();
  
      n=y+"-"+mon+"-"+d+" "+(h>=10?h:"0"+h)+":"+(m>=10?m:"0"+m)+":"+(s>=10?s:"0"+s);
      //alert(n);
      if (document.getElementById('nowdate')!=null) document.getElementById('nowdate').innerHTML=n;

      var count_down = Math.floor((user_end_time-x.getTime())/1000) ;
      if(count_down<0) count_down = 0 ;
      h = Math.floor(count_down/60/60) ;
      m = Math.floor(count_down/60)%60 ;
      s = count_down%60 ;
      n = h+":"+(m>=10?m:"0"+m)+":"+(s>=10?s:"0"+s);
      if(document.getElementById('countDown')!=null) {
        document.getElementById('countDown').innerHTML=n ;
        if (h<=0) document.getElementById('countDown').className = 'final';
      }
    } 
    setInterval(clock, 300);
</script>

</body>
</html>
