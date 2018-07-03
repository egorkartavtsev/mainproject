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
            $this->db->query("UPDATE ".DB_PREFIX."product_to_avito SET message = 1 WHERE dateEnd<NOW()");
        }
    }
    
}

