<?php

class ModelCatalogBrand extends Model {
    
    public function getBrandInfo($brand_id) {
        
        $query = $this->db->query("SELECT name AS brand_name FROM " . DB_PREFIX . "brand "
                . "WHERE id = ".$brand_id);
        
        return $query->rows[0];
    }
    
    public function getBrands() {
//        $query = $this->db->query("SELECT "
//                        . "b.id AS id, "
//                        . "b.name AS brand_name,  "
//                        . "m.image AS image, "
//                        . "md.manufacturer_id AS man_id "
//                        . "FROM " . DB_PREFIX . "brand b "
//                        . "LEFT JOIN ".DB_PREFIX."manufacturer_description md "
//                            . "ON (md.language_id = 2 AND md.name = b.name) "
//                        . "LEFT JOIN ".DB_PREFIX."manufacturer m "
//                            . "ON (m.manufacturer_id = md.manufacturer_id) "
//                        . "WHERE parent_id = 0 "
//                        . "ORDER BY b.name");
        $query = $this->db->query("SELECT "
                            . "b.id AS id, "
                            . "b.name AS brand_name, "
                            . "b.image AS image "
                            . "FROM " . DB_PREFIX . "brand b "
                            . "WHERE parent_id = 0 "
                            . "ORDER BY b.name");
        $result = $query->rows;
        return $result;
    }
    
    public function getChilds($parent_id) {
        
        $query = $this->db->query("SELECT id, name AS brand_name FROM " . DB_PREFIX . "brand "
                . "WHERE parent_id = ".$parent_id." ORDER BY name");
        
        return $query->rows;
    }
    
    public function getBrandProducts($brand_id) {
        
        $query = $this->db->query("SELECT "
                    . "p.image AS image, "
                    . "p.product_id AS product_id, "
                    . "p.minimum AS minimum, "
                    . "p.compability AS compability, "
                    . "pd.name AS name, "
                    . "p.vin AS vin, "
                    . "p.cond AS con_p, "
                    . "p.type AS ean, "
                    . "p.note AS note, "
                    . "p.catn AS catN, "
                    . "p.price AS price, "
                    . "c.whole AS com_whole, "
                    . "c.price AS com_price,"
                    . "p.comp AS comp "
//                . "(SELECT c.whole "
//                            . "FROM " . DB_PREFIX . "complects c "
//                            . "WHERE c.heading = p.vin OR c.heading = p.comp) AS com_whole, "
//                        . "(SELECT c.price "
//                            . "FROM " . DB_PREFIX . "complects c "
//                            . "WHERE c.heading = p.vin OR c.heading = p.comp) AS com_price "   
                . "FROM ".DB_PREFIX."product_to_brand p2b "
                . "LEFT JOIN ".DB_PREFIX."product p "
                    . "ON (p.product_id = p2b.product_id) "
                . "LEFT JOIN ".DB_PREFIX."product_description pd "
                    . "ON (pd.product_id = p2b.product_id) "
                . "LEFT JOIN ". DB_PREFIX . "complects c "
                    . "ON (p.vin = c.heading OR p.comp = c.heading) " 
                . "WHERE p2b.brand_id = ".$brand_id." "
                    . "AND pd.language_id = 1 "
                    . "AND p.status = 1 "
                    . "AND p.quantity != 0 ORDER BY p.date_added DESC");
        return $query->rows;
        
    }    
    
    public function getBCProds($brand_id, $cat_id) {
        
        $query = "SELECT * FROM ".DB_PREFIX."product_to_brand p2b "
                . "LEFT JOIN ".DB_PREFIX."product p "
                    . "ON (p.product_id = p2b.product_id) "
                . "LEFT JOIN ".DB_PREFIX."product_description pd "
                    . "ON (pd.product_id = p2b.product_id) "
                . "LEFT JOIN ".DB_PREFIX."product_to_category p2c "
                    . "ON (p2c.product_id = p.product_id) "
                . "WHERE p2b.brand_id = '".$brand_id."' "
                    . "AND p2c.category_id = '".$cat_id."' "
                    . "AND p.quantity != 0 ORDER BY p.date_added DESC"; 
        
        $fres = $this->db->query($query);
        
        $prods = $fres->rows;
        $result = array();
        foreach ($prods as $prod){
            $result[] = array(
                'image' => $prod['image'],
                'vin' => $prod['sku'],
                'catN' => $prod['isbn'],
                'compability' => $prod['compability'],
                'con_p' => $prod['upc'],
                'product_id' => $prod['product_id'],
                'minimum' => $prod['minimum'],
                'note' => $prod['jan'],
                'ean'=> $prod['ean'],
                'name' => $prod['name'],
                'description' => $prod['description'],
                'price' => $prod['price'],
                'comp' => $prod['comp']
            );
        }
        return $result;
        
    }    
}
