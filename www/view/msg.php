<?php
if($_view == 'reactivate_schema'){
	schema::update(array('schema_id'=>$_GET['schema_id'],'status'=>'on'));
}

include TEMPLATES_PATH.'msg.html';
?>