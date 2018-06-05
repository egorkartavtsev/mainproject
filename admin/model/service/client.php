<?php

class ModelServiceClient extends Model{
    
    public function create($info) {
        if((int)$info['legal']){
            $info['bdate'] = date('Y-m-d', strtotime($info['bdate']));
            $info['datepas'] = date('Y-m-d', strtotime($info['datepas']));
            $info['datedlicense'] = date('Y-m-d', strtotime($info['datedlicense']));
        }
        $sql = "INSERT INTO ".DB_PREFIX."client SET ";
        foreach ($info as $key => $value) {
            if($key!='legal'){
                $sql.= $key." = '".$value."', ";
            }
        }
        $sql.= " legal = ".$info['legal'];
        $this->db->query($sql);
        $sup = $this->db->query("SELECT MAX(id) as new FROM ".DB_PREFIX."client");
        return $sup->row['new'];
    }
    public function getClients() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."client WHERE 1");
        return $sup->rows;
    }
    public function getTotalClients() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."client WHERE 1");
        return $sup->num_rows;
    }
    
}

