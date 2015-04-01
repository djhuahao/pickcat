<?php
$template = 'schema.html';

if($_view == 'edit'){
	$template = 'schema_edit.html';
	if($_POST['submit_schema_activate']){
		$_POST['status'] = 'on';
		$_POST['submit_schema'] = 1;
	}
	if($_POST['submit_schema']){
		if(is_numeric($_POST['schema_id'])){
			schema::update($_POST);
			$template = 'schema.html';
		}else{
			schema::add($_POST);
			$template = 'schema.html';
		}
	}
}elseif($_view == 'test'){
	$keywords = str_replace('^^',' ',$_REQUEST['keywords']);
	$schema = array('channel'=>$_REQUEST['channel'],'url'=>$_REQUEST['url'],'keywords'=>$keywords);
	$result = fetch_web(array((object)$schema),true);
	echo $result;
	exit;
}elseif($_view == 'del'){
    schema::delete($_GET);
}

include TEMPLATES_PATH.$template;
?>