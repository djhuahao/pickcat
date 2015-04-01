<?php
/*
define('DB_NAME','pickcat');
define('DB_USER','pickcat');
define('DB_PASSWORD','pickcat');
define('DB_HOST','localhost');
*/

define('DB_CHARSET','utf8');

define('CACHE_DATA',false);
define('MEMCACHE_ON',false);
define('MEMCACHE_HOST','');
define('MEMCACHE_PORT','');
define('MEMCACHE_FLAG',false);

define('CACHE_HTML',false);

define('ROOT_PATH',dirname(__FILE__).'/');
define('INCLUDES_PATH',ROOT_PATH.'includes/');
define('VIEW_PATH',ROOT_PATH.'view/');
define('CONTENT_PATH',ROOT_PATH.'contents/');
define('UPLOAD_PATH',ROOT_PATH.'upload/');
//define('CACHE_PATH',ROOT_PATH.'cache/');
define('TEMPLATES_PATH',ROOT_PATH.'templates/');

define('LIST_LIMIT',10);

function __autoload($class){
	include(INCLUDES_PATH.$class.'.class.php');
}
//base-------------------------------

define('DB_NAME',SAE_MYSQL_DB);
define('DB_USER',SAE_MYSQL_USER);
define('DB_PASSWORD',SAE_MYSQL_PASS);
define('DB_HOST',SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT);
define('CACHE_PATH',SAE_TMP_PATH.'/');
define('WB_AUTH','trejob@126.com:041654690lin');
define( "WB_AKEY" , '2745147607' );
define( "WB_SKEY" , '358af73d5fe7f966c20c6abab2483afe' );
define( "WB_CALLBACK_URL" , 'http://pickcat.sinaapp.com/index.php?weibo_login=weibo_login' );
//sae--------------------------------

define('baidu_apiKey' , "SgUA4nYPkKbHXwWVREOtmGEK");
define('baidu_secretKey' , "IlRcOjpFFNEH7UcWNECZIgwjxtI4ter0");
//baidu------------------------------

$_channels = array(
1=>array('name'=>'天气',
	'schema_edit_inc'=>'schema_edit_city.html',
	'url_arg'=>CONTENT_PATH.'channel_weather.json',
	'url'=>array('中国天气网'=>'http://www.weather.com.cn/data/cityinfo/%s.html'),
	'website'=>array('中国天气网'=>'http://www.weather.com.cn/weather/%s.shtml')),
2=>array('name'=>'在线电视剧',
	'url'=>array('爱奇艺'=>'http://list.iqiyi.com/www/2/------------2-1-1-1---.html','乐视'=>'http://so.letv.com/list/c2_t_a_y_f_at_o_p.html','土豆'=>'http://www.tudou.com/cate/ach30a-2b-2c-2d-2e-2f-2g-2h-2i-2j-2k-2l-2m-2n-2o-2so2pe-2pa1.html','优酷'=>'http://www.youku.com/v_olist/c_97_a__s__g__r__lg__im__st__mt__d_1_et_0_ag_0_fv_0_fl__fc__fe__o_10.html')),
3=>array('name'=>'动画',
	'url'=>array('爱奇艺'=>'http://list.iqiyi.com/www/4/------------2-1-1-1---.html','乐视'=>'http://so.letv.com/list/c3_t_a_y_f_at_o_p.html','土豆'=>'http://www.tudou.com/cate/ach9a-2b-2c-2d-2e-2f-2g-2h-2i-2j-2k-2l-2m-2n-2o-2so2pe-2pa1.html','优酷'=>'http://www.youku.com/v_olist/c_100_a__s__g__r__lg__im__st__mt__d_1_et_0_ag_0_fv_0_fl__fc__fe__o_10.html')),
4=>array('name'=>'在线电影',
	'url'=>array('爱奇艺'=>'http://list.iqiyi.com/www/1/------------2-1-1-1---.html','乐视'=>'http://so.letv.com/list/c1_t_a_y_f_at_o_p.html','土豆'=>'http://www.tudou.com/cate/ach22a-2b-2c-2d-2e-2f-2g-2h-2i-2j-2k-2l-2m-2n-2o-2so2pe-2pa1.html','优酷'=>'http://www.youku.com/v_olist/c_96_a__s__g__r__lg__im__st__mt__d_1_et_0_ag_0_fv_0_fl__fc__fe__o_10.html')),
5=>array('name'=>'综艺',
	'url'=>array('爱奇艺'=>'http://list.iqiyi.com/www/6/------------2-1-1-1---.html','乐视'=>'http://so.letv.com/list/c11_i_a_t_tv_o_p.html','土豆'=>'http://www.tudou.com/cate/ach31a-2b-2c-2d-2e-2f-2g-2h-2i-2j-2k-2l-2m-2n-2o-2so2pe-2pa1.html','优酷'=>'http://www.youku.com/v_olist/c_85_a__s__g__r__lg__im__st__mt__d_1_et_0_ag_0_fv_0_fl__fc__fe__o_10.html')),
6=>array('name'=>'导购',
	'schema_edit_inc'=>'schema_edit_city.html',
	'url_arg'=>CONTENT_PATH.'channel_daogou.json',
	'url'=>array('什么值得买'=>'http://feed.smzdm.com','360团购'=>'http://tuan.360.cn/%s/?_chgcity=_ff','hao123'=>'http://zhekou.hao123.com/'),
	'website'=>array('什么值得买'=>'http://www.smzdm.com/')),
7=>array('name'=>'新闻',
	'url'=>array('新浪新闻'=>'http://roll.news.sina.com.cn/s/channel.php?ch=01#col=90,94,95,93,96,97,98,99&spec=&type=&ch=01&k=&offset_page=0&offset_num=0&num=60&asc=&page=1','凤凰资讯'=>'http://news.ifeng.com/rt-channel/rtlist_0/','网易新闻'=>'http://www.163.com/rss'),
	'website'=>array('网易新闻'=>'http://news.163.com/latest/')),
8=>array('name'=>'微博',
	'schema_edit_inc'=>'schema_edit_weibo.html',
	'url'=>array('新浪微博'=>'https://api.weibo.com/2/users/show.json?source='.WB_AKEY.'&screen_name=%s'),
	'website'=>array('新浪微博'=>'http://weibo.com')),
9=>array('name'=>'小说',
	'url'=>array('起点中文网'=>'http://all.qidian.com/book/bookStore.aspx?ChannelId=-1&SubCategoryId=-1&Tag=all&Size=-1&Action=-1&OrderId=6&P=all&PageIndex=1&update=-1&Vip=-1&Boutique=-1&SignStatus=-1','红袖添香'=>'http://www.hongxiu.com/novel/s/list_each_up.html','天下电子书'=>'http://www.txdzs.com/newupdate.html','飞库网'=>'http://home.feiku.com/category/')),
10=>array('name'=>'快递',
	'schema_edit_inc'=>'schema_edit_kuaidi.html',
	'url_arg'=>CONTENT_PATH.'channel_kuaidi.json',
	'url'=>array('聚合'=>'http://v.juhe.cn/exp/index?key=6a6137bf0b58cf6d5f293ae9e6943eeb&dtype=xml&com=%s&no=%s'),
	'website'=>array('聚合'=>'http://www.kuaidi100.com/')),
11=>array('name'=>'电影下载',
	'url'=>array('人人影视'=>'http://www.yyets.com/rss/feed/?channel=movie','电影天堂'=>'http://www.dy2018.com/','飘花电影'=>'http://www.piaohua.com/html/zuixindianying.html'),
	'website'=>array('人人影视'=>'http://www.yyets.com/html/today.html')),
12=>array('name'=>'电视剧下载',
	'url'=>array('人人影视'=>'http://www.yyets.com/rss/feed/?channel=tv','电影天堂'=>'http://www.dy2018.com/','飘花电影'=>'http://www.piaohua.com/html/lianxuju/index.html'),
	'website'=>array('人人影视'=>'http://www.yyets.com/html/today.html'))
);
?>