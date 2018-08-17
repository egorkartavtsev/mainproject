<?php

class ControllerCommonCrtUpdNotice extends Controller{
    
    public function index() {
        $this->load->model('tool/layout');
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
        $autor = $this->user->getUserInfo();
        $data['autor'] = $autor['firstname'].' '.$autor['lastname'];
        if(!empty($this->request->post)){
            $this->model_tool_layout->createNewUpdateMessage($this->request->post);
        }
        $this->response->setOutput($this->load->view('common/crtUpdNotice', $data));
    }    
}

