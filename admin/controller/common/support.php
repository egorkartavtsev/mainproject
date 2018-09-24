<?php

class ControllerCommonSupport extends Controller {

    public function index() {
        $this->load->model('tool/layout');
        $sup = array();
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
        
        $tmp = $this->db->query("SELECT * FROM ".DB_PREFIX."product_to_avito p2a "
                . "LEFT JOIN ".DB_PREFIX."product p ON p.product_id = p2a.product_id "
                . "LEFT JOIN ".DB_PREFIX."lib_fills lf ON lf.name = p.podcateg "
                . "WHERE p2a.number!=0 LIMIT 1000 OFFSET 4000 ");
        $data['red'] = $tmp;
        
        $this->response->setOutput($this->load->view('common/support', $data));
    }
}