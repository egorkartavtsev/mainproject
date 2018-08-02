<?php
    class ModelComplectComplect extends Model {
        public function validation($vin) {
            
            $prod = $this->db->query("SELECT pd.name AS name FROM ".DB_PREFIX."product p "
                    . "LEFT JOIN ".DB_PREFIX."product_description pd "
                        . "ON pd.product_id = p.product_id"
                    . " WHERE p.vin = '".$vin."' ");
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
            $query = "DELETE FROM ".DB_PREFIX."product WHERE vin = '".$ar->row['link']."' ";
            $this->db->query($query);
        }
        
        public function getComplect($complect) {
            
            $query_comp = $this->db->query("SELECT * FROM ".DB_PREFIX."complects WHERE id = '".$complect."' ");
            $complect_info = array(
                'id'        => $query_comp->row['id'],
                'name'      => $query_comp->row['name'],
                'price'     => $query_comp->row['price'],
                'heading'   => $query_comp->row['heading'],
                'sale'      => $query_comp->row['sale']
            );
            
            $query = 'SELECT p.product_id, p.vin AS vin, pd.name AS name, p.price AS price '
                   . 'FROM '.DB_PREFIX.'product p '
                   . 'LEFT JOIN '.DB_PREFIX.'product_description pd '
                        . 'ON pd.product_id = p.product_id '
                    . "WHERE p.comp = '".$complect_info['heading']."' OR p.vin = '".$complect_info['heading']."' ";
            $query_acc = $this->db->query($query);
            $complect_info['accessories'] = array();
            foreach ($query_acc->rows as $prod) {
                $complect_info['accessories'][] = array(
                    'vin'           => $prod['vin'],
                    'product_id'    => $prod['product_id'],
                    'heading'       => $complect_info['heading']==$prod['vin']?TRUE:FALSE,
                    'price'         => $prod['price'],
                    'name'          => $prod['name'],
                    'cp_link'       => $this->url->link('production/catalog/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $prod['product_id'])
                );
            }
            
            return $complect_info;
        }
        
        public function create($name, $price, $heading, $complect=0, $whole, $sale=0) {
            
            $quer = $this->db->query("SELECT * FROM ".DB_PREFIX."product WHERE vin = '".$heading."'");
            $image = $quer->row['image'];
            $link = uniqid('complect');
            $complects = $complect!=0?$complect:array();
        /*создаём пустой товар для обозначения комплекта*/
            $this->db->query("INSERT INTO ".DB_PREFIX."product (vin, price, status, quantity, viewes, image, date_added) VALUES ('".$link."', ".(int)$price.", 0, 1, 0, '".$image."', NOW())");
            $prod = $this->db->getLastId();
            $this->db->query("INSERT INTO ".DB_PREFIX."product_description (name, language_id, product_id) VALUES ('Комплект: ".$name."', 1, ".$prod.")");
            $this->db->query("INSERT INTO ".DB_PREFIX."product_to_store (product_id, store_id) VALUES (".(int)$prod.", 0)");
            
        /*создаём комплект*/
            $quer = "INSERT INTO ".DB_PREFIX."complects (name, price, heading, image, link, whole, sale) "
                    . "VALUES ('".$name."', ".(int)$price.", '".$heading."', '".$image."', '".$link."', ".(int)$whole.", ".(int)$sale.")";
            $this->db->query($quer);
            $comp_id = $this->db->getLastId();
        /*головному товару прописываем принадлежность к комплекту*/
            $quer = "UPDATE ".DB_PREFIX."product "
                    . "SET comp = '".$comp_id."', "
                    . "comp_price = '".(int)$price."', "
                    . "comp_whole = ".(int)$whole." "
                    . "WHERE vin = '".$heading."'";
            $this->db->query($quer);
        /*привязываем комплектующие к головному товару*/
            $price = 0;
            foreach ($complects as $com){
                if($com!==''){
                    $sup = $this->db->query("SELECT price FROM ".DB_PREFIX."product WHERE vin = '".$com."'");
                    $price+= $sup->row['price'];
                    $quer = "UPDATE ".DB_PREFIX."product "
                        . "SET comp = '".$heading."' "
                        . "WHERE vin = '".$com."'";
                    $this->db->query($quer);
                }
            }
            $sup = $this->db->query("SELECT price FROM ".DB_PREFIX."product WHERE vin = '".$heading."' ");
            $price+= $sup->row['price'];
            $sale = $sale==0?15:$sale;
            $supsale = 100 - $sale;
            $supsale = $supsale/100;
            $price = ceil($price*$supsale);
            //okruglenie
                if($price<500){
                    $rvr = $price%100;
                    if($rvr>0){
                        $rvr = 50 - $rvr;
                        $price = $price + $rvr;
                        if($sale%10!=0){
                            $helper = $price%100;
                            $price = $price+(100-$helper);
                        }
                    }
                } else {
                    $rvr = $price%100;
                    $rvr = 100 - $rvr;
                    $price = $price + $rvr;
                    if($sale%10!=0){
                        $helper = $price%100;
                        $price = $price+(100-$helper);
                    }
                }
            //---------------
            $this->db->query("UPDATE ".DB_PREFIX."complects SET price = '".$price."' WHERE heading = '".$heading."'");
            $this->db->query("UPDATE ".DB_PREFIX."product SET price = '".$price."' WHERE vin = '".$link."'");
            $this->db->query("UPDATE ".DB_PREFIX."product SET comp_price = '".$price."' WHERE vin = '".$heading."'");
        }
        
        public function editComplect($id, $name, $price, $heading, $complect=0, $whole, $sale=0) {
            $this->load->model('tool/complect');
            $quer = $this->db->query("SELECT * FROM ".DB_PREFIX."product WHERE vin = '".$heading."'");
            $image = $quer->row['image'];
            $query = $this->db->query("SELECT * FROM ".DB_PREFIX."complects WHERE id = '".$id."'");
            
            $quer = "UPDATE ".DB_PREFIX."complects "
                    . "SET name = '".$name."', "
                        . "price = ".(int)$price.", "
                        . "heading = '".$heading."', "
                        . "image = '".$image."', "
                        . "sale = '".$sale."', "
                        . "whole = ".(int)$whole." "
                    . "WHERE id = '".$id."' ";
            $this->db->query($quer);
            $quer = "UPDATE ".DB_PREFIX."product "
                    . "SET comp = '".$id."' "
                    . "WHERE vin = '".$heading."'";
            $this->db->query($quer);
            
            $this->db->query("UPDATE ".DB_PREFIX."product SET price = '".$price."' WHERE `vin` = '".$query->row['link']."' ");
            $this->db->query("UPDATE ".DB_PREFIX."product SET comp_whole = '".$whole."' WHERE `vin` = '".$heading."' ");
            $price = 0;

            foreach ($complect as $com){
                if($com!==''){
                    $sup = $this->db->query("SELECT price FROM ".DB_PREFIX."product WHERE vin = '".$com."'");
                    $price+= $sup->row['price'];
                    $quer = "UPDATE ".DB_PREFIX."product "
                        . "SET comp = '".$heading."' "
                        . "WHERE vin = '".$com."'";
                    $this->db->query($quer);
                }
            }
            $this->model_tool_complect->compReprice($heading);
//            $sup = $this->db->query("SELECT price FROM ".DB_PREFIX."product WHERE vin = '".$heading."' ");
//            $price+= $sup->row['price'];
//            $sale = $sale==0?15:$sale;
//            $supsale = 100 - $sale;
//            $supsale = $supsale/100;
//            $price = ceil($price*$supsale);
//            //okruglenie
//                if($price<500){
//                    $rvr = $price%100;
//                    if($rvr>0){
//                        $rvr = 50 - $rvr;
//                        $price = $price + $rvr;
//                        if($sale%10!=0){
//                            $helper = $price%100;
//                            $price = $price+(100-$helper);
//                        }
//                    }
//                } else {
//                    $rvr = $price%100;
//                    $rvr = 100 - $rvr;
//                    $price = $price + $rvr;
//                    if($sale%10!=0){
//                        $helper = $price%100;
//                        $price = $price+(100-$helper);
//                    }
//                }
//            //---------------
//            $this->db->query("UPDATE ".DB_PREFIX."complects SET price = '".$price."' WHERE heading = '".$heading."'");
//            $this->db->query("UPDATE ".DB_PREFIX."product SET price = '".$price."' WHERE vin = '".$query->row['link']."'");
//            $this->db->query("UPDATE ".DB_PREFIX."product SET comp_price = '".$price."' WHERE vin = '".$heading."'");
        }
        
        public function getTotalComplects() {
            $query = $this->db->query("SELECT * FROM ".DB_PREFIX."complects WHERE 1");
            $arr = $query->rows;
            if(!empty($arr)){
                foreach ($arr as $comp) {
                    $result[] = array(
                        'id' => $comp['id'],
                        'name' => $comp['name'],
                        'link' => $comp['link'],
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
                        . "WHERE ";
            if(count($reqwords)==1){
                $query.="0 OR LOCATE ('" . $this->db->escape($reqwords[0]) . "', c.name) OR LOCATE ('" . $this->db->escape($reqwords[0]) . "', c.heading)";
            } else {
                $query.="1 ";
                foreach ($reqwords as $word){
                    $query.="AND LOCATE ('" . $this->db->escape($word) . "', c.name) ";
                }
            }
            $result = $this->db->query($query);
            return $result->rows;
        }
    }