<?php
class ControllerCommonEditmodel extends Controller {
private $error = array();
    
    public function index() {
        $data = $this->getLayout();
        $this->load->model('common/edit_model');
        if(isset($this->request->post['newname'])){
            $oldname = $this->request->post['oldname'];
            $sup = $this->db->query("SELECT name, id FROM ".DB_PREFIX."brand WHERE parent_id = '".$this->request->post['pointer']."'");
            if(!empty($sup->rows)){
                foreach ($sup->rows as $mr) {
                    $newmr = str_replace($oldname, $this->request->post['newname'], $mr['name']);
//                    exit($newmr);
                    $this->db->query("UPDATE ".DB_PREFIX."product SET length = '".$newmr."', model = '".$this->request->post['newname']."' WHERE length = '".$mr['name']."'");
                    $this->db->query("UPDATE ".DB_PREFIX."brand "
                    . "SET name = '".$newmr."' "
                    . "WHERE id = '".$mr['id']."'");
                }
            } else {
                $this->db->query("UPDATE ".DB_PREFIX."product SET length = '".$this->request->post['newname']."' WHERE length = '".$sup->row['name']."'");
            }
            /*---------------------------------------------*/
            $repn = $this->db->query("SELECT name, product_id, description, tag FROM ".DB_PREFIX."product_description WHERE LOCATE('".$oldname."', name) ");
            foreach ($repn->rows as $value) {
                $newname = str_replace($oldname, $this->request->post['newname'], $value['name']);
                $newname = str_replace(">", "-", $newname);
                $newname = str_replace("'", "\'", $newname);
                $newdesc = str_replace($oldname, $this->request->post['newname'], $value['description']);
                $newdesc = str_replace("'", "\'", $newdesc);
                $newtag = str_replace($oldname, $this->request->post['newname'], $value['tag']);
                $this->db->query("UPDATE ".DB_PREFIX."product_description SET "
                                    . "name = '".$newname."', "
                                 . "meta_h1 = '".$newname."', "
                              . "meta_title = '".$newname."', "
                                     . "tag = '".$newtag."', "
                            . "meta_keyword = '".$newtag."', "
                             . "description = '".$newdesc."', "
                        . "meta_description = '".$newdesc."' "
                    . "WHERE product_id = '".$value['product_id']."'");
            }
            $this->db->query("UPDATE ".DB_PREFIX."brand "
                    . "SET name = '".$this->request->post['newname']."' "
                    . "WHERE id = '".$this->request->post['pointer']."'");
            //exit($this->request->post['pointer']);
        }
        $brands = $this->model_common_edit_model->getBrands(0);
        foreach ($brands as $brand) {
            $data['brands'][$brand['id']] = array(
                'id' => $brand['id'],
                'name' => $brand['name'],
                'trans' => $brand['transcript']
            );
        }
        
        $data['token_em'] = $this->session->data['token'];
        $this->response->setOutput($this->load->view('common/edit_model_list', $data));
    }
    
    public function getModel($id = 0) {
        $this->load->model('common/edit_model');
        if($id == 0){
            $token = $this->request->post['token'];
            $parentID = $this->request->post['brand'];
        }
        else{
            $parentID = $id;
            $token = $this->session->data['token'];
        }
        //exit($parentID);
        $mod_arr = $this->model_common_edit_model->getBrands($parentID);
        
        $view = '<table class="table table-striped">';
        foreach ($mod_arr as $model) {
            $view .= '<tr>';
                $view .= '<td id="name'.$model['id'].'" style="cursor: pointer;" class="col-lg-4 mod" onclick = "';
                    $view .= 'ajax({';
                    $view .= "url:'index.php?route=common/edit_model/getModelRow&token=".$token."',";
                    $view .= "statbox:'status',
                              method:'POST',
                              data:
                              {
                                brand: ".$model['id'].",
                                token: '".$token."'
                              },
                              success:function(data){document.getElementById('model_row_list').innerHTML=data;}";
                    $view .= '})';
                $view .= '">'.$model['name'].'</td>';
                $view .= '<td class="col-lg-5"><input class="form-control" oninput="checkInp(\''.$model['id'].'\');" id="trans'.$model['id'].'" value="'.$model['transcript'].'" placeholder="Введите транскрипцию..." maxlength="64"></td>';
                $view .= '<td><button class="btn btn-success" onclick="saveTrans(\''.$model['id'].'\');" id="save'.$model['id'].'" disabled><i class="fa fa-floppy-o"></i></button></td>';
                $view .= '<td id="delete'.$model['id'].'" class="col-lg-1">'
                            . '<button type="button" class="btn btn-danger" onclick="del('.$model['id'].', '.$parentID.');">'
                                . '<i class="fa fa-trash-o"></i>'
                            . '</button>'
                        . '</td>';
                $view .= '<td id="edit'.$model['id'].'" class="col-lg-1">'
                            . '<a href="index.php?route=common/edit_model/editForm&mod='.$model['id'].'&modname='.$model['name'].'&token='.$token.'" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Редактировать">'
                                . '<i class="fa fa-pencil"></i>'
                            . '</a>'
                        . '</td>';
            $view .= '</tr>';
        }
        $view .= '</table><hr/><input type="hidden" id="mod_par" value="'.$parentID.'" />'
                . '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModel">Добавить модель</button>';
        echo($view);
    }
    
    public function getModelRow($id = 0) {
        $this->load->model('common/edit_model');
        if($id == 0){
            $token = $this->request->post['token'];
            $parentID = $this->request->post['brand'];
        }
        else{
            $parentID = $id;
            $token = $this->session->data['token'];
        }
        //exit($parentID);
        $mod_arr = $this->model_common_edit_model->getBrands($parentID);
        
        $view = '<table class="table table-striped">';
        foreach ($mod_arr as $modelR) {
            $view .= '<tr>';
                $view .= '<td id="name'.$modelR['id'].'" class="col-lg-10"">'.$modelR['name'].'</td>';
                $view .= '<td id="delete" class="col-lg-1">'
                            . '<button type="button" class="btn btn-danger" onclick="delMR('.$modelR['id'].', '.$parentID.');">'
                                . '<i class="fa fa-trash-o"></i>'
                            . '</button>'
                        . '</td>';
                $view .= '<td id="edit" class="col-lg-1">'
                            . '<a href="index.php?route=common/edit_model/editForm&mod='.$modelR['id'].'&modname='.$modelR['name'].'&token='.$token.'" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Редактировать">'
                                . '<i class="fa fa-pencil"></i>'
                            . '</a>'
                        . '</td>';
            $view .= '</tr>';
        }
        $view .= '</table><hr/><input type="hidden" id="mod_row_par" value="'.$parentID.'" />'
                . '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModelRow">Добавить модельный ряд</button>';
        echo($view);
    }
    
    public function editForm() {
        $data = $this->getLayout();
        $data['token_em'] = $this->session->data['token'];
        $data['modID'] = $this->request->get['mod'];
        $data['modname'] = $this->request->get['modname'];
        $data['oldname'] = $this->request->get['modname'];
        
        $this->response->setOutput($this->load->view('common/edit_model_form', $data));
        
    }


    public function deleteModel() {
        
        $this->load->model('common/edit_model');
        $token = $this->request->post['token'];
        $parentID = $this->request->post['parent'];
        $model = $this->request->post['brand'];
        
        $this->model_common_edit_model->delete($model);
        $this->getModel($parentID);
        
    }
    
    public function addMR() {
        $this->load->model('common/edit_model');
        $token = $this->request->post['token'];
        $parentID = $this->request->post['parent'];
        $mR = $this->request->post['brand'];
        
        $this->model_common_edit_model->add($mR, $parentID);
        $this->getModelRow($parentID);
    }
    
    public function addM() {
        $this->load->model('common/edit_model');
        $token = $this->request->post['token'];
        $parentID = $this->request->post['parent'];
        $m = $this->request->post['brand'];
        
        $this->model_common_edit_model->add($m, $parentID);
        $this->getModel($parentID);
    }
    
        
    public function getLayout() {

        $this->load->language('common/edit_model');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('common/edit_model', 'token=' . $this->session->data['token'], true)
        );
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['token_em'] = $this->session->data['token'];
        return $data;

    } 
    
    public function saveTrans() {
        $data = $this->request->post;
        $this->load->model('common/edit_model');
        $result = $this->model_common_edit_model->saveTrans($data);
        echo $result;
    }
}