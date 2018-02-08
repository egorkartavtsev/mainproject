<?php

class ControllerCommonAvito extends Controller {
    public function categories(){
        //коммент для теста веток
        $this->load->language('common/addprod');
        $this->document->setTitle('Работа с категориями avito');
        
        $data['heading_title'] = 'Работа с категориями avito';

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
                'text' => 'Главная',
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
                'text' => 'Работа с категориями',
                'href' => $this->url->link('common/avito/categories', 'token=' . $this->session->data['token'], true)
        );
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['token'] = $this->session->data['token'];
        
        $this->load->model('common/avito');
        $data['categories'] = $this->model_common_avito->getTotalCats();
        
        $this->response->setOutput($this->load->view('avito/categories', $data));
    }
    
    public function settings(){
        $this->load->language('common/addprod');
        $this->document->setTitle('Настройки модуля');
        
        $data['heading_title'] = 'Настройки модуля';

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
                'text' => 'Главная',
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
                'text' => 'Настройки модуля',
                'href' => $this->url->link('common/avito/categories', 'token=' . $this->session->data['token'], true)
        );
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['token'] = $this->session->data['token'];
        
        $this->load->model('common/avito');
        $data['libr'] = $this->model_common_avito->getLibr();
        $data['settings'] = $this->model_common_avito->getSetts();
        
        $this->response->setOutput($this->load->view('avito/settings', $data));
    }
    
    public function saveSettings() {
        $data = $this->request->post;
        $this->load->model('common/avito');
        $this->model_common_avito->updateSetts($data);
        $this->response->redirect($this->url->link('common/avito/settings', 'token=' . $this->session->data['token'], true));
    }
    
    public function getSubCat() {
        $parent = $this->request->post['par'];
        $this->load->model("common/avito");
        $subcats = $this->model_common_avito->getSubCats($parent);
        $table = '<table class="table table-bordered table-hover table-responsive">'
                    . '<thead>'
                        . '<tr>'
                            . '<td>Название</td>'
                            . '<td>id - Авито</td>'
                        . '</tr>'
                    . '</thead>';
        foreach ($subcats as $sc){
            $table.='<tr>'
                        . '<td>'.$sc['name'].'</td>'
                        . '<td><input class="form-control" name="avitocat['.$sc['category_id'].']" type="text" value="'.$sc['avitoId'].'"/></td>'
                    . '</tr>';
        }
        $table.='</table>';
        echo $table;
    }
    
    public function saveForm(){
        $data = $this->request->post;
//        exit(var_dump($data['avitocat']));
        $this->load->model('common/avito');
        if(strlen($data['catAvito'])!==0){
            $this->model_common_avito->updateCat($data['catAvito'], $data['maincategory']);
        } else {
            $this->model_common_avito->updateSCats($data['avitocat']);
        }
        $this->response->redirect($this->url->link('common/avito/categories', 'token=' . $this->session->data['token'], true));
    }
}

