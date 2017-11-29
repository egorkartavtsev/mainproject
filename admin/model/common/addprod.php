<?php
class ModelCommonAddProd extends Model {
    
    public function setPhoto($vin) {
        $uploadtmpdir = DIR_IMAGE . "tmp/";
        mkdir(DIR_IMAGE . "catalog/demo/production/".$vin);
        $uploaddir = DIR_IMAGE . "catalog/demo/production/".$vin."/";
        $watermark = imagecreatefrompng(DIR_IMAGE . "watermark.png");

        $photo = array();

        $i = 0;
        foreach ($_FILES['photo']['name'] as $crit){
            $photo[$i]['name'] = $crit;
            $i++;
        }
        $i = 0;
        foreach ($_FILES['photo']['type'] as $crit){
            $photo[$i]['type'] = $crit;
            $i++;
        }
        $i = 0;
        foreach ($_FILES['photo']['error'] as $crit){
            $photo[$i]['error'] = $crit;
            $i++;
        }
        $i = 0;
        foreach ($_FILES['photo']['tmp_name'] as $crit){
            $photo[$i]['tmp_name'] = $crit;
            $i++;
        }
        $i = 0;
        foreach ($_FILES['photo']['size'] as $crit){
            $photo[$i]['size'] = $crit;
            $i++;
        }
        $optw = 1200;
        $name = 0;
        foreach ($photo as $file){
            //--------------//
            if ($file['type'] == 'image/jpeg'){
                $source = imagecreatefromjpeg ($file['tmp_name']);
            }
            elseif ($file['type'] == 'image/png'){
                $source = imagecreatefrompng ($file['tmp_name']);
            }
            elseif ($file['type'] == 'image/gif'){
                $source = imagecreatefromgif ($file['tmp_name']);
            }
            else{
                exit ('wtf, dude?!');
            }
           /*****************/

            $w_src = imagesx($source); 
            $h_src = imagesy($source);

            $ratio = $w_src/$optw;
            $w_dest = $optw;
            $h_dest = round($h_src/$ratio);

            $dest = imagecreatetruecolor($optw, $h_dest);

            imagecopyresampled($dest, $source, 0, 0, 0, 0, $optw, $h_dest, $w_src, $h_src);

            $marge_right = 10;
            $marge_bottom = 10;
            $sx = imagesx($watermark);
            $sy = imagesy($watermark);

            imagecopy($dest, $watermark, imagesx($dest) - $sx - $marge_right, imagesy($dest) - $sy - $marge_bottom, 0, 0, imagesx($watermark), imagesy($watermark));

            imagejpeg($dest, $uploadtmpdir . $file['name'], 90);
            imagedestroy($dest);
            imagedestroy($source);

            copy($uploadtmpdir . $file['name'], $uploaddir .$vin.'-'. $name . '.jpg');

            unlink($uploadtmpdir . $file['name']);

            $name++;
        }
    }
    
    public function createProduct($product, $manager) {
//        exit(var_dump($product));
        $univ = array(
            'brand' => FALSE,
            'model' => FALSE,
            'mr'    => FALSE
        ); 
        foreach ($product as $key => $row) {
            $$key = $row;
        }
        if($brand_id!='univ'){
            $h_ex = $this->db->query("SELECT name FROM ".DB_PREFIX."brand WHERE id = ".(int)$brand_id);
            $brand = $h_ex->row['name'];
            if($model_id!='univ'){
                $h_ex = $this->db->query("SELECT name FROM ".DB_PREFIX."brand WHERE id = ".(int)$model_id);
                $model = $h_ex->row['name'];
                if($modelRow_id!='univ'){
                    $h_ex = $this->db->query("SELECT name FROM ".DB_PREFIX."brand WHERE id = ".(int)$modelRow_id);
                    $model_row = $h_ex->row['name'];
                } else {
                    $univ['mr'] = TRUE;
                    $model_row = 'Универсальный';
                }
            } else {
                $univ['model'] = true;
                $univ['mr'] = TRUE;
                $model = 'Универсальный';
                $model_row = 'Универсальный';
            }
        } else {
            $univ['brand'] = true;
            $univ['model'] = true;
            $univ['mr'] = TRUE;
            $brand = 'Универсальный';
            $model = 'Универсальный';
            $model_row = 'Универсальный';
        }
        
        
        $h_ex = $this->db->query("SELECT name FROM ".DB_PREFIX."category_description WHERE category_id = ".(int)$category_id);
        $category = $h_ex->row['name'];
        $h_ex = $this->db->query("SELECT name FROM ".DB_PREFIX."category_description WHERE category_id = ".(int)$podcat_id);
        $podcategory = $h_ex->row['name'];
        $image = '';
        $photos = array();
        $name = $podcategory;
        
        if(!$univ['brand']){
            $name.= ' '.$brand;
            if(!$univ['mr']){
                $name.= ' '.$model_row;
            }
        }
        
        if(strlen($_FILES['photo']['name'][0])!=0){         
            $dir = DIR_IMAGE . 'catalog/demo/production/'.$vin;
            $photos = scandir($dir);
            array_shift($photos);
            array_shift($photos);
            $image = 'catalog/demo/production/'.$vin.'/'.$photos[0];
        }
        $this->load->model('common/tempdesc');
        $description = $this->model_common_tempdesc->getTemp(1);
        $description = str_replace("%mark%", $brand, $description);
        $description = str_replace("%model%", $model, $description);
        $description = str_replace("%mr%", $model_row, $description);
        $description = str_replace("%podcat%", $podcategory, $description);
        $description = str_replace("%prim%", '', $description);
        
        $tag = $brand.', '.$model.', '.$model_row.', '.$podcategory.', '.$name;
        
        
        $this->db->query("INSERT INTO ".DB_PREFIX."product "
                        . "SET "
                        . "`manufacturer_id` = '". $brand_id ."', "
                        . "`model` = '". $model ."', "
                        . "`category` = '". $category ."', "
                        . "`podcateg` = '". $podcategory ."', "
                        . "`manager` = '". $manager ."', "
                        . "`sku` = '". $vin ."', "
                        . "`mpn` = ' ', "
                        . "`price` = 0, "
                        . "`image` = '". $image ."', "
                        . "`quantity` = 1, "
                        . "`length` = '".$model_row."', "
                        . "`status` = 1, "
                        . "`date_added` = NOW(), "
                        . "`date_available` = NOW(), "
                        . "`date_modified` = NOW(), "
                        . "`stock_status_id` = 7");
        
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
        
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET "
                                        . "product_id = '" . (int)$product_id . "', "
                                        . "category_id = '" . (int)$category_id . "'");
                        
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET "
                                        . "product_id = '" . (int)$product_id . "', "
                                        . "category_id = '" . (int)$podcat_id . "'");
        
        $this->db->query("INSERT INTO ". DB_PREFIX ."url_alias "
                        . "SET "
                        . "query = 'product_id=".(int)$product_id."'");
        
        foreach ($photos as $photo){
                        $this->db->query("INSERT INTO ". DB_PREFIX ."product_image "
                                . "SET "
                                . "product_id = ". (int)$product_id .", "
                                . "image = 'catalog/demo/production/".$product['vin']."/".$photo."' ");
                    }
        if(!$univ['brand']){
            $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand "
                        . "SET "
                        . "product_id = ". (int)$product_id .", "
                        . "brand_id = ".$brand_id);
            if(!$univ['model']){
                $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand "
                        . "SET "
                        . "product_id = ". (int)$product_id .", "
                        . "brand_id = ".$model_id);
                if(!$univ['mr']){
                    $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand "
                        . "SET "
                        . "product_id = ". (int)$product_id .", "
                        . "brand_id = ".$modelRow_id);
                } else {
                    $quer = $this->db->query("SELECT id FROM ".DB_PREFIX."brand WHERE parent_id = ".(int)$model_id);
                    foreach ($quer->rows as $cpb) {
                        $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand "
                                . "SET "
                                . "product_id = ". (int)$product_id .", "
                                . "brand_id = ".(int)$cpb['id']);
                    }
                }
            } else {
                $quer = $this->db->query("SELECT id FROM ".DB_PREFIX."brand WHERE parent_id = ".(int)$brand_id);
                foreach ($quer->rows as $cpb) {
                    $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand "
                            . "SET "
                            . "product_id = ". (int)$product_id .", "
                            . "brand_id = ".(int)$cpb['id']);
                }
            }
        } else {
            $quer = $this->db->query("SELECT id FROM ".DB_PREFIX."brand WHERE 1");
            foreach ($quer->rows as $cpb) {
                $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand "
                        . "SET "
                        . "product_id = ". (int)$product_id .", "
                        . "brand_id = ".(int)$cpb['id']);
            }
        }
        
        $info = array(
            'id'        => $product_id,
            'name'      => $name,
            'photos'    => $photos,
            'vin'       => $vin,
            'univ'      => $univ
        );
        
        return $info;
    }
    
    public function updateDB($products) {
//        exit(var_dump($products));
        $result = '';
        foreach ($products as $key => $row) {
            $result.= $key.',';
            if(!isset($row['compability'])){
                $row['compability'] = '';
            }
            if($row['heading'] == 'create'){
                $this->load->model('complect/complect');
                $this->model_complect_complect->create($row['name'], $row['compl_price'], $row['vin'], 0, 0);
                $this->db->query("UPDATE ".DB_PREFIX."product "
                    . "SET "
                        . "isbn = '".$row['catn']."', "
                        . "jan = '".$row['note']."', "
                        . "price = ".($row['price']!=''?(int)$row['price']:0).", "
                        . "quantity = ".($row['quantity']!=''?(int)$row['quantity']:0).", "
                        . "ean = '".$row['type']."', "
                        . "width = '".$row['dop']."', "
                        . "upc = '".$row['cond']."', "
                        . "weight = '".$row['stock']."', "
                        . "compability = '".trim($row['compability'])."', "
                        . "location = '".$row['stell'].'/'.$row['jar'].'/'.$row['shelf'].'/'.$row['box']."' "
                    . "WHERE product_id = ".(int)$key);
            } elseif ($row['heading'] == 'skip') {
                $this->db->query("UPDATE ".DB_PREFIX."product "
                    . "SET "
                        . "isbn = '".$row['catn']."', "
                        . "jan = '".$row['note']."', "
                        . "price = ".($row['price']!=''?(int)$row['price']:0).", "
                        . "quantity = ".($row['quantity']!=''?(int)$row['quantity']:0).", "
                        . "ean = '".$row['type']."', "
                        . "width = '".$row['dop']."', "
                        . "upc = '".$row['cond']."', "
                        . "weight = '".$row['stock']."', "
                        . "compability = '".trim($row['compability'])."', "
                        . "location = '".$row['stell'].'/'.$row['jar'].'/'.$row['shelf'].'/'.$row['box']."' "
                    . "WHERE product_id = ".(int)$key);
            } else {
                $this->db->query("UPDATE ".DB_PREFIX."product "
                    . "SET "
                        . "isbn = '".$row['catn']."', "
                        . "jan = '".$row['note']."', "
                        . "price = ".($row['price']!=''?(int)$row['price']:0).", "
                        . "quantity = ".($row['quantity']!=''?(int)$row['quantity']:0).", "
                        . "ean = '".$row['type']."', "
                        . "width = '".$row['dop']."', "
                        . "upc = '".$row['cond']."', "
                        . "weight = '".$row['stock']."', "
                        . "location = '".$row['stell'].'/'.$row['jar'].'/'.$row['shelf'].'/'.$row['box']."', "
                        . "comp = '".$row['heading']."', "
                        . "compability = '".trim($row['compability'])."' "
                    . "WHERE product_id = ".(int)$key);
            }
            if(trim($row['compability'])!=''){
                $arrCpb = explode("; ", trim($row['compability']));
//                exit(var_dump($arrCpb));
                foreach ($arrCpb as $cpbItem) {
                    $cpbItem = str_replace(';', '', $cpbItem);
                    $quer = $this->db->query("SELECT id FROM ".DB_PREFIX."brand WHERE name = '".$cpbItem."' ");
                    $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand (`product_id`, `brand_id`) VALUES (".(int)$key.",".(int)$quer->row['id'].")");
                }
            }
            $name = $row['name'];
            if($row['uBrand']!=''){
                $name = $row['name'].' '.$row['dop'];
            } else {
                if($row['uMod']!=''){
                    $name = $row['name'].' '.$row['dop'];
                } else {
                    if($row['uMR']!=''){
                        $name = $row['name'].' '.$row['dop'];
                    }
                }
            }
            $this->db->query("UPDATE ".DB_PREFIX."product_description SET name = '".$name."' WHERE product_id = ".(int)$key);
        }
//        exit(var_dump(trim($result)));
        return trim($result);
    }
    
    public function validateVin($vin) {
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."product WHERE sku = '".$vin."' ");
        if(empty($query->row)){
            return 1;
        } else {
            return 0;
        }
    }
    
    public function pcs($req) {
        $query = "SELECT cd.name, cd.category_id AS id FROM ".DB_PREFIX."category_description cd LEFT JOIN ".DB_PREFIX."category c ON c.category_id = cd.category_id WHERE c.parent_id!=0 ";
        foreach ($req as $word){
            $query.="AND LOCATE('".$this->db->escape($word)."', name) ";
        }
        $result = $this->db->query($query);
        return $result->rows;
    }
}