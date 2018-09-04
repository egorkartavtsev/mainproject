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
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."product p WHERE product_id = ".(int)$id);
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
    
    public function getOptionInfo($opt) {
        $sup = $this->db->query("SELECT "
                    . "tl.name, tl.description, "
                    . "tl.text, tl.type_id, "
                    . "tl.lib_id, tl.vals, tl.type_id, "
                    . "tl.def_val, tl.required, tl.similar, tl.sim_showlist, "
                    . "tl.field_type, tl.libraries, tl.sort_order, tl.viewed, "
                    . "tl.unique_field, tl.searching, tl.filter, "
                    . "l.text AS lib_name "
                . "FROM ".DB_PREFIX."type_lib tl "
                . "LEFT JOIN ".DB_PREFIX."libraries l ON tl.libraries = l.library_id "
                . "WHERE tl.lib_id = ".(int)$opt);
        return $sup->row;
    }
    
    public function saveExcelTemplate($templ) {
        foreach ($templ as $key => $value) {
            $this->db->query("UPDATE ".DB_PREFIX."type_lib SET excel = ".(int)$value." WHERE lib_id = ".(int)$key);
        }
    }
    
    public function getExcelTempl($type) {
        $sup = $this->db->query("SELECT tl.text AS text, tl.excel AS excel, tl.name AS name FROM ".DB_PREFIX."type_lib tl WHERE tl.type_id = ".(int)$type." AND tl.excel != 0 ORDER BY tl.excel ");
        $res = array();
        $res['vin'] = array(
            'excel' => 0,
            'text'  => 'Внутренний номер'
        );
        $res['price'] = array(
            'excel' => 1,
            'text'  => 'Цена'
        );
        $res['quantity'] = array(
            'excel' => 2,
            'text'  => 'Количество'
        );
        $i=3;
        foreach ($sup->rows as $lib) {
            $res[$lib['name']] = array(
                'excel' => $lib['excel']+2,
                'text'  => $lib['text']
            );
            ++$i;
        }
        $res['comp'] = array(
            'excel' => $i,
            'text'  => 'Комплектность'
        );
        ++$i;
        $res['comp_price'] = array(
            'excel' => $i,
            'text'  => 'Цена комплекта'
        );
        ++$i;
        $res['comp_whole'] = array(
            'excel' => $i,
            'text'  => 'Способ покупки'
        );
        ++$i;
        $res['date_added'] = array(
            'excel' => $i,
            'text'  => 'Дата фотографии'
        );
        ++$i;
        
        return $res;
    }
    
    public function getOptions($type) {
        $result = array();
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."type_lib WHERE type_id = '".$type."' ORDER BY sort_order ");
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."product_type WHERE type_id = '".$type."'");
        $result = array(
            'temp' => $query->row['temp'],
            'options' => $sup->rows
        );
        return $result;
    }
    
    public function saveTypeLabel($label, $field, $old, $color) {
        $this->db->query("UPDATE ".DB_PREFIX."type_lib SET label_color = 0, label_order = 0 WHERE lib_id = ".(int)$old);
        if($field!=='-'){
            $this->db->query("UPDATE ".DB_PREFIX."type_lib SET label_color = '".$color."', label_order = ".(int)$label." WHERE lib_id = ".(int)$field);
        }
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
        $result['settings']['smart'] = $sup->row['smart'];
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
        if($this->db->query("UPDATE ".DB_PREFIX."lib_fills SET name = '".trim($name)."' WHERE id = '".$id."'")){
            $this->db->query("UPDATE ".DB_PREFIX."product_description SET name = REPLACE(name, '".$sup->row['name']."', '".trim($name)."')");
            $this->db->query("UPDATE ".DB_PREFIX."product SET `".$field."` = REPLACE(`".$field."`, '".$sup->row['name']."', '".trim($name)."')");
            return 1;}
        else {return 0;}
    }
    
    public function saveNewFillName($fill) {
        if($this->db->query("INSERT INTO ".DB_PREFIX."lib_fills SET "
                . "library_id = '".$fill['libraryId']."', "
                . "item_id = '".$fill['itemId']."', "
                . "parent_id = '".$fill['parent']."', "
                . "name = '".trim($fill['name'])."' ")){
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
            default :
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
    
    public function saveShowNav($temp, $type) {
        $this->db->query("UPDATE ".DB_PREFIX."product_type SET top_nav = ".(int)$temp." WHERE type_id = ".(int)$type);
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
        $sup = $this->db->query("SELECT lf.name, (SELECT ls.name FROM ".DB_PREFIX."lib_struct ls WHERE ls.item_id = lf.item_id) AS col FROM ".DB_PREFIX."lib_fills lf WHERE id = ".(int)$fill);
        foreach ($fields as $key => $value) {
            $this->db->query("UPDATE ".DB_PREFIX."lib_fills SET ".$key." = '".trim($value)."' WHERE id = ".(int)$fill);
        }
        $this->db->query("UPDATE ".DB_PREFIX."product SET ".$sup->row['col']." = '".$fields['name']."' WHERE ".$sup->row['col']." = '".$sup->row['name']."'");
        $this->db->query("UPDATE ".DB_PREFIX."product_description SET "
                . "tag = REPLACE(tag, '".$sup->row['name']."', '".$fields['name']."'), "
                . "meta_title = REPLACE(meta_title, '".$sup->row['name']."', '".$fields['name']."'), "
                . "meta_h1 = REPLACE(meta_h1, '".$sup->row['name']."', '".$fields['name']."'), "
                . "meta_description = REPLACE(meta_description, '".$sup->row['name']."', '".$fields['name']."'), "
                . "meta_keyword = REPLACE(meta_keyword, '".$sup->row['name']."', '".$fields['name']."'), "
                . "name = REPLACE(name, '".$sup->row['name']."', '".$fields['name']."'), "
                . "description = REPLACE(description, '".$sup->row['name']."', '".$fields['name']."') ");
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
        $sup = $this->db->query("SELECT structure, vin FROM ".DB_PREFIX."product WHERE product_id = ".(int)$id);
        $structure = $this->getProdTypeTemplate($sup->row['structure']);
        $result = array();
        $result['structure'] = $sup->row['structure'];
        $result['temp'] = $structure['temp'];
        $result['vin'] = $sup->row['vin'];
        $result['desctemp'] = $structure['desctemp'];
        foreach($structure['options'] as $option){
            if(isset($info['info'][$option['name']])){
                $result['options'][$option['name']] = array(
                    'field_type' => $option['field_type'],
                    'value' => htmlspecialchars($info['info'][$option['name']], ENT_QUOTES)
                );
            }
        }
        $youtube = '';
        if($info['info']['youtube']!==''){
            $sup = strrchr($info['info']['youtube'], "=");
            if($sup){
                $youtube = str_replace("=", "", $sup);
            } else {
                $sup = explode("/", $info['info']['youtube']);
                if(count($sup)>1){
                    $index = count($sup)-1;
                    $youtube = $sup[$index];
                } else {
                    $youtube = $sup[0];
                }                
            }
        }
        $result['options']['avitoname'] = array('field_type' => 'system', 'value' => $info['info']['avitoname']);
        $result['options']['quantity'] = array('field_type' => 'system', 'value' => $info['info']['quantity']);
        $result['options']['status'] = array('field_type' => 'system', 'value' => $info['info']['status']);
        $result['options']['price'] = array('field_type' => 'system', 'value' => $info['info']['price']);
        $result['options']['selfprice'] = array('field_type' => 'system', 'value' => $info['info']['selfprice']?$info['info']['selfprice']:ceil($info['info']['price']/2));
        $result['options']['image'] = array('field_type' => 'system', 'value' => $info['info']['image']);
        $result['options']['youtube'] = array('field_type' => 'system', 'value' => $youtube);
        $result['image'] = isset($info['image'])?$info['image']:'no_image.png';
        $result['manager'] = $info['manager'];
        return $result;
    }
    
    public function searchingProds($request) {
        $reqwords = explode(" ", $request);

        $query = "SELECT "
                  . "pd.name AS name, "
                  . "p.stock AS stock, "
                  . "p.price AS price, "
                  . "p.quantity AS quantity, "
                  . "p.vin AS vin, "
                  . "p.product_id "
                . "FROM ".DB_PREFIX."product_description pd "
                    . "LEFT JOIN ".DB_PREFIX."product p "
                        . "ON pd.product_id = p.product_id "
                    . "WHERE !LOCATE('complect', p.vin) ";
        foreach ($reqwords as $word){
            $query.="AND (LOCATE('" . $this->db->escape($word) . "', pd.name) "
                       . "OR p.vin = '".$this->db->escape($word)."' "
                       . "OR LOCATE('" . $this->db->escape($word) . "', p.catn)) ";
        }
        $result = $this->db->query($query);
        return $result->rows;
    }
    
    public function getProdInfo($id) {
        $this->load->model("tool/image");
        $this->load->model("complect/complect");
        $this->load->model("tool/complect");
        $result = array();
        $sup = $this->db->query("SELECT * "
                . "FROM ".DB_PREFIX."product p "
                . "LEFT JOIN ".DB_PREFIX."product_description pd ON p.product_id = pd.product_id "
                . "WHERE p.product_id = ".(int)$id);
        
    //get options + systems
        $result = array(
            'image' => $this->model_tool_image->resize($sup->row['image'], 1200, 900),
            'vin' => $sup->row['vin'],
            'status' => $sup->row['status'],
            'complect' => FALSE,
            'name' => $sup->row['name'],
            'price' => $sup->row['price'],
            'selfprice' => $sup->row['selfprice'],
            'quantity' => $sup->row['quantity'],
            'edit' => $this->url->link('production/catalog/edit', 'token='.$this->session->data['token'].'&product_id='.$sup->row['product_id']),
            'go_site' => HTTP_CATALOG.'index.php?route=catalog/product&product_id='.$sup->row['product_id'],
            //опциональная строчка!!!!! Удалить для дальнейшей эксплуатации!!!!
            'location' => $sup->row['stock'].'/'.$sup->row['stell'].'/'.$sup->row['jar'].'/'.$sup->row['shelf'].'/'.$sup->row['box']
        );
        
        $type = $this->getOptions($sup->row['structure']);
        foreach($type['options'] as $option){
            if($sup->row[$option['name']]!=='' && $option['name'] !== 'stock' && $option['name'] !== 'stell' && $option['name'] !== 'jar' && $option['name'] !== 'shelf' && $option['name'] !== 'box'){
                $result['options'][$option['name']] = array(
                    'text' => $option['text'],
                    'value' => $sup->row[$option['name']]
                );
            }
        }
    
    //get complect info
        $result['complect'] = $this->model_tool_complect->isCompl($result['vin']);
        if($result['complect']){
            $comp = $this->model_complect_complect->getComplect($result['complect']['complect']['id']);
            $result['complect']['accs'] = $comp['accessories'];
        }
        
        $histsup = $this->db->query("SELECT * FROM ".DB_PREFIX."product_history WHERE sku = '".$result['vin']."' ");
        $result['history'] = array();
        foreach ($histsup->rows as $hItem) {
            if((string)$hItem['date_added']!=='0000-00-00 00:00:00'){
                $result['history'][] = array(
                    'date'      => date("d.m.Y H:i", strtotime($hItem['date_added'])),
                    'label'     => '<span class="btn btn-info btn-sm" style="cursor: auto!important;"><i class="fa fa-plus"></i></span>',
                    'type'      => $hItem['type_modify'],
                    'manager'   => $hItem['manager']
                );
            } elseif((string)$hItem['date_sale']!=='0000-00-00 00:00:00'){
                $result['history'][] = array(
                    'date'      => date("d.m.Y", strtotime($hItem['date_sale'])),
                    'label'     => '<span class="btn btn-success btn-sm" style="cursor: auto!important;"><i class="fa fa-recycle"></i></span>',
                    'type'      => $hItem['type_modify'],
                    'manager'   => $hItem['manager']
                );
            } elseif((string)$hItem['date_refund']!=='0000-00-00 00:00:00'){
                $result['history'][] = array(
                    'date'      => date("d.m.Y", strtotime($hItem['date_refund'])),
                    'label'     => '<span class="btn btn-warning btn-sm" style="cursor: auto!important;"><i class="fa fa-frown-o"></i></span>',
                    'type'      => $hItem['type_modify'],
                    'manager'   => $hItem['manager']
                );
            } elseif((string)$hItem['date_modify']!=='0000-00-00 00:00:00'){
                $result['history'][] = array(
                    'date'      => date("d.m.Y", strtotime($hItem['date_modify'])),
                    'label'     => '<span class="btn btn-primary btn-sm" style="cursor: auto!important;"><i class="fa fa-upload"></i></span>',
                    'type'      => $hItem['type_modify'],
                    'manager'   => $hItem['manager']
                );
            }
        }
        //saled product
        if($result['quantity'] == '0' && $result['status'] == '0'){
            $sisup = $this->db->query("SELECT * FROM ".DB_PREFIX."sales_info WHERE sku = '".$result['vin']."'");
            if($sisup->num_rows){
                $result['date_sale'] = date("d.m.Y",strtotime($sisup->row['date']));
            } else {
                $result['date_sale'] = FALSE;
            }
        } else {
            $result['date_sale'] = FALSE;
        }
        
        return $result;
    }
    
    public function librSetSave($data) {
        $this->db->query("UPDATE ".DB_PREFIX."libraries SET ".$data['target']." = '".$data['value']."' WHERE library_id = ".(int)$data['library_id']);
    }
    
    public function getSmartVariants($req, $item) {
        $query = "SELECT * FROM ".DB_PREFIX."lib_fills WHERE item_id = ".(int)$item." ";
        foreach ($req as $word){
            $query.="AND (LOCATE('".$this->db->escape($word)."', name) OR LOCATE('".$this->db->escape($word)."', translate) )";
        }
        $result = $this->db->query($query);
        return $result->rows;
    }
    
    public function getSmartVarParents($req) {
        $result = array();
        $sup = $this->db->query("SELECT *, (SELECT ls.text FROM ".DB_PREFIX."lib_struct ls WHERE lf.item_id = ls.item_id) AS text FROM ".DB_PREFIX."lib_fills lf WHERE lf.id = ".(int)$req);
        $id = (int)$sup->row['id'];
        $goal = (int)$sup->row['parent_id'];
        while ($goal>0) {
            $sup = $this->db->query("SELECT *, (SELECT ls.text FROM ".DB_PREFIX."lib_struct ls WHERE lf.item_id = ls.item_id) AS text, (SELECT ls.name FROM ".DB_PREFIX."lib_struct ls WHERE lf.item_id = ls.item_id) AS itemName FROM ".DB_PREFIX."lib_fills lf WHERE lf.id = ".(int)$goal);            
            $result[] = $sup->row;
            $id = (int)$sup->row['id'];
            $goal = (int)$sup->row['parent_id'];
        }
        
        return $result;
    }
    
    public function getSimilarVariants($request, $target, $field, $opt) {
        $sql = '';
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."type_lib WHERE lib_id = ".(int)$opt);
        $fields = explode(",", $sup->row['similar']);
        $result = array();
        $sql = "SELECT * FROM ".DB_PREFIX.$target." WHERE 1 ";
        foreach ($request as $word) {
            $sql.= "AND LOCATE('".$this->db->escape($word)."', ".$field.") ";
        }
        $sup = $this->db->query($sql);
        $tmp = 0;
        foreach ($sup->rows as $row) {
            $text = '';
            foreach ($fields as $val) {
                $text.= $row[$val].' | ';
            }
            $text.= $row[$field];
            if(isset($row['product_id'])){
                $tmp = $row['product_id'];
            } elseif (isset($row['id'])) {
                $tmp = $row['id'];
            }
            $result[] = array('text' => $text, 'id' => $row[$field], 'tmp' => $tmp);
        }
        return $result;
    }
    
    public function getItemInfo($req) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."type_lib WHERE lib_id = ".$req['opt']);
        if($req['target']=='product'){
            $tmp = "product_id = ".(int)$req['tmp'];
        } else {
            $tmp = "id = ".(int)$req['tmp'];
        }
        return $this->db->query("SELECT ".$sup->row['similar']." FROM ".DB_PREFIX.$req['target']." WHERE ".$tmp." ")->row;
        
    }
}