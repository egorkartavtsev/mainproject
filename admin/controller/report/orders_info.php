<?php

class ControllerReportOrdersInfo extends Controller{
    public function index() {
        $this->load->model('tool/order');
        $this->load->model("tool/layout");
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
        $order = $this->model_tool_order->getOrderInfo($this->request->get['order_id']);
        $data['order'] = array(
            'id' => $order['id'],
            'date_added' => $order['date_added'],
            'firstname'  => $order['firstname'],
            'lastname'   => $order['lastname'],
            'patron'   => $order['patron'],
            'email' => $order['email'],
            'telephone' => $order['telephone'],
            'zone' => $order['zone'],
            'city' => $order['city'],
            'address' => $order['address'],
            'total' => $order['total'],
            'order_status_id' => $order['order_status_id']
        );
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
        } else {
        }
        $result = $this->model_tool_order->added_prod($prod[''], $this->request->post['order']);
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
}

