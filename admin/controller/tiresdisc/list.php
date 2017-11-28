<?php

class ControllerTiresDiscList extends Controller {
    public function index() {
        $data = $this->getLayout();
        $this->load->model("tiresdisc/tiresdisc");
        $list = $this->model_tiresdisc_tiresdisc->getList();
        $this->load->model('tool/image');
		$data['list'] = array();
		foreach ($list as $prod) {
            if($prod['dImage']!==NULL){
                if (is_file(DIR_IMAGE.$prod['dImage'])) {
                        $image = $this->model_tool_image->resize($prod['dImage'], 40, 40);
                } else {
                        $image = $this->model_tool_image->resize('no_image.png', 40, 40);
                }
                $data['list'][] = array(
                    'name'      => $prod['name'],
                    'image'     => $image,
                    'cat'       => 'disc',
                    'cat_name'  => 'Диски',
                    'date'      => $prod['date'],
                    'link'      => $prod['link'],
                    'linkEdit'  => $this->url->link('tiresdisc/edit', 'token='.$this->session->data['token'].'&prod='.$prod['link'], TRUE),      
                    'price'     => $prod['price'],
                    'vin'       => $prod['vin'],      
                    'quant'     => $prod['quan'],      
                    'locate'    => $prod['stock'].'/'.$prod['location'],      
                    'stat'      => $prod['status'],      
                    'type'      => $prod['type'],      
                    'cond'      => $prod['cond']      
                );
            } else {
                if (is_file(DIR_IMAGE.$prod['tImage'])) {
                        $image = $this->model_tool_image->resize($prod['tImage'], 40, 40);
                } else {
                        $image = $this->model_tool_image->resize('no_image.png', 40, 40);
                }
                $data['list'][] = array(
                    'name'  => $prod['name'],
                    'image'  => $image,
                    'cat'  => 'tire',
                    'cat_name'  => 'Шины',
                    'date'  => $prod['date'],
                    'price'     => $prod['price'],
                    'vin'       => $prod['vin'],      
                    'quant'     => $prod['quan'],      
                    'locate'    => $prod['stock'].'/'.$prod['location'],      
                    'stat'      => $prod['status'],
                    'link'      => $prod['link'],
                    'linkEdit'  => $this->url->link('tiresdisc/edit', 'token='.$this->session->data['token'].'&prod='.$prod['link'], TRUE),      
                    'type'      => $prod['type'],      
                    'cond'      => $prod['cond']
                );
            }
        }
        
        
        $this->response->setOutput($this->load->view("tiresdisc/list", $data));
    }
    
    public function deleteItem(){
        $id = $this->request->post['id'];
        $this->load->model('tiresdisc/tiresdisc');
        $result = $this->model_tiresdisc_tiresdisc->deleteProd($id);
        echo $result;
    }


    public function getLayout() {
        
        
        $this->load->language('tiresdisc/list');

        $this->document->setTitle($this->language->get('heading_title'));
        $data['token'] = $this->session->data['token'];
        $data['heading_title'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
                'text' => 'Главная',
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('tiresdisc/list', 'token=' . $this->session->data['token'], true)
        );
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        return $data;
        
    }
}

