<?php
$template = 'user_edit.html';

if($_view == 'logout'){
	user::logout();
	$template = 'index.html';
}elseif($_view == 'user_register'){
	$template = 'user_register.html';
}elseif($_view == 'edit'){
	$template = 'user_edit.html';
}elseif($_view == 'password_edit'){
	$template = 'user_edit_password.html';
}elseif($_view == 'activate_email'){
	user::activate_email($_GET['key']);
	$_notice = array('title'=>'邮箱激活成功','content'=>'现在可以设置一个抓取计划或者复制别人的抓取计划','button'=>'点击这里开始','url'=>'/');
	$template = 'notice.html';
}elseif($_view=='weibo_login'){
	include('saetv2.ex.class.php');
	$sae_oauth = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
	$login_url = $sae_oauth->getAuthorizeURL( WB_CALLBACK_URL );
	header("Location:$login_url");
	exit;
}elseif($_view=='qq_login'){
	include(INCLUDES_PATH.'qqapi/qqConnectAPI.php');
	$qc = new QC();
	$qc->qq_login();
	exit;
}

if(isset($_POST['email']) || isset($_POST['password'])){
	if(isset($_POST['email'])){
		if(!$_POST['email_notify']) $_POST['email_notify'] = 'off';
	}
	if(isset($_POST['app_notify_pin'])){
		if(!$_POST['app_notify']) $_POST['app_notify'] = 'off';
	}
	
	$result = user::update($_POST);
	
	switch($result){
	case 'email':
		$_notice = array('title'=>'您已申请修改邮箱','content'=>'请尽快进入您的邮箱激活','button'=>'点击这里回首页','url'=>'/');
		break;
	case 'email_exist':
		$_notice = array('title'=>'邮箱已存在','content'=>'请您重新设置帐户信息','button'=>'返回我的帐户','url'=>'/user/edit');
		break;
	case false:
		$_notice = array('title'=>'您填写的信息有误','content'=>'请重新填写','button'=>'返回我的帐户','url'=>'/user/edit');
		break;
	default:
		$_notice = array('title'=>'您的账户信息已经更新','content'=>'','button'=>'点击这里回首页','url'=>'/');
		break;
	}
	$template = 'notice.html';
}

include TEMPLATES_PATH.$template;
?>