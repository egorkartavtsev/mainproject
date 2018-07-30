<?php

class ModelCommonDonor extends Model {
    public function create($data) {
        $quer = $this->db->query("SELECT name FROM ".DB_PREFIX."brand WHERE id = '".$data['brand_id']."' ");
        $brand = $quer->row['name'];
        $quer = $this->db->query("SELECT name FROM ".DB_PREFIX."brand WHERE id = '".$data['model_id']."' ");
        $model = $quer->row['name'];
        $quer = $this->db->query("SELECT name FROM ".DB_PREFIX."brand WHERE id = '".$data['modelRow_id']."' ");
        $model_row = $quer->row['name'];
        $name = $brand." ".$model_row." (ГВ: ".$data['year'].") - (VIN: ".$data['vin'].")";
        $youtube = '';
        if($data['youtube']!==''){
            $sup = strrchr($data['youtube'], "=");
            if($sup){
                $youtube = str_replace("=", "", $sup);
            } else {
                $sup = explode("/", $data['youtube']);
                if(count($sup)>1){
                    $index = count($sup)-1;
                    $youtube = $sup[$index];
                } else {
                    $youtube = $sup[0];
                }                
            }
        }
        $this->db->query("INSERT INTO ".DB_PREFIX."donor "
                            . "SET "
                            . "numb = '".$data['number']."', "
                            . "note = '".$data['note']."', "
                            . "name = '".$name."', "
                            . "brand = '".$brand."', "
                            . "model = '".$model."', "
                            . "mod_row = '".$model_row."', "
                            . "youtube = '".$youtube."', "
                            . "ctype = '".$data['cuzov']."', "
                            . "year = '".$data['year']."', "
                            . "kmeters = '".$data['kilometers']."', "
                            . "vin = '".$data['vin']."', "
                            . "dvs = '".$data['dvs']."', "
                            . "trmiss = '".$data['trans']."', "
                            . "priv = '".$data['privod']."', "
                            . "color = '".$data['color']."', "
                            . "price = '".$data['price']."' ");
        $donor_id = $this->db->getLastId();
        if(strlen($_FILES['photo']['name'][0])!=0){         
            $dir = DIR_IMAGE . 'catalog/demo/donor/'.$data['number'];
            $photos = scandir($dir);
            array_shift($photos);
            array_shift($photos);
            $image = 'catalog/demo/donor/'.$data['number']."/".$photos[0];
            foreach ($photos as $photo){
                $this->db->query("INSERT INTO ".DB_PREFIX."donor_image "
                        . "SET "
                        . "donor_id = '".$donor_id."', "
                        . "image = 'catalog/demo/donor/".$data['number']."/".$photo."' ");
            }
            $this->db->query("UPDATE ".DB_PREFIX."donor SET image = '".$image."' WHERE id = '".$donor_id."' ");
        }
    }
    
    public function getDonors($filter){
        $sql = "SELECT * FROM ".DB_PREFIX."donor WHERE ";
        
        if (empty($filter)) {
            $sql.="1 ORDER BY id DESC ";
        }
        
        $query = $this->db->query($sql);
        
        return $query->rows;
    }
    
    public function deleteDonor($id) {
        $vin = $this->db->query("SELECT numb FROM ".DB_PREFIX."donor WHERE id = '".$id."' ");
        $this->db->query("DELETE FROM ".DB_PREFIX."donor WHERE id = '".$id."' ");
        $this->db->query("DELETE FROM ".DB_PREFIX."donor_image WHERE id = '".$id."' ");
        $dir = DIR_IMAGE."catalog/demo/production/".$vin->row['numb']."/";
        if(is_dir($dir)){
            $this->removeDirectory($dir);
        }
    }
    
    private function removeDirectory($dir) {
		$objs = scandir($dir);
            //??????я так захотел**************
                $fuck = array_shift($objs);
                $fuck = array_shift($objs);
            //*********************************
		
		foreach($objs as $obj) {
				$objct = $dir;
				$objct.= $obj;
                unlink($objct);
        }
		rmdir($dir);
    }
    
    public function getImages($id) {
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."donor_image WHERE donor_id = '".$id."' ORDER BY sort_order");
        return $query->rows;
    }
    
    public function getDonorInfo($id) {
        $sql = "SELECT * FROM ".DB_PREFIX."donor WHERE id = '".$id."' ";
                
        $query = $this->db->query($sql);
        
        return $query->row;
    }
    
    public function getDonorShow($numb) {
        $sql = "SELECT * FROM ".DB_PREFIX."donor WHERE numb = '".$numb."' ";
                
        $query = $this->db->query($sql);
        
        return $query->row;
    }
    
    public function getProds($donor){
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."product p "
                . "LEFT JOIN ".DB_PREFIX."product_description pd ON p.product_id = pd.product_id "
                . "WHERE p.donor = '".$donor."'");
        
        $results = array();
        
        foreach ($query->rows as $row){
            if($row['model']!=''){
                $results[] = $row;
            }
        }
        
        return $results;
    }
    
    public function updateDonor($data, $id) {
//        exit(var_dump($data));
        $quer = $this->db->query("SELECT name FROM ".DB_PREFIX."brand WHERE id = '".$data['brand_id']."' ");
        $brand = $quer->row['name'];
        $quer = $this->db->query("SELECT name FROM ".DB_PREFIX."brand WHERE id = '".$data['model_id']."' ");
        $model = $quer->row['name'];
        $quer = $this->db->query("SELECT name FROM ".DB_PREFIX."brand WHERE id = '".$data['modelRow_id']."' ");
        $model_row = $quer->row['name'];
        $name = $brand." ".$model_row." (ГВ: ".$data['year'].") - (VIN: ".$data['vin'].")";
        
        $youtube = '';
        if($data['youtube']!==''){
            $sup = strrchr($data['youtube'], "=");
            if($sup){
                $youtube = str_replace("=", "", $sup);
            } else {
                $sup = explode("/", $data['youtube']);
                if(count($sup)>1){
                    $index = count($sup)-1;
                    $youtube = $sup[$index];
                } else {
                    $youtube = $sup[0];
                }                
            }
        }
        $this->db->query("UPDATE ".DB_PREFIX."donor "
                            . "SET "
                            . "numb = '".$data['number']."', "
                            . "name = '".$name."', "
                            . "image = '".$data['main-image']."', "
                            . "brand = '".$brand."', "
                            . "model = '".$model."', "
                            . "mod_row = '".$model_row."', "
                            . "ctype = '".$data['cuzov']."', "
                            . "year = '".$data['year']."', "
                            . "note = '".$data['note']."', "
                            . "youtube = '".$youtube."', "
                            . "kmeters = '".$data['kilometers']."', "
                            . "vin = '".$data['vin']."', "
                            . "dvs = '".$data['dvs']."', "
                            . "trmiss = '".$data['trans']."', "
                            . "priv = '".$data['privod']."', "
                            . "color = '".$data['color']."', "
                            . "price = '".$data['price']."' "
                . "WHERE id = '".$id."'");
        
        $this->db->query("DELETE FROM ".DB_PREFIX."donor_image WHERE donor_id = '".$id."'");
        if((isset($data['image'])) && (!empty($data['image']))){
            foreach ($data['image'] as $img) {
                $this->db->query("INSERT INTO ".DB_PREFIX."donor_image (donor_id, image, sort_order) VALUES (".(int)$this->db->escape($id).", '".$this->db->escape($img['img'])."', '".$this->db->escape($img['sort-order'])."')");
            }
        }    
    }
    
    public function filterList($request) {
        $rWords = explode(" ", trim($request));
        $sql = "SELECT * FROM ".DB_PREFIX."donor WHERE 0 ";
        foreach ($rWords as $word) {
            $sql.="OR LOCATE('".$word."', name) OR LOCATE('".$word."', numb) OR LOCATE('".$word."', dvs) ";
        }
        $sql.="ORDER BY id DESC";
        $result = $this->db->query($sql);
        return $result->rows;
    }
}

