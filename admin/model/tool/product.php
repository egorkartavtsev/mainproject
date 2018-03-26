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
    
    public function saveLibrary($info) {
        $this->load->model('tool/translate');
        $this->load->model('tool/product');
        $this->db->query("INSERT INTO ".DB_PREFIX."libraries SET "
                . "text = '".$info['libr_text']."', "
                . "name = '".$info['libr_name']."', "
                . "description = '".$info['libr_description']."' ");
        $sup = $this->db->query("SELECT MAX(`library_id`) AS id FROM ".DB_PREFIX."libraries");
        $library = $sup->row['id'];
        $last_key = count($info['field']) - 1;
        $parent = 0;
        foreach ($info['field'] as $key => $field) {
            $name = $this->model_tool_translate->translate($field['text']);
            $this->db->query("INSERT INTO ".DB_PREFIX."lib_struct SET "
                    . "name = '".$name."', "
                    . "text = '".$field['text']."', "
                    . "library_id = '".$library."', "
                    . "parent_id = '".$parent."', "
                    . "isparent = '".($key == $last_key?0:1)."' ");
            $sup = $this->db->query("SELECT MAX(`item_id`) AS id FROM ".DB_PREFIX."lib_struct");
            $parent = $sup->row['id'];
            if(!$this->model_tool_product->hasColumn($name)){
                $this->db->query("ALTER TABLE ".DB_PREFIX."product ADD `".$name."` VARCHAR(512) NOT NULL ");
            }
        }
    }
    
    public function getLibrInfo($id) {
        $result = array();
        
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."libraries WHERE library_id = '".$id."'");
        $result['text'] = $sup->row['text'];
        $result['description'] = $sup->row['description'];
        
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_struct WHERE library_id = '".$id."' ORDER BY item_id");
        $result['struct'] = array();
        $count = 0;
        foreach ($sup->rows as $item){
            $result['struct'][] = $item;
            ++$count;
        }
        $supF = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE parent_id = 0 AND library_id = '".$id."'");
        $result['mainFills'] = $supF->rows;
        if($count>=12 || $count==0){
            $class = 'col-md-1';
        } else {
            $class = 'col-md-'.(floor(12/$count));
        }
        $result['divClass'] = $class;
//        exit(var_dump($sup->rows));
        //exit(var_dump($result));
        return $result;
    }
    
    public function getChildFills($parent) {
        $supF = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE parent_id = '".$parent."'");
        return $supF->rows;
    }
    
    public function saveChangeFillName($id, $name, $field) {
        $sup = $this->db->query("SELECT name FROM ".DB_PREFIX."lib_fills WHERE id = '".$id."'");
        if($this->db->query("UPDATE ".DB_PREFIX."lib_fills SET name = '".$name."' WHERE id = '".$id."'")){
            $this->db->query("UPDATE ".DB_PREFIX."product_description SET name = REPLACE(name, '".$sup->row['name']."', '".$name."')");
            $this->db->query("UPDATE ".DB_PREFIX."product SET `".$field."` = REPLACE(`".$field."`, '".$sup->row['name']."', '".$name."')");
            return 1;}
        else {return 0;}
    }
    
    public function saveNewFillName($fill) {
        if($this->db->query("INSERT INTO ".DB_PREFIX."lib_fills SET "
                . "library_id = '".$fill['libraryId']."', "
                . "item_id = '".$fill['itemId']."', "
                . "parent_id = '".$fill['parent']."', "
                . "name = '".$fill['name']."' ")){
            $sup = $this->db->query("SELECT MAX(id) as id FROM ".DB_PREFIX."lib_fills ");
            return $sup->row['id'];
        } else {
            return 0;
        }
    }
    
    public function deleteFill($id) {
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."product_to_lib WHERE fill_id = '".$id."'");
        if(empty($query->rows)){
            $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE parent_id = '".$id."'");
            if(empty($sup->rows)){
                $this->db->query("DELETE FROM ".DB_PREFIX."lib_fills WHERE id = '".$id."'");
            }
            else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
        
    }
}
