<?php

class ControllerCommonRefund extends Controller {
    
    public function index(){
        $data = $this->getLayout();
        $this->response->setOutput($this->load->view('common/refund', $data));
    }
    
     public function getLayout() {


                    $this->load->language('common/addprod');

                    $this->document->setTitle($this->language->get('heading_title'));

                    $data['heading_title'] = $this->language->get('heading_title');

                    $data['breadcrumbs'] = array();

                    $data['breadcrumbs'][] = array(
                            'text' => $this->language->get('text_home'),
                            'href' => $this->url->link('common/excel', 'token=' . $this->session->data['token'], true)
                    );

                    $data['breadcrumbs'][] = array(
                            'text' => $this->language->get('heading_title'),
                            'href' => $this->url->link('common/addprod', 'token=' . $this->session->data['token'], true)
                    );
                    $data['header'] = $this->load->controller('common/header');
                    $data['column_left'] = $this->load->controller('common/column_left');
                    $data['footer'] = $this->load->controller('common/footer');
                    $data['token_add'] = $this->session->data['token'];
                    return $data;

        }
}
