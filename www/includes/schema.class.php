<?php
class schema{
	public static $default_value = array('schema_id'=>'','user_id'=>'','channel'=>'0','title'=>'','keywords'=>'','url'=>'','max_num'=>'','min_num'=>'','auto_reactivate'=>'off','share'=>'on','status'=>'on');
	
	public static function get_list($argv=null,$select=array('*')){
		global $_db,$_cache;
        
        $select = implode(',',$select);
		
        $where = "WHERE 1";
        $_where = array_intersect_key($argv,self::$default_value);
        foreach($_where as $k=>$v){
            $where .= " AND `$k`='$v'";
        }
        
        $order = 'ORDER BY schema_id DESC';
        
		$limit = $argv['limit'];
		if(!abs($limit)) $limit = LIST_LIMIT;
		$page = $argv['page'];
		$limit = abs($page)?"LIMIT ".$page*$limit.",$limit":"LIMIT $limit";
		$sql = "select $select from `schemas` $where $order $limit";
		$list = $_db->get_results($sql);
        
		$result = array('list'=>$list,'total'=>count($list));
		return $result;
	}
	
	public static function get_one($argv){
		global $_db,$_cache,$_user;
		if(!$argv['schema_id']) return false;
		$argv = array_intersect_key($argv,self::$default_value);
		$where = "where (user_id={$_user->user_id} or share = 'on')";
		foreach ($argv as $k=>$v){
			$where .= " and `$k`='$v'";
		}
		$sql = "select * from `schemas` $where limit 1";
		$result = $_db->get_row($sql);
		return $result;
	}
	
	public static function update($argv){
		global $_db,$_user;
		$schema_id = $argv['schema_id'];
		if(!$schema_id) return false;
		$argv = array_intersect_key($argv,self::$default_value);
		if(isset($argv['channel']) && isset($argv['url']) && !$argv['channel'] && !filter_var($argv['url'], FILTER_VALIDATE_URL)){
			return false;
		}
		if($argv['keywords']){
			$argv['keywords'] = keywords_segment($argv['keywords']);
		}
		if($argv) $_db->update('schemas',$argv,array('schema_id'=>$schema_id,'user_id'=>$_user->user_id));
	}
	
	public static function add($argv){
		global $_db,$_user;
		unset($argv['schema_id']);
		$argv['user_id'] = $_user->user_id;
		$argv = right_merge($argv,self::$default_value);
		if(isset($argv['channel']) && isset($argv['url']) && !$argv['channel'] && !filter_var($argv['url'], FILTER_VALIDATE_URL)){
			return false;
		}
		if($argv['keywords']){
			$argv['keywords'] = keywords_segment($argv['keywords']);
		}
		$_db->insert('schemas',$argv);
        return $_db->insert_id;
	}
    
    public static function delete($argv){
        global $_db,$_user;
        $argv['user_id'] = $_user->user_id;
        $where = array_intersect_key($argv,self::$default_value);
        $_db->delete('schemas',$where);
    }
}
?>