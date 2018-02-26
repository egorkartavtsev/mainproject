<?php

class ModelToolComplect extends Model {
    
    public function compReprice($vin) {
        $sup = $this->isCompl($vin);
        if($sup){
            $price = 0;
            $query = $this->db->query("SELECT price FROM ".DB_PREFIX."product WHERE sku = '".$sup['complect']['heading']."' OR comp = '".$sup['complect']['heading']."'");
            foreach ($query->rows as $item) {
                $price+=$item['price'];
            }
            $sale = $sup['complect']['sale']==0?15:$sup['complect']['sale'];
            $price = floor($price*(100-$sale)/100);
            if($price>500){
                $rup = 100;
            } else {
                $rup = 50;
            }
            $price+=$rup - $price%100;
            $this->db->query("UPDATE ".DB_PREFIX."complects SET price = '".$price."' WHERE link = '".$sup['complect']['link']."' ");
            $this->db->query("UPDATE ".DB_PREFIX."product SET price = '".$price."' WHERE sku = '".$sup['complect']['link']."'");
            $this->db->query("UPDATE ".DB_PREFIX."product SET comp_price = '".$price."' WHERE sku = '".$sup['complect']['heading']."'");
        }
    }
    
    public function isHeading($vin) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."complects WHERE heading = '".$vin."'");
        if(empty($sup->row)){
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    public function isCompl($vin) {
        $sup = $this->db->query("SELECT price, comp FROM ".DB_PREFIX."product WHERE sku = '".$vin."'");
        $comp = $this->db->query("SELECT * FROM ".DB_PREFIX."complects WHERE heading = '".$sup->row['comp']."' OR heading = '".$vin."'");
        if(empty($comp->row)){
            return FALSE;
        } else {
            $result = array(
                'product'   => $sup->row,
                'complect'  => $comp->row,
                'heading'   => $this->isHeading($vin)
            );
            return $result;
        }
    }
    
    public function repriceById($pid) {
        $sup = $this->db->query("SELECT sku FROM ".DB_PREFIX."product WHERE product_id = ".(int)$pid);
        $this->compReprice($sup->row['sku']);
    }
}

