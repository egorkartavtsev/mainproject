<?php

class ControllerSettingLibraries extends Controller {
    private $info = array(
        'this'      => array(
                'name'  => 'Конструктор библиотек',
                'link'  => 'setting/libraries'
        ),
        'parent'    => array(
                'name'  => 'Конструкторы',
                'link'  => 'setting/constructors'
        ),
        'description'   => 'Создание и редактирование структур библиотек данных. Влияет на форму фильтров, формы создания, редактирования и списания продукции. '
    );
    public function index() {
        $this->load->model('setting/prodtype');
        $this->load->model('tool/layout');
        $this->load->model('tool/product');
        $data = $this->model_tool_layout->getLayout($this->info);
        if(!empty($this->request->post)){
            //exit(var_dump($this->request->post));
            $this->model_tool_product->saveLibrary($this->request->post);
            $data = $this->model_tool_layout->getLayout($this->info);
            $data['success'] = 'Библиотека успешно создана. Теперь вы можете её наполнить. Библиотека доступна для редактирования в левом меню, а также Вы можете использовать её в конструкторе типов товаров, подключив к одному из свойств.';
        }
        $this->response->setOutput($this->load->view('setting/libraries', $data));
    }
    
    public function edit() {
        $this->load->model('setting/prodtype');
        $this->load->model('tool/layout');
        $this->load->model('tool/product');
        $data = $this->model_tool_layout->getLayout($this->info);
        $data['library'] = $this->model_tool_product->getLibrInfo($this->request->get['lib']);
        $data['library_id'] = $this->request->get['lib'];
        $this->response->setOutput($this->load->view('setting/librEdit', $data));
    }
    
    public function getChilds() {
        $parent = $this->request->post['parent'];
        $level = $this->request->post['level'];
        $this->load->model('tool/product');
        $fills = $this->model_tool_product->getChildFills($parent);
        $result = '';
        foreach ($fills as $fill) {
            $result.='<tr id="fill'.$fill['id'].'" fill_id="'.$fill['id'].'" item_level="'.$level.'"><td td_type="fillName">'.$fill['name'].'</td><td><button class="btn btn-info" btn_type="changeFill"><i class="fa fa-pencil" ></i></button><button class="btn btn-danger" btn_type="deleteFill"><i class="fa fa-trash-o"></i></button></td></td></tr>';
        }
        $result.= '<tr><td class="text-center" colspan="2"><button class="btn btn-success" item_level="'.$level.'" id="addItem'.$level.'" fill-parent="'.$parent.'"><i class="fa fa-plus-circle"></i> добавить элемент</button></td></tr>';
        echo $result;
    }
    
    public function saveChangeFillName() {
        $id = $this->request->post['id'];
        $name = $this->request->post['name'];
        $field = $this->request->post['field'];
        $this->load->model('tool/product');
        $res = $this->model_tool_product->saveChangeFillName($id, $name, $field);
        echo $res;
    }
    
    public function saveNewFillName() {
        $fill['itemId'] = $this->request->post['itemId'];
        $fill['name'] = $this->request->post['name'];
        $fill['libraryId'] = $this->request->post['libraryId'];
        $fill['parent'] = $this->request->post['parent'];
        
        $this->load->model('tool/product');
        $res = $this->model_tool_product->saveNewFillName($fill);
        echo $res;
    }
    
    public function deleteFill() {
        $id = $this->request->post['id'];
        $this->load->model('tool/product');
        $res = $this->model_tool_product->deleteFill($id);
        if($res){
            echo '1';
        } else {
            echo '0';
        }
    }
    
    public function savelibrName() {
        $this->load->model('tool/product');
        $this->model_tool_product->savelibrName($this->request->post['librName'], $this->request->post['library_id']);
    }
    
    public function saveShowNav() {
        $this->load->model('tool/product');
        $this->model_tool_product->saveLibrShowNav($this->request->post['show'], $this->request->post['library_id']);
        echo 'ok';
    }
    
}

