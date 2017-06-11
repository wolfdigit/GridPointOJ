<?php
$ci =&get_instance();
$ci->load->model('User');

$user_id = $ci->User->currentUserId();
?>
<style>
	nav {
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		margin: 0;
		padding: 3px 1em;
	}
</style>
<?php
$this->load->helper('url');
if ($user_id!=false) {
	$logoutUrl = site_url('User/logout');
?>
<nav style="background-color:#bdf">Hello, <?=$user_id?>. <a href="<?=$logoutUrl?>">登出</a></nav>
<?php
}
else {
	$loginUrl = site_url('User/login');
?>
<nav style="background-color:#eef"><a href="<?=$loginUrl?>">登入</a></nav>
<?php
}
