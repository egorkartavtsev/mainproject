<?php

class ModelToolComplect extends Model {
    
    public function checkCompl($heading) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."product WHERE comp = '".$heading."' OR vin = '".$heading."' ");
        if($sup->num_rows === 1) {
            
            $this->db->query("UPDATE ".DB_PREFIX."product SET comp_price = '', comp = '', comp_whole = '' WHERE vin = '".$sup->row['vin']."' ");
            $this->db->query("DELETE FROM ".DB_PREFIX."complects WHERE heading = '".$heading."'");
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    public function createComplect($vin, $name) {
        $link = uniqid('complect');
        $this->db->query("INSERT INTO ".DB_PREFIX."complects SET heading = '".$vin."', link = '".$link."', name = '".$name."', sale = 10");
        $this->db->query("INSERT INTO ".DB_PREFIX."product (vin, price, status, quantity, viewes, date_added) VALUES ('".$link."', 0, 0, 1, 0, NOW())");
        $prod = $this->db->getLastId();
        $this->db->query("INSERT INTO ".DB_PREFIX."product_description (name, language_id, product_id) VALUES ('Комплект: ".$name."', 1, ".$prod.")");
        $this->db->query("INSERT INTO ".DB_PREFIX."product_to_store (product_id, store_id) VALUES (".(int)$prod.", 0)");
    }
    
    public function compReprice($vin) {
        $sup = $this->isCompl($vin);
        if($sup){
            $price = 0;
            $query = $this->db->query("SELECT price FROM ".DB_PREFIX."product WHERE vin = '".$sup['complect']['heading']."' OR comp = '".$sup['complect']['heading']."'");
            foreach ($query->rows as $item) {
                $price+=$item['price'];
            }
            $sale = $sup['complect']['sale']==0?15:$sup['complect']['sale'];
            $supprice = floor($price*(100-$sale)/100);
            if($supprice>500){
                $rup = 100;
            } else {
                $rup = 50;
            }
            if($supprice+($rup-($supprice%100))<$price){
                if(($supprice%100)>0){
                    $price = $supprice + $rup - $supprice%100;
                } else {
                    $price = $supprice;
                }
            }
            $this->db->query("UPDATE ".DB_PREFIX."complects SET price = '".$price."' WHERE link = '".$sup['complect']['link']."' ");
            $this->db->query("UPDATE ".DB_PREFIX."product SET price = '".$price."' WHERE vin = '".$sup['complect']['link']."'");
            $this->db->query("UPDATE ".DB_PREFIX."product SET comp_price = '".$price."' WHERE vin = '".$sup['complect']['heading']."'");
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
        $sup = $this->db->query("SELECT price, comp FROM ".DB_PREFIX."product WHERE vin = '".$vin."'");
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
        $sup = $this->db->query("SELECT vin FROM ".DB_PREFIX."product WHERE product_id = ".(int)$pid);
        $this->compReprice($sup->row['vin']);
    }
    
    public function constrCompField($num){
        $result = '<div class="form-group">'
                    . '<select class="form-control" name="info['.$num.'][complect]">'
                        . '<option value="skip">Не в комплекте</option>'
                        . '<option value="create">Головной товар</option>'
                        . '<option value="set">Комплектующее</option>'
                    . '</select>'
                . '<input type="hidden" name="info['.$num.'][heading]" class="form-control" id="cHeader" placeholder="Введите ВН головного товара...">';
        $result.= '</div>';
        return $result;
    }
}

