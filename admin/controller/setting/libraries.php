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
        $data = $this->model_tool_layout->getLayout($this->info);
        if(!empty($this->request->post)){
            //exit(var_dump($this->request->post));
            $this->saveLibrary($this->request->post);
            $data = $this->model_tool_layout->getLayout($this->info);
            $data['success'] = 'Библиотека успешно создана. Теперь вы можете её наполнить. Библиотека доступна для редактирования в левом меню, а также Вы можете использовать её в конструкторе типов товаров, подключив к одному из свойств.';
        }
        $this->response->setOutput($this->load->view('setting/libraries', $data));
    }
    
    private function saveLibrary($info) {
        $this->load->model('tool/translate');
        $this->load->model('tool/product');
        $this->db->query("INSERT INTO ".DB_PREFIX."libraries SET "
                . "text = '".$info['libr_text']."', "
                . "name = '".$info['libr_name']."', "
                . "description = '".$info['libr_description']."' ");
        $sup = $this->db->query("SELECT MAX(`library_id`) AS id FROM ".DB_PREFIX."libraries");
        $library = $sup->row['id'];
        $last_key = count($info['field']) - 1;
        $parent = 0;
        foreach ($info['field'] as $key => $field) {
            $name = $this->model_tool_translate->translate($field['text']);
            $this->db->query("INSERT INTO ".DB_PREFIX."lib_struct SET "
                    . "name = '".$name."', "
                    . "text = '".$field['text']."', "
                    . "library_id = '".$library."', "
                    . "parent_id = '".$parent."', "
                    . "isparent = '".($key == $last_key?0:1)."' ");
            $sup = $this->db->query("SELECT MAX(`item_id`) AS id FROM ".DB_PREFIX."lib_struct");
            $parent = $sup->row['id'];
            if(!$this->model_tool_product->hasColumn($name)){
                $this->db->query("ALTER TABLE ".DB_PREFIX."product ADD `".$name."` VARCHAR(512) NOT NULL ");
            }
        }
    }
}

