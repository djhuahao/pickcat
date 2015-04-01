<?php
$template = 'faq.html';
if($_view == 'app'){
	$template = 'app.html';
}else if($_view == '404'){
    header('HTTP/1.1 404 Not Found');
    header('status: 404 Not Found'); 
	$template = '404.html';
}
include TEMPLATES_PATH.$template;
?>