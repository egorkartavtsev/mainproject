<?php

class ModelCommonAlternative extends Model{
    public function getTotalCategories() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."category c "
                . "LEFT JOIN ".DB_PREFIX."category_description cd ON c.category_id = cd.category_id "
                . "WHERE c.parent_id = 0 ORDER BY cd.name");
        return $sup->rows;
    }
    
    public function getSCats($parent) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."category c "
                . "LEFT JOIN ".DB_PREFIX."category_description cd ON c.category_id = cd.category_id "
                . "WHERE c.parent_id = '".$parent."' ORDER BY cd.name");
        return $sup->rows;
    }
    
    public function saveAlts($id, $alters) {
        $search = array('+', '.', '/', '\\', '*', '<', '>', '!', '@', '#', '$', '%', '^', '&', '(', ',', '?', '~', '`');
        $exp = explode(";", $alters);
        $result = '';
        foreach ($exp as $word) {
            $word = str_replace($search, '', trim($word));
            if(trim($word)!='' && trim($word)!=';'){
                $result.= $word.'; ';
            }
        }
        $alters = trim($result);
        $this->db->query("UPDATE ".DB_PREFIX."category_description SET alters = '".trim($alters)."' WHERE category_id='".$id."'");
        return $alters;
    }
}
