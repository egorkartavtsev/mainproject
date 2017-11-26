<?php
    class ModelComplectComplect extends Model {
        public function validation($vin) {
            
            $prod = $this->db->query("SELECT pd.name AS name FROM ".DB_PREFIX."product p "
                    . "LEFT JOIN ".DB_PREFIX."product_description pd "
                        . "ON pd.product_id = p.product_id"
                    . " WHERE p.sku = '".$vin."' ");
            return $prod->row;
        }
        
        public function writeOff($id) {
            
            $query = "SELECT * FROM ".DB_PREFIX."complects WHERE id = '".$id."' ";
            $ar = $this->db->query($query);
            $heading = $ar->row['heading'];
            
            $query = "UPDATE ".DB_PREFIX."product SET comp = '' WHERE comp = '".$heading."' OR comp = '".$id."' ";
            $this->db->query($query);
            $query = "DELETE FROM ".DB_PREFIX."complects WHERE id = '".$id."' ";
            $this->db->query($query);
            $query = "DELETE FROM ".DB_PREFIX."product WHERE sku = '".$ar->row['link']."' ";
            $this->db->query($query);
        }
        
        public function getComplect($complect) {
            
            $query_comp = $this->db->query("SELECT * FROM ".DB_PREFIX."complects WHERE id = '".$complect."' ");
            $complect_info = array(
                'id' => $query_comp->row['id'],
                'name' => $query_comp->row['name'],
                'price' => $query_comp->row['price'],
                'heading' => $query_comp->row['heading']
            );
            
            $query = 'SELECT p.sku AS vin, pd.name AS name '
                   . 'FROM '.DB_PREFIX.'product p '
                   . 'LEFT JOIN '.DB_PREFIX.'product_description pd '
                        . 'ON pd.product_id = p.product_id '
                    . "WHERE p.comp = '".$complect_info['heading']."' ";
            $query_acc = $this->db->query($query);
            $complect_info['accessories'] = array();
            foreach ($query_acc->rows as $prod) {
                $complect_info['accessories'][] = array(
                    'vin' => $prod['vin'],
                    'name' => $prod['name']
                );
            }
            
            return $complect_info;
        }
        
        public function create($name, $price, $heading, $complect=0, $whole) {
            
            $quer = $this->db->query("SELECT * FROM ".DB_PREFIX."product WHERE sku = '".$heading."'");
            $image = $quer->row['image'];
            $link = uniqid('complect');
            $complects = $complect!=0?$complect:array();
        /*создаём пустой товар для обозначения комплекта*/
            $this->db->query("INSERT INTO ".DB_PREFIX."product (sku, price, status, quantity, viewed, image, date_added) VALUES ('".$link."', ".(int)$price.", 0, 1, 0, '".$image."', NOW())");
            $prod = $this->db->getLastId();
            $this->db->query("INSERT INTO ".DB_PREFIX."product_description (name, language_id, product_id) VALUES ('Комплект: ".$name."', 1, ".$prod.")");
            $this->db->query("INSERT INTO ".DB_PREFIX."product_to_store (product_id, store_id) VALUES (".(int)$prod.", 0)");
            
        /*создаём комплект*/
            $quer = "INSERT INTO ".DB_PREFIX."complects (name, price, heading, image, link, whole) "
                    . "VALUES ('".$name."', ".(int)$price.", '".$heading."', '".$image."', '".$link."', ".(int)$whole.")";
            $this->db->query($quer);
            $comp_id = $this->db->getLastId();
        /*головному товару прописываем принадлежность к комплекту*/
            $quer = "UPDATE ".DB_PREFIX."product "
                    . "SET comp = '".$comp_id."', "
                    . "comp_price = '".(int)$price."', "
                    . "comp_whole = ".(int)$whole." "
                    . "WHERE sku = '".$heading."'";
            $this->db->query($quer);
        /*привязываем комплектующие к головному товару*/
            foreach ($complects as $com){
                if($com!==''){
                    $quer = "UPDATE ".DB_PREFIX."product "
                        . "SET comp = '".$heading."', "
                            . "image = '".$image."' "
                        . "WHERE sku = '".$com."'";
                    $this->db->query($quer);
                }
            }
        }
        
        public function editComplect($id, $name, $price, $heading, $complect=0, $whole) {
            
            $quer = $this->db->query("SELECT * FROM ".DB_PREFIX."product WHERE sku = '".$heading."'");
            $image = $quer->row['image'];
            $query = $this->db->query("SELECT * FROM ".DB_PREFIX."complects WHERE id = '".$id."'");
            
            $quer = "UPDATE ".DB_PREFIX."complects "
                    . "SET name = '".$name."', "
                        . "price = ".(int)$price.", "
                        . "heading = '".$heading."', "
                        . "image = '".$image."', "
                        . "whole = ".(int)$whole." "
                    . "WHERE id = '".$id."' ";
            $this->db->query($quer);
            $quer = "UPDATE ".DB_PREFIX."product "
                    . "SET comp = '".$id."' "
                    . "WHERE sku = '".$heading."'";
            $this->db->query($quer);
            
            $this->db->query("UPDATE ".DB_PREFIX."product SET price = '".$price."' WHERE `sku` = '".$query->row['link']."' ");
                
            foreach ($complect as $com){
                if($com!==''){
                    $quer = "UPDATE ".DB_PREFIX."product "
                        . "SET comp = '".$heading."', "
                            . "image = '".$image."' "
                        . "WHERE sku = '".$com."'";
                    $this->db->query($quer);
                }
            }
        }
        
        public function getTotalComplects() {
            $query = $this->db->query("SELECT * FROM ".DB_PREFIX."complects WHERE 1");
            $arr = $query->rows;
            if(!empty($arr)){
                foreach ($arr as $comp) {
                    $result[] = array(
                        'id' => $comp['id'],
                        'name' => $comp['name'],
                        'price' => $comp['price'],
                        'heading' => $comp['heading'],
                        'href' => HTTP_SERVER.'index.php?route=complect/complect/edit&complect='.$comp['id'].'&token='.$this->session->data['token']
                    );
                }
            }
            return (isset($result))?$result:NULL;
        }
        
        public function searchComplects($request) {
            $reqwords = explode(" ", $request);
            
            $query = "SELECT * FROM ".DB_PREFIX."complects c "
                        . "WHERE 1 ";
            foreach ($reqwords as $word){
                $query.="AND LOCATE ('" . $this->db->escape($word) . "', c.name) ";
            }
            $result = $this->db->query($query);
            return $result->rows;
        }
    }