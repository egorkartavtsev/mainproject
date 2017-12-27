<?php

class ModelCommonDonor extends Model {
    public function create($data) {
        $quer = $this->db->query("SELECT name FROM ".DB_PREFIX."brand WHERE id = '".$data['brand_id']."' ");
        $brand = $quer->row['name'];
        $quer = $this->db->query("SELECT name FROM ".DB_PREFIX."brand WHERE id = '".$data['model_id']."' ");
        $model = $quer->row['name'];
        $quer = $this->db->query("SELECT name FROM ".DB_PREFIX."brand WHERE id = '".$data['modelRow_id']."' ");
        $model_row = $quer->row['name'];
        $name = $brand." ".$model_row."(".$data['year'].") - ".$data['vin'];
        $this->db->query("INSERT INTO ".DB_PREFIX."donor "
                            . "SET "
                            . "numb = '".$data['number']."', "
                            . "name = '".$name."', "
                            . "brand = '".$brand."', "
                            . "model = '".$model."', "
                            . "mod_row = '".$model_row."', "
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
            foreach ($photos as $photo){
                $this->db->query("INSERT INTO ".DB_PREFIX."donor_image "
                        . "SET "
                        . "donor_id = '".$donor_id."', "
                        . "image = 'catalog/demo/donor/".$data['number']."/".$photo."' ");
            }
        }
    }
    
    public function getDonors($filter){
        $sql = "SELECT * FROM ".DB_PREFIX."donor d LEFT JOIN ".DB_PREFIX."donor_image di ON d.id = di.donor_id WHERE ";
        
        if (empty($filter)) {
            $sql.="1 ";
        }
        
        $sql.="GROUP BY d.id ";
        
        $query = $this->db->query($sql);
        
        return $query->rows;
    }
}

