<?php

class ControllerCommonAlternative extends Controller{
    public function index() {
        $this->document->setTitle('Работа с синонимами');
        
        $data['heading_title'] = 'Работа с синонимами';

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
                'text' => 'Главная',
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
                'text' => 'Работа с синонимами',
                'href' => $this->url->link('common/alternative', 'token=' . $this->session->data['token'], true)
        );
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['token'] = $this->session->data['token'];
        $this->load->model("common/alternative");
        $data['categories'] = $this->model_common_alternative->getTotalCategories();
        
        $this->response->setOutput($this->load->view('common/alternative', $data));
    }
    
    public function getSupCat() {
        $parent = $this->request->post['parent'];
        $this->load->model('common/alternative');
        $sup = $this->model_common_alternative->getSCats($parent);
        $result='';
        $max = count($sup)/2;
        $id = 0;
        /*-------------------------------------------------------*/
            $result = '<div class="col-sm-12">'
                    . '<table class="table table-hover table-striped">'
                    . '<thead>'
                        . '<tr>'
                            . '<td>Подкатегория</td>'
                            . '<td></td>'
                            . '<td>Синонимы</td>'
                        . '</tr>'
                    . '</thead>';
            foreach ($sup as $sc) {
                $result.='<tr>';
                    $result.='<td class="col-sm-3">'.$sc['name'].'</td>';
                    $result.='<td class="col-sm-1"><button class="btn btn-success" disabled id="save'.$sc['category_id'].'" onclick="saveAlters(\''.$sc['category_id'].'\')"><i class="fa fa-floppy-o"></i></button></td>';
                    $result.='<td class="col-sm-8"><textarea class="form-control alters" id="alts'.$sc['category_id'].'" oninput="getAlters(\''.$sc['category_id'].'\')">'.$sc['alters'].'</textarea></td>';
                $result.='</tr>';
            }
            $result.= '</table></div>';
        /*-------------------------------------------------------*/
//        echo $result;
        echo $result;
    }
    
    public function saveAlter() {
        $this->load->model("common/alternative");
        $result = $this->model_common_alternative->saveAlts($this->request->post['id'], $this->request->post['alters']);
        echo $result;
    }
}

