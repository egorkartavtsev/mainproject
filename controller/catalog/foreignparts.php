<?php

class ControllerCatalogForeignparts extends Controller{
    public function index() {
        $this->document->setTitle('Контрактные автозапчасти');
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
                'text' => 'Главная',
                'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
                'text' => 'Контрактные автозапчасти',
                'href' => $this->url->link('catalog/foreignparts')
        );

        $data['heading_title'] = 'Контрактные автозапчасти';
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        
        $this->response->setOutput($this->load->view('catalog/foreignparts', $data));

    }
}

