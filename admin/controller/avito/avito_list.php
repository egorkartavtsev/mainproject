<?php

class ControllerAvitoAvitoList extends Controller{
    
    public function index() {
        $this->load->model('tool/layout');
        $this->load->model('tool/image');
        $this->load->model('product/avito');
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
        $data['stocks'] = $this->model_product_avito->getStocks();
        $data['ads'] = array();
        $url = '';
        $data['url'] = 'index.php?route='.$this->request->get['route'].'&token='.$this->session->data['token'];
        $page = 0;
        $filter = array(
            'priceFrom' => 0,
            'priceTo'   => 99999999,
            'date'      => '1976-01-01',
            'modbr'     => '',
            'model'     => '',
            'podcat'     => '',
            'stock'     => '',
            'vin'     => '',
            'sort'      => 'p2a.dateEnd',
            'start'     => 0,
            'limit'     => 100,
            'order'     => 'DESC'
        );
        
        if(isset($this->request->get['page'])){
            $page = $this->request->get['page'];
            $filter['start'] = ($page-1)*50;
        }
        if(isset($this->request->get['filter_stock'])){
            $data['filter']['stock'] = $this->request->get['filter_stock'];
            $data['url'].= '&filter_stock='.$data['filter']['stock'];
            $url.= '&filter_stock='.$data['filter']['stock'];
            $filter['stock'] = $this->request->get['filter_stock'];
        }
        if(isset($this->request->get['filter_model'])){
            $data['filter']['model'] = $this->request->get['filter_model'];
            $data['url'].= '&filter_model='.$data['filter']['model'];
            $url.= '&filter_model='.$data['filter']['model'];
            $filter['model'] = $this->request->get['filter_model'];
        }
        if(isset($this->request->get['filter_podcat'])){
            $data['filter']['podcat'] = $this->request->get['filter_podcat'];
            $data['url'].= '&filter_podcat='.$data['filter']['podcat'];
            $url.= '&filter_podcat='.$data['filter']['podcat'];
            $filter['podcat'] = $this->request->get['filter_podcat'];
        }
        if(isset($this->request->get['filter_vin'])){
            $data['filter']['vin'] = $this->request->get['filter_vin'];
            $data['url'].= '&filter_vin='.$data['filter']['vin'];
            $url.= '&filter_vin='.$data['filter']['vin'];
            $filter['vin'] = $this->request->get['filter_vin'];
        }
        if(isset($this->request->get['filter_priceFrom'])){
            $data['filter']['priceFrom'] = $this->request->get['filter_priceFrom'];
            $data['url'].= '&filter_priceFrom='.$data['filter']['priceFrom'];
            $url.= '&filter_priceFrom='.$data['filter']['priceFrom'];
            $filter['priceFrom'] = $this->request->get['filter_priceFrom'];
        }
        if(isset($this->request->get['filter_priceTo'])){
            $data['filter']['priceTo'] = $this->request->get['filter_priceTo'];
            $data['url'].= '&filter_priceTo='.$data['filter']['priceTo'];
            $url.= '&filter_priceTo='.$data['filter']['priceTo'];
            $filter['priceTo'] = $this->request->get['filter_priceTo'];
        }
        if(isset($this->request->get['filter_date'])){
            $data['filter']['date'] = date('Y-m-d', strtotime($this->request->get['filter_date']));
            $data['url'].= '&filter_date='.$data['filter']['date'];
            $url.= '&filter_date='.$data['filter']['date'];
            $filter['date'] = date('Y-m-d', strtotime($this->request->get['filter_date']));
        }
        if(isset($this->request->get['filter_modbr'])){
            $data['filter']['modbr'] = $this->request->get['filter_modbr'];
            $data['url'].= '&filter_modbr='.$data['filter']['modbr'];
            $url.= '&filter_modbr='.$data['filter']['modbr'];
            $filter['modbr'] = trim($this->request->get['filter_modbr']);
        }
        if(isset($this->request->get['filter_mess'])){
            $data['filter']['mess'] = $this->request->get['filter_mess'];
            $data['url'].= '&filter_mess='.$data['filter']['mess'];
            $url.= '&filter_mess='.$data['filter']['mess'];
            $filter['mess'] = trim($this->request->get['filter_mess']);
        }
        if(isset($this->request->get['sort'])){
            $filter['sort'] = $this->request->get['sort'];
            $data['sort'] = $this->request->get['sort'];
            $url.= '&sort='.$this->request->get['sort'];            
            if(isset($this->request->get['order'])){
                $filter['order'] = $this->request->get['order'];
                $data['order'] = $this->request->get['order'];
                $url.= '&order='.$this->request->get['order'];            
            }
        }
        
        $ads = $this->model_product_avito->getProducts($filter);
        $total = $this->model_product_avito->getProductsTotal($filter);
        $pagination = new Pagination();
        $pagination->total = $total;
        $pagination->page = $page;
        $pagination->limit = 50;
        $pagination->url = $this->url->link('avito/avito_list', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();
        foreach ($ads as $ad) {
            $data['ads'][$ad['product_id']] = array(
                'name' => $ad['name'],
                'image' => $this->model_tool_image->resize($ad['image'], 70, 70),
                'vin' => $ad['vin'],
                'dateStart' => $ad['dateStart'],
                'dateEnd' => $ad['dateEnd'],
                'price' => $ad['price'],
                'class' => (int)$ad['message']?'elderAD':'noteld'
            );
        }
        $this->response->setOutput($this->load->view('avito/list', $data));
    }
    
}

