<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="zh_TW">
<head>
	<meta charset="utf-8">
	<title>GridPointOJ - ranking</title>

</head>
<body>
<?php $this->load->view('Contest/header'); ?>
<style>
body { margin:0; }
div.event {
	position: absolute;
	text-align: right;
	line-height: 1.2ex;
}
div.event a {
	text-decoration: none;
}
</style>
<div id="legend" style="position:fixed; height:20px; top:20px; left:10em; z-index:100; background-color:white; border-radius:5px; border:1px solid black; padding:4px"><?=$cinfo->title?>: </div>
<div id="listHead" style="position:fixed; width:10em; height:720px; top:50px; left:0; z-index:99; background-color:#CCF"></div>
<script>
window.addEventListener("scroll", function() {
	document.getElementById("listHead").style.top = -window.scrollY + 50 + "px";
});
</script>
<div id="canvas" style="position:relative; width:1280px; height:720px; top:50px; left:10em; background-color:#EEE">
</div>
<script>
var probColors = {};
const colors = ["#888", "#d55d5d", "#F88", "#FFC966", "#FF8", "#8F8", "#88F", "#F8F", "#CCC", "#FFF"];
var data = <?=json_encode($solutions)?>;
for (var i=0; i<data.length; i++) {
	data[i].in_date = new Date(data[i].in_date);
	data[i].result = parseInt(data[i].result);

	probColors[data[i].problem_id] = colors[Math.floor(data[i].problem_id/3)%10];
}

var users = <?=json_encode($users)?>;
var userOrder = {};
for (var i=0; i<users.length; i++) {
	userOrder[users[i].user_id] = i;
}

/*
var minTime = new Date();
var maxTime = new Date('2000/1/1');
for (var i=0; i<data.length; i++) {
	//console.log(data[i].in_date);
	if (data[i].in_date<minTime) minTime = data[i].in_date;
	if (data[i].in_date>maxTime) maxTime = data[i].in_date;
}
*/
var minTime = new Date('<?=$cinfo->start_time?>');
var maxTime = new Date('<?=$cinfo->end_time?>');
//console.log(minTime);
//console.log(maxTime);
var timeLength = maxTime - minTime;
document.getElementById("canvas").style.width = timeLength/1000/60 +1 + "em";


function getX(inDate) {
	return (inDate-minTime) / 1000 / 60 + "em";
}
function getY(uid, pid=0) {
	return (userOrder[uid]+0.5)/users.length * 720 + pid%3*10 + "px";
}

const judge_class = ["PD", "PD", "ING", "ING", "AC", "PE", "WA", "TLE", "MLE", "OLE", "RE", "CE", "ING", "XD"];
function resultToClass(result) {
	return judge_class[result];
}
const judge_txt = ["D", "D", "I", "I", "A", "P", "W", "T", "M", "O", "R", "C", "I", "X"];
function resultToTxt(result) {
	return judge_txt[result];
}

for (var i=data.length-1; i>=0; i--) {
	var d = data[i];
	var x = getX(d.in_date);
	var y = getY(d.user_id, d.problem_id);
	var newTag = document.createElement("div");
	newTag.className = "event "+resultToClass(d.result);
	newTag.style.top = y;
	newTag.style.left = 0;
	newTag.style.width = x;
	//newTag.textContent = resultToTxt(d.result);
	newTag.style.backgroundColor = probColors[d.problem_id];
	newTag.innerHTML = '<a href="<?=$sourceUrl?>'+d.solution_id+'">'+resultToTxt(d.result)+'</a>';
	document.getElementById("canvas").appendChild(newTag);
}

for (var i=0; i<users.length; i++) {
	var newTag = document.createElement("div");
	newTag.className = "event";
	newTag.innerHTML = users[i].nick;
	newTag.style.top = getY(users[i].user_id);
	document.getElementById("listHead").appendChild(newTag);
}

var pids = Object.keys(probColors);
for (var i=0; i<pids.length; i++) {
	var newTag = document.createElement("span");
	newTag.innerHTML = '<a href="<?=$probUrl?>'+pids[i]+'">'+pids[i]+'</a> &nbsp; ';
	newTag.style.backgroundColor = probColors[pids[i]];
	document.getElementById("legend").appendChild(newTag);
}
</script>
</body>
</html>
