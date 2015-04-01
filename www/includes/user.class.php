<?php
class user{
	public static $default_value = array('user_id'=>'','username'=>'','password'=>'','email'=>'','email_notify'=>'on','app_notify'=>'on','screen_name'=>'','weibo_uid'=>'','qq_uid'=>'','baidu_uid'=>'','avatar_url'=>'');
	
	public static function get_one($argv=null){
		global $_db,$_cache;
		$user_id = abs($argv['user_id']);
		$user = $_cache->get("user_$user_id");
		if(!$user && $argv){
			$where = " WHERE 1";
			$argv = array_intersect_key($argv,self::$default_value);
			foreach($argv as $k=>$v){
				$where .= " AND `$k`='$v'";
			}
			$sql = "SELECT * FROM `users` $where LIMIT 1";
			$user = $_db->get_row($sql);
			if(!$user) return false;
			$_cache->add("user_{$user->user_id}",$user);
		}
		
		return $user;
	}

	public static function login($argv){
		global $_cache,$_db;
		$default = array('email'=>'','password'=>'');
		$argv = right_merge($argv,$default);
		if($argv['email'] == '' || $argv['password'] == '') return false;
		$sql = "SELECT * FROM users WHERE email = '" . $_db->escape($argv['email']) . "' AND password = '" . $_db->escape($argv['password']) . "' LIMIT 1";
		$user = $_db->get_row($sql);
		if(!$user) return false;
		$_cache->add("user_{$user->user_id}",$user);
		
		session_start();
		$_SESSION['uid'] = $user->user_id;
		//set_cookie('uid',$user->user_id);
		return $user;
	}

	public static function login_by_cookie(){
		global $_cache,$_db;
		
		session_start();
		$user_id = $_SESSION['uid'];
		//$user_id = $_COOKIE['uid'];
		if(!$user_id) return false;
		return self::get_one(array('user_id'=>$user_id));
	}

	public static function register($argv){
		global $_db,$_cache;
		unset($argv['user_id']);
		$argv = right_merge($argv,self::$default_value);
		$_db->insert('users',$argv);
		return $_db->insert_id;
	}

	public static function update($argv){
		global $_cache,$_user,$_db;
		$result = 'succeed';
		if(!$_user->user_id) return false;
		
		if($argv['password'] != ''){
			if($argv['repeat_password'] == '' || $argv['password'] != $argv['repeat_password']) return false;
		}

		session_start();
		$cache_key = md5("activate_{$_user->user_id}");
		$email = filter_var($argv['email'], FILTER_VALIDATE_EMAIL);
		unset($argv['email']);
		if($email && $email != $_user->email && !isset($_SESSION['activate'][$cache_key])){
			//ÓÊÏäÒÑ´æÔÚ
			if(self::get_one(array('email'=>$email))) return 'email_exist';
			
			mail::send_activate($email,$cache_key);
	//		$_cache->add($cache_key,$user->user_id.'_'.$email);
			$_SESSION['activate'][$cache_key] = $_user->user_id.'_'.$email;
			$result = 'email';
		}
		$argv = array_intersect_key($argv,self::$default_value);
		if(!empty($argv)){
			$_db->update('users',$argv,array('user_id'=>$_user->user_id));
			$_cache->delete("user_{$user->user_id}");
		}
		return $result;
	}
	
	public static function activate_email($cache_key){
		global $_cache,$_db;
		session_start();
	//	$user_id_email = $_cache->get($cache_key);
		$user_id_email = $_SESSION['activate'][$cache_key];
		if(empty($user_id_email)) return false;
		unset($_SESSION['activate'][$cache_key]);
		if(!$user_id_email)return false;
		$user_id_email = explode('_',$user_id_email);
		$_db->update('users',array('email'=>$user_id_email[1]),array('user_id'=>$user_id_email[0]));
	//	$_cache->delete($cache_key);
	//	$_cache->delete("user_{$user_id_email[0]}");
		return true;
	}

	public static function logout(){
		session_start();
		unset($_SESSION['uid']);
		//set_cookie('uid','',time()-3600);
	}
}
?>