<?php

class ModelToolProduct extends Model {
    
    public function getType($type_id){
        $sql = $this->db->query("SELECT * FROM ".DB_PREFIX."type_lib WHERE type_id = '".$type_id."' ORDER BY sort_order ");
        
        $pot = array();
        foreach ($sql->rows AS $cell) {
            $pot[$cell['name']] = array ( 
                'text' => $cell['text'],
                'viewed' => $cell['viewed'],
                'label_order' => $cell['label_order'],
                'label_color' => $cell['label_color'],
                'value' => ''  
            );    
        }
        return $pot;
    }
    
    public function getDescription($id){
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."product_description WHERE product_id = ".(int)$id);
        return $sup->row;
    }
    public function getProductCompls($comp) {
            if($comp!==''){
                $sup = $this->db->query("SELECT "
                            . "p.product_id AS id_comp_ref, "
                            . "c.id AS c_id, "
                            . "p.price AS comp_price, "
                            . "p.vin AS link,"
                            . "c.whole "
                        . "FROM ".DB_PREFIX."complects c "
                        . "LEFT JOIN ".DB_PREFIX."product p ON c.link=p.vin "
                        . "WHERE c.id = '".$comp."' OR heading = '".$comp."' ")->row;
                
                $sup['complect'] = $this->db->query("SELECT "
                            . "p.product_id, pd.name, p.minimum, p.price, p.status "
                        . "FROM ".DB_PREFIX."product p "
                        . "LEFT JOIN ".DB_PREFIX."product_description pd ON p.product_id = pd.product_id "
                        . "WHERE comp = '".$comp."' OR vin = '".$comp."'")->rows;
                
                return $sup;
            } else {
                return FALSE;
            }
	}
    public function getProdImg($id) {
        $photos = $this->db->query("SELECT * FROM ".DB_PREFIX."product_image WHERE product_id = ".(int)$id." ORDER BY sort_order ");
        return $photos->rows;
    }     
}    
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

