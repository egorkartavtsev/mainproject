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
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
//        if(isset($this->request->get['synch']) && $this->request->get['synch']==='true'){
//            $sup = $this->db->query("SELECT 
//                                        p2b.product_id,
//                                        lf.id AS fill_id
//                                    FROM `".DB_PREFIX."product_to_brand` p2b 
//                                    LEFT JOIN `".DB_PREFIX."brand` b 
//                                            ON p2b.brand_id = b.id 
//                                    LEFT JOIN `".DB_PREFIX."lib_fills` lf 
//                                            ON lf.name = b.name");
//            foreach ($sup->rows as $row) {
//                $this->db->query("INSERT INTO ".DB_PREFIX."product_to_lib SET "
//                        . "product_id = '".$row['product_id']."', "
//                        . "fill_id = '".$row['fill_id']."' ");
//            }
//            $sup = $this->db->query("SELECT 
//                                        p2c.product_id,
//                                        lf.id AS fill_id
//                                    FROM `".DB_PREFIX."product_to_category` p2c 
//                                    LEFT JOIN `".DB_PREFIX."category_description` cd 
//                                            ON p2c.category_id = cd.category_id 
//                                    LEFT JOIN `".DB_PREFIX."lib_fills` lf 
//                                            ON lf.name = cd.name");
//            foreach ($sup->rows as $row) {
//                $this->db->query("INSERT INTO ".DB_PREFIX."product_to_lib SET "
//                        . "product_id = '".$row['product_id']."', "
//                        . "fill_id = '".$row['fill_id']."' ");
//            }
//        }
        $data['templates'] = $this->model_tool_product->getStructures();
        $data['ckeditor'] = $this->config->get('config_editor_default');
        $data['modal'] = $this->load->view('modals/clear', array(
            'target' => 'options',
            'key' => 'sets_option',
            'header' => 'Настройки свойства'
        ));
//        exit(var_dump($data['ckeditor']));
        $this->response->setOutput($this->load->view('setting/prodtype', $data));
    }
    
    public function showOptions() {
        $this->load->model('tool/product');
        $results = $this->model_tool_product->getOptions($this->request->post['id']);
        $info = $this->model_tool_product->getStructInfo($this->request->post['id']);
        
        $options = '<div>';
        $divInfo = '';
        $divsOpt = '';
        $divExc = '<div role="tabpanel" class="tab-pane" id="excel">'
                . '<h3>Шаблон выгрузки и загрузки товаров типа "'.$info['text'].'"</h3>'
                . '<table class="table table-hover">'
                . '<thead>'
                    . '<tr>'
                        . '<th>Свойство</th>'
                        . '<th>Столбец</th>'
                    . '</tr>'
                . '</thead>'
                . '<tbody>';
        $options.= '<ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Свойства</a></li>
                        <li role="presentation"><a href="#descript" aria-controls="descript" role="tab" data-toggle="tab">Общие</a></li>
                        <li role="presentation"><a href="#excel" aria-controls="excel" role="tab" data-toggle="tab">Excel</a></li>
                    </ul>'
                . '<div class="tab-content"><div role="tabpanel" class="tab-pane active" id="home"><h4 type="optHeader"><h4 id="optHeader">Свойства товара: </h4>';
        $divsOpt.= '<div class="clearfix"></div><div class="clearfix"><p></p></div><button id="newOpt" class="btn btn-success" onclick="addOption()"><i class="fa fa-plus-circle"></i> создать нововое свойство товара</button><div class="clearfix"></div><div class="clearfix"><p></p></div>';
        if(!empty($results['options'])){
            foreach ($results['options'] as $result) {
                $options.='<button class="btn btn-success btn-lg col-md-3" btn_type="sets_opt" data-toggle="modal" data-target="#optionsModal" option="'.$result['lib_id'].'">'
                                .'<p>'.$result['text']
                            .'</p></button> ';
                $divExc.= '<tr>'
                        . '<td>'.$result['text'].'</td>'
                        . '<td><input class="form-control" name="excel" target="'.$result['lib_id'].'" value="'.$result['excel'].'"/></td>'
                        . '</tr>';
            }
        }
        $divsOpt.= '</div>';
        $divInfo.= '<div role="tabpanel" class="tab-pane" id="descript">'
                    . '<div class="col-lg-12 form-group">'
                        . '<div class="col-lg-8"><label for="typeName">Маска наименования продуктов данного типа:</label>'
                        . '<input class="form-control" id="typeName" type="text" type_id="'.$this->request->post['id'].'" value="'.$info['text'].'"/></div>'
                        . '<label>&nbsp;</label><br><button class="btn btn-success" disabled btn_type="typeNameSave"><i class="fa fa-floppy-o"></i></button>'
                    . '</div>'
                    . '<div class="col-lg-12 form-group">'
                        . '<div class="col-lg-8"><label for="templName">Маска наименования продуктов данного типа:</label>'
                        . '<input class="form-control" id="templName" type="text" type_id="'.$this->request->post['id'].'" value="'.$info['temp'].'"/></div>'
                        . '<label>&nbsp;</label><br><button class="btn btn-success" disabled btn_type="tempNameSave"><i class="fa fa-floppy-o"></i></button>'
                    . '</div>'
                    . '<div class="col-lg-12 form-group">'
                        . '<div class="col-lg-8"><label for="showNav">Отображение в верхнем меню витрины:</label>'
                        . '<select class="form-control" id="showNav" type="text" type_id="'.$this->request->post['id'].'">'
                            . '<option value="0" '.($info['top_nav']==='0'?'selected':'').'>Не отображать</option>'
                            . '<option value="1" '.($info['top_nav']==='1'?'selected':'').'>Отображать</option>'
                        . '</select>'
                        . '</div>'
                        . '<label>&nbsp;</label><br><button class="btn btn-success" disabled btn_type="showNavSave"><i class="fa fa-floppy-o"></i></button>'
                    . '</div>';
        $divInfo.= '</div>';
        
        $divExc.= '<tr><td colspan="2" class="text-center"><button btn_type="saveExcelTempl" class="btn btn-success">сохранить шаблон</button></td></tr></tbody></table></div>';
        $options.='</h4>';
        echo $options.$divsOpt.$divInfo.$divExc;
//        echo $options.$divInfo.$divExc;
    }
    
    public function addOption() {
        $this->load->model('tool/product');
        $result = '<div class="clearfix"></div><div class="clearfix"><p></p></div><div class="alert alert-warning">';
            $result.='<div class="col-md-6">'
                        . '<input type="text" id="textOption" class="form-control" placeholder="Введите название свойства(по-русски)">'
                        . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                        . '<span type="text" id="nameOption" class="label label-success"></span>'
                        . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                        . '<select id="field_typeOption" class="form-control">'
                            . '<option value="input">Текстовое поле</option>'
                            . '<option value="select">Выбор вариантов</option>'
                            . '<option value="library">Привязать библиотеку</option>'
                            . '<option value="compability">Библиотечная совместимость</option>'
                        . '</select>'
                        . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                        . '<select id="librariesOption" class="form-control" disabled>';
                        $librs = $this->model_tool_product->getLibrs();
                        foreach ($librs as $lib){
                            $result.='<option value="'.$lib['library_id'].'">'.$lib['text'].'</option>';
                        }
                $result.='</select><div class="clearfix"></div><div class="clearfix"><p></p></div>'
                        . '<input type="text" id="def_valOption" class="form-control" placeholder="Введите значение свойства по умолчанию">'
                        . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                        . '<select id="unique_fieldOption" class="form-control">'
                            . '<option value="0">Неуникальное поле</option>'
                            . '<option value="1">Уникальное поле</option>'
                        . '</select>'
                        . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                        . '<button id="saveOpt" class="btn btn-info"><i class="fa fa-floppy-o"></i> сохранить</button>&nbsp;'
                        . '<button id="delNewOpt" class="btn btn-danger"><i class="fa fa-trash-o"></i> удалить</button>'
                   . '</div>';
            $result.='<div class="col-md-6">'
                        . '<input type="text" id="valsOption" class="form-control" disabled placeholder="Введите значения свойства через ;">'
                        . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                        . '<input type="text" id="descriptionOption" class="form-control" placeholder="Введите описание свойства ">'
                        . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                        . '<select id="requiredOption" class="form-control">'
                            . '<option value="0">Необязательное поле</option>'
                            . '<option value="1">Обязательное обязательное</option>'
                        . '</select>'
                        . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                        . '<select id="viewedOption" class="form-control">'
                            . '<option value="1">Отображать на витрине</option>'
                            . '<option value="0">Не отображать на витрине</option>'
                        . '</select>'
                        . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                        . '<select id="searchingOption" class="form-control">'
                            . '<option value="1">Участвует в поиске</option>'
                            . '<option value="0">Не участвует в поиске</option>'
                        . '</select>'
                        . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                        . '<input type="text" id="sort_orderOption" class="form-control" placeholder="Порядок сортировки">'
                        . '<input type="hidden" id="oldOption" value="0">'
                        . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                   . '</div>'
                    . '<div class="clearfix"></div><div class="clearfix"><p></p></div>';
        $result.= '</div>';
        echo $result;
    }
    
    public function saveOption() {
        $rows = explode(",", $this->request->post['data']);
        $res = array();
        foreach ($rows as $field) {
            $sup = explode(": ", $field);
            if(trim($sup[0])!==''){
                $index = str_replace('Option', '', $sup[0]);
                $res[trim($index)] = isset($sup[1])?trim($sup[1]):'не существует';
            }
        }
        $res['type_id'] = $this->request->post['type_id'];
        $this->load->model('tool/product');
        $result = $this->model_tool_product->saveOption($res);
        if($res['field_type']==='libraries'){
            //exit('не работает бэк');
            $divsOpt.= '<div class="alert alert-success">';
            $divsOpt.= '<h4>Библиотека: '.$result[0]['library_name'].'</h4>';
            foreach ($result as $item) {
                $divsOpt.= '<span class="label label-success">'.$item['text'].'</span>';
            }
            $divsOpt.= '</div><div class="clearfix"></div><div class="clearfix"><p></p></div>';
            exit($divsOpt);
        }
    }
    
    public function translateOption() {
        $this->load->model('tool/translate');
        echo $this->model_tool_translate->translate($this->request->post['text']);
    }
    
    public function saveNewType() {
        $name = $this->request->post['data'];
        $this->load->model('tool/product');
        $result = $this->model_tool_product->saveType($name);
        echo $result;
    }
    
    public function deleteOption() {
        $name = $this->request->post['name'];
        $type_id = $this->request->post['type_id'];
        $this->load->model('tool/product');
        $this->model_tool_product->deleteOption($name, $type_id);
    }
    
    public function saveTempName() {
        $this->load->model('tool/product');
        $this->model_tool_product->saveTempName($this->request->post['tempName'], $this->request->post['type_id']);
        echo 'ok';
    }
    
    public function saveTypeName() {
        $this->load->model('tool/product');
        $this->model_tool_product->saveTypeName($this->request->post['typeName'], $this->request->post['type_id']);
        echo 'ok';
    }
    
    public function saveShowNav() {
        $this->load->model('tool/product');
        $this->model_tool_product->saveShowNav($this->request->post['show'], $this->request->post['type_id']);
        echo 'ok';
    }
    
    public function saveDT() {
        $this->load->model('tool/product');
        
        echo $this->model_tool_product->saveDT($this->request->post['temp'], $this->request->post['temp_id']);
    }
    
    public function getSetsOpt() {
        $opt = $this->request->post['opt'];
        $this->load->model('tool/product');
        $result = $this->model_tool_product->getOptionInfo($opt);
        $output = '';
        switch ($result['field_type']) {
            case 'input':
                $output = $this->load->view('form/sets_input', $result);
            break;
            case 'select':
                $output = $this->load->view('form/sets_select', $result);
            break;
            case 'library':
                $output = $this->load->view('form/sets_libr', $result);
            break;
            case 'compability':
                $output = $this->load->view('form/sets_cpb', $result);
            break;
        }
        echo $output;
    }
    
    public function saveExcelTempl(){
        $this->load->model('tool/product');
        $templ = array();
        $sup = explode(',', $this->request->post['templ']);
        foreach ($sup as $value) {
            $subsup = explode(":", $value);
            if(isset($subsup[1])){
                $templ[$subsup[0]] = $subsup[1];
            }
        }
        $this->model_tool_product->saveExcelTemplate($templ);
        echo 'COXPAHEHO';
    }
}
