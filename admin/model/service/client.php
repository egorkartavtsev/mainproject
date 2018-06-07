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
    public function getClients($data) {
        $sql = "SELECT * FROM ".DB_PREFIX."client WHERE ";
        if(isset($data['filter_fio'])){
            $req = explode(" ", $data['filter_fio']);
            foreach ($req as $word) {
                $sql.= "(LOCATE('".$word."', secondname) OR LOCATE('".$word."', name) OR LOCATE('".$word."', firstname) OR LOCATE('".$word."', patronymic)) AND ";
            }
        }
        if(isset($data['filter_phone'])){
            $sql.= "(LOCATE('".$data['filter_phone']."', phone1) OR LOCATE('".$data['filter_phone']."', phone2)) AND ";
        }
        if(isset($data['filter_city'])){
            $sql.= "(LOCATE('".$data['filter_city']."', fcity) OR LOCATE('".$data['filter_city']."', lcity)) AND ";
        }
        $sql.= "1 ORDER BY id DESC LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
//        exit($sql);
        $sup = $this->db->query($sql);
        return $sup->rows;
    }
    public function getTotalClients($data) {
        $sql = "SELECT * FROM ".DB_PREFIX."client WHERE ";
        if(isset($data['filter_fio'])){
            $req = explode(" ", $data['filter_fio']);
            foreach ($req as $word) {
                $sql.= "(LOCATE('".$word."', secondname) OR LOCATE('".$word."', name) OR LOCATE('".$word."', firstname) OR LOCATE('".$word."', patronymic)) AND ";
            }
        }
        if(isset($data['filter_phone'])){
            $sql.= "(LOCATE('".$data['filter_phone']."', phone1) OR LOCATE('".$data['filter_phone']."', phone2)) AND ";
        }
        if(isset($data['filter_city'])){
            $sql.= "(LOCATE('".$data['filter_city']."', fcity) OR LOCATE('".$data['filter_city']."', lcity)) AND ";
        }
        $sql.= "1 ORDER BY id DESC ";
        $sup = $this->db->query($sql);
        return $sup->num_rows;
    }
    
}

