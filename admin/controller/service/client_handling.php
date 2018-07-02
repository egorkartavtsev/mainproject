<?php

class ControllerServiceClientHandling extends Controller {
    public function index() {
        $this->load->model('tool/layout');
        $this->load->model('service/tools');
        $this->load->model('service/handling');
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
        $data['client'] = $this->model_service_tools->getClientInfo($this->request->get['client_id']);
        $data['auto'] = $this->model_service_tools->getClientAuto($this->request->get['client_id']);
        $data['action'] = "index.php?route=service/client_handling/createHandling&token=".$data['token'];
        
        if(isset($this->request->get['handling'])){
            $data['handling'] = $this->model_service_handling->getHandlingInfo($this->request->get['handling']);
            $data['action'] = "index.php?route=service/client_handling/updateHandling&token=".$data['token']."&handling=".$this->request->get['handling'];
            $data['modal_contract'] = $this->load->view('modals/clear', array('target'=>'contractcreate', 'header'=>'Добавить услугу', 'key'=>'contract'));
            if((int)$data['handling']['accident']){
                $data['accident'] = $this->model_service_handling->getAccidentInfo($data['handling']['accident']);
                $data['accident']['insurences'] = $this->model_service_tools->getInsurences();
                $data['accident']['commissars'] = $this->model_service_tools->getCommissars();
                $data['accident_form'] = $this->load->view('form/accident', $data['accident']);
            }
        }
        
        $this->response->setOutput($this->load->view('service/client_handling', $data));
    }
    
    public function getAccidentForm() {
        $this->load->model('service/tools');
        $data['commissars'] = $this->model_service_tools->getCommissars();
        $data['insurences'] = $this->model_service_tools->getInsurences();
        echo $this->load->view('form/accident', $data);
    }
    
    public function showContract() {
        $this->load->model('service/tools');
        $data = array();
        if((int)$this->request->post['contract']){
            
        }
        $data['handl_types'] = $this->model_service_tools->getHandlTypes();
        $data['handling'] = $this->request->get['handling'];
//        $data['commissars'] = $this->model_service_tools->getCommissars();
//        $data['insurences'] = $this->model_service_tools->getInsurences();
        echo $this->load->view('form/contract', $data);
    }
    
    public function createHandling() {
        $this->load->model('service/handling');
        $handling = $this->request->post['handling'];
        unset($this->request->post['handling']);
        if(!empty($this->request->post)){
            $accident = $this->request->post;
            $accident_id = $this->model_service_handling->createAccident($accident);
            $handling['accident'] = $accident_id;
        } else {
            $handling['accident'] = 0;
        }
        $handl = $this->model_service_handling->createHandling($handling);
        
        $this->response->redirect($this->url->link('service/client_handling', 'token='.$this->session->data['token'].'&client_id='.$handling['client'].'&handling='.$handl));
//        exit(var_dump($this->request->post));
    }
}

