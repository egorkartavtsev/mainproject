<?php

class ModelToolProduct extends Model {
    
    public function getType($type_id){
        $sql = $this->db->query("SELECT * FROM ".DB_PREFIX."type_lib WHERE type_id = '".$type_id."' ORDER BY sort_order ");
        
        $pot = array();
        foreach ($sql->rows AS $cell) {
            $pot[$cell['name']] = array ( 
                'text' => $cell['text'],
                'viewed' => $cell['viewed'],
                'value' => ''  
            );    
        }
        return $pot;
    }
    
    public function getDescription($id){
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."product_description WHERE product_id = ".(int)$id);
        return $sup->row;
    }
    public function getProductCompls($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "' AND comp != '' ORDER BY sort_order ASC");
                
                if (!empty($query->row)){
                    $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."complects "
                                . "WHERE heading = '".$query->row['vin']."'");
                    
                    if(!empty($sup->row)){
                        $prods = $this->db->query("SELECT * FROM ".DB_PREFIX."product p "
                                . "LEFT JOIN ".DB_PREFIX."product_description pd "
                                    . "ON pd.product_id = p.product_id "
                                . "WHERE p.comp = '".$sup->row['heading']."' OR p.vin = '".$sup->row['heading']."' ORDER BY pd.name ");
                        $comp_ref = $this->db->query("SELECT * FROM ".DB_PREFIX."product "
                                . "WHERE vin = '".$sup->row['link']."'");
                           
                        $complect['id_comp_ref'] = $comp_ref->row['product_id'];
                        //exit(var_dump($complect['id_comp_ref']));
                        $complect['complect'] = $prods->rows;
                        $complect['compl_price'] = $sup->row['price'];
                        $complect['link'] = $sup->row['link'];
                        $complect['whole'] = $sup->row['whole'];
                        $complect['c_id'] = $sup->row['id'];
                    } else {
                        $prods = $this->db->query("SELECT * FROM ".DB_PREFIX."product p "
                                . "LEFT JOIN ".DB_PREFIX."product_description pd "
                                    . "ON pd.product_id = p.product_id "
                                . "WHERE p.comp = '".$query->row['comp']."' OR p.vin = '".$query->row['comp']."' ORDER BY pd.name ");
//                         $comp_ref = $this->db->query("SELECT * FROM ".DB_PREFIX."product "
//                                . "WHERE vin = '".$sup->row['link']."'");
                        $complect['complect'] = $prods->rows;
//                        $complect['id_comp_ref'] = $comp_ref->row['product_id'];
//                        
                        foreach ($prods->rows as $prod) {
                            $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."complects "
                                . "WHERE heading = '".$prod['vin']."'");
                            
                            if(!empty($sup->row)){
                                $comp_ref = $this->db->query("SELECT * FROM ".DB_PREFIX."product "
                                . "WHERE vin = '".$sup->row['link']."'");
                                $complect['id_comp_ref'] = $comp_ref->row['product_id'];
                                $complect['compl_price'] = $sup->row['price'];
                                $complect['link'] = $sup->row['link'];
                                $complect['whole'] = $sup->row['whole'];
                                $complect['c_id'] = $sup->row['id'];
                            }
                        }                        
                    }
                    return $complect;
                }
		return FALSE;
	}
    
        
}    
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

