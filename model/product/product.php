<?php

class ModelProductProduct extends Model {
    public function getProducts($flag, $param, $sort, $filter=0) {
        $sql = "";
        $limof = "";
        if(!$filter){
            $page = isset($this->request->get['page'])?$this->request->get['page']:1;
            $limit = $this->config->get('theme_default_product_limit');
            if($page==1){
                $offset = $page-1;
            } else {            
                $offset = ($page-1)*$limit;
            }
            $limof = "LIMIT ".(int)$limit." OFFSET ".(int)$offset;
        }
        switch ($flag) {
            case 'type':
                $sql = "SELECT *, (SELECT name FROM ".DB_PREFIX."product_description pd WHERE pd.product_id = p.product_id) AS name FROM ".DB_PREFIX."product p WHERE p.structure = ".(int)$param." ";
                if(is_array($filter)){
                    foreach ($filter as $key => $value) {
                        if(trim($value)!=='' && trim($value)!=='-'){
                            $sql.= "AND (LOCATE('".$value."', p.".$key.") OR LOCATE('".$value."', p.compability)) ";
                        }
                    }
                    $limof = "";
                }
                $sql.= "AND p.vin!='' AND !LOCATE('complect', p.vin) AND (p.status = 1 OR p.status = 2)  ".$sort." ".$limof." ";
            break;
            case 'libr':
                $sql = "SELECT *, (SELECT name FROM ".DB_PREFIX."product_description pd WHERE pd.product_id = p.product_id) AS name FROM ".DB_PREFIX."product_to_lib p2l "
                        . "LEFT JOIN ".DB_PREFIX."product p ON p2l.product_id = p.product_id "
                    . "WHERE p2l.fill_id = ".(int)$param." ";
                if(is_array($filter)){
                    foreach ($filter as $key => $value) {
                        if(trim($value)!=='' && trim($value)!=='-' && trim($value)!=='Все товары'){
                            $sql.= "AND LOCATE('".$value."', p.".$key.") ";
                        }
                    }
                    $limof = "";
                }
                $sql.= "AND p.vin!='' AND !LOCATE('complect', p.vin) AND (p.status = 1 OR p.status = 2) ".$sort." ".$limof." ";
            break;
            case 'search':
            break;
        }
//        exit(var_dump($filter));
//        exit($sql);
        $query = $this->db->query($sql);
        return $query->rows;
    }
    public function getTotalProducts($flag, $param, $filter=0) {
        $sql = "";
        switch ($flag) {
            case 'type':
                $sql = "SELECT * FROM ".DB_PREFIX."product WHERE structure = ".(int)$param." ";
                if(is_array($filter)){
                    foreach ($filter as $key => $value) {
                        if(trim($value)!=='' && trim($value)!=='-'){
                            $sql.= "AND LOCATE('".$value."', ".$key.") ";
                        }
                    }
                }
//                $sql.= "ORDER BY date_added DESC ";
                $sql.= "AND vin!='' AND !LOCATE('complect', vin) AND (status = 1 OR status = 2) ORDER BY date_added DESC ";
            break;
            case 'libr':
                $sql = "SELECT * FROM ".DB_PREFIX."product_to_lib p2l "
                        . "LEFT JOIN ".DB_PREFIX."product p ON p2l.product_id = p.product_id "
                    . "WHERE p2l.fill_id = ".(int)$param." ";
                if(is_array($filter)){
                    foreach ($filter as $key => $value) {
                        if(trim($value)!=='' && trim($value)!=='-'){
                            $sql.= "AND LOCATE('".$value."', ".$key.") ";
                        }
                    }
                }
                $sql.= " ORDER BY p.date_added DESC ";
            break;
            case 'search':
            break;
        }
        $query = $this->db->query($sql);
        return count($query->rows);
    }
    
    public function applyFilter($filter){
        $result = array();
        $sql = "";
        
        return $result;
    }
    
    public function getSimilar($id, $struct=1){
        $result = [];
        if($struct==1){
            $result = $this->db->query("SELECT * FROM ".DB_PREFIX."product p "
                    . "LEFT JOIN ".DB_PREFIX."product_description pd ON p.product_id = pd.product_id "
                    . "WHERE p.price > 0 AND p.status > 0 AND p.model=(SELECT model FROM ".DB_PREFIX."product p2 WHERE p2.product_id=".(int)$id.") ORDER BY p.date_added DESC LIMIT 15")->rows;
        }
        return $result;
    }
    
}
