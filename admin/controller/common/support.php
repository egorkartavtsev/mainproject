<?php

class ControllerCommonSupport extends Controller {

    public function index() {
        $this->load->model('tool/layout');
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
        
        $lop = $this->db->query("SELECT * FROM oc_product_history ph "
                . "WHERE ph.date_added != '0000-00-00 00:00:00'");
        
        $sql = "SELECT";
        
        $data['res'] = $lop;
        
        $this->response->setOutput($this->load->view('common/support', $data));
    }
}