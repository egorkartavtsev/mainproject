<?php

class ModelToolExcel extends Model {
    private $emptyRow = array(0);
    private $letters = array(
        'avito'         => 'A',
        'drom'          => 'B',
        'brand'         => 'C',
        'model'         => 'D',
        'modRow'        => 'E',
        'category'      => 'F',
        'podcat'        => 'G',
        'name'          => 'H',
        'vin'           => 'I',
        'cond'          => 'J',
        'type'          => 'K',
        'note'          => 'L',
        'dop'           => 'M',
        'catN'          => 'N',
        'compability'   => 'O',
        'stock'         => 'P',
        'stell'         => 'Q',
        'jar'           => 'R',
        'shelf'         => 'S',
        'box'           => 'T',
        'price'         => 'U',
        'quant'         => 'V',
        'cprice'        => 'W',
        'complect'      => 'X',
        'whole'         => 'Y',
        'donor'         => 'Z',
        'date_added'    => 'AA'
    );
    
    private $templeDrom = array(
        'podcat'        => 'A',
        'type'          => 'B',
        'brand'         => 'C',
        'modRow'        => 'D',
        'vin'           => 'E',
        'note'          => 'F',
        'price'         => 'G',
        'photos'        => 'H',
        'description'   => 'I',
        'catn'          => 'J',
        'quant'         => 'K'
    );
    private $templeARu = array(
        'vin'           => 'A',
        'name'          => 'B',
        'catn'          => 'C',
        'brand'         => 'D',
        'description'   => 'E',
        'type'          => 'F',
        'price'         => 'G',
        'status'        => 'H',
        'note'          => 'I',
        'photos'        => 'J',
        'compability'   => 'K',
        'quant'         => 'L'
    );

    private $extent = array('whole', 'cprice', 'date_added');
    private $files = array(
        'prodList'  => DIR_DWNXL.'prodList.xls',
        'drom'      => DIR_DWNXL.'auto-parts-MGNAUTO.xls',
        'aru'       => DIR_DWNXL.'autoru_parts.xlsx'
    );

/*---------------------------------- tools -----------------------------------*/
    private function openFile($flag) {
        $file = $this->files[$flag];
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $objPHPExcel->setActiveSheetIndex(0);
        return $objPHPExcel;
    }
    
    public function getStocks() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE item_id = 20");
        return $sup->rows;
    }
    
    private function saveFile($file, $objPHPExcel) {
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        $objWriter->save($file);
    }
	
    private function saveFileXLSX($file, $objPHPExcel) {
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel, 'Excel2010');
        $objWriter->save($file);
    }
    
    private function getProdArr($aSheet) {
        $array = array();
        foreach($aSheet->getRowIterator() as $row){
          $cellIterator = $row->getCellIterator();
          $item = array();
          foreach($cellIterator as $cell){
            array_push($item, $cell->getCalculatedValue());
          }
          array_push($array, $item);
        }

        $result = $array;
        return $result;
    }
    
    private function findProdRow($aSheet, $data, $flag) {
        switch ($flag) {
            case 'drom':
                $col = 4;
            break;
            case 'aru':
                $col = 0;
            break;
            case 'prodList':
                $col = 8;
            break;
        }
        $result = 1;
        foreach($aSheet->getRowIterator() as $row){
            $cellIterator = $row->getCellIterator();
            $item = array();
            foreach($cellIterator as $cell){
              array_push($item, $cell->getCalculatedValue());
            }
            //ищем пустые строки
            if($item[$col]==NULL || trim($item[$col])===''){
                if($this->emptyRow[0]===0){array_shift($this->emptyRow);}
                $this->emptyRow[] = $result;
                ++$result;
            } else {
                //ищем строки товаров 
                $key = array_search($item[$col], array_column($data, 'vin'));
                if($key){
                  $data[$key]['needlyrow'] = $result;
                  ++$result;
                } else {
                  ++$result;
                }
            }
        }
        if(count($this->emptyRow)==1 && $this->emptyRow[0]===0){array_shift($this->emptyRow);}
        $this->emptyRow[] = $result;
        return $data;
    }
    
    private function updateItem($data, $flag, $sheet) {
        switch ($flag) {
            case 'drom':
                $letters = $this->templeDrom;
                if($data['quant']==='0' && isset($data['needlyrow'])){
                    $sheet = $this->deleteItem($data['needlyrow'], $sheet);
                }
            break;
            case 'prodList':
                $letters = $this->letters;
                if($data['quant']==='0' && isset($data['needlyrow'])){
                    $sheet = $this->saleItem($data['needlyrow'], $data['quant'], $sheet);
                } elseif($data['quant']!=='0' && isset($data['needlyrow'])){
                    $sheet = $this->saleItem($data['needlyrow'], $data['quant'], $sheet, 'refund');
                }
            break;
            case 'aru':
                $letters = $this->templeARu;
            break;
        }
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        if(isset($data['needlyrow'])){
            $row = $data['needlyrow'];
        } else {
            $row = array_shift($this->emptyRow);
            if(empty($this->emptyRow)){
                $this->emptyRow[] = $row+1;
            }
        }
        foreach ($letters as $key => $letter) {
            if($key!=='photos'){$sheet->getColumnDimension($letter)->setAutoSize(true);}
            $sheet->setCellValueExplicit($letter.$row, isset($data[$key])?(string)$data[$key]:(string)$sheet->getCell($letter.$row), PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->getStyle($letter.$row.':'.$letter.$row)->applyFromArray($styleArray);
        }
        return $sheet;
    }
    
    private function saleItem($row, $endq, $sheet, $refund=0) {
        $sheet->setCellValueExplicit($this->letters['quant'].$row, $endq, PHPExcel_Cell_DataType::TYPE_STRING);
        foreach ($this->letters as $letter) {
            if($refund){
                $sheet
                    ->getStyle($letter.$row.':'.$letter.$row)
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('DD6666');
            } else {
                $sheet
                    ->getStyle($letter.$row.':'.$letter.$row)
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('FFFFFF');
            }
        }
        return $sheet;
    }   

    private function refundItem($row, $sheet, $endq) {
        $sheet->setCellValueExplicit($this->letters['quant'].$row, $endq, PHPExcel_Cell_DataType::TYPE_STRING);
        foreach ($this->letters as $letter) {
            $sheet
                ->getStyle($letter.$row.':'.$letter.$row)
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('ffffff');
        }
        return $sheet;
    }
    
    private function deleteItem($row, $sheet) {
		//exit(var_dump($row));
        foreach ($this->templeDrom as $letter) {
            $sheet->setCellValueExplicit($letter.$row, '', PHPExcel_Cell_DataType::TYPE_STRING);
        }
        return $sheet;
    }
    
    private function getDromDescription($data) {
    //--------------------------------------------------------------
        $this->load->model('common/avito');
        $settings = $this->model_common_avito->getSetts();
        $stock = $this->db->query("SELECT * FROM ".DB_PREFIX."stocks WHERE name = '".$data['stock']."'");
        $weekend = $data['stock']=='KM'?'СБ, ВС - выходной':'СБ 11:00-16:00 , ВС - выходной';
        $phone = $data['stock']=='KM'?'+7 (908) 825-52-40':'+7 (912) 475-08-70';
        $img = $data['stock']=='KM'?'KM.jpg':'shop.jpg';
        //-----------------------------------------
        $templ = htmlspecialchars_decode($this->model_common_avito->getDescTempl());
        /************************/
            $templ = str_replace('%podcat%', $data['podcat'], $templ);
            $templ = str_replace('%brand%', $data['brand'], $templ);
            $templ = str_replace('%modrow%', $data['modRow'], $templ);
            $templ = str_replace('%trbrand%', '', $templ);
            $templ = str_replace('%trmodrow%', '', $templ);
            $templ = str_replace('%stock%', isset($stock->row['adress'])?$stock->row['adress']:'г. Магнитогорск, ул. Магнитная 109/1', $templ);
            $templ = str_replace('%vin%', $data['vin'], $templ);
            if(trim($data['catN'])!==''){
                $templ = str_replace('%catn%', '<li>Каталожный номер: <strong>'.$data['catN'].'</strong></li>', $templ);
            } else {
                $templ = str_replace('%catn%', '', $templ);
            }
            if(trim($data['cond'])!=='-'){
                $templ = str_replace('%condit%', '<li>Состояние: '.$data['cond'].'</li>', $templ);
            } else {
                $templ = str_replace('%condit%', '', $templ);
            }
            if(trim($data['compability'])!==''){
                $templ = str_replace('%compabil%', '<li>Подходит на: '.$data['compability'].'</li>', $templ);
            } else {
                $templ = str_replace('%compabil%', '', $templ);
            }
            if(trim($data['note'])!==''){
                $templ = str_replace('%note%', '<li>'.$data['note'].'</li>', $templ);
            } else {
                $templ = str_replace('%note%', '', $templ);
            }
            if(trim($data['dop'])!==''){
                $templ = str_replace('%dopinfo%', '<li>'.$data['dop'].'</li>', $templ);
            } else {
                $templ = str_replace('%dopinfo%', '', $templ);
            }
            $templ = str_replace('%weekend%', $weekend, $templ);
    /******************************************************************/
        return $templ;
    }
/*---------------------------- methods ---------------------------------------*/
    
    public function updateFile($flag) {
        $sup_date = $this->db->query("SELECT MAX(date) AS date FROM ".DB_PREFIX."downloads_history WHERE flag = '".$flag."'");
        $sup = $this->db->query("SELECT "
                . "p.product_id AS pid, "
                . "ph.sku AS vin, "
                . "p.model AS model, "
                . "p.modR AS modRow, "
                . "p.cond AS cond, "
                . "p.type AS type, "
                . "p.catn AS catN, "
                . "p.note AS note, "
                . "p.quantity AS quant, "
                . "b.name AS brand, "
                . "pd.name AS name, "
                . "p.price AS price, "
                . "p.status AS status, "
                . "p.stock AS stock, "
                . "p.stell AS stell, "
                . "p.jar AS jar, "
                . "p.shelf AS shelf, "
                . "p.box AS box, "
                . "p.dop AS dop, "
                . "p.donor AS donor, "
                . "p.avito AS avito, "
                . "p.drom AS drom, "
                . "p.comp AS complect, "
                . "p.comp_price AS cprice, "
                . "p.comp_whole AS whole, "
                . "p.category AS category, "
                . "p.podcateg AS podcat, "
                . "p.compability AS compability  "
                . "FROM ".DB_PREFIX."product_history ph "
                . "LEFT JOIN ".DB_PREFIX."product p ON ph.sku = p.vin "
                . "LEFT JOIN ".DB_PREFIX."product_description pd ON pd.product_id = p.product_id "
                . "LEFT JOIN ".DB_PREFIX."brand b ON b.id = p.brand "
                . "WHERE "
                    . "ph.date_added > '".$sup_date->row['date']."' "
                    . "OR ph.date_modify > '".$sup_date->row['date']."' "
                    . "OR ph.date_sale > '".$sup_date->row['date']."' "
                    . "OR ph.date_refund > '".$sup_date->row['date']."' "
                . "GROUP BY ph.sku");
        if(!empty($sup->rows)){
            $xls = $this->openFile($flag);
            $xls->setActiveSheetIndex(0);
            $sheet = $xls->getActiveSheet();
            $array = $this->findProdRow($sheet, $sup->rows, $flag);
//            exit(var_dump($this->emptyRow));
//            exit(var_dump($array));
            foreach ($array as $data) {
                switch ($flag) {
                    case 'drom':
                        $qphot = $this->db->query("SELECT * FROM ".DB_PREFIX."product_image WHERE product_id = '".$data['pid']."' ORDER BY sort_order ");
                        $photos = '';
                        foreach ($qphot->rows as $phot) {
                            $photos.= HTTP_CATALOG.'image/'.$phot['image'].'; ';
                            $data['photos'] = trim($photos);
                        }
                        $data['description'] = $this->getDromDescription($data);
                    break;
                    case 'aru':
                        $qphot = $this->db->query("SELECT * FROM ".DB_PREFIX."product_image WHERE product_id = '".$data['pid']."' ORDER BY sort_order ");
                        $photos = '';
                        foreach ($qphot->rows as $phot) {
                            $photos.= HTTP_CATALOG.'image/'.$phot['image'].'; ';
                            $data['photos'] = trim($photos);
                        }
                        $data['description'] = $this->getDromDescription($data);
                        $data['type'] = $data['type']==='Новый'?1:0;
                    break;
                }
                $sheet = $this->updateItem($data, $flag, $sheet);
//                exit(var_dump($data));
            }
            if($flag === 'aru'){
                    $this->saveFileXLSX($this->files[$flag], $xls);
            } else {
                    $this->saveFile($this->files[$flag], $xls);
            }
        }
        $this->db->query("INSERT INTO ".DB_PREFIX."downloads_history (flag, manager, date) VALUES ('".$flag."', '".$this->session->data['username']."', NOW())");
        return $this->files[$flag];
    }
}