<?php
require('config.php');
require(INCLUDES_PATH.'functions.php');

$_db = new db();
$_cache = new cache();
$_user = user::login_by_cookie();
$_module = $_GET['module'];
$_view = $_GET['view'];

if(!$_user){
	if(isset($_GET['weibo_login'])){
		$res = login_by_weibo();
	}elseif(isset($_GET['qq_login'])){
		$res = login_by_qq();
	}elseif($_POST['submit_login']){
		$_user = user::login($_POST);
	}elseif($_GET['app']){
		if($_GET['baidu_uid']){
			set_cookie('baidu_uid',$_GET['baidu_uid']);
		}
	}
	
	$baidu_uid = $_COOKIE['baidu_uid'];

	//如果是新注册用户
	if($res == 'new_user'){
		$_module = 'user';
		$_view = 'user_register';
	}elseif($_user && $baidu_uid && $_user->baidu_uid != $baidu_uid){
		$_user->baidu_uid = $baidu_uid;
		user::update(array('baidu_uid'=>$baidu_uid));
		delete_cookie('baidu_uid');
	}elseif(!$_user && $_module != 'article' && $_view != 'weibo_login' && $_view != 'qq_login'){
	//登录失败，而且不是第三方请求
		include TEMPLATES_PATH."index.html";
		exit;
	}
}

if(!$_module) $_module = 'msg';
if(!is_file(VIEW_PATH."$_module.php")){
	$_module = 'article';
	$_view = '404';
}
include VIEW_PATH."$_module.php";
?>