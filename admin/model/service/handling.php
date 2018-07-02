<?php

class ModelServiceHandling extends Model {
    public function createAccident($acc){
        $sql = "INSERT INTO ".DB_PREFIX."accidents SET ";
        foreach ($acc as $key => $value) {
            if($key!='date' && $key!='dateprot' && $key!='datedecree' && $key!='dateref' && $key!='datespec' && $key!='causer_ins_date'){
                $sql.= $key." = '".$value."', ";
            }
        }
        $sql.= "date = '".date("Y-m-d H:i:s", strtotime($acc['date']))."', ";
        $sql.= "dateprot = '".date("Y-m-d", strtotime($acc['dateprot']))."', ";
        $sql.= "datedecree = '".date("Y-m-d", strtotime($acc['datedecree']))."', ";
        $sql.= "dateref = '".date("Y-m-d", strtotime($acc['dateref']))."', ";
        $sql.= "datespec = '".date("Y-m-d", strtotime($acc['datespec']))."', ";
        $sql.= "causer_ins_date = '".date("Y-m-d H:i:s", strtotime($acc['causer_ins_date']))."' ";
        $this->db->query($sql);
        $id = $this->db->getLastId();
        return $id;
    }
    
    public function createHandling($handling) {
        $sql = "INSERT INTO ".DB_PREFIX."handling SET ";
        foreach ($handling as $key => $value) {
            $sql.= $key." = '".$value."', ";
        }
        $sql.= "date = NOW()";
        $this->db->query($sql);
        return $this->db->getLastId();
    }
    
    public function getHandlingInfo($handl) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."handling WHERE id = ".(int)$handl);
        return $sup->row;
    }
    public function getAccidentInfo($acc) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."accidents WHERE id = ".(int)$acc);
        return $sup->row;
    }
}

