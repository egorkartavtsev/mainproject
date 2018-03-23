<?php

class ModelToolLayout extends Model {
    public function getLayout($info) {
        $this->document->setTitle($info['this']['name']);
/***************************breadcrumbs******************************/
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => 'Главная',
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );
        
        $data['breadcrumbs'][] = array(
            'text' => $info['parent']['name'],
            'href' => $this->url->link($info['parent']['link'], 'token=' . $this->session->data['token'], true)
        );
        
        $data['breadcrumbs'][] = array(
            'text' => $info['this']['name'],
            'href' => $this->url->link($info['this']['link'], 'token=' . $this->session->data['token'], true)
        );
/*****************************page_struct*****************************/
        $data['heading_title'] = $info['this']['name'];
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['token'] = $this->session->data['token'];
        $data['description'] = $info['description'];
        return $data;
    }
}

