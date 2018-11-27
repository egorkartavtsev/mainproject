<?php
class ModelCheckoutNewOrder extends Model {
    
	public function newOrder($data) {
            //exit(var_dump($data));
            $sql = "INSERT INTO ".DB_PREFIX."order SET ";
            foreach ($data as $key => $value) {
                $sql.= $key." = '". $this->db->escape($value)."', ";
            }
            $custLogged = $this->customer->isLogged();
            if ($custLogged == ''){ 
                $custLogged= '0';
            }
            $sql.="customer_id =  ".$custLogged.", store_id = 0, order_status_id = 16, date_added = NOW(), date_modified = NOW() ";
            $this->db->query($sql);
            $this->session->data['order_id'] = $this->db->getLastId();
	}
        
        public function setProducts($prods) {
            $order = $this->session->data['order_id'];
            $sql = "INSERT INTO ".DB_PREFIX."order_product (order_id, product_id, name,quantity, price, total) VALUES ";
            $total = 0;            
            foreach($prods as $key => $prod){
                $sql.= "(".(int)$order.", "
                        . "".(int)$prod['product_id'].", "
                        . "'".$prod['name']."', "
                        .(int)$prod['quantity'].", "
                        .(int)$prod['price'].", "
                        .(int)$prod['total'].")";
                if($key < (count($prods))-1){
                    $sql.= ", ";
                }
                $total+= $prod['total'];
                $this->db->query("UPDATE ".DB_PREFIX."product SET status = 2 WHERE product_id = ".$prod['product_id']);
            }
            $sql.= ";";
            $this->db->query($sql);
            $this->updateOrder("total", $total);
        }
        
        public function updateOrder($field, $value) {
            $order = $this->session->data['order_id'];
            $this->db->query("UPDATE ".DB_PREFIX."order SET ".$field." = '".$value."' WHERE order_id = ".(int)$order);
        }
}