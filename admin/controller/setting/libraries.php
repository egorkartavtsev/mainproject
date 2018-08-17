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
        $this->load->model('tool/product');
        $this->load->model('tool/layout');
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
        $librs = $this->model_tool_product->getLibrs();
        foreach ($librs as $lib) {
            $data['libr_list'][] = array(
                'text' => $lib['text'],
                'href' => $this->url->link('setting/libraries/edit', 'token='.$this->session->data['token'].'&lib='.$lib['library_id'])
            );
        }
        if(!empty($this->request->post)){
            //exit(var_dump($this->request->post));
            $this->model_tool_product->saveLibrary($this->request->post);
            $data['success'] = 'Библиотека успешно создана. Теперь вы можете её наполнить. Библиотека доступна для редактирования в левом меню, а также Вы можете использовать её в конструкторе типов товаров, подключив к одному из свойств.';
        }
        $this->response->setOutput($this->load->view('setting/libraries', $data));
    }
    
    public function edit() {
        $this->load->model('setting/prodtype');
        $this->load->model('tool/layout');
        $this->load->model('tool/product');
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
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
            $result.='<tr id="fill'.$fill['id'].'" fill_id="'.$fill['id'].'" item_level="'.$level.'"><td td_type="fillName">'.$fill['name'].'</td><td><button class="btn btn-info" btn_type="changeFill" fill="'.$fill['id'].'" type="button" data-toggle="modal" data-target="#settingsLevel" btn_type="levelSettings"><i class="fa fa-pencil" ></i></button><button class="btn btn-danger" btn_type="deleteFill"><i class="fa fa-trash-o"></i></button></td></td></tr>';
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
        $check = $this->db->query("SELECT name FROM ".DB_PREFIX."lib_fills WHERE item_id = '".$fill['itemId']."' AND name = '".$fill['name']."' AND parent_id = '".$fill['parent']."'");
        $check_num = $check->num_rows;
        if ($check_num <= 0){
            $this->load->model('tool/product');
            $res = $this->model_tool_product->saveNewFillName($fill); 
        } else {
            $res = 'exists' ;
        }
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
    
    public function levelSISave() {
        $this->load->model('tool/product');
        $this->model_tool_product->levelSISave($this->request->post['SI'], $this->request->post['item_id']);
        echo 'ok';
    }
    
    public function getLevelSets() {
        $this->load->model('tool/product');
        $sets = $this->model_tool_product->getLevelSets($this->request->post['item_id']);
        $result = '';
//        exit(var_dump($sets));
        $result.= '<div class="col-lg-12 form-group">
                    <div class="col-lg-8"><label for="">Отображение фотографий на уровне:</label>
                    <select class="form-control" id="showImg" library_id="">
                        <option value="0" >Не отображать</option>
                        <option value="1" '.($sets['showImg']==='0'?'':'selected').'>Отображать</option>
                    </select>
                    </div>
                    <label>&nbsp;</label><br><button class="btn btn-success" btn_type="levelSISave" item="'.$this->request->post['item_id'].'"><i class="fa fa-floppy-o"></i></button>
                  </div>';
        echo $result;
    }
    
    public function getFillSets() {
        $this->load->model('tool/product');
        $this->load->model('tool/image');
        $sets = $this->model_tool_product->getFillSets($this->request->post['fill_id']);
        $oldname = $this->request->post['oldname'];
        $result = '';
        
        $result.= ' <div class="col-lg-12 form-group">
                      <label for="name">Название:</label>
                      <input class="form-control" id="name" type="text" value="'.$sets['name'].'"/>
                    </div>';
        $result.= ' <div class="col-lg-12 form-group">
                      <label for="avitoId">Категория Авито:</label>
                      <input class="form-control" id="avitoId" type="text" value="'.$sets['avitoId'].'"/>
                    </div>';
        $result.= ' <div class="col-lg-12 form-group">
                      <label for="prompt">Напоминание:</label>
                      <input class="form-control" id="prompt" type="text" value="'.$sets['prompt'].'"/>
                    </div>';
        $result.= ' <div class="col-lg-12 form-group">
                      <label for="translate">Синонимы(через ;):</label>
                      <textarea class="form-control" id="translate">'.$sets['translate'].'</textarea>
                    </div>';
        $result.= ' <div class="col-lg-12 form-group">
                      <label for="image">Фотография:</label><br>
                      <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail" data-toggle="popover">
                        <img src="'.$this->model_tool_image->resize($sets['image'], 100, 100).'" />
                      </a>
                      <input class="form-control" data-toggle="input-image" id="image" type="hidden" value="'.$sets['image'].'"/>
                    </div>';
        $result.= ' <div class="col-lg-12 form-group">
                        <button class="btn btn-success" fill="'.$sets['id'].'" oldname = "'.$oldname.'" btn_type="fillSetsSave"><i class="fa fa-floppy-o"></i>сохранить</button>
                    </div>';
        echo $result;
    }
    
    public function saveFillSets() {
        $fill = $this->request->post['fill'];
        $oldname = $this->request->post['oldname'];
        $request = $this->request->post['fields'];       
        $request = explode("; ", $request);
        foreach ($request as $req) {
            $sup = explode(":", $req);
            if(trim($sup[0])!=='' && trim($sup[1])!==''){
                $fields[trim($sup[0])] = trim($sup[1]);
            }
        }
        if ($oldname != $fields['name']) {
            $itemid = $this->db->query("SELECT item_id FROM ".DB_PREFIX."lib_fills WHERE id = '".$fill."' LIMIT 1")->row['item_id'];
            $parent = $this->db->query("SELECT parent_id FROM ".DB_PREFIX."lib_fills WHERE id = '".$fill."' LIMIT 1")->row['parent_id'];
            $check = $this->db->query("SELECT name FROM ".DB_PREFIX."lib_fills WHERE name = '".$fields['name']."' AND item_id = '".$itemid."' AND parent_id= '".$parent."'");
            $check_num = $check->num_rows;
            if ($check_num <= 0){
                $this->load->model('tool/product');
                $this->model_tool_product->saveFillSets($fields, $fill);
            } else {
                $res = 'exists';        
            }
        } else {
            $this->load->model('tool/product');
            $this->model_tool_product->saveFillSets($fields, $fill);
        }
        echo $res;
    }
    
    public function librSetSave() {
        $this->load->model('tool/product');
        $this->model_tool_product->librSetSave($this->request->post);
        echo "Готово";
    }
    public function Reminder() {
        //$fill = $this->request->post['fill_id'];
        $prompt = $this->db->query("SELECT prompt FROM ".DB_PREFIX."lib_fills WHERE id = '2'")->row['prompt'];
        echo "asdasd";
    }
}

