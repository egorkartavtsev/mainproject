<?php

class ModelCommonAvito extends Model {
    public function getTotalCats() {
        $sup = $this->db->query("SELECT c.category_id AS id, cd.name AS name FROM ".DB_PREFIX."category c "
                . "LEFT JOIN ".DB_PREFIX."category_description cd ON c.category_id = cd.category_id "
                . "WHERE c.parent_id = 0 ORDER BY cd.name");
        return $sup->rows;
    }
    
    public function getSubCats($parent) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."category c "
                . "LEFT JOIN ".DB_PREFIX."category_description cd ON c.category_id = cd.category_id "
                . "WHERE c.parent_id = '".$parent."' ORDER BY cd.name");
        return $sup->rows;
    }
    
    public function updateCat($cav, $cid) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."category WHERE parent_id = '".$cid."'");
        $this->db->query("UPDATE ".DB_PREFIX."category_description SET avitoId = '".$cav."' WHERE category_id = '".$cid."'");
        foreach ($sup->rows as $scat) {
            $this->db->query("UPDATE ".DB_PREFIX."category_description SET avitoId = '".$cav."' WHERE category_id = '".$scat['category_id']."'");
        }
    }
    
    public function updateSCats($cats) {
        foreach ($cats as $cid => $cav) {
            $this->db->query("UPDATE ".DB_PREFIX."category_description SET avitoId = '".$cav."' WHERE category_id = '".$cid."'");
        }
    }
    
    public function getLibr() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."aset_libr WHERE 1");
        $result = array();
        foreach ($sup->rows as $row) {
            $result[$row['name']][] = array(
                'value'         => $row['value'],
                'description'   => $row['description']
            );
        }
        
        return $result;
    }
    
    public function getSetts() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."avito_settings WHERE 1");
        return $sup->row;
    }
    
    public function updateSetts($data) {
        $query = "UPDATE ".DB_PREFIX."avito_settings SET ";
        foreach ($data as $key => $value){
            if($key=='edate' && ($value=='' || $value==0)){
                $value = 30;
            }
            if($key=='price' && ($value=='' || $value==0)){
                $value = 500;
            }
            $query.= $key." = '".$value."', ";
        }
        $query.= "id = 1 ";
        $query.= "WHERE id = 1 ";
        $this->db->query($query);
    }
    
    public function getPCID($pcat) {
        $quer = $this->db->query("SELECT avitoId FROM ".DB_PREFIX."lib_fills WHERE id = '".$pcat."'");
        return $quer->row['avitoId'];
    }
    
    public function getDescTempl() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."text_template WHERE id = 2 ");
        return $sup->row['text'];
    }
}
