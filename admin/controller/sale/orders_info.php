<?php

class ControllerSaleOrdersInfo extends Controller{
    public function index() {
        $this->load->model('tool/order');
        $data['href'] = 'index.php?route=sale/order_info&token='.$this->session->data['token'];
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
        $data['order'] = $this->model_tool_order->getOrderInfo($this->request->get['order_id']);
        $data['statuses'] = $this->model_tool_order->getOrderStatuses();
        $data['catalog'] = HTTP_CATALOG.'index.php?route=catalog/product';
        $this->document->setTitle('Информация о заказе');
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
                'text' => 'Главная',
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
                'text' => 'Заказы',
                'href' => $this->url->link('sale/orders', 'token=' . $this->session->data['token'].$url, true)
        );
        $data['btn_back'] = $this->url->link('sale/orders', 'token=' . $this->session->data['token'].$url, TRUE);
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        if(!isset($filter)){
            $filter = 0;
        }
        $data['utype'] = $this->session->data['uType'];
        $data['href'].= $url;
        $this->response->setOutput($this->load->view('sale/orders_info', $data));
    }
    
    public function added_prod() {
        $this->load->model('tool/order');
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
        $result = $this->model_tool_order->save_status($this->request->post['stat'], $this->request->post['order']);
        echo $result;
    }
}

