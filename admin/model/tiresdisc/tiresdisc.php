<?php
class ModelTiresdiscTiresdisc extends Model {
    public function getParameters() {
        $parameters = array();
        
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."td_params WHERE 1");
        if(!empty($query->rows)){
            foreach ($query->rows as $param) {
                if($param['belong'] == 'disk'){
                    $parameters['disc'][$param['id']] = $param['name'];
                } else {
                    $parameters['tire'][$param['id']] = $param['name'];
                }
            }
        }
        
        return $parameters;
    }
    
    public function editPValue($parent, $value, $type) {
        if($type=='0'){
            $this->db->query("INSERT INTO ".DB_PREFIX."td_lib (`id_param`, `value`) VALUES (".(int)$parent.", '".$value."')");
            $query = $this->db->query("SELECT * FROM ".DB_PREFIX."td_lib WHERE value = '".$value."' AND id_param = ".(int)$parent);
        } else {
            $this->db->query("UPDATE ".DB_PREFIX."td_lib SET value = '".$value."' WHERE id = ".(int)$type);
            $query = $this->db->query("SELECT * FROM ".DB_PREFIX."td_lib WHERE id = ".(int)$type);
        }
        return $query->row;
    }
    
    public function getValues($param) {
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."td_lib WHERE id_param=".$param);
        return $query->rows;
    }
    
    public function editParam($param, $name, $belong) {
        if($param==0){
            $this->db->query("INSERT INTO ".DB_PREFIX."td_params (`belong`, `name`) VALUES ('". $this->db->escape($belong)."','".$this->db->escape($name)."')");
        } else {
            $this->db->query("UPDATE ".DB_PREFIX."td_params SET `name` = '".$this->db->escape($name)."' WHERE id = ".(int)$param);
        }
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."td_params WHERE name = '".$name."'");
        return $query->row;
    }
    
    public function deleteParam($param) {
        if($this->db->query("DELETE FROM ".DB_PREFIX."td_params WHERE id = ".(int)$param)){
            $this->db->query("DELETE FROM ".DB_PREFIX."td_lib WHERE id_param = ".(int)$param);
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function delPVal($param) {
        if($this->db->query("DELETE FROM ".DB_PREFIX."td_lib WHERE id = ".(int)$param)){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    /**********************************************************************************************************/
    public function getAllParameters($belong) {
        $parameters = array();
        
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."td_params WHERE belong = '".$belong."'");
        $params = $query->rows;
        foreach ($params as $param) {
            $quer = $this->db->query("SELECT value FROM ".DB_PREFIX."td_lib WHERE id_param = '".$param['id']."'");
            $parameters[$param['field']] = array(
                'name'      => $param['name'],
                'values'    => $quer->rows
            );
        }
        
        return $parameters;
    }
    
    public function takeDBParam($belong) {
        $parameters = array();
        
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."td_params WHERE 1");
        if(!empty($query->rows)){
            foreach ($query->rows as $param) {
                if($param['belong'] == $belong){
                    $parameters[$param['field']] = $param['name'];
                }
            }
        }
        
        return $parameters;
    }
    
    public function createProd($prod, $photos) {
        $params = $this->takeDBParam($prod['cat']);
        if(!empty($photos)){
            $image = 'catalog/demo/production/'.$prod['vin'].'/'.$this->setPhotos($prod['vin'], $photos);
        } else {
            $image = '';
        }
        
        if($prod['cat']=='disk'){
            $name = 'Диск колёсный '.$prod['type'].' '.$prod['diameter'].'/'.$prod['width'].'/'.$prod['qHoles'].'x'.$prod['dHoles'].' '.$prod['dop'];
            $table = 'disc';
        } else {
            $name = 'Шина '.$prod['season'].' '.$prod['brand'].' '.($prod['tModel']==''?'-':$prod['tModel']).' '.$prod['width'].'/'.$prod['hProf'].'/R'.$prod['diameter'].' '.$prod['dop'];
            $table = 'tires';
        }
        
        $sql = "INSERT INTO ".DB_PREFIX."td_".$table." SET vin = '".$prod['vin']."', price = '".$prod['price']."', ";
        foreach ($params as $key => $value) {
            $sql.= $key." = '".$prod[$key]."', ";
        }
        $sql.="image = '".$image."' ";
//        exit(var_dump($sql));
        
        $this->db->query($sql);
        $description = "&lt;p&gt;&lt;b&gt;Авторазбор174.рф предлагает Вам купить ".$name." со склада в г.Магнитогорске.&amp;nbsp;&amp;nbsp;&lt;/b&gt;&lt;/p&gt;&lt;p&gt;&lt;b&gt;Авторазбор автозапчасти б/у и новые в наличии и под заказ.&lt;/b&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;b&gt;ЕСЛИ ВЫ НЕ НАШЛИ НЕОБХОДИМУЮ АВТОЗАПЧАСТЬ - ПОЗВОНИТЕ ПО ОДНОМУ ИЗ УКАЗАННЫХ ТЕЛЕФОНОВ&lt;/b&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;b&gt;МЫ ПРОКОНСУЛЬТИРУЕМ ВАС ПО НАЛИЧИЮ, СТОИМОСТИ И СРОКАХ ДОСТАВКИ.&lt;/b&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;b&gt;‎+7 (3519) 43 49 03&amp;nbsp;&lt;/b&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;b&gt;‎+7 (3519) 47 71 71 ‎&lt;/b&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;b&gt;+7 (912) 475 08 70 ‎&lt;/b&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;b&gt;+7 (908) 825 52 40 ‎&lt;/b&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;b&gt;+7 (951) 122 56 39&lt;/b&gt;&lt;/p&gt;&lt;p&gt;&lt;b&gt;autorazbor174@mail.ru&lt;/b&gt;&lt;/p&gt;&lt;p&gt;&lt;b&gt;БУДЬТЕ ВНИМАТЕЛЬНЫ!!&lt;/b&gt;&lt;b&gt;&amp;nbsp;С ДРУГИХ ТЕЛЕФОНОВ И E-MAIL МЫ НЕ ОТПРАВЛЯЕМ ИНФОРМАЦИЮ!!!&amp;nbsp;&lt;br&gt;&lt;/b&gt;&lt;br&gt;&lt;/p&gt;";
        $this->db->query("INSERT INTO ".DB_PREFIX."product "
                    . "(donor, vin, price, status, width, note, quantity, viewed, image, date_added, cond, type, stock, location) "
                . "VALUES "
                    . "('".$prod['donor']."', '".$prod['vin']."', ".(int)$prod['price'].", 1, '".$prod['dop']."', '".$prod['note']."', ".(int)$prod['quant'].", 0, '".$image."', NOW(), '".$prod['cond']."', '".$prod['ctype']."', '".$prod['stock']."', '".$prod['stell']."/".$prod['jar']."/".$prod['shelf']."/".$prod['box']."')");
        $link = $this->db->getLastId();
        $this->db->query("INSERT INTO ".DB_PREFIX."product_description (name, description, language_id, product_id) VALUES ('".$name."', '".$description."', 1, ".$link.")");
        $this->db->query("INSERT INTO ".DB_PREFIX."product_to_store (product_id, store_id) VALUES (".(int)$link.", 0)");
        
        $this->db->query("UPDATE ".DB_PREFIX."td_".$table." SET link = ".(int)$link." WHERE vin = '".$prod['vin']."' ");
        
        if(is_dir(DIR_IMAGE . "catalog/demo/production/".$prod['vin'])){
            $images = scandir(DIR_IMAGE . "catalog/demo/production/".$prod['vin']);
            array_shift($images);
            array_shift($images);
            foreach ($images as $photo) {
                $this->db->query("INSERT INTO ". DB_PREFIX ."product_image "
                                . "SET "
                                . "product_id = ". (int)$link .", "
                                . "image = 'catalog/demo/production/".$prod['vin']."/".$photo."' ");
            }
        }
        
        
    }
    
    private function setPhotos($vin, $photo) {
        
        $uploadtmpdir = DIR_IMAGE . "tmp/";
        if(!is_dir(DIR_IMAGE . "catalog/demo/production/".$vin)){mkdir(DIR_IMAGE . "catalog/demo/production/".$vin);}
        $uploaddir = DIR_IMAGE . "catalog/demo/production/".$vin."/";
        $watermark = imagecreatefrompng(DIR_IMAGE . "watermark.png");

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
        return $vin.'-0.jpg';
    }
/***************************************************************************************************************/
    public function getList($filter, $sort) {
        $sql = "";
        if(empty($filter) || !$filter['cat']){
            $sql = "SELECT "
                . "pd.name AS name, "
                . "p.product_id AS link, "
                . "p.price AS price, "
                . "p.date_added AS date, "
                . "tire.image AS tiresImage, "
                . "disc.image AS discImage, "
                . "p.location AS location, "
                . "p.stock AS stock, "
                . "p.vin AS vin, "
                . "p.quantity AS quan, "
                . "p.cond AS cond, "
                . "p.type AS type, "
                . "p.status AS status "
              ."FROM `".DB_PREFIX."product` p "
              ."LEFT JOIN ".DB_PREFIX."td_tires tire ON tire.link = p.product_id "
              ."LEFT JOIN ".DB_PREFIX."td_disc disc ON disc.link = p.product_id "
              ."LEFT JOIN ".DB_PREFIX."product_description pd ON p.product_id = pd.product_id "
              ."WHERE tire.link = p.product_id OR disc.link = p.product_id ".$sort;
        } else {
            $sql.= "SELECT "
                . "pd.name AS name, "
                . "p.product_id AS link, "
                . "p.price AS price, "
                . "p.date_added AS date, "
                . $filter['cat'].".image AS ".$filter['cat']."Image, "
                . "p.location AS location, "
                . "p.stock AS stock, "
                . "p.vin AS vin, "
                . "p.quantity AS quan, "
                . "p.cond AS cond, "
                . "p.type AS type, "
                . "p.status AS status "
              ."FROM `".DB_PREFIX."product` p "
              ."LEFT JOIN ".DB_PREFIX."td_".$filter['cat']." ".$filter['cat']." ON ".$filter['cat'].".link = p.product_id "
              ."LEFT JOIN ".DB_PREFIX."product_description pd ON p.product_id = pd.product_id "
              ."WHERE ".$filter['cat'].".link = p.product_id ";
            foreach ($filter as $field => $value) {
                if($value && $field!='cat'){
                    $sql.= "AND ".$filter['cat'].".".$field."='".$value."' ";
                }
            }
            $sql.= $sort;
        }
        
        
        
        $result = $this->db->query($sql);
        return $result->rows;
    }
    
    public function deleteProd($id) {
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."td_disc WHERE link = ".(int)$id);
        if(!empty($query->row)){
            $table = DB_PREFIX.'td_disc';
        } else {
            $table = DB_PREFIX.'td_tires';
        }
        
        if ($this->db->query("DELETE FROM ".$table." WHERE link = ".(int)$id)){
            if($this->db->query("DELETE FROM ".DB_PREFIX."product WHERE product_id = ".(int)$id)){
                if($this->db->query("DELETE FROM ".DB_PREFIX."product_description WHERE product_id = ".(int)$id)){
                    if($this->db->query("DELETE FROM ".DB_PREFIX."product_image WHERE product_id = ".(int)$id)){
                        return TRUE;
                    } else { return FALSE;}
                } else { return FALSE;} 
            } else { return FALSE;}
        } else { return FALSE;}
    }
    /******************************************************************************************************************/
    public function getCat($id) {
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."td_disc WHERE link = ".(int)$id);
        if (empty($query->row)){
            return 'tire';
        } else {
            return 'disk';
        }
    }
    
    public function getProdInfo($id, $cat) {
        if($cat=='disk'){
            $table = DB_PREFIX.'td_disc';
        } else {
            $table = DB_PREFIX.'td_tires';
        }
        
        $query = $this->db->query("SELECT * FROM ".$table." WHERE link = ".(int)$id);
        $info = $query->row;
        
        $query = $this->db->query("SELECT image, stock, donor, note, width, price, cond, type, quantity, status, location FROM ".DB_PREFIX."product WHERE product_id = ".(int)$id);
        $locate = explode("/", $query->row['location']);
        $info['stell'] = isset($locate[0])?$locate[0]:'';
        $info['jar'] = isset($locate[1])?$locate[1]:'';
        $info['shelf'] = isset($locate[2])?$locate[2]:'';
        $info['box'] = isset($locate[3])?$locate[3]:'';
        
        $info['main-image'] = $query->row['image'];
        $info['donor'] = $query->row['donor'];
        $info['stock'] = $query->row['stock'];
        $info['note'] = $query->row['note'];
        $info['dop'] = $query->row['width'];
        $info['price'] = $query->row['price'];
        $info['status'] = $query->row['status'];
        $info['ctype'] = $query->row['type'];
        $info['cond'] = $query->row['cond'];
        $info['quant'] = $query->row['quantity'];
        
        $query = $this->db->query("SELECT name FROM ".DB_PREFIX."product_description WHERE product_id = ".(int)$id);
        $info['name'] = $query->row['name'];
        
        return $info;
    }
    
    public function getImages($id) {
        $result = array();
        $query = $this->db->query("SELECT image, sort_order FROM ".DB_PREFIX."product_image WHERE product_id = ".(int)$id);
        foreach ($query->rows as $item){
            $result[] = array(
                'img'           =>  $item['image'],
                'sort_order'    =>  $item['sort_order']
            );
        }
        return $result;
    }
    
    public function updateDB($prod, $id) {
        $cat = $this->getCat($id);
        if($cat=='disk'){
            $name = 'Диск колёсный '.$prod['type'].' '.$prod['diameter'].'/'.$prod['width'].'/'.$prod['qHoles'].'x'.$prod['dHoles'].' '.$prod['dop'];
            $table = DB_PREFIX.'td_disc';
        } else {
            $name = 'Шина '.$prod['season'].' '.$prod['brand'].' '.($prod['tModel']==''?'-':$prod['tModel']).' '.$prod['width'].'/'.$prod['hProf'].'/R'.($prod['diameter']).' '.$prod['dop'];
            $table = DB_PREFIX.'td_tires';
        }
        
        $parameters = $this->takeDBParam($cat);
        
        $sql = "UPDATE ".$table." SET ";
        foreach ($parameters as $field => $param) {
            $sql.= $field." = '".$prod[$field]."', ";
        }
        $sql.="price = ".(int)$prod['price'].", "
                . "image = '".$prod['mainimage']."' WHERE link = ".(int)$id;
        $this->db->query($sql);
//        exit($sql);
        $this->db->query("UPDATE ".DB_PREFIX."product "
                . "SET "
                    . "price = ".(int)$prod['price'].", "
                    . "cond = '".$prod['cond']."', "
                    . "donor = '".$prod['donor']."', "
                    . "type = '".$prod['ctype']."', "
                    . "stock = '".$prod['stock']."', "
                    . "width = '".$prod['dop']."', "
                    . "note = '".$prod['note']."', "
                    . "image = '".$prod['mainimage']."', "
                    . "quantity = '".$prod['quant']."', "
                    . "status = '".$prod['status']."', "
                    . "location = '".$prod['stell']."/".$prod['jar']."/".$prod['shelf']."/".$prod['box']."' WHERE product_id  = ".(int)$id);
        
        $this->db->query("UPDATE ".DB_PREFIX."product_description SET name = '".$name."' WHERE product_id = ".(int)$id);
        
        $this->db->query("DELETE FROM ".DB_PREFIX."product_image WHERE product_id = ".(int)$id);
       
        foreach ($prod['image'] as $image) {
            $this->db->query("INSERT INTO ".DB_PREFIX."product_image (product_id, image, sort_order) VALUES (".(int)$id.", '".$image['img']."', '".$image['sort-order']."')");           
        }
    }
}

