<?php

class ControllerProductProductAdd extends Controller{
    private $info = array(
        'this'      => array(
                'name'  => 'Создать продукт',
                'link'  => 'product/product_add'
        ),
        'parent'    => array(
                'name'  => 'Главная',
                'link'  => 'common/dashboard'
        ),
        'description'   => 'Внесение продукции в программу.'
    );
    public function index() {
        $this->load->model('tool/forms');
        $this->load->model('tool/layout');
        $this->load->model('tool/product');
        $data = $this->model_tool_layout->getLayout($this->info);
        $types = $this->model_tool_product->getStructures();
        $data['firstSelect'] = '<select class="form-control">';
        $data['formaction'] = $this->url->link($this->info['this']['link'], 'token='.$this->session->data['token']);
        foreach ($types as $type) {
            $data['firstSelect'].='<option value="'.$type['type_id'].'">'.$type['text'].'</option>';
        }
        if(!empty($this->request->post)){
            $this->model_tool_forms->saveProdList($this->request->post['info'], $this->request->files);
            $data['success'] = 'Товары успешно сохранены в базу';
        }
        $data['firstSelect'].='</select>';
        $this->response->setOutput($this->load->view('product/product_add', $data));
    }
    //add prodAddForm to new prods list
    public function addToList() {
        $this->load->model('tool/forms');
        $form = $this->model_tool_forms->generateAddForm($this->request->post['type'], $this->request->post['num']);
        echo $form;
    }
}

