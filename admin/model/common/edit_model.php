<?php
class ModelCommonEditModel extends Model {
    
    public function getBrands($param) {
        
        $query = $this->db->query("SELECT id, name FROM ".DB_PREFIX."brand WHERE parent_id = '".$param."' ORDER BY name ASC");
        return $query->rows;
    }
    
    public function delete($id){
        $this->db->query("DELETE FROM ".DB_PREFIX."brand WHERE id = '".$id."' ");
        $this->db->query("DELETE FROM ".DB_PREFIX."brand WHERE parent_id = '".$id."' ");
        //$childs = $this->isparent($id);
        
    }
    
    public function add($name, $par) {
        $this->db->query("INSERT INTO ".DB_PREFIX."brand (name, parent_id) "
                . "VALUES ('".$name."', '".$par."')");
    }
    
    public function isparent($id) {
        
        $query = $this->db->query("SELECT * FROM '.DB_PREFIX.'brand WHERE parent_id = '".$id."' ");
        $query = $query->rows;
        if(empty($query)){
            return false;
        }
        else{
            foreach($query as $child){
                $res[] = array(
                    'id' => $child['id']
                );
            }
            return $res;
        }
    }
}

