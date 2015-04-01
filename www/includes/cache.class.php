<?php
/* 
 * group 'default' is for template cache
 */
class cache{
	private $cache = array();

	function add($key,$data,$expire=''){
		if($this->get($key) !== false) return false;
		return $this->set($key,$data);
	}
	
	function set($key,$data,$expire=''){
		$this->cache[$key] = $data;
		return true;
	}
	
	function get($key){
		if(isset($this->cache[$key])) return $this->cache[$key];
		else return false;
	}
	
	function increment($key,$increment=1){
		if(!isset($this->cache[$key])) return false;
		if(!is_numeric($this->cache[$key])) $this->cache[$key] = 0;
		$this->cache[$key] += (int)$increment;
		return $this->cache[$key];
	}
	
	function decrement($key,$decrement=1){
		if(!isset($this->cache[$key])) return false;
		if(!is_numeric($this->cache[$key])) $this->cache[$key] = 0;
		$this->cache[$key] -= (int)$increment;
		return $this->cache[$key];
	}
	
	function delete($key){
		unset($this->cache[$key]);
		return true;
	}
	
	function flush(){
		$this->cache = array();
		return true;
	}

	function cache_output_start(){
		ob_start('ob_gzhandler');
	}
		
	function cache_output_finish(){	
	}

	function get_output_cache($key){
	}
}
?>