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
}
