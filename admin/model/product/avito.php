<?php

class ModelProductAvito extends Model {
    
    public function hideNotice($vin){
        $this->db->query("UPDATE ".DB_PREFIX."product_to_avito SET message = 0 WHERE vin = '".$vin."'");
        echo $vin;
    }
    
    public function dropAD($vin){
        $this->db->query("DELETE FROM ".DB_PREFIX."product_to_avito WHERE vin = '".$vin."'");
            
        $xmls = simplexml_load_file('../Avito/ads.xml');
        $sup = 0;
        foreach($xmls as $ad){
            if(in_array($vin, (array)$ad)){
                $dom=dom_import_simplexml($xmls->Ad[$sup]);
                $dom->parentNode->removeChild($dom);
                $xmls->saveXML('../Avito/ads.xml');
                return 1;
            } else {
                ++$sup;
            }
        }    
            
        echo $vin;
    }
    
    public function deactAD($vin){
        $date = date('Y-m-d', strtotime(date('Y-m-d')."-5 days"));
        $this->db->query("UPDATE ".DB_PREFIX."product_to_avito SET dateEnd = '".$date."', dateStart = '1999-01-01' WHERE vin = '".$vin."'");
        $xmls = simplexml_load_file('../Avito/ads.xml');
        $sup = 0;
        foreach($xmls as $ad){
            if(in_array($vin, (array)$ad)){
                $xmls->Ad[$sup]->DateBegin = '1999-01-01';
                $xmls->Ad[$sup]->DateEnd = $date;
                $xmls->saveXML('../Avito/ads.xml');
                return 1;
            } else {
                ++$sup;
            }
        }
        echo $vin;
    }
    
    public function reactAD($vin){
        $dateE = date('Y-m-d', strtotime(date('Y-m-d')."+30 days"));
        $dateS = date('Y-m-d', strtotime(date('Y-m-d')));
        $this->db->query("UPDATE ".DB_PREFIX."product_to_avito SET dateEnd = '".$dateE."', dateStart = '".$dateS."' WHERE vin = '".$vin."'");
        $xmls = simplexml_load_file('../Avito/ads.xml');
        $sup = 0;
        foreach($xmls as $ad){
            if(in_array($vin, (array)$ad)){
                $xmls->Ad[$sup]->DateBegin = $dateS;
                $xmls->Ad[$sup]->DateEnd = $dateE;
                $xmls->saveXML('../Avito/ads.xml');
                return 1;
            } else {
                ++$sup;
            }
        }
        echo $vin;
    }
    
    public function getProducts($filter) {
        $sql = "SELECT * FROM ".DB_PREFIX."product_to_avito p2a "
                . "LEFT JOIN ".DB_PREFIX."product p ON p.product_id = p2a.product_id "
                . "LEFT JOIN ".DB_PREFIX."product_description pd ON pd.product_id = p2a.product_id "
                . "WHERE LOCATE('".$filter['modbr']."', p.brand) "
                    . "AND LOCATE('".$filter['podcat']."', p.podcateg) "
                    . "AND LOCATE('".$filter['model']."', p.model) "
                    . "AND LOCATE('".$filter['vin']."', p.vin) "
                    . "AND p.price>=".(int)$filter['priceFrom']." "
                    . "AND p.price<=".(int)$filter['priceTo']." "
                    . "AND p2a.dateEnd>='".$filter['date']."' ";
        if(isset($filter['mess']) && (int)$filter['mess']){
            $sql.= "AND p2a.dateEnd>NOW() ";
        } elseif(isset($filter['mess']) && !(int)$filter['mess']) {
            $sql.= "AND p2a.dateEnd<=NOW() ";
        }
        $sql.= "ORDER BY ".$filter['sort']." ".$filter['order']." LIMIT ".(int)$filter['limit']." OFFSET ".(int)$filter['start'];
//        exit(var_dump($sql));
        $sup = $this->db->query($sql);
        return $sup->rows;
    }

    public function getProductsTotal($filter) {
        $sql = "SELECT * FROM ".DB_PREFIX."product_to_avito p2a "
                . "LEFT JOIN ".DB_PREFIX."product p ON p.product_id = p2a.product_id "
                . "LEFT JOIN ".DB_PREFIX."product_description pd ON pd.product_id = p2a.product_id "
                . "WHERE LOCATE('".$filter['modbr']."', p.brand) "
                    . "AND LOCATE('".$filter['podcat']."', p.podcateg) "
                    . "AND LOCATE('".$filter['model']."', p.model) "
                    . "AND LOCATE('".$filter['vin']."', p.vin) "
                    . "AND p.price>=".(int)$filter['priceFrom']." "
                    . "AND p.price<=".(int)$filter['priceTo']." "
                    . "AND p2a.dateEnd>='".$filter['date']."' ";
        if(isset($filter['mess']) && (int)$filter['mess']){
            $sql.= "AND p2a.dateEnd>NOW() ";
        } elseif(isset($filter['mess']) && !(int)$filter['mess']) {
            $sql.= "AND p2a.dateEnd<=NOW() ";
        }
        $sql.= "ORDER BY ".$filter['sort']." ".$filter['order'];
//        exit(var_dump($sql));
        $sup = $this->db->query($sql);
        return $sup->num_rows;
    }
}

