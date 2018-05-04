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
    
    public function getDescription($id){
        $this->load->model('common/tempdesc');
        $templ = $this->model_common_tempdesc->getTemp(3);
        $sup = $this->db->query("SELECT *, (SELECT name FROM oc_brand b WHERE b.id = p.brand) AS brand, (SELECT adress FROM oc_stocks s WHERE s.name = p.stock) AS adress FROM ".DB_PREFIX."product p WHERE product_id = ".(int)$id);
//        exit(var_dump($sup->row));
        foreach ($sup->row as $key => $value) {
            $templ = str_replace('%'.$key.'%', $value, $templ);
        }
        return htmlspecialchars_decode($templ);
    }
    
    public function getLibrs(){
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."libraries ");
        return $sup->rows;
    }
    
    public function getStructures() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."product_type ");
        return $sup->rows;
    }
    
    public function getOptions($type) {
        $result = array();
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."type_lib WHERE type_id = '".$type."' ORDER BY sort_order, lib_id ");
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."product_type WHERE type_id = '".$type."'");
        $result = array(
            'temp' => $query->row['temp'],
            'options' => $sup->rows
        );
        return $result;
    }
    
    public function getStructInfo($id) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."product_type WHERE type_id = ".(int)$id);
        return $sup->row;
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
        $result['settings']['top_nav'] = $sup->row['top_nav'];
        $result['settings']['name'] = $sup->row['text'];
        $result['settings']['library_id'] = $sup->row['library_id'];
        
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_struct WHERE library_id = '".$id."' ORDER BY item_id");
        $result['struct'] = array();
        $count = 0;
        foreach ($sup->rows as $item){
            $result['struct'][] = $item;
            ++$count;
        }
        $supF = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE parent_id = 0 AND library_id = '".$id."' ORDER BY name");
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
        $supF = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE parent_id = '".$parent."' ORDER BY name ");
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
                return TRUE;
            }
            else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }
    
    public function saveOption($data) {
        $sql = '';
        $result = TRUE;
        switch ($data['old']) {
            case '1':
                $sql.="UPDATE ".DB_PREFIX."type_lib SET ";
                foreach ($data as $key => $value) {
                    if($key!=='old' && $key!=='type_id'){
                        $sql.= $key." = '".$value."', ";
                    }
                }
                $sql.="type_id = '".$data['type_id']."' WHERE name = '".$data['name']."' AND type_id = '".$data['type_id']."' ";
                $this->db->query($sql);
                break;
            case '0':
                $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."type_lib WHERE name = '".$data['name']."' AND type_id = '".$data['type_id']."' ");
                $allow = empty($sup->row)?TRUE:FALSE;
                if($allow){
                    $isColumn = $this->hasColumn($data['name']);
                    switch ($data['field_type']) {
                        case 'input':
                            $sql.= "INSERT INTO ".DB_PREFIX."type_lib SET ";
                            foreach ($data as $key => $value) {
                                if($key!=='old' && $key!=='libraries' && $key!=='vals' && $key!=='type_id'){
                                    $sql.= $key." = '".$value."', ";
                                }
                            }
                            $sql.="type_id = '".$data['type_id']."' ";
                            $this->db->query($sql);
                            if(!$isColumn){
                                $this->db->query("ALTER TABLE `".DB_PREFIX."product` ADD `".$data['name']."` VARCHAR(512) NOT NULL");
                            }
                            $result = TRUE;
                            break;
                        case 'compability':
                            $sql.= "INSERT INTO ".DB_PREFIX."type_lib SET ";
                            foreach ($data as $key => $value) {
                                if($key!=='old' && $key!=='def_val' && $key!=='vals' && $key!=='type_id'){
                                    $sql.= $key." = '".$value."', ";
                                }
                            }
                            $sql.="type_id = '".$data['type_id']."' ";
                            $this->db->query($sql);
                            if(!$isColumn){
                                $this->db->query("ALTER TABLE `".DB_PREFIX."product` ADD `".$data['name']."` VARCHAR(512) NOT NULL");
                            }
                            $result = TRUE;
                            break;
                        case 'select':
                            $sql.= "INSERT INTO ".DB_PREFIX."type_lib SET ";
                            foreach ($data as $key => $value) {
                                if($key!=='old' && $key!=='libraries' && $key!=='def_val' && $key!=='required'  && $key!=='type_id'){
                                    $sql.= $key." = '".$value."', ";
                                }
                            }
                            $sql.="type_id = '".$data['type_id']."' ";
                            $this->db->query($sql);
                            if(!$isColumn){
                                $this->db->query("ALTER TABLE `".DB_PREFIX."product` ADD `".$data['name']."` VARCHAR(512) NOT NULL");
                            }
                            $result = TRUE;
                            break;
                        case 'library':
                            $sup = $this->db->query("SELECT l.name AS library_name, ls.name AS name, ls.text AS text, ls.item_id AS item_id FROM ".DB_PREFIX."lib_struct ls LEFT JOIN ".DB_PREFIX."libraries l ON l.library_id = ls.library_id WHERE ls.library_id = '".$data['libraries']."' ");
                            foreach ($sup->rows as $item) {
                                $sql = "INSERT INTO ".DB_PREFIX."type_lib SET "
                                        . "type_id = '".$data['type_id']."', "
                                        . "name = '".$item['name']."', "
                                        . "text = '".$item['text']."', "
                                        . "field_type = '".$data['field_type']."', "
                                        . "required = '".$data['required']."', "
                                        . "libraries = '".$item['item_id']."', "
                                        . "viewed = '".$data['viewed']."' ";
                                $this->db->query($sql);
                            }
                            return $sup->rows;
                            break;
                    }
                } else {
                    $result = FALSE;
                }
                break;
        }
        return $result;
    }
    
    public function saveType($text) {
        $this->load->model('tool/translate');
        $name = $this->model_tool_translate->translate($text);
        $this->db->query("INSERT INTO ".DB_PREFIX."product_type SET "
                . "name = '".$name."', "
                . "text = '".$text."', "
                . "description = '".$text."' ");
        $result = $this->db->query('SELECT MAX(type_id) AS id FROM '.DB_PREFIX.'product_type ');
        return $result->row['id'];
    }
    
    public function deleteOption($name, $type_id) {
        $this->db->query("UPDATE ".DB_PREFIX."type_lib SET type_id = 0 WHERE name = '".name."' AND type_id = '".$type_id."'");
    }
    
    public function getItems(){
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."controllers ORDER BY controller");
        return $sup->rows;
    }
    
    public function getIcons() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."icon_lib ORDER BY icon");
        return $sup->rows;
    }
    
    public function saveControllerInfo($info) {
        $this->db->query("UPDATE ".DB_PREFIX."controllers SET name = '".$info['name']."', icon = '".$info['icon']."' WHERE control_id = '".$info['id']."'");
    }
    
    public function getFCMenuItems($id) {
        $result = array();
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."user_customs WHERE user_id = ".(int)$id." ");
        if($sup->num_rows){
            $items = explode(";", $sup->row['fast_call']);
            foreach ($items as $item) {
                if(strlen(trim($item))!== 0){
                    $query = $this->db->query("SELECT * FROM ".DB_PREFIX."controllers WHERE controller = '".trim($item)."'");
                    $result[] = array(
                        'name'  => trim($item),
                        'text'  => $query->row['name'],
                        'icon'  => $query->row['icon']
                    );
                }
            }
        }
        return $result;
    }
    
    public function addItem($item, $uid) {
        $sup = $this->db->query("SELECT fast_call FROM ".DB_PREFIX."user_customs WHERE user_id = ".(int)$uid);
        if($sup->num_rows) {
            $this->db->query("UPDATE ".DB_PREFIX."user_customs SET fast_call = '".$sup->row['fast_call'].$item.";' WHERE user_id = ".(int)$uid);
        } else {
            $this->db->query("INSERT INTO ".DB_PREFIX."user_customs SET fast_call = '".$item.";', user_id = ".(int)$uid);
        }
    }
    
    public function dropItem($item, $uid) {
        $sup = $this->db->query("SELECT fast_call FROM ".DB_PREFIX."user_customs WHERE user_id = ".(int)$uid);
        $newFC = str_replace($item.';', '', $sup->row['fast_call']);
        $this->db->query("UPDATE ".DB_PREFIX."user_customs SET fast_call = '".$newFC."' WHERE user_id = ".(int)$uid);
    }
    
    public function saveTempName($temp, $type) {
        $this->db->query("UPDATE ".DB_PREFIX."product_type SET temp = '".$temp."' WHERE type_id = ".(int)$type);
    }
    
    public function saveTypeName($temp, $type) {
        $this->db->query("UPDATE ".DB_PREFIX."product_type SET text = '".$temp."' WHERE type_id = ".(int)$type);
    }
    
    public function savelibrName($name, $library_id) {
        $this->db->query("UPDATE ".DB_PREFIX."libraries SET text = '".$name."' WHERE library_id = ".(int)$library_id);
    }
    
    public function saveShowNav($temp, $type) {
        $this->db->query("UPDATE ".DB_PREFIX."product_type SET top_nav = ".(int)$temp." WHERE type_id = ".(int)$type);
    }
    
    public function saveLibrShowNav($show, $library_id) {
        $this->db->query("UPDATE ".DB_PREFIX."libraries SET top_nav = ".(int)$show." WHERE library_id = ".(int)$library_id);
    }
    
    public function saveTemp($temp, $type) {
        $this->db->query("UPDATE ".DB_PREFIX."product_type SET desctemp = '".$temp."' WHERE type_id = ".(int)$type);
    }
    
    public function oldLinks($pid, $fills) {
        $brands = array();
        $categories = array();
        foreach ($fills as $fid) {
            $sup = $this->db->query("SELECT b.id AS bid FROM ".DB_PREFIX."lib_fills lf LEFT JOIN ".DB_PREFIX."brand b ON b.name = lf.name WHERE lf.id = ".(int)$fid);
            if($sup->row['bid']){$brands[] = $sup->row['bid'];}
        }
        foreach ($fills as $fid) {
            $sup = $this->db->query("SELECT cd.category_id AS cid FROM ".DB_PREFIX."lib_fills lf LEFT JOIN ".DB_PREFIX."category_description cd ON cd.name = lf.name WHERE lf.id = ".(int)$fid);
            if($sup->row['cid']){$categories[] = $sup->row['cid'];}
        }
        foreach ($brands as $brand) {
            $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand SET product_id = ".(int)$pid.", brand_id = ".(int)$brand);
        }
        foreach ($categories as $cat) {
            $this->db->query("INSERT INTO ".DB_PREFIX."product_to_category SET product_id = ".(int)$pid.", category_id = ".(int)$cat);
        }
    }
    
    public function getLevelSets($item) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_struct WHERE item_id = ".(int)$item);
        return $sup->row;
    }
    
    public function getFillSets($fill) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE id = ".(int)$fill);
        return $sup->row;
    }
    
    public function levelSISave($si, $item) {
        $this->db->query("UPDATE ".DB_PREFIX."lib_struct SET showImg = ".(int)$si." WHERE item_id = ".(int)$item);
    }
    
    public function saveFillSets($fields, $fill) {
        foreach ($fields as $key => $value) {
            $this->db->query("UPDATE ".DB_PREFIX."lib_fills SET ".$key." = '".$value."' WHERE id = ".(int)$fill);
        }
    }
    
    public function getProduct($id) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."product WHERE product_id = ".(int)$id);
        return $sup->row;
    }
    
    public function getProdImg($id) {
        $photos = $this->db->query("SELECT * FROM ".DB_PREFIX."product_image WHERE product_id = ".(int)$id." ORDER BY sort_order ");
        return $photos->rows;
    }
    public function getProdName($id) {
        $name = $this->db->query("SELECT name FROM ".DB_PREFIX."product_description WHERE product_id = ".(int)$id);
        return $name->row['name'];
    }
    
    public function getProdStructure($info, $id) {
        $sup = $this->db->query("SELECT structure FROM ".DB_PREFIX."product WHERE product_id = ".(int)$id);
        $structure = $this->getProdTypeTemplate($sup->row['structure']);
        $result = array();
        $result['temp'] = $structure['temp'];
        $result['desctemp'] = $structure['desctemp'];
        foreach($structure['options'] as $option){
            if(isset($info['info'][$option['name']])){
                $result['options'][$option['name']] = array(
                    'field_type' => $option['field_type'],
                    'value' => $info['info'][$option['name']]
                );
            }
        }
        $result['options']['quantity'] = array('field_type' => 'system', 'value' => $info['info']['quantity']);
        $result['options']['status'] = array('field_type' => 'system', 'value' => $info['info']['status']);
        $result['options']['price'] = array('field_type' => 'system', 'value' => $info['info']['price']);
        $result['options']['image'] = array('field_type' => 'system', 'value' => $info['info']['image']);
        $result['image'] = $info['image'];
        return $result;
    }
}
