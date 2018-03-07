<?php
    class ModelCommonExcel extends Model {
        
        public function getProductTemplate() {
            $query = $this->db->query("SELECT * FROM ".DB_PREFIX."excel_template ORDER BY id");
            $template = array();
            foreach ($query->rows as $row) {
                $template[$row['id']] = array(
                    'name' => trim($row['name']),
                    'text' => trim($row['text']),
                    'important' => trim($row['important']),
                    'system' => trim($row['system'])
                );
            }
            return $template;
        }
        
        public function getdescripttemplate(){
            $query = $this->db->query("SELECT text FROM ".DB_PREFIX."text_template WHERE id = 1 ");
            return $query->row['text'];
        }
        
        public function constructDescription($template, $product){
            
            $this->load->controller('common/desctemp');
            $descT = new ControllerCommonDescTemp($this->registry);
            $regex = $descT->regex;
            foreach ($regex as $key => $ex) {
                $template = str_replace($ex, $product[$key], $template);
            }
            return $template;
        }
        
        public function getListProds($quer=0) {
            $cell = $quer===0?'sku':$quer;
            $query = $this->db->query("SELECT * FROM ".DB_PREFIX."product WHERE sku != '' ");
            $results = array();
            foreach ($query->rows as $row) {
                $results[] = $row[$cell];
            }
            return $results;
        }
        
        public function getbrands() {
            $query = $this->db->query("SELECT name FROM ".DB_PREFIX."brand WHERE 1 ");
            $brands = array();
            foreach ($query->rows as $row) {
                $brands[] = mb_convert_case(trim($row['name']), MB_CASE_UPPER);
            }
            return $brands;
        }
        
        public function getcategories() {
            $query = $this->db->query("SELECT name FROM ".DB_PREFIX."category_description WHERE 1 ");
            $cats = array();
            foreach ($query->rows as $row) {
                $cats[] = mb_convert_case(trim($row['name']), MB_CASE_UPPER);
            }
            return $cats;
        }
        
        public function setToDB($data, $vin, $descripttemplate, $images, $comp = 0, $manager) {
            
            $quer = $this->db->query("SELECT * FROM ".DB_PREFIX."product WHERE sku = '".$vin."' ");
            if(empty($quer->rows)){
            /* в этом блоке объединяем некоторые разрозненные данные по товару в переменные */
                $name = $data['podcat'] . " ". $data['brand'] ." ". $data['modr'];
                $tag = $data['brand'].', '.$data['model'].', '.$data['modr'].', '.$data['podcat'].', '.$name.', '.$data['catn'];
                $description = $this->constructDescription($descripttemplate, $data);
                $price = $data['price']!=NULL?$data['price']:0;
                $quantity = $data['quant']!=NULL?$data['quant']:0;
                $date = $data['date']!=''?"'".$data['date']."'":"NOW()";
                $query = $this->db->query("SELECT id FROM ".DB_PREFIX."brand WHERE name = '".$data['brand']."' ");
                $brand_id = $query->row['id'];
                
                $query = $this->db->query("SELECT id FROM ".DB_PREFIX."brand WHERE name = '".$data['model']."' ");
                $mod_id = $query->row['id'];
                
                $query = $this->db->query("SELECT id FROM ".DB_PREFIX."brand WHERE name = '".$data['modr']."' ");
                $modr_id = $query->row['id'];
                
                $query = $this->db->query("SELECT category_id FROM ".DB_PREFIX."category_description WHERE name = '".$data['category']."' ");
                $cat_id = $query->row['category_id'];
                
                $query = $this->db->query("SELECT category_id FROM ".DB_PREFIX."category_description WHERE name = '".$data['podcat']."' ");
                $podcat_id = $query->row['category_id'];
                
                $image = ((is_array($images)) && (!empty($images)))?'catalog/demo/production/'.$vin.'/'.$images[0]:' ';
                $comp_price = $data['comp_price']!=NULL?$data['comp_price']:'';
                $complect = '';
                $comp_whole = $data['whole']!=NULL?1:'';
                $donor = $data['donor']!=NULL?$data['donor']:'';
            /*******************************************************************************/
            /******************** блок работы с комплектами ********************************/
                if($data['comp_price']!=NULL){
                    $whole = $data['whole']!=NULL?1:0;
                    $heading = $vin;
                    $c_price = $data['comp_price'];
                    $this->load->model('complect/complect');
                    $this->model_complect_complect->create($name, $c_price, $heading, 0, $whole);
                    $query = $this->db->query("SELECT id FROM ".DB_PREFIX."complects WHERE heading = '".$heading."' ");
                    $comp_id = $query->row['id'];
                    $complect = "`comp` = '".$comp_id."', ";
                } elseif ($data['complect']!=NULL) {
                    $complect = "`comp` = '".$data['complect']."', ";
                }
                $status = $quantity!=0?1:0;
            /******************************************************************************/
            /******* окончательно сформированный товар укладываем в базу потаблично *******/
                $query = "INSERT INTO ".DB_PREFIX."product "
                        . "SET "
                            . "`manufacturer_id` = '". $brand_id ."', "
                            . "`model` = '". $data['model'] ."', "
                            . "`category` = '". $data['category'] ."', "
                            . "`podcateg` = '". $data['podcat'] ."', "
                            . "`jan` = '". $data['note'] ."', "
                            . "`sku` = '". $vin ."', "
                            . "`upc` = '". $data['condit'] ."', "
                            . "`height` = '". $donor ."', "
                            . "`ean` = '". $data['type'] ."', "
                            . "`location` = '".$data['still']."/".$data['jar']."/".$data['shelf']."/".$data['box']."', "
                            . "`isbn` = '". $data['catn'] ."', "
                            . "`mpn` = '". $data['type'] ."', "
                            . "`weight` = '". $data['stock'] ."', "
                            . "`price` = ". $price .", "
                            . "`image` = '". $image ."', "
                            . "`quantity` = ".$quantity.", "
                            . "`comp_price` = '".$comp_price."', "
                            . "`comp_whole` = '".$comp_whole."', "
                            . "`manager` = '".$manager."', "
                            . "`length` = '".$data['modr']."', "
                            ."`status` = ".(int)$status.", "
                            ."`date_added` = ".$date.", "
                            ."`date_available` = NOW(), "
                            ."`date_modified` = NOW(), "
                            . "avito = '".$data['avito']."', "
                            . "drom = '".$data['drom']."', "
                            .$complect. "`stock_status_id` = 7 ";
                $this->db->query($query);
                $product_id = $this->db->getLastId();
                
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_description "
                            . "SET "
                            . "product_id = '" . (int)$product_id . "', "
                            . "language_id = 1, "
                            . "name = '" . $name ."', "
                            . "description = '".$description."', "
                            . "tag =  '".$tag."', "
                            . "meta_title = '" . $name . "', "
                            . "meta_h1 = '" . $name . "', "
                            . "meta_description = '" . $description . "', "
                            . "meta_keyword = '" . $tag . "'");
                
                $this->db->query("INSERT INTO ". DB_PREFIX ."product_to_store "
                    . "SET "
                    . "product_id = '".(int)$product_id."',"
                    . "store_id = 0");
                
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category "
                                . "SET "
                                . "product_id = '" . (int)$product_id . "', "
                                . "category_id = '" . (int)$cat_id . "', "
                                . "main_category = 1");

                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category "
                                . "SET "
                                . "product_id = '" . (int)$product_id . "', "
                                . "category_id = '" . (int)$podcat_id . "'");

                $this->db->query("INSERT INTO ". DB_PREFIX ."url_alias "
                                . "SET "
                                . "query = 'product_id=".(int)$product_id."'");
                
                $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand "
                                . "SET "
                                . "product_id = ". $product_id .", "
                                . "brand_id = ".$brand_id);
                
                $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand "
                                . "SET "
                                . "product_id = ". $product_id .", "
                                . "brand_id = ".$mod_id);
                
                $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand "
                                . "SET "
                                . "product_id = ". $product_id .", "
                                . "brand_id = ".$modr_id);
                
                if((!empty($images))){
                    foreach ($images as $file){
                        if($file!=' '){
                            $this->db->query("INSERT INTO ". DB_PREFIX ."product_image "
                                . "SET "
                                . "product_id = ". $product_id .", "
                                . "image = 'catalog/demo/production/".$vin."/".$file."' ");
                        }
                    }
                }
                
                if(!empty($comp)){
                    foreach ($comp as $modR){
                        $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand "
                            . "SET "
                            . "product_id = ". $product_id .", "
                            . "brand_id = ".$modR['id']."; ");
                    }
                }
            /******************************************************************************/ 
            }   
        }
        
        public function synchToDB($data) {
            $this->db->query("UPDATE ".DB_PREFIX."product "
                . "SET quantity = ".$data['quant'].", "
                    . "status = 0, "
					. "price = ".$data['price'].", "
					. "image = '' "
                . "WHERE sku = '".$data['vnutn']."';");
            $dir = DIR_IMAGE."catalog/demo/production/".$data['vnutn']."/";
            $this->removeDirectory($dir);
            
            $prod = $this->db->query("SELECT product_id FROM ".DB_PREFIX."product WHERE sku = '".$data['vnutn']."'");
            $prod_id = $prod->row['product_id'];

            $this->db->query("DELETE FROM ".DB_PREFIX."product_image "
                    . "WHERE product_id = '".$prod_id."'");
        }
        
        public function removeDirectory($dir) {
            if(is_dir($dir)){
                $objs = scandir($dir);
                array_shift($objs);
                array_shift($objs);

                foreach($objs as $obj) {
                    $objct = $dir;
                    $objct.= $obj;
					unlink($objct);
                }
                rmdir($dir);
            }
        }
        
        public function updateToDB($data) {
            $status = $data['quant']!=0?1:0;
            $this->db->query("UPDATE ".DB_PREFIX."product "
                . "SET "
                    . "upc = '".$data['condit']."', "
                    . "ean = '".$data['type']."', "
                    . "quantity = '".$data['quant']."', "
                    . "jan = '".$data['note']."', "
                    . "isbn = '".$data['catn']."', "
                    . "weight = '".$data['stock']."', "
                    . "location = '".$data['still']."/".$data['jar']."/".$data['shelf']."/".$data['box']."', "
                    . "price = '".$data['price']."', "
                    . "status = '".$status."', "
                    . "comp = '".$data['complect']."', "
                    . "drom = '".$data['drom']."', "
                    . "avito = '".$data['avito']."' "
                . "WHERE sku = '".$data['vnutn']."';");
        }
        
        public function haveImg($id) {
            $query = $this->db->query("SELECT * FROM ".DB_PREFIX."product_image WHERE product_id = ".(int)$id);
            $result = !empty($query->row)?TRUE:FALSE;
            return $result;
        }
        
        public function setImg($id, $dir) {
            $files = scandir(DIR_IMAGE.$dir);
            array_shift($files);
            array_shift($files);
            
            foreach($files as $image){
                if($image!='Thumbs.db'){
                    $this->db->query("INSERT INTO ".DB_PREFIX."product_image "
                            . "SET "
                            . "product_id = ".(int)$id.", "
                            . "image = '".$dir.$image."' ");
                    $this->db->query("UPDATE ".DB_PREFIX."product "
                            . "SET "
                            . "image = '".$dir.$files[0]."' "
                            . "WHERE product_id = ".(int)$id);
                }
            }
        }
        
        public function getParam($param, $id){
            $table = $param; 
            $field = 'id';
            if($param == 'category'){
                $table = $param.'_description';
                $field = $param.'_id';
            }
            $query = $this->db->query("SELECT name FROM ".DB_PREFIX.$table." WHERE ".$field." = ".(int)$id);
            return $query->row['name'];
        }
        
        public function getProductsInfo($listProdsId, $template, $filter) {
            $result = array();
            foreach($listProdsId as $id){
                $prod = array();
                //avito
                $query = $this->db->query("SELECT avito FROM ".DB_PREFIX."product WHERE product_id = ".(int)$id);
                $prod[$template[0]['name']] = $query->row['avito'];
                //drom
                $query = $this->db->query("SELECT drom FROM ".DB_PREFIX."product WHERE product_id = ".(int)$id);
                $prod[$template[1]['name']] = $query->row['drom'];
                //brand
                    $query = $this->db->query("SELECT b.name AS brand FROM ".DB_PREFIX."brand b "
                            . "LEFT JOIN ".DB_PREFIX."product p ON p.product_id = ".(int)$id
                            ." WHERE p.manufacturer_id = b.id AND p.status = 1 ");
                    if($filter['brand']){
                        if($filter['brand']==$query->row['brand']){
                            $prod[$template[2]['name']] = $query->row['brand'];
                        } else {continue;}
                    } else {
                        $prod[$template[2]['name']] = $query->row['brand'];
                    }
                //model
                    $query = $this->db->query("SELECT model FROM ".DB_PREFIX."product WHERE product_id = ".(int)$id);
                    if($filter['model']){
                        if($filter['model']==$query->row['model']){
                            $prod[$template[3]['name']] = $query->row['model'];
                        } else {continue;}
                    } else {
                        $prod[$template[3]['name']] = $query->row['model'];
                    }
                //modr
                    $query = $this->db->query("SELECT length AS modr FROM ".DB_PREFIX."product WHERE product_id = ".(int)$id);
                    if($filter['modr']){
                        if($filter['modr']==$query->row['modr']){
                            $prod[$template[4]['name']] = $query->row['modr'];
                        } else {continue;}
                    } else {
                        $prod[$template[4]['name']] = $query->row['modr'];
                    }
                    
                //category
                    $query = $this->db->query("SELECT cd.name AS category FROM ".DB_PREFIX."category_description cd "
                                                . "LEFT JOIN ".DB_PREFIX."product_to_category p2c ON p2c.product_id = ".(int)$id
                                                . " LEFT JOIN ".DB_PREFIX."category c ON p2c.category_id = c.category_id "
                                            . "WHERE c.parent_id = 0 AND cd.category_id = c.category_id");
                    if($filter['category']){
                        if($filter['category']==$query->row['category']){
                            $prod[$template[5]['name']] = $query->row['category'];
                        } else {continue;}
                    } else {
                        $prod[$template[5]['name']] = $query->row['category'];
                    }
                //podcat
                    $query = $this->db->query("SELECT cd.name AS podcat FROM ".DB_PREFIX."category_description cd "
                                                . "LEFT JOIN ".DB_PREFIX."product_to_category p2c ON p2c.product_id = ".(int)$id
                                                . " LEFT JOIN ".DB_PREFIX."category c ON p2c.category_id = c.category_id "
                                            . "WHERE c.parent_id != 0 AND cd.category_id = c.category_id");
                    if($filter['podcat']){
                        if($filter['podcat']==$query->row['podcat']){
                            $prod[$template[6]['name']] = $query->row['podcat'];
                        } else {continue;}
                    } else {
                        $prod[$template[6]['name']] = $query->row['podcat'];
                    }
                //name
                $query = $this->db->query("SELECT name FROM ".DB_PREFIX."product_description WHERE product_id = ".(int)$id);
                $prod[$template[7]['name']] = $query->row['name'];
                //vnutn
                $query = $this->db->query("SELECT sku FROM ".DB_PREFIX."product WHERE product_id = ".(int)$id);
                $prod[$template[8]['name']] = $query->row['sku'];
                //condit
                $query = $this->db->query("SELECT upc FROM ".DB_PREFIX."product WHERE product_id = ".(int)$id);
                $prod[$template[9]['name']] = $query->row['upc'];
                //type
                $query = $this->db->query("SELECT ean FROM ".DB_PREFIX."product WHERE product_id = ".(int)$id);
                $prod[$template[10]['name']] = $query->row['ean'];
                //note
                $query = $this->db->query("SELECT jan FROM ".DB_PREFIX."product WHERE product_id = ".(int)$id);
                $prod[$template[11]['name']] = $query->row['jan'];
                //catn
                $query = $this->db->query("SELECT isbn FROM ".DB_PREFIX."product WHERE product_id = ".(int)$id);
                $prod[$template[12]['name']] = $query->row['isbn'];
                //comp
                $prod[$template[13]['name']] = '';
                //stock
                    $query = $this->db->query("SELECT weight FROM ".DB_PREFIX."product WHERE product_id = ".(int)$id);
                    if($filter['stock']){
                        foreach ($filter['stock'] as $key => $stock){
                            $stock[] = $key;
                        }
                        if(in_array($query->row['weight'], $stock)){
                            $prod[$template[14]['name']] = $query->row['weight'];
                        } else {continue;}
                    } else {
                        $prod[$template[14]['name']] = $query->row['weight'];
                    }
                //still//jar//shelf//box
                    $query = $this->db->query("SELECT location FROM ".DB_PREFIX."product WHERE product_id = ".(int)$id);
                    list($still, $jar, $shelf, $box) = explode("/", $query->row['location']);
                    if($filter['stock'][$prod[$template[14]['name']]]['still']!=''){
                        if($filter['stock'][$prod[$template[14]['name']]]['still']==$still){
                            $prod[$template[15]['name']] = $still;
                        } else {continue;}
                    } else {
                        $prod[$template[15]['name']] = $still;
                    }

                    if($filter['stock'][$prod[$template[14]['name']]]['jar']!=''){
                        if($filter['stock'][$prod[$template[14]['name']]]['jar']==$jar){
                            $prod[$template[16]['name']] = $jar;
                        } else {continue;}
                    } else {
                        $prod[$template[16]['name']] = $jar;
                    }

                    if($filter['stock'][$prod[$template[14]['name']]]['shelf']!=''){
                        if($filter['stock'][$prod[$template[14]['name']]]['shelf']==$shelf){
                             $prod[$template[17]['name']] = $shelf;
                        } else {continue;}
                    } else {
                         $prod[$template[17]['name']] = $shelf;
                    }
                    
                    if($filter['stock'][$prod[$template[14]['name']]]['box']!=''){
                        if($filter['stock'][$prod[$template[14]['name']]]['box']==$box){
                             $prod[$template[18]['name']] = $box;
                        } else {continue;}
                    } else {
                         $prod[$template[18]['name']] = $box;
                    }
                //price
                    $query = $this->db->query("SELECT price FROM ".DB_PREFIX."product WHERE product_id = ".(int)$id);
                    $prod[$template[19]['name']] = $query->row['price'];
                //quant
                    $quan = '';
                    if($filter['prod_on']){
                        if($filter['prod_off']){
                            $quan = " AND quantity >= -1 ";
                        }
                        $quan = " AND quantity >= 1 ";
                    } elseif ($filter['prod_off']) {
                        $quan = " AND quantity = 0 ";
                    }
                    $query = $this->db->query("SELECT quantity FROM ".DB_PREFIX."product WHERE product_id = ".(int)$id.$quan);
                    if(!empty($query->row)){
                        $prod[$template[20]['name']] = $query->row['quantity'];
                    } else {continue;}
                //complects
                    $query = $this->db->query("SELECT * FROM ".DB_PREFIX."complects WHERE heading = '".$prod['vnutn']."' ");
                    if(!empty($query->row)){
                        $prod[$template[21]['name']] = $query->row['price'];
                        $prod[$template[23]['name']] = $query->row['whole'];
                        $prod[$template[22]['name']] = '';
                    } else {
                        $query = $this->db->query("SELECT comp FROM ".DB_PREFIX."product WHERE product_id = ".(int)$id);
                        $prod[$template[22]['name']] = $query->row['comp'];
                        $prod[$template[21]['name']] = '';
                        $prod[$template[23]['name']] = '';
                    }
                    $result[] = $prod;
            }
            return $result;
        }
        
        public function searchingProds($request) {
            $reqwords = explode(" ", $request);
            
            $query = "SELECT pd.name AS name, p.sku AS vin FROM ".DB_PREFIX."product_description pd "
                        . "LEFT JOIN ".DB_PREFIX."product p "
                            . "ON pd.product_id = p.product_id "
                        . "WHERE 1 ";
            foreach ($reqwords as $word){
                $query.="AND LOCATE ('" . $this->db->escape($word) . "', pd.name) ";
            }
            $result = $this->db->query($query);
            return $result->rows;
        }
        
        public function constructQuery($filter) {
            $template = $this->getProductTemplate();
            $query = "SELECT "
                        . "p.avito AS ".$template[0]['name'].", "
                        . "p.drom AS ".$template[1]['name'].", "
                        . "b.name AS ".$template[2]['name'].", "
                        . "p.model AS ".$template[3]['name'].", "
                        . "p.length AS ".$template[4]['name'].", "
                        . "p.category AS ".$template[5]['name'].", "
                        . "p.podcateg AS ".$template[6]['name'].", "
                        . "pd.name AS ".$template[7]['name'].", "
                        . "p.sku AS ".$template[8]['name'].", "
                        . "p.upc AS ".$template[9]['name'].", "
                        . "p.ean AS ".$template[10]['name'].", "
                        . "p.jan AS ".$template[11]['name'].", "
                        . "p.width AS ".$template[12]['name'].", "
                        . "p.isbn AS ".$template[13]['name'].", "
                        . "p.compability AS ".$template[14]['name'].", "
                        . "p.weight AS ".$template[15]['name'].", "
                        . "p.location AS location, "
                        . "p.price AS ".$template[20]['name'].", "
                        . "p.comp AS ".$template[23]['name'].", "
                        . "p.comp_price AS ".$template[22]['name'].", "
                        . "p.comp_whole AS ".$template[24]['name'].", "
                        . "p.height AS ".$template[25]['name'].", "
                        . "p.date_added AS ".$template[26]['name'].", "
                        . "p.quantity AS ".$template[21]['name']." "
                        . "FROM ".DB_PREFIX."product p "
                        . "LEFT JOIN ".DB_PREFIX."brand b ON p.manufacturer_id = b.id "
                        . "LEFT JOIN ".DB_PREFIX."product_description pd ON p.product_id = pd.product_id "
                        . "WHERE p.category != '' ";
            $query.=$filter['manager'];
            if($filter){
                if($filter['brand']){
                    if($filter['model']){
                        if($filter['modr']){
                            $query.="AND p.length = '".$filter['modr']."' ";
                        } else {
                            $query.="AND p.model = '".$filter['model']."' ";
                        }
                    } else {
                        $query.="AND b.name = '".$filter['brand']."' ";
                    }
                }
                if($filter['category']){
                    if($filter['podcat']){
                        $query.="AND p.podcateg = '".$filter['podcat']."' ";
                    } else {
                        $query.="AND p.category = '".$filter['category']."' ";
                    }
                }
                if($filter['prod_on']){
                    if(!$filter['prod_off']){
                        $query.="AND p.quantity >= 1 ";
                    } else {
                        $query.="AND p.quantity >= 0 ";
                    }
                } else {
                    if($filter['prod_off']){
                        $query.="AND p.quantity = '0' ";
                    }
                }
                if($filter['stock']){
                    $query.="AND (0 ";
                    foreach ($filter['stock'] as $stock => $value) {
                        $query.="OR p.weight = '".$stock."' ";
                    }
                    $query.=") ";
                }
                if($filter['date_start']){
                    $query.="AND p.date_added >= '".$filter['date_start']."' ";
                }
                if($filter['date_end']){
                    $query.="AND p.date_added <= '".$filter['date_end']."' ";
                }
            }
            $query.="ORDER BY p.sku";
//            exit(var_dump($query));
            return $query;
        }


        public function getInfoProducts($filter) {
            $query = $this->constructQuery($filter);
            $products = $this->db->query($query);
            $prods = $products->rows;
            $allow = TRUE;
            $product_info = array();
            for($i = 0; $i<count($prods); ++$i){
                $locate = explode("/", $prods[$i]['location']);
                
                $still = isset($locate[0])?$locate[0]:'';
                $jar = isset($locate[1])?$locate[1]:'';
                $shelf = isset($locate[2])?$locate[2]:'';
                $box = isset($locate[3])?$locate[3]:'';
                    
                $prods[$i]['still'] = $still;
                $prods[$i]['jar'] = $jar;
                $prods[$i]['shelf'] = $shelf;
                $prods[$i]['box'] = $box;
                if($filter){
                    if($filter['stock']){
                        foreach ($filter['stock'] as $stock => $sInfo) {
                /*------------------------------------------------------------------*/
                            if($prods[$i]['stock'] == $stock){
                                if($sInfo['still']){
                                    if($sInfo['still']==$prods[$i]['still']){
                                        if($sInfo['jar']){
                                            if($sInfo['jar']==$prods[$i]['jar']){
                                                if($sInfo['shelf']){
                                                    if($sInfo['shelf']==$prods[$i]['shelf']){
                                                        if ($sInfo['box']){
                                                            if($sInfo['box']==$prods[$i]['box']){
                                                                $allow = TRUE;
                                                            } else {
                                                                $allow = false;
                                                            }
                                                        } else {
                                                            $allow = TRUE;
                                                        }
                                                    } else {
                                                        $allow = FALSE;
                                                    }
                                                } else {
                                                    $allow = TRUE;
                                                }
                                            } else {
                                                $allow = false;
                                            }
                                        } else {
                                            $allow = TRUE;
                                        }
                                    } else{
                                        $allow = false;
                                    }
                                }
                            }
                /*------------------------------------------------------------------*/
                        }
                    }
                }
                if($allow){
                    $product_info[] = $prods[$i];
                }
            }
            //exit(var_dump($product_info));
            return $product_info;
        }
    }