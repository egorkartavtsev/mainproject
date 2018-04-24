<?php
class ModelProductCatalog extends Model {
           
    public function getProductInfo($product_id){
        $sql = $this->db->query("SELECT * FROM ".DB_PREFIX."product WHERE product_id = '".$product_id."'");
        //exit(var_dump($sql));
        return $sql->row;
    }
    
    public function getProductImages($product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");
        return $query->rows;
    }
    
 
//    public function getBrand($param);
//    public function getCategory($param);
//    public function getSearch($param);
    
    
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

