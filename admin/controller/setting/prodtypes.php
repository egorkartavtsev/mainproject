<?php

class ControllerSettingProdTypes extends Controller {
    
    private $info = array(
        'this'      => array(
                'name'  => 'Конструктор типов продуктов',
                'link'  => 'setting/prodtypes'
        ),
        'parent'    => array(
                'name'  => 'Конструкторы',
                'link'  => 'setting/constructors'
        ),
        'description'   => 'Создание и редактирование шаблонов типов продуктов. Влияет на форму фильтров, формы создания, редактирования и списания продукции.'
    );
    
    public function index() {
        $this->load->model('setting/prodtype');
        $this->load->model('tool/product');
        $this->load->model('tool/layout');
        $data = $this->model_tool_layout->getLayout($this->info);
        $data['templates'] = $this->model_tool_product->getStructures();
        $this->response->setOutput($this->load->view('setting/prodtype', $data));
    }
    
    public function showOptions() {
        $this->load->model('tool/product');
        $result = $this->model_tool_product->getOptions($this->request->post['id']);
        $options = '';
        $options.='<h4>Свойства товара:</h4>';
        if(!empty($result)){
            
        }
        $options.= '<div class="clearfix"></div><div class="clearfix"><p></p></div><button id="newOpt" class="btn btn-success" onclick="addOption()"><i class="fa fa-plus-circle"></i> создать нововое свойство товара</button>';
        echo $options;
    }
    
    public function addOption() {
        $result = '<div class="alert alert-success">';
            $result.='<div class="col-md-6">'
                        . '<input type="text" id="textOption" class="form-control" placeholder="Введите название свойства(по-русски)">'
                        . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                        . '<input type="text" id="nameOption" class="form-control" disabled>'
                        . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                        . '<select id="typeOption" class="form-control">'
                            . '<option value="input">Текстовое поле</option>'
                            . '<option value="select">Выбор вариантов</option>'
                        . '</select>'
                        . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                        . '<input type="text" id="defOption" class="form-control" placeholder="Введите значение свойства по умолчанию">'
                        . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                   . '</div>';
            $result.='<div class="col-md-6">'
                        . '<input type="text" id="valsOption" class="form-control" placeholder="Введите значения свойства через ;">'
                        . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                        . '<input type="text" id="descOption" class="form-control" placeholder="Введите описание свойства ">'
                        . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                        . '<select id="reqOption" class="form-control">'
                            . '<option value="0">Необязательное поле</option>'
                            . '<option value="1">Обязательное обязательное</option>'
                        . '</select>'
                        . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                        . '<button id="saveOpt" class="btn btn-info" onclick="saveOption()"><i class="fa fa-floppy-o"></i> сохранить</button>&nbsp;'
                        . '<button id="delOpt" class="btn btn-danger"><i class="fa fa-trash-o"></i> удалить</button>'
                   . '</div>';
        $result.= '</div>';
        echo $result;
    }
    
    public function createOption() {
        
    }
    
    public function translateOption() {
        $this->load->model('tool/translate');
        echo $this->model_tool_translate->translate($this->request->post['text']);
    }
}
