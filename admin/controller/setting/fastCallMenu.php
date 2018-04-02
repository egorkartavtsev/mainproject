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
        $data = $this->model_tool_layout->getLayout($this->info);
        $items = $this->model_tool_product->getItems();
        $ignore = array(
			'common/dashboard',
			'common/excelTools',
			'common/column_left',
                        'common/filemanager',
                        'donor/edit',
                        'donor/show',
                        'donor/show',
                        'product/product_edit',
                        'setting/fastCallMenu',
                        'sale/order',
                        'sale/recurring',
                        'sale/return',
                        'sale/voucher',
                        'sale/voucher_theme',
                        'tiresdisc/edit',
			'common/startup',
			'common/login',
			'common/logout',
			'common/forgotten',
			'common/reset',			
			'common/footer',
			'common/header',
			'error/not_found',
			'error/permission'
		);
        $data['fcItems'] = $this->model_tool_product->getFCMenuItems($this->user->getId());
        foreach ($items as $item){
            if($this->user->hasPermission('access', $item['controller']) && !in_array($item['controller'], array_column($data['fcItems'], 'name')) && !in_array($item['controller'], $ignore)){
                $data['items'][] = $item;
            }
        }
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

