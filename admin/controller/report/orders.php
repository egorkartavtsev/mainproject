<?php

class ControllerReportOrders extends Controller{
    public function index() {
        $this->load->model('tool/order');
        $this->load->model("tool/layout");
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
        $url = '';
        $data['href'] = 'index.php?route=report/orders_info&token='.$this->session->data['token'];
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
            $filter['order_id'] = $this->request->get['filter-order_id'];
            $data['filter_order_id'] = $filter['order_id'];
        }else {
            $data['filter_order_id'] = '';
        }
        if(isset($this->request->get['filter-lastname']) && $this->request->get['filter-lastname']!=''){
            $url.='&filter-lastname='.$this->request->get['filter-lastname'];
            $filter['lastname'] = $this->request->get['filter-lastname'];
            $data['filter_lastname'] = $filter['lastname'];
        } else {
            $data['filter_lastname'] = '';
        }
        if(isset($this->request->get['filter-telephone']) && $this->request->get['filter-telephone']!=''){
            $url.='&filter-telephone='.$this->request->get['filter-telephone'];
            $filter['telephone'] = $this->request->get['filter-telephone'];
            $data['filter_telephone'] = $filter['telephone'];
        } else {
            $data['filter_telephone'] = '';
        }
        if(!isset($filter)){
            $filter = 0;
        }
        $data['utype'] = $this->session->data['uType'];
        $data['href'].= $url;
        $data['orders'] = $this->model_tool_order->getOrders($offset, $filter);
        $data['total_orders'] = $this->model_tool_order->getTotalOrders($filter);
        
        $pagination = new Pagination();
        $pagination->total = $data['total_orders'];
        $pagination->page = $page;
        $pagination->limit = 30;
        $pagination->url = $this->url->link('report/orders', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();
        
        $this->response->setOutput($this->load->view('sale/orders', $data));
    }
}

