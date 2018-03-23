<?php

class ModelToolProduct extends Model {
    public function getProdTypeTemplate($prodType = 1){
        $temp = array();
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."product_type WHERE type_id = '".$prodType."' ");
        if(!empty($sup->row)){
            $temp = $sup->row;
        }
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."type_lib WHERE type_id = '".$temp['type_id']."' ");
        if(!empty($sup->row)){
            $temp['options'] = $sup->rows;
        }
        return $temp;
    }
    
    public function getStructures() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."product_type ");
        return $sup->rows;
    }
    
    public function getOptions($type) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."type_lib WHERE type_id = '".$type."'");
        return $sup->rows;
    }
    
    public function hasColumn($name) {
        $sup = $this->db->query("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".DB_PREFIX."product' AND Column_Name = '".$name."'");
        if(!empty($sup->row)){
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
