<?php
class tag{
    public static function get_list($argv){
        global $_db,$_cache;
        $key = $argv['key'];
        
        $where = "WHERE name LIKE '$key%'";
        
        $sql = "SELECT tag_id,name FROM tags $where LIMIT 5";
        $list = $_db->get_results($sql);
        return $list;
    }
    
    public static function add_tag($argv){
        global $_db;
        $_db->insert('tags',array('name'=>$argv['name']));
        return $_db->insert_id;
    }
    
    public static function add_tag_relation($tag_id,$schema_id){
        global $_db;
        $_db->insert('tag_relation',array('tag_id'=>$tag_id,'schema_id'=>$schema_id));
    }
    
    public static function delete_tag_relation($tag_id,$schema_id){
        global $_db;
        $_db->delete('tag_relation',array('tag_id'=>$tag_id,'schema_id'=>$schema_id));
    }
}
?>