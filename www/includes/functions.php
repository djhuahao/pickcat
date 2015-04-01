<?php
function prepare_upload_files($files){
	$tmp_dir = '';
	$result = array();
	if(is_array($files['error'])){
		$max = count($files['error']);
		for($i=0;$i<$max;$i++){
			if($files['error'][$i] == UPLOAD_ERR_OK){
				$result[] = $file;
			}
		}
	}elseif($files['error'] == UPLOAD_ERR_OK){
		$result = $files;
	}
	
	return array('tmp_dir'=>sys_get_temp_dir(),'files'=>$result);
}

function get_unique_name($src){
	return md5($src.time());
}

function del_file($path){
	if(file_exists($path)) return unlink($path);
}

/* key 以default为准，value以argv为准
 */
function right_merge($argv,$default){
	$intersect = array_intersect_key($argv,$default);
	$result = array_merge($default,$intersect);
	return $result;
}

function auto_convert_code($str,$from=null,$to=null){
	$encode = mb_detect_encoding($str, array('ASCII','GB2312','GBK','UTF-8'));
	if($encode=="GBK"){
		$result = iconv("UTF-8","UTF-8",$str);
		$str=mb_convert_encoding($str,"GBK","utf-8");
	}
	return $result;
}

function is_ssl(){
	if ( isset($_SERVER['HTTPS']) ) {
		if ( 'on' == strtolower($_SERVER['HTTPS']) )
			return true;
		if ( '1' == $_SERVER['HTTPS'] )
			return true;
	} elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
		return true;
	}
	return false;
}

function set_cookie($key,$value,$expire=-1){
	if($expire == -1) $expire = time()+3600*24;
	setcookie($key,$value,$expire);
}

function delete_cookie($key){
	setcookie($key,'',time()-3600);
}
//base-----------------------------------------------------------------

function login_by_weibo(){
	include('saetv2.ex.class.php');
	global $_db,$_cache,$_user;
	session_start();
	$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

	if (isset($_REQUEST['code'])) {
		$keys = array();
		$keys['code'] = $_REQUEST['code'];
		$keys['redirect_uri'] = WB_CALLBACK_URL;
		try {
			$token = $o->getAccessToken( 'code', $keys ) ;
		} catch (OAuthException $e) {
		}
	}

	if ($token) {
		$_SESSION['token'] = $token;
		setcookie( 'weibojs_'.$o->client_id, http_build_query($token) );
		
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
		$uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$_user = user::get_one(array('weibo_uid'=>$uid));
		$user_id = $_user->user_id;
		$user_info = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
		if(!$user_id){
			$user_id = user::register(array('screen_name'=>$user_info['screen_name'],'weibo_uid'=>$uid,'avatar_url'=>$user_info['profile_image_url']));
			$_user = user::get_one(array('user_id'=>$user_id));
			$res = 'new_user';
		}else{
			$res = $_user;
		}
		$_SESSION['uid'] = $user_id;
		//set_cookie('uid',$user_id);
		return $res;
	} else {
	//授权失败
	}
	return false;
}

function login_by_qq(){
	include(INCLUDES_PATH.'qqapi/qqConnectAPI.php');
	global $_db,$_cache,$_user;
	$qc = new QC();
	$access_token = $qc->qq_callback();
	$qq_uid = $qc->get_openid();
	
	if ($qq_uid) {
		$_user = user::get_one(array('qq_uid'=>$qq_uid));
		$user_id = $_user->user_id;
		$qc = new QC($access_token,$qq_uid);
		$user_info = $qc->get_user_info();//获取用户等基本信息
		if(!$user_id){
			$user_id = user::register(array('screen_name'=>$user_info['nickname'],'qq_uid'=>$qq_uid,'avatar_url'=>$user_info['figureurl_qq_1']));
			$_user = user::get_one(array('user_id'=>$user_id));
			$res = 'new_user';
		}else{
			$res = $_user;
		}
		$_SESSION['uid'] = $user_id;
		//set_cookie('uid',$user_id);
		return $res;
	} else {
	//授权失败
	}
	return false;
}

/*
 * 对str进行分词
 */
function keywords_segment($str,$word_tag=0){
	$str = str_replace(array(',',';','，','。','；','、'),' ',$str);
	$str = trim($str);
	//如果已经分词则返回
	if(strpos($str,' ') || mb_strlen($str,'utf8')<4) return $str;
	
	$seg = new SaeSegment();
	$ret = $seg->segment($str);
	if ($ret === false) return $str;
	$str_ret = '';
	foreach($ret as $v){
		if($v['word']) $str_ret .= $v['word'].' ';
	}
	return rtrim($str_ret);
}

function json2xml($source,$charset='utf8') {
	$source = trim($source);
	if(empty($source) || strpos($source,'{') !==0 ){
		return false;
	}
	$array = json_decode($source);
	if(!$array) return false;
	
	$xml ="<?xml version='1.0' encoding='$charset' ?>";
	$xml .= assoc2xml($array);
	return $xml;
}

function assoc2xml($source) {
	$string=""; 
	foreach($source as $k=>$v){ 
		$string .="<".$k.">";
		if(is_array($v) || is_object($v)){ //判断是否是数组，或者，对像
			$string .= assoc2xml($v);//是数组或者对像就的递归调用
		}else{
			$string .=$v;//取得标签数据
		}
		$string .="</".$k.">";
	}
	return $string;
}

/*
 * 判断str里是否有数字在 max, min之间
 */
function find_num($str,$max,$min){
	if($max == '')$max = PHP_INT_MAX;
	if($min == '')$min = -PHP_INT_MAX;
	
	$regex = '/(-?\d+)(\.\d+)?/';
	if(preg_match_all($regex,$str,$matches)){
		foreach($matches[0] as $match){
			if($match <= $max && $match >= $min){
				return $str;
			}
		}
	}
	return false;
}

function format_script($source) {
	$html = strtolower($source);
	
	//如果是json则转换成xml，返回
	$xml = json2xml($html);
	if($xml !== false) return $xml;

	//如果是html则忽略头部,否则返回
	$i = strpos($html, '<body');
	if($i === false) return $html;
	
	$html = mb_strcut($html, $i);
	$regex = array ("#<script[^>]*?>.*?</script>#si", // 去掉 javascript 
		"#<style[^>]*?>.*?</style>#si", // 去掉 css 
		"#<!--[/!]*?[^<>]*?>#si", // 去掉 注释标记 
		);
	$replace = array('','','');
	$html = preg_replace($regex, $replace, $html);
//	$html = html_entity_decode($html,ENT_QUOTES);
	return $html;
}

function find_key($html,$keywords,$pos=0){
    if( empty($keywords) ) return $pos;
	foreach($keywords as $key){
		$i = strpos($html,$key,$pos);
		if($i === false || substr_count($html,'</',$pos,$i-$pos) >5) return false;
		$pos = $i+strlen($key);
	}
	return $i;
}
/*
function mb_find_key($html,$keywords,$pos=0){
    if( empty($keywords) ) return $pos;
	foreach($keywords as $key){
		$i = mb_strpos($html,$key,$pos);
		if($i === false || substr_count($html,'</',$pos,$i-$pos) >5) return false;
		$pos = $i+mb_strlen($key);
	}
	return $i;
}
*/

/*通过channel的参数产生正确的url
 *$channel = $schema->channel
 *$url_key = (key)$_channel['url']
 *$url = (value)$_channel['url']
 *$url_args = $schema->url
 */
function mkurl($channel,$url_key,$url,$url_args){
	global $_channels;
	//如果是自己填写的url或者没有附加参数或者栈桥不支持参数，则直接返回
	if(!$channel || !$url_args)return $url;
	$url_args = explode(',',$url_args);
	if($_channels[$channel]['url_arg']){
		$channel_args = json_decode(file_get_contents($_channels[$channel]['url_arg']),true);
		//如果网址不支持参数，则直接返回
		if($channel_args[$url_key]){
			foreach($url_args as $k=>$v){
				if(isset( $channel_args[$url_key][$v])) $url_args[$k] = $channel_args[$url_key][$v];
			}
		}
	}
	$url = vsprintf($url,$url_args);
	return $url;
}

/*为file_get_content制作context for sae
 *$url_key = (key)$_channel['url']
 */
function mkfp_context($url_key){
	if($url_key == '新浪微博'){
		$context = stream_context_create(array(
			'http' => array(
				'header'  => "Authorization: Basic " . base64_encode(WB_AUTH)
			)
		));
	}
	return $context;
}

function fetch_web($schemas,$test=null){
    global $_user,$_channels;
    
	$updated_file = array();
	foreach($schemas as $k=>$v){
		if ((!$v->channel&&$v->url=='') || $v->keywords == '') continue;
		if ($v->channel) {
			$url_arr = $_channels[$v->channel]['url'];
		}else{
			$url_arr = explode(' ', $v->url);
		}
		foreach ($url_arr as $url_key => $url) {
			$url = mkurl($v->channel,$url_key,$url,$v->url);
			$filename = md5($url).'.tmp';

			if(in_array($filename,$updated_file)){
				$html = file_get_contents(CACHE_PATH.$filename);
			}else{
				$file = '';
				$context = $v->channel?mkfp_context($url_key):null;
				$html = file_get_contents($url,false,$context);
				$html = format_script($html);
				file_put_contents(CACHE_PATH.$filename,$html);
				$updated_file[] = $filename;
			}

			//处理关键词
			$keywords = $v->keywords;
            
			$encoding = mb_detect_encoding($html,array('ASCII','GB2312','GBK','UTF-8'));
			if($encoding == 'EUC-CN'){
				$encoding = 'GB2312';
				$keywords = iconv('UTF-8','GBK',$keywords);
			}elseif ($encoding == 'CP936') {
				$keywords = iconv('UTF-8', $encoding, $keywords);
			}
            
			$keywords =explode(' ',$keywords);
			$first_key = array_shift($keywords);
			$lpos = 0;
			$rpos = NULL;

			$len = strlen($first_key);
			for($lpos=strpos($html,$first_key,$lpos);$lpos!==false;$lpos=strpos($html,$first_key,$lpos+$len)){
				$rpos = find_key($html,$keywords,$lpos+$len);

				if( $rpos ){
					$html_len = strlen($html);
					//得到关键词上下文
					for($j=1;$j<6 && $lpos !== false;$j++){
						$lpos = strrpos($html, '</',$lpos-$html_len-2);
					}
					if(!$lpos) $lpos = 0;
					for($i=1;$i<5;$i++){
						$rpos = strpos($html,'</',$rpos+2);
					}
					if(!$rpos)$rpos = $html_len;
					$result = substr($html, $lpos,$rpos-$lpos);
					break;
				}
			}
			
			$result = trim(strip_tags($result,'<a>'));
			//确定包含数字
			if($v->max_num!='' || $v->min_num!=''){
				$result = find_num($result,$v->max_num,$v->min_num);
			}

			if($result){
				$result = str_replace(array("\n","\r","\t","  "),'',$result);
				if ($encoding != 'UTF-8') {
                    $result = substr($result,0,400);
					$result = iconv('GBK','UTF-8',$result);
                }else{
                    $result = mb_substr($result,0,400,'utf8');
                }
				$website = isset($_channels[$v->channel]['website'][$url_key])?$_channels[$v->channel]['website'][$url_key]:$url;
				$content = $result."<a href='$website' target='_blank'>查看</a>";
				if($test){
					return $content;
				}else{
					$_user = user::get_one(array('user_id'=>$v->user_id));
					schema::update(array('status'=>'off','schema_id'=>$v->schema_id));
					msg::add(array('schema_id'=>$v->schema_id,'title'=>$v->title,'content'=>$content,'status'=>'new'));
					$email = filter_var($_user->email, FILTER_VALIDATE_EMAIL);
					if($_user->email_notify == 'on' && $email) mail::send_cron($email,$v->title,$content);
					if($_user->app_notify == 'on' && $_user->baidu_uid) push::push_message($_user->baidu_uid,$v->title,$result);
				}
				$result = NULL;
				break;
			}
		}
	}
}
?>