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
                . "WHERE a2c.client_id = ".(int)$id);
        return $sup->rows;
    }
    public function tryVIN($vin) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."automobile WHERE vin = '".(int)$vin."'");
        if($sup->num_rows){
            return $sup->row;
        } else {
            return FALSE;
        }
    }
}
