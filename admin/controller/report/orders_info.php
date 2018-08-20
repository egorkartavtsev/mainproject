<?php

class ControllerReportOrdersInfo extends Controller{
    public function index() {
        $this->load->model('tool/order');
        $this->load->model("tool/layout");
        $order = $this->model_tool_order->getOrderInfo($this->request->get['order_id']);
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
        $data['href'] = 'index.php?route=report/order_info&token='.$this->session->data['token'];
        $url = '';
        if(isset($this->request->get['page'])){
            $offset = 30*($this->request->get['page']-1);
            $page = $this->request->get['page'];
            $data['href'].= '&page='.$this->request->get['page'];
        } else {
            $offset = 0;
            $page = 1;
        }
        if(isset($this->request->get['filter-order_id']) && $this->request->get['filter-order_id']!=''){
            $url.='&filter-order_id='.$this->request->get['filter-order_id'];
        }
        if(isset($this->request->get['filter-lastname']) && $this->request->get['filter-lastname']!=''){
            $url.='&filter-lastname='.$this->request->get['filter-lastname'];
        }
        if(isset($this->request->get['filter-telephone']) && $this->request->get['filter-telephone']!=''){
            $url.='&filter-telephone='.$this->request->get['filter-telephone'];
        }
        
        $data['order'] = $order;
        $data['complects'] = array();
        foreach ($order['products'] as $prod) {
            $data['order']['products'][] = $prod;
            $comp = $this->model_tool_order->tryComp($prod['vin']);
            if($comp){
                $data['complects'][$comp['key']] = $comp['prods'];
            }
        }
        $data['statuses'] = $this->model_tool_order->getOrderStatuses();
        $data['catalog'] = HTTP_CATALOG.'index.php?route=catalog/product';
        $data['btn_back'] = $this->url->link('report/orders', 'token=' . $this->session->data['token'].$url, TRUE);
        if(!isset($filter)){
            $filter = 0;
        }
        $data['utype'] = $this->session->data['uType'];
        $data['href'].= $url;
        
        $data['ship'] = $this->model_tool_order->getShipLib();
        
        $this->response->setOutput($this->load->view('sale/orders_info', $data));
    }
    
    public function added_prod() {
        $this->load->model('tool/order');
        $result = '';
        $comp = $this->model_tool_order->tryComp($this->request->post['vin']);
        if($comp){
            foreach($comp['prods'] as $prod){
                $result = $this->model_tool_order->update_prod($prod['vin'], 2);
            }
        }
        $result = $this->model_tool_order->added_prod($this->request->post['vin'], $this->request->post['order']);
        echo $result;
    }
    public function delete_prod() {
        $this->load->model('tool/order');
        $result = $this->model_tool_order->delete_prod($this->request->post['prod'], $this->request->post['order']);
        echo $result;
    }
    
    public function save_status() {
        $this->load->model('tool/order');
        $stat = $this->request->post['stat'];
        switch ($stat) {
            case '13': //oplacheno
                $prods = $this->model_tool_order->getOrderInfo($this->request->post['order']);
                $list = array();
                $invoice = uniqid("r_");
                $this->load->model('common/write_off');
                foreach ($prods['products'] as $prod) {
                    $comp = $this->model_common_write_off->isCompl($prod['vin']);
                    if($comp){
                        foreach($comp as $compl){
                            $list[] = array(
                                'name' => $compl['name'],
                                'vin' => $compl['vin'],
                                'price' => $compl['price'],
                                'quan' => $compl['quantity'],
                                'quanfact' => $prod['factquantity'],
                                'saleprice' => $compl['factprice'],
                                'reason' => $compl['reason'],
                                'wherefrom' => 'сайт',
                                'manager' => $this->session->data['username'],
                                'location' => '',
                                'summ' => $prod['factquantity']*$compl['factprice'],
                                'date' => date("d.m.Y"),
                                'city' => $prods['city'],
                                'client' => $prods['lastname'].' '.$prods['firstname'].' '.$prods['patron']
                            );
                        }
                    } else {
                        $list[] = array(
                            'name' => $prod['name'],
                            'vin' => $prod['vin'],
                            'price' => $prod['price'],
                            'quan' => $prod['quantity'],
                            'quanfact' => $prod['factquantity'],
                            'saleprice' => $prod['price'],
                            'reason' => '',
                            'wherefrom' => 'сайт',
                            'manager' => $this->session->data['username'],
                            'location' => '',
                            'date' => date("d.m.Y"),
                            'summ' => $prod['factquantity']*$prod['price'],
                            'city' => $prods['city'],
                            'client' => $prods['lastname'].' '.$prods['firstname'].' '.$prods['patron']
                        );                        
                    }
                }
                $this->model_common_write_off->sale($list, $invoice);
            break;
            case '9': //otmena
                $result = $this->model_tool_order->updateProds($this->request->post['order']);
            break;
        }
        $result = $this->model_tool_order->save_status($stat, $this->request->post['order']);
        echo $result;
    }
    
    public function saveShipInfo() {
        $sup = explode(",", $this->request->post['data']);
        $tmp = array();
        foreach ($sup as $row) {
            $i = explode("=", $row);
            if(trim($i[0])!=''){
                $tmp[$i[0]] = $i[1];
            }
        }
        $tmp['target'] = $this->request->post['target'];
        $this->load->model('tool/order');
        $this->model_tool_order->saveShip($tmp);
        echo var_dump($tmp);
    }
    
}

