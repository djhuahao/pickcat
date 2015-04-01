<?php
require('config.php');
require(INCLUDES_PATH.'functions.php');

$_db = new db();
$_cache = new cache();
$_user;

function cron($start=0,$limit=70,$schema_id=0){
	global $_db;
	$where = "WHERE `status` = 'on'";
	if($schema_id > 0){
		$where .= " AND schema_id = $schema_id";
		$start = 0;
		$limit = 1;
	}
	$sql = "SELECT * FROM `schemas` $where LIMIT $start,$limit";
	$schemas = $_db->get_results($sql);
	fetch_web($schemas);
    return count($schemas);
}

if(is_numeric($_REQUEST['start'])){
	echo cron($_REQUEST['start'],$_REQUEST['limit']);
}elseif(empty($_REQUEST['start'])){
	$total = $_db->get_var("SELECT count(*) FROM `schemas` WHERE `status` = 'on'");
	$queue = new SaeTaskQueue("cron_tail");
	$array = array();
	for($i=0;$i<=$total+70;$i+=70){
		$array[] = array('url'=>"http://pickcat.sinaapp.com/cron.php", "postdata"=>"start=$i&limit=70");
	}
    $queue->addTask($array);
	$ret = $queue->push();
	if ($ret === false)var_dump($queue->errno(), $queue->errmsg());
	else echo 'true';
}

?>