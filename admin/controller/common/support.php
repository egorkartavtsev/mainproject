<?php

class ControllerCommonSupport extends Controller {

    public function index() {
        $this->load->model('tool/layout');
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
        
        $sup = $this->db->query("SELECT * FROM `oc_kladr` WHERE kladr LIKE '__000___00000' AND item_id = 30");
//        foreach ($sup->rows as $row) {
//            $this->db->query("INSERT INTO `oc_kladr` "
//                    . "(`library_id`, `item_id`, `parent_id`, `name`, `kladr`) "
//                    . "VALUES (5, 31,0,'".$row['name']."','".$row['kladr']."')");
//        }
        $this->response->setOutput($this->load->view('common/support', $data));
    }
}