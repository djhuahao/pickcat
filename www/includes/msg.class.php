<?php
class msg{
	public static $default_value = array('msg_id'=>'','schema_id'=>'','user_id'=>'','title'=>'','content'=>'','status'=>'','addtime'=>'');
	
	public static function get_list($argv=null){
		global $_db,$_cache,$_user;
        
        $where = "WHERE 1";
        $_where = array_intersect_key($argv,self::$default_value);
        foreach($_where as $k=>$v){
            $where .= " AND `$k`='$v'";
        }
        
        $order = 'ORDER BY msg_id DESC';
        
		$limit = $argv['limit'];
		if(!abs($limit)) $limit = LIST_LIMIT;
		$page = $argv['page'];
		$limit = abs($page)?"LIMIT ".$page*$limit.",$limit":"LIMIT $limit";
        
		$sql = "SELECT * FROM `msgs` $where $order $limit";
		$list = $_db->get_results($sql);
		$result = array('list'=>$list,'total'=>count($list));
		return $result;
	}
	
	public static function update($argv){
		global $_db,$_user;
		$msg_id = $argv['msg_id'];
		if(!$msg_id) return false;
		$argv = array_intersect_key($argv,self::$default_value);
		$_db->update($argv,array('msg_id'=>$msg_id,'user_id'=>$_user->user_id));
	}
	
	public static function add($argv){
		global $_db,$_user;
		unset($argv['msg_id']);
		$argv['user_id'] = $_user->user_id;
		$argv['addtime'] = time();
		$argv = right_merge($argv,self::$default_value);
		$_db->insert('msgs',$argv);
        return $_db->insert_id;
	}
}
?>