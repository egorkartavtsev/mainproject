<?php

class ModelToolLayout extends Model {
    public function getLayout($route) {
        $module = explode("/", $route);
        $sup = $this->db->query("SELECT "
                    . "m.name AS name,"
                    . "m.text AS text,"
                    . "m.description AS description,"
                    . "m1.name AS parent, "
                    . "m1.text AS parenttext "
                . "FROM ".DB_PREFIX."modules  m "
                . "LEFT JOIN ".DB_PREFIX."modules  m1 on m.parent_id = m1.id "
                . "WHERE m.name = '".$module[1]."' "
                    . "AND m.parent_id = m1.id ");
        $this->document->setTitle($sup->row['text']);
/***************************breadcrumbs******************************/
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => 'Главная',
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );
        
        $data['breadcrumbs'][] = array(
            'text' => $sup->row['parenttext'],
            'href' => FALSE
        );
        
        $data['breadcrumbs'][] = array(
            'text' => $sup->row['text'],
            'href' => $this->url->link($sup->row['parent'].'/'.$sup->row['name'], 'token=' . $this->session->data['token'], true)
        );
/*****************************page_struct*****************************/
        $data['heading_title'] = $sup->row['text'];
        $data['header'] = $this->load->controller('layout/header');
        $data['column_left'] = $this->load->controller('layout/columnleft');
        $data['footer'] = $this->load->controller('layout/footer');
        $data['token'] = $this->session->data['token'];
        $data['description'] = $sup->row['description'];
        return $data;
    }
    
    public function updateADS($route) {
        if($route=='common/dashboard'){
            $date = date('Y-m-d');
            $this->db->query("UPDATE ".DB_PREFIX."product_to_avito SET message = 1 WHERE dateEnd = '".$date."' ");
        }
    }
    
    public function getUserNotice($user){
        return $this->db->query("SELECT * FROM ".DB_PREFIX."notices WHERE (access_lvl >= ".(int)$user['minaccesslevel']." AND access_lvl <= ".(int)$user['userAL'].") OR access_lvl = 0");
    }
    
    public function getnoticeTotals() {
        $user = $this->user->getUserInfo();
        $sup = $this->getUserNotice($user);
        $result = array(
            'notices' => array(),
            'notified' => 0,
            'new' => 0
        );
        foreach ($sup->rows as $row) {
            $result['notices'][$row['name']] = array(
                'icon' => $row['icon'],
                'text' => $row['text'],
                'fastviewed' => $row['fastviewed'],
                'target_table' => $row['target_table'],
                'new'  => 0
            );
            $sql = "SELECT * FROM ".DB_PREFIX.$row['target_table']." WHERE viewed = 0 ";
            $sql1 = "UPDATE ".DB_PREFIX.$row['target_table']." SET notified = 1 WHERE viewed = 0 ";
            if(!(int)$row['overal']){
                $sql.= "AND target_user = ".(int)$user['user_id'];
                $sql1.= "AND target_user = ".(int)$user['user_id'];
            }
            $tmp = $this->db->query($sql);
            if($tmp->num_rows){
                $result['notices'][$row['name']]['new'] = $tmp->num_rows;
                $result['new'] = 1;
                $result['notified'] = in_array(0,array_column($tmp->rows,'notified'))?1:0;
                $this->db->query($sql1);
            }
        }
        return $result;
    }
    
    public function getorders() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."order  LEFT JOIN ".DB_PREFIX."order_status ON ".DB_PREFIX."order.order_status_id = ".DB_PREFIX."order_status.order_status_id  WHERE 1 ORDER BY viewed, date_added DESC LIMIT 15 OFFSET 0");
        $data['token'] = $this->session->data['token'];
        $data['orders'] = $sup->rows;     
        return $this->load->view('modals/tab_orders', $data);
    }
    
    public function getupdates() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."update_logs WHERE target_user = ".$this->user->getId()." ORDER BY viewed, date_added DESC LIMIT 15 OFFSET 0");
        $data['logs'] = $sup->rows;
        return $this->load->view('modals/tab_updates', $data);
    }
    
    public function getwarnings() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."warnings WHERE target_user = ".$this->user->getId()." ORDER BY viewed, date_added DESC LIMIT 15 OFFSET 0");
        $data['warns'] = $sup->rows;
        return $this->load->view('modals/tab_warns', $data);
    }
    
    public function createNewUpdateMessage($info) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."user WHERE status = 1 ");
        foreach ($sup->rows as $usr) {
            $this->db->query("INSERT INTO ".DB_PREFIX."update_logs SET "
                    . "target_user = " . (int)$usr['user_id'] . ", "
                    . "date_added = NOW(), "
                    . "viewed = 0, "
                    . "text = '" . $this->db->escape($info['update_info']) . "', "
                    . "autor = '" . $this->db->escape($info['autor']) . "' ");
        }
    }
    
    public function createNewWarnMessage($info, $users) {
        $tmp1sql = "(";
        $tmp2sql = "";
        $i = 0;
        foreach ($info as $key => $value) {
            if($key!='files'){
                $tmp1sql.= $key.",";
            }
        }
        $tmp1sql.= "target_user,viewed,notified,date_added)";
        foreach ($users as $usr) {
            $tmp2sql.= "(";
            foreach ($info as $key => $value) {
                if($key!='files'){
                    $tmp2sql.= "'".$this->db->escape($value)."',";
                }
            }
            $tmp2sql.= "'".$usr."',0,0,NOW())";
            ++$i;
            if($i< count($users)){
                $tmp2sql.= ", ";
            }
        }
        $query = "INSERT INTO ".DB_PREFIX."warnings ".$tmp1sql." VALUES ".$tmp2sql;
//        exit($query);
        $this->db->query($query);
    }
    
    public function checkfastviewed($target) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."notices WHERE name = '".$target."'");
        if($sup->num_rows){
            $sql = "UPDATE ".DB_PREFIX.$sup->row['target_table']." SET viewed = 1 WHERE ";
            if(!(int)$sup->row['overal']){
                $sql.= "target_user = ".(int)$this->user->getId();
            } else {
                $sql.= "1";                
            }
            $this->db->query($sql);
        }
    }
    
    public function getUserList() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."user WHERE status = 1");
        return $sup->rows;
    }
    
}

