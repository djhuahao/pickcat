<?php
//http://www.juhe.cn/docs/api/id/43/aid/103
$a = array('聚合'=>array(
	'顺丰'=>'sf',
	'申通'=>'sto',
	'圆通'=>'yt',
	'韵达'=>'yd',
	'天天'=>'tt',
	'EMS'=>'ems',
	'中通'=>'zto',
	'汇通'=>'ht'
));
file_put_contents('channel_kuaidi.json',json_encode($a));
?>