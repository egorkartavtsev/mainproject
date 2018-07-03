<?php

class ControllerCommonSupport extends Controller {

    public function index() {
        $this->load->model('tool/layout');
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
        $xmls = simplexml_load_file('../Avito/ads.xml');
        $sql = "INSERT INTO `oc_product_to_avito` (vin, dateStart, dateEnd) VALUES ";
        foreach($xmls->Ad as $ad){
            $sup = (array)$ad;
            $sql.= "('".$sup['Id']."', '".$sup['DateBegin']."', '".$sup['DateEnd']."'), ";
        }
        $sql.= "('',NOW(),NOW()) ";
        $data['sql'][] = $sql;
        $this->db->query($sql);
        $sup = $this->db->query("SELECT p.product_id, p.vin FROM oc_product_to_avito p2a LEFT JOIN oc_product p ON p.vin = p2a.vin WHERE p2a.vin!='' ");
        foreach ($sup->rows as $value) {
            $this->db->query("UPDATE oc_product_to_avito SET product_id = ".(int)$value['product_id']." WHERE vin = '".$value['vin']."' ");
        }
        //exit(var_dump($sup->rows));
        $this->response->setOutput($this->load->view('common/support', $data));
    }
}