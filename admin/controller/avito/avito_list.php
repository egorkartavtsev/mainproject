<?php

class ControllerAvitoAvitoList extends Controller{
    
    public function index() {
        $this->load->model('tool/layout');
        $this->load->model('tool/image');
        $this->load->model('product/avito');
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
        $data['ads'] = array();
        $data['url'] = 'index.php?route='.$this->request->get['route'].'&token='.$this->session->data['token'];
        $filter = array(
            'priceFrom' => 0,
            'priceTo'   => 99999999,
            'date'      => '1976-01-01',
            'modbr'     => '',
            'sort'      => 'p2a.dateEnd',
            'order'     => 'DESC'
        );
        
        if(isset($this->request->get['filter_priceFrom'])){
            $data['filter']['priceFrom'] = $this->request->get['filter_priceFrom'];
            $data['url'].= '&filter_priceFrom='.$data['filter']['priceFrom'];
            $filter['priceFrom'] = $this->request->get['filter_priceFrom'];
        }
        if(isset($this->request->get['filter_priceTo'])){
            $data['filter']['priceTo'] = $this->request->get['filter_priceTo'];
            $data['url'].= '&filter_priceTo='.$data['filter']['priceTo'];
            $filter['priceTo'] = $this->request->get['filter_priceTo'];
        }
        if(isset($this->request->get['filter_date'])){
            $data['filter']['date'] = date('Y-m-d', strtotime($this->request->get['filter_date']));
            $data['url'].= '&filter_date='.$data['filter']['date'];
            $filter['date'] = date('Y-m-d', strtotime($this->request->get['filter_date']));
        }
        if(isset($this->request->get['filter_modbr'])){
            $data['filter']['modbr'] = $this->request->get['filter_modbr'];
            $data['url'].= '&filter_modbr='.$data['filter']['modbr'];
            $filter['modbr'] = trim($this->request->get['filter_modbr']);
        }
        if(isset($this->request->get['filter_mess'])){
            $data['filter']['mess'] = $this->request->get['filter_mess'];
            $data['url'].= '&filter_mess='.$data['filter']['mess'];
            $filter['mess'] = trim($this->request->get['filter_mess']);
        }
        if(isset($this->request->get['sort'])){
            $filter['sort'] = $this->request->get['sort'];
            $data['sort'] = $this->request->get['sort'];
            if(isset($this->request->get['order'])){
                $filter['order'] = $this->request->get['order'];
                $data['order'] = $this->request->get['order'];
            }
        }
        
        $ads = $this->model_product_avito->getProducts($filter);
        $total = $this->model_product_avito->getProductsTotal($filter);
        foreach ($ads as $ad) {
            $data['ads'][$ad['product_id']] = array(
                'name' => $ad['name'],
                'image' => $this->model_tool_image->resize($ad['image'], 70, 70),
                'vin' => $ad['vin'],
                'dateStart' => $ad['dateStart'],
                'dateEnd' => $ad['dateEnd'],
                'price' => $ad['price'],
                'class' => (int)$ad['message']?'elderAD':''
            );
        }
        $this->response->setOutput($this->load->view('avito/list', $data));
    }
    
}

