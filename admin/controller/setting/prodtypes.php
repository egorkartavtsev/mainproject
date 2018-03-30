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
        if(isset($this->request->get['synch']) && $this->request->get['synch']==='true'){
            $sup = $this->db->query("SELECT 
                                        p2b.product_id,
                                        lf.id AS fill_id
                                    FROM `".DB_PREFIX."product_to_brand` p2b 
                                    LEFT JOIN `".DB_PREFIX."brand` b 
                                            ON p2b.brand_id = b.id 
                                    LEFT JOIN `".DB_PREFIX."lib_fills` lf 
                                            ON lf.name = b.name");
            foreach ($sup->rows as $row) {
                $this->db->query("INSERT INTO ".DB_PREFIX."product_to_lib SET "
                        . "product_id = '".$row['product_id']."', "
                        . "fill_id = '".$row['fill_id']."' ");
            }
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
        }
        $data = $this->model_tool_layout->getLayout($this->info);
        $data['templates'] = $this->model_tool_product->getStructures();
        $this->response->setOutput($this->load->view('setting/prodtype', $data));
    }
    
    public function showOptions() {
        $this->load->model('tool/product');
        $results = $this->model_tool_product->getOptions($this->request->post['id']);
        $options = '';
        $divsOpt = '';
        $options.='<h4 type="optHeader"><span id="optHeader">Свойства товара: </span>';
        $divsOpt.= '<div class="clearfix"></div><div class="clearfix"><p></p></div><button id="newOpt" class="btn btn-success" onclick="addOption()"><i class="fa fa-plus-circle"></i> создать нововое свойство товара</button><div class="clearfix"></div><div class="clearfix"><p></p></div>';
        if(!empty($results)){
            foreach ($results as $result) {
                $options.='<span class="label label-success" type-name="'.$result['name'].'">'.$result['text'].($result['field_type']=='library'?'(библиотечное)':'').'</span> ';
                $divsOpt.= '<div class="alert alert-success">';
                if($result['field_type']!=='library' && $result['libraries']==='0'){
                        $divsOpt.='<div class="col-md-6">'
                                    . '<input type="text" id="textOption" class="form-control" value="'.$result['text'].'" placeholder="Введите название свойства(по-русски)">'
                                    . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                                    . '<span type="text" id="nameOption" class="label label-success">'.$result['name'].'</span>'
                                    . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                                    . '<select id="field_typeOption" class="form-control">'
                                        . '<option value="input" '.($result['field_type']=='input'?'selected':'').'>Текстовое поле</option>'
                                        . '<option value="select" '.($result['field_type']=='select'?'selected':'').'>Выбор вариантов</option>'
                                    . '</select>'
                                    . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                                    . '<input type="text" id="def_valOption" class="form-control" '.($result['field_type']=='input'?'':'disabled').' value="'.$result['def_val'].'" placeholder="Введите значения свойства по-умолчанию">'
                                    . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                                    . '<button id="saveOpt" class="btn btn-info"><i class="fa fa-floppy-o"></i> сохранить</button>&nbsp;'
                                    . '<button id="delOpt" class="btn btn-danger"><i class="fa fa-trash-o"></i> отвязать</button>'
                               . '</div>';
                        $divsOpt.='<div class="col-md-6">'
                                    . '<input type="text" id="valsOption" class="form-control" '.($result['field_type']=='select'?'':'disabled').' value="'.$result['vals'].'" placeholder="Введите значения свойства через ;">'
                                    . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                                    . '<input type="text" id="descriptionOption" class="form-control" value="'.$result['description'].'" placeholder="Введите описание свойства">'
                                    . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                                    . '<select id="requiredOption" class="form-control">'
                                        . '<option value="0" '.($result['required']=='0'?'selected':'').'>Необязательное поле</option>'
                                        . '<option value="1" '.($result['required']=='1'?'selected':'').'>Обязательное поле</option>'
                                    . '</select>'
                                    . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                                    . '<select id="viewedOption" class="form-control">'
                                        . '<option value="1" '.($result['viewed']=='1'?'selected':'').'>Отображать на витрине</option>'
                                        . '<option value="0" '.($result['viewed']=='0'?'selected':'').'>Не отображать на витрине</option>'
                                    . '</select>'
                                    . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                                    . '<input type="text" id="sort_orderOption" class="form-control" value="'.$result['sort_order'].'" placeholder="Порядок сортировки">'
                                    . '<input type="hidden" id="oldOption" value="1">'
                                    . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                                . '</div>'
                                . '<div class="clearfix"></div><div class="clearfix"><p></p></div>';
                } else {
                    $divsOpt.= '<div><h4>'.$result['text'].'</h4><span class="label label-success" id="nameOption">'.$result['name'].'</span>'
                            . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                            . '<select id="viewedOption" class="form-control">'
                                . '<option value="1" '.($result['viewed']=='1'?'selected':'').'>Отображать на витрине</option>'
                                . '<option value="0" '.($result['viewed']=='0'?'selected':'').'>Не отображать на витрине</option>'
                            . '</select>'
                            . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                            . '<input type="text" id="sort_orderOption" class="form-control" value="'.$result['sort_order'].'" placeholder="Порядок сортировки">'
                            . '<input type="hidden" id="oldOption" value="1">'
                            . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                            . '<button id="saveOpt" class="btn btn-info"><i class="fa fa-floppy-o"></i> сохранить</button>&nbsp;'
                            . '<button id="delOpt" class="btn btn-danger"><i class="fa fa-trash-o"></i> отвязать</button>'
                            . '</div>';
                }
                    $divsOpt.= '</div>';
            }
        }
        $options.='</h4>';
        echo $options.$divsOpt;
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
                        . '</select>'
                        . '<div class="clearfix"></div><div class="clearfix"><p></p></div>'
                        . '<select id="librariesOption" class="form-control" disabled hidden="hidden">';
                        $librs = $this->model_tool_product->getLibrs();
                        foreach ($librs as $lib){
                            $result.='<option value="'.$lib['library_id'].'">'.$lib['text'].'</option>';
                        }
                $result.='</select><div class="clearfix"></div><div class="clearfix"><p></p></div>'
                        . '<input type="text" id="def_valOption" class="form-control" placeholder="Введите значение свойства по умолчанию">'
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
        //exit(var_dump($result));
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
}
