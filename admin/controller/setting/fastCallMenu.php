<?php

class ControllerSettingFastCallMenu extends Controller {
    private $info = array(
        'this'      => array(
                'name'  => 'Конструктор меню быстрого доступа',
                'link'  => 'setting/fastCallMenu'
        ),
        'parent'    => array(
                'name'  => 'Конструкторы',
                'link'  => 'setting/constructors'
        ),
        'description'   => 'Редактирование пунктов меню быстрого доступа.'
    );
    
    public function index() {
        $this->load->model('tool/product');
        $this->load->model('tool/layout');
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
        
        $fcItems = $this->user->getLayout();
        $data['fcItems'] = $fcItems['fcmenu'];
        $data['items'] = $fcItems['leftcolumn'];
        $this->response->setOutput($this->load->view('setting/fastCallMenu', $data));
    }
    
    public function addItem() {
        $item = $this->request->post['item'];
        $this->load->model('tool/product');
        $this->model_tool_product->addItem($item, $this->user->getId());
    }
    
    public function dropItem() {
        $item = $this->request->post['item'];
        $this->load->model('tool/product');
        $this->model_tool_product->dropItem($item, $this->user->getId());
    }
}

