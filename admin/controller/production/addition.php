<?php

class ControllerProductionAddition extends Controller{
    
    public function index() {
        $this->load->model('tool/forms');
        $this->load->model('tool/product');
        $this->load->model('tool/layout');
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
        $types = $this->model_tool_product->getStructures();
        $data['firstSelect'] = '<select class="form-control">';
        $data['formaction'] = $this->url->link('production/addition', 'token='.$this->session->data['token']);
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

