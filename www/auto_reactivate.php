<?php
require('./config.php');
require(INCLUDES_PATH.'functions.php');
$_db = new db();
$_db->update('schemas',array('status'=>'on'),array('status'=>'off','auto_reactivate'=>'on'));
?>