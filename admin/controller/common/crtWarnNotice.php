<?php

class ControllerCommonCrtWarnNotice extends Controller{
    
    public function index() {
        $this->load->model('tool/layout');
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
        $autor = $this->user->getUserInfo();
        $data['users'] = $this->model_tool_layout->getUserList();
        $data['autor'] = $autor['firstname'].' '.$autor['lastname'];
        if(isset($this->request->post['users'])){
            if($this->request->post['text']!=''){
                $sup = $this->request->post;
                $users = $sup['users'];
                unset($sup['users']);
                $this->model_tool_layout->createNewWarnMessage($sup, $users);
            }
        }
        $this->response->setOutput($this->load->view('common/crtWarnNotice', $data));
    }    
}

