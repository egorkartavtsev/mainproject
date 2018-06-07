<?php

class ControllerServiceClient extends Controller {
    public function index() {
        $this->load->model('tool/layout');
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
        $this->response->setOutput($this->load->view('service/client', $data));
    }
    
    public function getAddForm() {
        $data['legal'] = $this->request->post['legal'];
        $data['token'] = $this->request->get['token'];
        if($data['legal'] == '0'){
            echo $this->load->view('form/add_ul', $data);
        } else {
            echo $this->load->view('form/add_fl', $data);
        }
    }
    
    public function getAdress(){
        $child = $this->request->post['child'];
        $kladr = $this->request->post['kladr'];
        $lvl = $this->request->post['lvl'];
        $req = $this->request->post['req'];
        $this->load->model('service/tools');
        $results = $this->model_service_tools->findLocate($req, $kladr, $lvl);
        $output = '';
        if(count($results)){
            foreach($results as $res){
                switch ($lvl){
                    case '29':
                        $ckldr = substr($res['kladr'], 0, 2);
                    break;
                    case '30':
                        $ckldr = substr($res['kladr'], 0, 8);
                    break;
                    case '31':
                        $ckldr = substr($res['kladr'], 0, 11);
                    break;
                    case '32':
                        $ckldr = 'none';
                    break;
                }
                $output.= '<li type="setLevelVal" class="adressitem" child="'.$child.'" kladr="'.$ckldr.'">'.$res['name'].'<li>';
            }
        } else {
            $output.= '<li disabled class="adressitem">К сожалению, ничего не найдено... Попробуйте изменить запрос.<li>';
        }
        echo $output;
    }
    
    public function create() {
        $client = $this->request->post;
        $this->load->model('service/client');
        $id = $this->model_service_client->create($client);
        $this->response->redirect($this->url->link('service/client_list', 'token='.$this->request->get['token'].'&client='.$id));
    }
}

