<?php
    class ModelCommonExcelOld extends Model {
        
        public function confirmline($data) {
            //проверка строки на ошибки
            $queryMAN = $this->db->query("SELECT id AS manufacturer_id FROM ".DB_PREFIX."brand "
                         . "WHERE name = '".$data[2]."'");
            $queryMOD = $this->db->query("SELECT id AS model_id FROM ".DB_PREFIX."brand "
                            . "WHERE name = '".$data[3]."'");
            $queryMR = $this->db->query("SELECT id FROM ".DB_PREFIX."brand "
                            . "WHERE name = '".trim($data[4])."'");
            $cat = $this->db->query("SELECT category_id FROM ".DB_PREFIX."category_description "
                            . "WHERE name = '".$data[5]."'");
            $podcat = $this->db->query("SELECT category_id FROM ".DB_PREFIX."category_description "
                            . "WHERE name = '".$data[6]."'");
            
            if(!empty($queryMAN->row)) {
                $result['man'] = $queryMAN->row['manufacturer_id'];
            } else{
                $result['man'] = NULL;
            }
            if(!empty($queryMOD->row)) {
                $result['mod'] = $queryMOD->row['model_id'];
            } else{
                $result['mod'] = NULL;
            }
            if(!empty($queryMR->row)) {
                $result['mr'] = $queryMR->row['id'];
            } else{
                $result['mr'] = NULL;
            }
            if(!empty($cat->row)) {
                $result['cat'] = $cat->row['category_id'];
            } else{
                $result['cat'] = NULL;
            }
            if(!empty($podcat->row)) {
                $result['podcat'] = $podcat->row['category_id'];
            } else{
                $result['podcat'] = NULL;
            }
            
            return $result;
        }
        
        private function compatibility($str) {
            $comp_arr = explode('; ', $str);
            //exit(var_dump($comp));
            foreach ($comp_arr as $modR) {
                $query = $this->db->query("SELECT id, name FROM ".DB_PREFIX."brand WHERE name = '".$modR."' ");
                if(!empty($query->rows)){
                    $comp[] = $query->rows;
                } else {
                    return FALSE;
                }
            }
            if(is_array($comp)){
                $comp1 = array();
                foreach ($comp as $el){
                    foreach($el as $arr){
                        $comp1[] = array(
                            'id' => $arr['id'],
                            'name' => $arr['name']
                        );
                    }
                }
            }
            $comp = $comp1;
            return $comp;
        }
        
        public function allowed($data) {
            
            $this->load->language('common/excel');
            if($data[13]!=NULL){
                $comp = $this->compatibility($data[13]);
            } else {
                $comp = TRUE;
            }
            //exit(var_dump($comp));
            $sup = $this->confirmline($data);
            $upload_errs = array();
            if($sup['man'] == NULL){
                $upload_errs[] = $this->language->get('err').'Внутренний номер - <b>'.$data['vin'].'</b>; '.$data[2].' - производитель не найден.';
                $allow = FALSE;
            } else {
                if($sup['mod'] == NULL){
                    $upload_errs[] = $this->language->get('err').'Внутренний номер - <b>'.$data['vin'].'</b>; '.$data[3].' - модель не найдена.';
                    $allow = FALSE;
                } else{
                    if($sup['mr'] == NULL){
                        $upload_errs[] = $this->language->get('err').'Внутренний номер - <b>'.$data['vin'].'</b>; '.$data[4].' - модельный ряд не найден.';
                        $allow = FALSE;
                    } else {
                        if($sup['cat'] == NULL){
                            $upload_errs[] = $this->language->get('err').'Внутренний номер - <b>'.$data['vin'].'</b>; '.$data[5].' - категория не найдена.';
                            $allow = FALSE;
                        } else{
                            if($sup['podcat'] == NULL){
                                $upload_errs[] = $this->language->get('err').'Внутренний номер - <b>'.$data['vin'].'</b>; '.$data[6].' - подкатегория не найдена.';
                                $allow = FALSE;
                            } else {
                                if($comp){
                                    $allow = TRUE;
                                } else {
                                    $upload_errs[] = $this->language->get('err').'Внутренний номер - <b>'.$data['vin'].'</b>; '.$data[13].' - найдены ошибки в совместимости.';
                                    $allow = FALSE;
                                }
                            }
                        }
                    }
                }
            }
            
            $result = array(
                'errs' => $upload_errs,
                'allow' => $allow,
                'comp' => $comp,
                'sup' => $sup
            );
            
            return $result;
            
        }
        
        public function settodb($data, $files1, $image, $sup, $comp) {
            $vin = str_replace("/", "-", $data[8]);
            $name = $data[6] . " ". $data[2] ." ". $data[4];
            $tag = $data[2].', '.$data[3].', '.$data[4].', '.$data[6].', '.$name.', '.$data[12];
            $description = "<h6>Авторазбор174.рф</h6> предлагает Вам "
                . "купить ".$data[6] . " для автомобиля ". $data[2] ." ". $data[4].""
                . " со склада в г.Магнитогорске. <br><br>" 
                ."Авторазбор автозапчасти б/у для ".$data[2]." ".$data[4];
            if($data[11]!=NULL) {$description.="<h6><b>Примечание:</b></h6>".$data[11]."<br/>";}

            if ($data[19] != NULL) {
                $price = $data[19];
            }
            else {
                $price = 0;
            }
            if($data[20] != NULL){
                $quantity = $data[20];
            } else {
                $quantity = 0;
            }
        /* блок работы с комплектами */
            if($data[21]!=NULL){
                if($data[23]!=NULL){
                    $whole = 1;
                } else {
                    $whole = 0;
                }
                $heading = $vin;
                $name = $data[6];
                $price = $data[21];
                $this->load->model('complect/complect');
                $this->model_complect_complect->create($name, $price, $heading, $whole);
                $comp_id = $this->db->getLastId();
                $complect = "`comp` = '".$comp_id."', ";
            } elseif ($data[22]!=NULL) {
                $complect = "`comp` = '".$data[22]."', ";
        }
        /*****************************/
            $query = "INSERT INTO ".DB_PREFIX."product "
                        . "SET "
                            . "`manufacturer_id` = '". $sup['man'] ."', "
                            . "`model` = '". $data[3] ."', "
                            . "`jan` = '". $data[11] ."', "
                            . "`sku` = '". $vin ."', "
                            . "`upc` = '". $data[9] ."', "
                            . "`ean` = '". $data[10] ."', "
                            . "`location` = '".$data[15]."/".$data[16]."/".$data[17]."/".$data[18]."', "
                            . "`isbn` = '". $data[12] ."', "
                            . "`mpn` = '". $data[10] ."', "
                            . "`weight` = '". $data[14] ."', "
                            . "`price` = ". $price .", "
                            . "`image` = '". $image ."', "
                            . "`quantity` = ".$quantity.", "
                            . "`length` = '".$data[4]."', "
                            ."`status` = 1, "
                            ."`date_added` = NOW(), "
                            ."`date_available` = NOW(), "
                            ."`date_modified` = NOW(), "
                            . "avito = '".$data[0]."', "
                            . "drom = '".$data[1]."', "
                            .$complect
                            . "`stock_status_id` = 7";
            
            $this->db->query();
            
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
                            . "meta_description = '" . $tag . "', "
                            . "meta_keyword = '" . $tag . "'");

            $this->db->query("INSERT INTO ". DB_PREFIX ."product_to_store "
                    . "SET "
                    . "product_id = '".(int)$product_id."',"
                    . "store_id = 0");

            $category_id = $sup['cat'];
            $podcategory_id = $sup['podcat'];

            $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category "
                        . "SET "
                        . "product_id = '" . (int)$product_id . "', "
                        . "category_id = '" . (int)$sup['cat'] . "', "
                        . "main_category = 1");

            $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category "
                        . "SET "
                        . "product_id = '" . (int)$product_id . "', "
                        . "category_id = '" . (int)$sup['podcat'] . "'");

            $this->db->query("INSERT INTO ". DB_PREFIX ."url_alias "
                . "SET "
                . "query = 'product_id=".(int)$product_id."'");

            if(!empty($files1)){
                foreach ($files1 as $file){
                    $this->db->query("INSERT INTO ". DB_PREFIX ."product_image "
                            . "SET "
                            . "product_id = ". $product_id .", "
                            . "image = 'catalog/demo/production/".$vin."/".$file."' ");
                }
            }
            
            $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand "
                . "SET "
                . "product_id = ". $product_id .", "
                . "brand_id = ".$sup['man']);
            $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand "
                . "SET "
                . "product_id = ". $product_id .", "
                . "brand_id = ".$sup['mod']);
            $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand "
                . "SET "
                . "product_id = ". $product_id .", "
                . "brand_id = ".$sup['mr']);
            if((is_array($comp)) && (!empty($comp))){
                $compability = '';
                foreach ($comp as $modR){
                    $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand "
                        . "SET "
                        . "product_id = ". $product_id .", "
                        . "brand_id = ".$modR['id'].";");
                    $compability.=$modR['name'].'; ';
                }
                $this->db->query("UPDATE ".DB_PREFIX."product SET `compability` = '".$compability."' WHERE product_id = '".$product_id."' ");
            }
        }
        
        public function getProds($filter) {
            
            //exit(var_dump($filter));
            /*
             * берём весь массив продуктов и поочерёдно применяем фильтр к нему
            */
            $result = array();
            $products = $this->getallprods();
            
        //************************************************************************************************************    
            if($filter['brand'] != ''){
                $f_brand = $this->getFilter($products, $filter['prodquan'], 0, 0, 0, $filter['brand'], 0, 0);
                if($filter['mod']!=''){
                    $help_arr = $this->getFilter($f_brand, $filter['prodquan'], 0, 0, 0, 0, $filter['mod'], 0);
                    $f_brand = $help_arr;
                    if($filter['mr']!=''){
                        $help_arr = $this->getFilter($f_brand, $filter['prodquan'], 0, 0, 0, 0, 0, $filter['mr']);
                        $f_brand = $help_arr;
                    }
                }
                $products = $f_brand;
            } //на выходе массив продуктов отфильтрованных по марке/модели/модельному ряду.
        //************************************************************************************************************
            if($filter['cat']!=''){
                $f_cat = $this->getFilter($products, $filter['prodquan'], 0, $filter['cat'], 0, 0, 0, 0);
                if($filter['podcat']!=''){
                    $help_arr = $this->getFilter($f_cat, $filter['prodquan'], 0, 0, $filter['podcat'], 0, 0, 0);
                    $f_cat = $help_arr;
                }
                $products = $f_cat;
            }//на выходе массив продуктов отфильтрованных по категории/подкатегории
        //************************************************************************************************************
            if($filter['stock_info']!=''){
                $products = $this->getFilter($products, $filter['prodquan'], $filter['stock_info'], 0, 0, 0, 0, 0);
            }
        //************************************************************************************************************
            $products = $this->getFilter($products, $filter['prodquan'], 0, 0, 0, 0, 0, 0);
            return $products;
        }
        
        public function getallprods() {
            
            $query = "SELECT "
                        . "p.avito AS avito, "
                        . "p.drom AS drom, "
                        . "p.sku AS vin, "
                        . "p.model AS model, "
                        . "p.upc AS cond, "
                        . "p.ean AS type, "
                        . "p.manufacturer_id AS man_id, "
                        . "p.jan AS note, "
                        . "p.isbn AS cat_numb, "
                        . "p.location AS locate, "
                        . "p.quantity AS quan, "
                        . "p.price AS price, "
                        . "p.length AS mod_row, "
                        . "p.comp AS comp, "
                        . "p.weight AS stock, "
                        . "pd.name AS name, "
                        . "p.product_id AS pid, "
                        . "c.price AS c_price, "
                        . "c.whole AS c_whole "
                    . "FROM ".DB_PREFIX."product p "
                    . "LEFT JOIN ".DB_PREFIX."product_description pd "
                        . "ON p.product_id = pd.product_id "
                    . "LEFT JOIN ".DB_PREFIX."complects c "
                        . "ON p.sku = c.heading "
                    . "WHERE p.sku != '' ";
            
            $result_quer = $this->db->query($query);
            $i=0;
            foreach($result_quer->rows as $res){
                if($res['man_id']!=NULL){
                    $result[$i] = array(
                        'avito' => $res['avito'],
                        'drom' => $res['drom'],
                        'pid' => $res['pid'],
                        'vin' => $res['vin'],
                        'model' => $res['model'],
                        'cond' => $res['cond'],
                        'type' => $res['type'],
                        'note' => $res['note'],
                        'cat_numb' => $res['cat_numb'],
                        'locate' => $res['locate'],
                        'quan' => $res['quan'],
                        'price' => $res['price'],
                        'mod_row' => $res['mod_row'],
                        'comp' => $res['comp'],
                        'stock' => $res['stock'],
                        'name' => $res['name'],
                        'comp' => $res['comp'],
                        'c_whole' => isset($res['c_whole']) ? (($res['c_whole']==1)?'комплектом':'') : '',
                        'c_price' => isset($res['c_price']) ? $res['c_price'] : ''
                    );

                    $prod2cat = $this->db->query("SELECT "
                                                    . "cd.name AS name, "
                                                    . "c.parent_id AS child "
                                                . "FROM ".DB_PREFIX."product_to_category p2c "
                                                . "LEFT JOIN ".DB_PREFIX."category c "
                                                    . "ON p2c.category_id = c.category_id "
                                                . "LEFT JOIN ".DB_PREFIX."category_description cd "
                                                    . "ON cd.category_id = c.category_id "
                                                . "WHERE p2c.product_id = '".$res['pid']."'");
                    $quer = $this->db->query("SELECT name FROM ".DB_PREFIX."brand WHERE id = '".$res['man_id']."'");
                    if(!empty($quer->row)){$bName = $quer->row['name'];} else {$bName = '';}
                    foreach ($prod2cat->rows as $p2c){
                        if($p2c['child'] == 0){
                            $result[$i]['cat'] = isset($p2c['name'])?$p2c['name']:'';
                        } else{
                            $result[$i]['podcat'] = isset($p2c['name'])?$p2c['name']:'';
                            if(!isset($result[$i]['cat'])){
                                $quer = $this->db->query("SELECT name FROM ".DB_PREFIX."category_description WHERE category_id = '".$p2c['child']."'");
                                $result[$i]['cat'] = isset($quer->row['name'])?$quer->row['name']:'';
                            }
                        }
                    }
                    if (!isset($result[$i]['podcat'])) {
                            $result[$i]['podcat'] = '';
                        }
                    $result[$i]['brand'] = $bName;
                    ++$i;
                }
            }
            return $result;
        }
        
        public function getFilter($products, $prodquan, $stocks, $cat, $podcat, $brand, $model, $mr) {
            //exit(var_dump($products));
            $filterResult = array();
        //***фильтруем по марке*************************************************************************************
            if($brand!=0){
                $quer = $this->db->query("SELECT name FROM ".DB_PREFIX."brand WHERE id = '".$brand."' ");
                $bname = $quer->row['name'];
                //exit(var_dump($bname));
                foreach($products as $prod){
                    if($prod['brand'] == $bname){
                        $filterResult[] = $prod;
                    }
                }
                return $filterResult;
            }
        //***фильтруем по модели*************************************************************************************
            if($model!=0){
                $quer = $this->db->query("SELECT name FROM ".DB_PREFIX."brand WHERE id = '".$model."' ");
                $bname = $quer->row['name'];
                //exit(var_dump($bname));
                foreach($products as $prod){
                    if($prod['model'] == $bname){
                        $filterResult[] = $prod;
                    }
                }
                return $filterResult;
            }
        //***фильтруем по модельному ряду***************************************************************************
            if($mr!=0){
                $quer = $this->db->query("SELECT name FROM ".DB_PREFIX."brand WHERE id = '".$mr."' ");
                $bname = $quer->row['name'];
                //exit(var_dump($bname));
                foreach($products as $prod){
                    if($prod['mod_row'] == $bname){
                        $filterResult[] = $prod;
                    }
                }
                return $filterResult;
            }
        //***фильтруем по категории*******************************************************************************************
            if($cat!=0){
                $quer = $this->db->query("SELECT name FROM ".DB_PREFIX."category_description WHERE category_id = '".$cat."' ");
                $cname = $quer->row['name'];
                //exit(var_dump($bname));
                foreach($products as $prod){
                    if($prod['cat'] == $cname){
                        $filterResult[] = $prod;
                    }
                }
                return $filterResult;
            }
        //***фильтруем по подкатегории******************************************************************************
            if($podcat!=0){
                $quer = $this->db->query("SELECT name FROM ".DB_PREFIX."category_description WHERE category_id = '".$podcat."' ");
                $pcname = $quer->row['name'];
                //exit(var_dump($bname));
                foreach($products as $prod){
                    if($prod['podcat'] == $pcname){
                        $filterResult[] = $prod;
                    }
                }
                return $filterResult;
            }
        //***фильтр по данным склада*******************************************************************************
            if($stocks!=0){
                foreach($products as $prod){
                    list($still, $jar, $shelf, $box) = explode("/", $prod['locate']);
                    foreach ($stocks as $stock) {
                        if($prod['stock'] == $stock['stock']){
                            if($stock['still']!=''){
                                if($still == $stock['still']){
                                    if($stock['jar']!=''){
                                        if($jar == $stock['jar']){
                                            if($stock['shelf']!=''){
                                                if($stock['shelf']==$shelf){
                                                    if($stock['box']!=''){
                                                        if($box == $stock['box']){
                                                            $filterResult[] = $prod;
                                                        }
                                                    } else {
                                                        $filterResult[] = $prod;
                                                    }
                                                }
                                            } else {
                                                $filterResult[] = $prod;
                                            }
                                        }
                                    } else {
                                        $filterResult[] = $prod;
                                    }
                                }
                            } else {
                                $filterResult[] = $prod;
                            }
                        }
                    }
                }
                return $filterResult;
            }
        //***фмльтруем по количеству*******************************************************************************************    
        foreach ($products as $prod) {
            if($prodquan == 0){
                if($prod['quan'] == $prodquan){$filterResult[] = $prod;}
            } elseif($prodquan == 1) {
                if($prod['quan'] >= $prodquan){$filterResult[] = $prod;}
            } else {
                if($prod['quan'] >= $prodquan){$filterResult[] = $prod;}
            }
        }
            return $filterResult;
        }
        
    }