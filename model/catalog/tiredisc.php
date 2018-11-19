<?php

class ModelCatalogTiredisc extends Model {
    
    public function getAll() {
        $result = array();
        $query = "SELECT "
                    . "p.product_id AS pid, "
                    . "p.minimum AS minimum, " 
                    . "pd.name AS name, "
                    . "p.image AS image, "
                    . "p.vin AS vin, "
                    . "p.price AS price, "
                    . "p.type AS type, "
                    . "p.cond AS cond, "
                    . "disc.image AS dImage, "
                    . "tire.image AS tImage "
                . "FROM ".DB_PREFIX."product p "
                . "LEFT JOIN ".DB_PREFIX."product_description pd ON p.product_id = pd.product_id "
                . "LEFT JOIN ".DB_PREFIX."td_disc disc ON p.vin = disc.vin "
                . "LEFT JOIN ".DB_PREFIX."td_tires tire ON p.vin = tire.vin "
                . "WHERE p.status='1' AND p.product_id = tire.link OR p.status='1' AND p.product_id = disc.link ";
        $list = $this->db->query($query);
        
        return $list->rows;
    }
    
    public function getTires($filter) {
        $result = array();
        $query = "SELECT "
                    . "p.product_id AS pid, "
                    . "p.minimum AS minimum, "   
                    . "pd.name AS name, "
                    . "p.image AS image, "
                    . "p.vin AS vin, "
                    . "p.price AS price, "
                    . "p.type AS type, "
                    . "p.cond AS cond "
                . "FROM ".DB_PREFIX."product p "
                . "LEFT JOIN ".DB_PREFIX."product_description pd ON p.product_id = pd.product_id "
                . "LEFT JOIN ".DB_PREFIX."td_tires tire ON p.vin = tire.vin "
                . "WHERE p.product_id = tire.link ";
        if(!empty($filter)){
            foreach ($filter as $field => $value) {
                $query.= "AND tire.".$field." = '".$value."' ";
            }
        }
        $query.= "AND p.status='1' ";
//        exit(var_dump($query));
        $list = $this->db->query($query);
        
        return $list->rows;
    }
    
    public function getDisc($filter) {
        $result = array();
        $query = "SELECT "
                    . "p.product_id AS pid, "
                    . "p.minimum AS minimum, "
                    . "pd.name AS name, "
                    . "p.image AS image, "
                    . "p.vin AS vin, "
                    . "p.price AS price, "
                    . "p.type AS type, "
                    . "p.cond AS cond "
                . "FROM ".DB_PREFIX."product p "
                . "LEFT JOIN ".DB_PREFIX."product_description pd ON p.product_id = pd.product_id "
                . "LEFT JOIN ".DB_PREFIX."td_disc disc ON p.vin = disc.vin "
                . "WHERE p.product_id = disc.link ";
        if(!empty($filter)){
            foreach ($filter as $field => $value) {
                $query.= "AND disc.".$field." = '".$value."' ";
            }
        }
        $query.= "AND p.status='1' ";
//        exit(var_dump($query));
        $list = $this->db->query($query);
        
        return $list->rows;
    }
    
    public function getParams($belong) {
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."td_params WHERE belong = '".$belong."' ");
        $result = array();
        foreach ($query->rows as $param) {
            $result[$param['field']] = $param['name'];
        }
        return $result;
    }
    
    public function getProdInfo($pid, $belong) {
        $params = $this->getParams($belong);
        $table = $belong=='tire'?'tires':'disc';
        $sql = "SELECT ";
        foreach ($params as $field => $value){
            $sql.= $belong.".".$field." AS ".$field.", ";
        }
        $sql.="p.image AS image, "
            . "p.price AS price, "
            . "p.stock AS stock, "
            . "p.type AS type, "
            . "p.vin AS vin, "
            . "p.quantity AS quantity, "
            . "pd.name AS name, "
            . "pd.tag AS tags, "
            . "pd.description AS description, "
            . "p.comp AS complect, "
            . "p.cond AS cond "
        . " FROM ".DB_PREFIX."td_".$table." ".$belong." "
                . "LEFT JOIN ".DB_PREFIX."product p ON p.vin = ".$belong.".vin "
                . "LEFT JOIN ".DB_PREFIX."product_description pd ON pd.product_id = p.product_id "
                . "WHERE p.product_id = ".(int)$pid;
        $result = $this->db->query($sql);
        return $result->row;
    }
    
    public function getValues($param, $belong) {
        $query = $this->db->query("SELECT lib.value FROM ".DB_PREFIX."td_lib lib LEFT JOIN ".DB_PREFIX."td_params prm ON prm.id = lib.id_param WHERE prm.field = '".$param."' AND prm.belong = '".$belong."'");
        $result = array();
        foreach ($query->rows as $value) {
            $result[] = $value['value'];
        }
        return $result;
    }
    
    public function getCItems($heading) {
        $result = array();
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."complects WHERE heading = '".$heading."' OR id = '".$heading."'");
        $qlink = $this->db->query("SELECT product_id FROM ".DB_PREFIX."product WHERE vin = '".$sup->row['link']."'");
        $result['whole'] = $sup->row['whole'];
        $result['c_price'] = $sup->row['price'];
        $result['link'] = $qlink->row['product_id'];
        $qitems = $this->db->query("SELECT pd.name, p.price, pd.product_id FROM ".DB_PREFIX."product p LEFT JOIN ".DB_PREFIX."product_description pd ON p.product_id = pd.product_id WHERE p.vin = '".$sup->row['heading']."' OR p.comp = '".$sup->row['heading']."'");
        $result['items'] = $qitems->rows;
//        exit(var_dump($result));
        return $result;
    }
}

