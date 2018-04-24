<?php

class ControllerCommonCreateXML extends Controller {
//    public function index() {
//        $sup = $this->db->query("SELECT location, product_id FROM ".DB_PREFIX."product WHERE location != '' ");
//        foreach ($sup->rows as $row) {
//            $location = explode("/", $row['location']);
//            
//            $this->db->query("UPDATE ".DB_PREFIX."product SET "
//                    . "stell = '".(isset($location[0])?$location[0]:'')."', "
//                    . "jar = '".(isset($location[1])?$location[1]:'')."', "
//                    . "shelf = '".(isset($location[2])?$location[2]:'')."', "
//                    . "box = '".(isset($location[3])?$location[3]:'')."' "
//                    . "WHERE product_id = ".(int)$row['product_id']);
//        }
//        $this->db->query("UPDATE ".DB_PREFIX."product SET adress = 'пр. Карла Маркса, 179' WHERE stock = 'KM '");
//        echo $sup->num_rows;
//    }
    public function index() {
//        $query = array();
//        $sup = $this->db->query("SELECT brand FROM ".DB_PREFIX."product WHERE 1 GROUP BY brand ORDER BY brand");
        $this->db->query("UPDATE ".DB_PREFIX."product SET brand = 'SKODA' WHERE brand = 'Bridgestone'");
//        //$this->db->query("UPDATE ".DB_PREFIX."product SET structure = '1' WHERE structure = 'product'");
//        foreach ($sup->rows as $brand){
//            $req = (int)$brand['brand'];
//            if($req){
//                $q = $this->db->query("SELECT name FROM ".DB_PREFIX."brand WHERE id = ".(int)$req);
//                $query[$req] = $q->row['name'];
//            }
//        }
//        foreach($query as $key => $name){
//            $this->db->query("UPDATE ".DB_PREFIX."product SET brand = '".$name."' WHERE brand = '".$key."' ");
//        }
//        exit(var_dump($query));
    }
//    public function index() {
//        $this->load->model('tool/xml');
//        $file = DIR_DWNXL.'autoru_parts.xlsx';
//        $data = array();
//        $objPHPExcel = PHPExcel_IOFactory::load($file);
//        $objPHPExcel->setActiveSheetIndex(0);
//        $aSheet = $objPHPExcel->getActiveSheet();
//        foreach($aSheet->getRowIterator() as $row){
//            $cellIterator = $row->getCellIterator();
//            $item = array();
//            foreach($cellIterator as $cell){
//              array_push($item, $cell->getCalculatedValue());
//            }
//            $data = array(
//                'name'   => $item[1],
//                'vin'    => $item[0],
//                'brand'  => $item[3],
//                'type'   => $item[5]===0?'Б/У':'Новый',
//                'quant'  => $item[11],
//                'status' => $item[7],
//                'price'  => $item[6],
//                'compability'  => $item[10],
//                'note'   => $item[8],
//                'catN'   => $item[2]
//            );
//            if($data['vin']!='id'){
//                $this->model_tool_xml->findARPart($data);
////                exit();
//            }
//        }
//        exit();
//    }
}