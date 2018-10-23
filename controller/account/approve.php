<?php

class ControllerAccountApprove extends Controller{
    public function index() {
        $this->load->model('account/customer');
        if(isset($this->request->get['email']) && isset($this->request->get['code']) && $this->request->get['code']!=='' && $this->request->get['email']!==''){
            if($this->model_account_customer->checkEmail($this->request->get['email']) && $this->model_account_customer->approvEmail($this->request->get['email'], $this->request->get['code'])){
                $this->response->redirect($this->url->link('account/login'));
            } else {
                $this->response->redirect($this->url->link('common/home'));
            }
        } else {
            $this->response->redirect($this->url->link('common/home'));
        }
    }
}

