<?php
class ModelCommonTempDesc extends Model {
    
    public function getTemp($temp_id) {
        $query = $this->db->query("SELECT text FROM ".DB_PREFIX."text_template WHERE id = ".(int)$temp_id);
        return (isset($query->row['text']))?$query->row['text']:'';
    }
    
    public function saveTemp($temp_id, $temp_text){
        if($temp_id==2){
            $temp_text = str_replace("<b>", "<strong>", $temp_text);
            $temp_text = str_replace("&lt;b&gt;", "&lt;strong&gt;", $temp_text);
            $temp_text = str_replace("</b>", "</strong>", $temp_text);
            $temp_text = str_replace("&lt;/b&gt;", "&lt;/strong&gt;", $temp_text);
        }
        $query = $this->db->query("UPDATE ".DB_PREFIX."text_template SET text = '".$temp_text."' WHERE id = ".(int)$temp_id);
    }
    
    public function apply($data) {
        foreach ($data as $prod){
            $this->db->query("UPDATE ".DB_PREFIX."product_description SET description = ' ".$prod['text']." ' WHERE product_id = ".(int)$prod['id']." ");
        }
    }
    
    public function getProducts() {
        $query = "SELECT "
                    . "p.product_id AS pid, "
                    . "pd.description AS descript, "
                    . "cd.name AS podcat, "
                    . "b.name AS mark, "
                    . "p.length AS mr, "
                    . "p.model AS model, "
                    . "p.jan AS prim "
                . "FROM ".DB_PREFIX."product p "
                . "LEFT JOIN ".DB_PREFIX."product_description pd "
                    . "ON p.product_id = pd.product_id "
                . "LEFT JOIN ".DB_PREFIX."brand b "
                    . "ON p.manufacturer_id = b.id "
                . "LEFT JOIN ".DB_PREFIX."product_to_category p2c "
                    . "ON p.product_id = p2c.product_id "
                . "LEFT JOIN ".DB_PREFIX."category c "
                    . "ON c.category_id = p2c.category_id "
                . "LEFT JOIN ".DB_PREFIX."category_description cd "
                    . "ON cd.category_id = c.category_id "
                . "WHERE c.parent_id != 0 "
                . "ORDER BY p.product_id";
        $r = $this->db->query($query);
        $prods = $r->rows;
        $result = array();
        $i = 0;
        foreach ($prods as $prod){
            $result[$i] = array(
                'pid' => $prod['pid'],
                'brand' => $prod['mark'],
                'modr' => $prod['mr'],
                'podcat' => $prod['podcat'],
                'model' => $prod['model']
            );
            if($prod['prim']!=NULL){
                $result[$i]['note'] = 'Примечание: '.$prod['prim'];
            } else {
                $result[$i]['note'] = '';
            }
            ++$i;
        }
        return $result;
    }
}

