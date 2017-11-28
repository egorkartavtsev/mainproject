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
        if($photos){
            $image = $this->setPhotos($prod['vin'], $photos);
        } else {
            $image = '';
        }
        
        if($prod['cat']=='disk'){
            $name = 'Диск колёсный '.$prod['ctype'].' '.$prod['diameter'].'/'.$prod['width'].'/'.$prod['qHoles'].'x'.$prod['dHoles'];
            $table = 'disc';
        } else {
            $name = 'Шина '.$prod['season'].' '.($prod['tModel']==''?'-':$prod['tModel']).' '.$prod['width'].'/'.$prod['hProf'].'/R'.($prod['diameter']);
            $table = 'tires';
        }
        
        $sql = "INSERT INTO ".DB_PREFIX."td_".$table." SET vin = '".$prod['vin']."', price = '".$prod['price']."', ";
        foreach ($params as $key => $value) {
            $sql.= $key." = '".$prod[$key]."', ";
        }
        $sql.="image = 'catalog/demo/production/".$prod['vin']."/".$image."' ";
//        exit(var_dump($sql));
        
        $this->db->query($sql);
        
        $this->db->query("INSERT INTO ".DB_PREFIX."product "
                    . "(sku, price, status, quantity, viewed, image, date_added, upc, ean, weight, location) "
                . "VALUES "
                    . "('".$prod['vin']."', ".(int)$prod['price'].", 0, ".(int)$prod['quant'].", 0, '".$image."', NOW(), '".$prod['cond']."', '".$prod['ctype']."', '".$prod['stock']."', '".$prod['stell']."/".$prod['jar']."/".$prod['shelf']."/".$prod['box']."')");
        $link = $this->db->getLastId();
        $this->db->query("INSERT INTO ".DB_PREFIX."product_description (name, language_id, product_id) VALUES ('".$name."', 1, ".$link.")");
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
    public function getList() {
        $sql = "SELECT "
                . "pd.name AS name, "
                . "p.price AS price, "
                . "p.date_added AS date, "
                . "tire.image AS tImage, "
                . "disc.image AS dImage, "
                . "p.location AS location, "
                . "p.weight AS stock, "
                . "p.sku AS vin, "
                . "p.quantity AS quan, "
                . "p.upc AS cond, "
                . "p.ean AS type, "
                . "p.status AS status "
              ."FROM `".DB_PREFIX."product` p "
              ."LEFT JOIN ".DB_PREFIX."td_tires tire ON tire.link = p.product_id "
              ."LEFT JOIN ".DB_PREFIX."td_disc disc ON disc.link = p.product_id "
              ."LEFT JOIN ".DB_PREFIX."product_description pd ON p.product_id = pd.product_id "
              ."WHERE tire.link = p.product_id OR disc.link = p.product_id ";
        $result = $this->db->query($sql);
        return $result->rows;
    }
}

