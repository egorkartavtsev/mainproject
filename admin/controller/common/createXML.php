<?php

class ControllerCommonCreateXML extends Controller {
    public function index() {
        $this->load->model("tool/translate");
        $replace = array("й", "ц", "у", "к", "е", "н", "г", "ш", "щ", "з", "х", "ъ", "а", "п", "ч", "к", "б", "о");
        $sup = $this->db->query("SELECT vin, catN FROM ".DB_PREFIX."product WHERE LOCATE(' ', catN) OR LOCATE('*', catN) OR LOCATE('-', catN) OR LOCATE('/', catN)");
        echo $sup->num_rows.'<br><hr><br>';
        foreach($sup->rows as $row){
            echo $row['vin'].' - '.str_replace("", "", $row['catN']).'<br>';
        }
        exit();
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