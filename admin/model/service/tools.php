<?php

class ModelServiceTools extends Model{
    public function findLocate($req, $kladr, $lvl) {
        $sql = "SELECT * FROM ".DB_PREFIX."kladr WHERE LOCATE('".$req."', name) ";
        if($kladr!='0'){
            $sql.= "AND kladr LIKE '".$kladr."%' ";
        }
        $sql.= "AND item_id = '".$lvl."' ORDER BY kladr LIMIT 10";
        $sup = $this->db->query($sql);
        return $sup->rows;
    }
    
    public function getClientInfo($id) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."client WHERE id = ".(int)$id);
        return $sup->row;
    }
    
    public function getClientAuto($id) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."auto_to_client a2c "
                . "LEFT JOIN ".DB_PREFIX."automobiles a ON a2c.auto_id = a.id "
                . "WHERE a2c.client_id = ".(int)$id." ORDER BY a2c.status DESC, a2c.id DESC");
        return $sup->rows;
    }
    
    public function getClientHandlings($id) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."handling "
                . "WHERE client = ".(int)$id." ORDER BY date DESC");
        return $sup->rows;
    }
    
    public function tryVIN($vin) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."automobiles WHERE vin = '".$vin."' ORDER BY id DESC");
        if($sup->num_rows){
            $reslt = $sup->row;
            $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE name = '".$reslt['model']."' AND item_id = 17");
            $reslt['model'] = array(
                'id' => $sup->row['id'],
                'name' => $sup->row['name']
            );
            return $reslt;
        } else {
            return FALSE;
        }
    }
        
    public function getBrands() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE parent_id = 0 AND item_id = 16 ORDER BY name");
        return $sup->rows;
    }
    public function getEclass() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE parent_id = 0 AND item_id = 32 ORDER BY name");
        return $sup->rows;
    }
    public function getColors() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE parent_id = 0 AND item_id = 29 ORDER BY name");
        return $sup->rows;
    }
    public function getCateg() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE parent_id = 0 AND item_id = 30 ORDER BY name");
        return $sup->rows;
    }
    public function getTypes() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE parent_id = 0 AND item_id = 31 ORDER BY name");
        return $sup->rows;
    }
    public function getCommissars() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE parent_id = 0 AND item_id = 33 ORDER BY name");
        return $sup->rows;
    }
    public function getInsurences() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE parent_id = 0 AND item_id = 34 ORDER BY name");
        return $sup->rows;
    }
    public function getHandlTypes() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE parent_id = 0 AND item_id = 35 ORDER BY name");
        return $sup->rows;
    }
}
