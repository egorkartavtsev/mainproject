<?php

class ModelServiceAuto extends Model{
    public function addAuto($info) {
        $sql = '';
        $brand = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE id = ".(int)$info['select-brand']);
        $model = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE id = ".(int)$info['select-model']);
        $datepts = date("Y-m-d", strtotime($info['datepts']));
        $datesor = date("Y-m-d", strtotime($info['datesor']));
//        unset($info['datepts']);
//        unset($info['datesor']);
        unset($info['select-brand']);
        unset($info['select-model']);
        $info['datepts'] = $datepts;
        $info['datesor'] = $datesor;
        $info['brand'] = $brand->row['name'];
        $info['model'] = $model->row['name'];
//        exit(var_dump($info));
        $sql.= "INSERT INTO ".DB_PREFIX."automobiles SET ";
        foreach ($info as $key => $value) {
            if($key!='owner' && $key!='auto_id' && $key!='numb'){
                $sql.= $key." = '".$value."', ";
            }
        }
        $sql.="numb = '".$info['numb']."'";
        $this->db->query($sql);
        $auto_id = $this->db->getLastId();
        if(isset($info['auto_id'])){
            $this->db->query("UPDATE ".DB_PREFIX."auto_to_client SET status = 0 WHERE auto_id = ".(int)$info['auto_id']);
        }
        $this->db->query("INSERT INTO ".DB_PREFIX."auto_to_client SET client_id = ".(int)$info['owner'].", auto_id = ".(int)$auto_id.", status = 1 ");
        return $info;
    }
}

