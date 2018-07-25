<?php

class ModelToolOrder extends Model{
    public function getOrders($offset, $filter = 0) {
        $result = array();
        $sql = "SELECT * FROM ".DB_PREFIX."order WHERE 1 ";
        if($filter){
            foreach ($filter as $key => $value) {
                $sql.= "AND LOCATE('".$value."', ".$key.")";
            }
        }
        $sql.= "ORDER BY date_added DESC LIMIT 30 OFFSET ".(int)$offset;
        $sup = $this->db->query($sql);
        foreach ($sup->rows as $order) {
            $stat = $this->db->query("SELECT * FROM ".DB_PREFIX."order_status WHERE order_status_id = ".(int)$order['order_status_id']);
            $result[$order['order_id']] = array(
                'store_name' => $order['store_name'],
                'customer'   => $order['firstname'].'<br>'.$order['lastname'],
                'contacts'   => $order['telephone'].'<br>'.$order['email'],
                'viewed'     => (int)$order['viewed'],
                'total'      => $order['total'],
                'status'     => isset($stat->row['name'])?$stat->row['name']:'Не указано',
                'date_added' => DateTime::createFromFormat('Y-m-d H:i:s', $order['date_added'])->format('d.‌​m.Y H:i')
            );
        }
        return $result;
    }
    
    public function updateProds($order) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."order_product WHERE order_id = ".(int)$order);
        $sql = "UPDATE ".DB_PREFIX."product SET status = 1 WHERE 0 ";
        foreach ($sup->rows as $prod) {
            $sql.= "OR product_id = ".(int)$prod['product_id'];
        }
        $this->db->query($sql);
    }
    
    public function getTotalOrders($filter = 0) {
        $sql = "SELECT * FROM ".DB_PREFIX."order WHERE 1 ";
        if($filter){
            foreach ($filter as $key => $value) {
                $sql.= "AND LOCATE('".$value."', ".$key.")";
            }
        }
        $sup = $this->db->query($sql);
        return $sup->num_rows;
    }
    
    public function getOrderInfo($id) {
        
        $result = array();
        $isup = $this->db->query("SELECT * FROM ".DB_PREFIX."order WHERE order_id = ".(int)$id);
        $psup = $this->db->query("SELECT *, op.quantity as factquantity FROM ".DB_PREFIX."order_product op "
                . "LEFT JOIN ".DB_PREFIX."product p ON op.product_id = p.product_id "
                . "WHERE order_id = ".(int)$id);
        if($isup->num_rows){
            $this->db->query("UPDATE ".DB_PREFIX."order SET viewed = 1 WHERE order_id = ".(int)$id);
            $result = array(
                'id' => $id,
                'date_added' => DateTime::createFromFormat('Y-m-d H:i:s', $isup->row['date_added'])->format('d.‌​m.Y H:i'),
                'firstname'  => $isup->row['firstname'],
                'lastname'   => $isup->row['lastname'],
                'patron'   => $isup->row['patron'],
                'email' => $isup->row['email'],
                'telephone' => $isup->row['telephone'],
                'zone' => $isup->row['payment_zone'],
                'city' => $isup->row['payment_city'],
                'address' => $isup->row['payment_address_1'],
                'total' => (int)$isup->row['total'],
                'order_status_id' => (int)$isup->row['order_status_id']
            );
            if($psup->num_rows){
                $result['products'] = $psup->rows;
            }
        }
        return $result;
    }
    
    public function tryComp($vin) {
        $result = array('key','prods');
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."complects WHERE link = '".$vin."'");
        if($sup->num_rows){
            $result['key'] = $sup->row['name'];
            $qprod = $this->db->query("SELECT * FROM ".DB_PREFIX."product p "
                    . "LEFT JOIN ".DB_PREFIX."product_description pd ON p.product_id = pd.product_id "
                    . "WHERE vin = '".$sup->row['heading']."' OR comp = '".$sup->row['heading']."' ");
            foreach ($qprod->rows as $value) {
                $result['prods'][] = $value;
            }
            return $result;
        } else {
            return FALSE;
        }
    }
    
    public function getOrderStatuses() {
        $sup = $this->db->query("SELECT order_status_id AS id, name FROM ".DB_PREFIX."order_status status");
        return $sup->rows;
    }
    
    public function pagination($total, $curr_page, $url) {
        $total_page = ceil($total/30);
        $result = '';
        if($total>30){
            $min = 1;
            $max = $total_page;
            $class = ($curr_page==$min)?'btn-primary':'btn-default';
            if($max<10){
                $result = '<a href="'.$this->url->link('sale/orders', 'token='.$this->session->data['token'].$url).'" class="btn '.$class.' btn-sm">1</a>';
                for ($i = 2; $i <= $max; $i++) {
                    $class = ($curr_page==$i)?'btn-primary':'btn-default';
                    $result.= '<a href="'.$this->url->link('sale/orders', 'token='.$this->session->data['token'].'&page='.$i.$url).'" class="btn '.$class.' btn-sm">'.$i.'</a>';
                }
            } else{
                if($curr_page>=6 && $curr_page<=($max-5)){
                    $result = '<a href="'.$this->url->link('sale/orders', 'token='.$this->session->data['token'].$url).'" class="btn '.$class.' btn-sm">1</a> ... ';
                    for($i = $curr_page-3; $i<=$curr_page+3; $i++){
                        $class = ($curr_page==$i)?'btn-primary':'btn-default';
                        $result.= '<a href="'.$this->url->link('sale/orders', 'token='.$this->session->data['token'].'&page='.$i.$url).'" class="btn '.$class.' btn-sm">'.$i.'</a>';
                    }
                    $result.= ' ... <a href="'.$this->url->link('sale/orders', 'token='.$this->session->data['token'].'&page='.$max.$url).'" class="btn btn-default btn-sm">'.$max.'</a>';
                } elseif ($curr_page<6) {
                    $result = '<a href="'.$this->url->link('sale/orders', 'token='.$this->session->data['token'].$url).'" class="btn '.$class.' btn-sm">1</a>';
                    for($i = 2; $i<=$curr_page+3; $i++){
                        $class = ($curr_page==$i)?'btn-primary':'btn-default';
                        $result.= '<a href="'.$this->url->link('sale/orders', 'token='.$this->session->data['token'].'&page='.$i.$url).'" class="btn '.$class.' btn-sm">'.$i.'</a>';
                    }
                    $result.= ' ... <a href="'.$this->url->link('sale/orders', 'token='.$this->session->data['token'].'&page='.$max.$url).'" class="btn btn-default btn-sm">'.$max.'</a>';
                } elseif ($curr_page>($max-5)) {
                    $result = '<a href="'.$this->url->link('sale/orders', 'token='.$this->session->data['token'].$url).'" class="btn '.$class.' btn-sm">1</a> ... ';
                    for($i = $curr_page-3; $i<$max; $i++){
                        $class = ($curr_page==$i)?'btn-primary':'btn-default';
                        $result.= '<a href="'.$this->url->link('sale/orders', 'token='.$this->session->data['token'].'&page='.$i.$url).'" class="btn '.$class.' btn-sm">'.$i.'</a>';
                    }
                    $result.= '<a href="'.$this->url->link('sale/orders', 'token='.$this->session->data['token'].'&page='.$max.$url).'" class="btn btn-default btn-sm">'.$max.'</a>';
                    
                }
            }
        }
        return $result;
    }
    
    public function updateProdStat($vin, $status) {
        $this->db->query("UPDATE ".DB_PREFIX."product SET status = ".(int)$status." WHERE vin = '".$vin."'");
    }
    
    public function added_prod($vin, $order_id) {
        $sup = $this->db->query("SELECT p.product_id, p.price, pd.name, p.quantity "
                                    . "FROM ".DB_PREFIX."product p "
                                    . "LEFT JOIN ".DB_PREFIX."product_description pd ON p.product_id = pd.product_id "
                                . "WHERE p.vin = '".$vin."' ");
        if($sup->num_rows){
            $quer = $this->db->query("SELECT * FROM ".DB_PREFIX."order_product "
                    . "WHERE order_id = ".(int)$order_id." AND product_id = ".(int)$sup->row['product_id']." ");
            if($quer->num_rows && $sup->row['quantity']>0){
                $this->db->query("UPDATE ".DB_PREFIX."order_product SET "
                                    . "quantity = quantity + 1, "
                                    . "total = total + ".(int)$sup->row['price']." "
                                 . "WHERE order_id = ".(int)$order_id." AND product_id = ".(int)$sup->row['product_id']." ");
            } elseif(!$quer->num_rows && $sup->row['quantity']>0){
                $this->db->query("INSERT INTO ".DB_PREFIX."order_product SET "
                        . "order_id = ".(int)$order_id.", "
                        . "product_id = ".(int)$sup->row['product_id'].", "
                        . "price = ".(int)$sup->row['price'].", "
                        . "quantity = 1, "
                        . "total = ".(int)$sup->row['price'].", "
                        . "name = '".$sup->row['name']."' ");
            } elseif($sup->row['quantity']<=0) {
                return FALSE;
            }
            $this->db->query("UPDATE ".DB_PREFIX."product SET "
                                . "status = 2 "
                             . "WHERE product_id = ".(int)$sup->row['product_id']." ");
            $this->db->query("UPDATE ".DB_PREFIX."order SET "
                                . "total = total + ".(int)$sup->row['price']." "
                             . "WHERE order_id = ".(int)$order_id." ");
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function delete_prod($prod, $order) {
        $sup = $this->db->query("SELECT op.total, op.price, op.quantity "
                                    . "FROM ".DB_PREFIX."order_product op "
                                    . "WHERE op.product_id = '".(int)$prod."' AND op.order_id = ".(int)$order);
        if($sup->num_rows){
            if($sup->row['quantity']==1){
                $this->db->query("DELETE FROM ".DB_PREFIX."order_product "
                        . "WHERE product_id = '".(int)$prod."' AND order_id = ".(int)$order);
            } else {
                $this->db->query("UPDATE ".DB_PREFIX."order_product SET "
                            . "quantity = quantity - 1, "
                            . "total = total - ".(int)$sup->row['price']." "
                         . "WHERE order_id = ".(int)$order." AND product_id = ".(int)$prod." ");
            }
            $this->db->query("UPDATE ".DB_PREFIX."order SET "
                                . "total = total - ".(int)$sup->row['price']." "
                             . "WHERE order_id = ".(int)$order." ");
            $this->db->query("UPDATE ".DB_PREFIX."product SET "
                                . "status = 1 "
                             . "WHERE product_id = ".(int)$prod." ");
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function save_status($stat, $order) {
        $this->db->query("UPDATE ".DB_PREFIX."order SET "
                            . "order_status_id = ".(int)$stat." "
                         . "WHERE order_id = ".(int)$order." ");
    }
}

