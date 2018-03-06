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
        'model'         => 'D',
        'vin'           => 'E',
        'note'          => 'F',
        'price'         => 'G',
        'photos'         => 'H',
        'description'   => 'J',
        'catn'          => 'K',
        'quant'         => 'L'
    );

    private $extent = array('whole', 'cprice', 'date_added');
    private $files = array(
        'prodList'  => DIR_DWNXL.'prodList.xls',
        'drom'      => DIR_DWNXL.'auto-parts-MGNAUTO.xls'
    );

/*---------------------------------- tools -----------------------------------*/
    private function openFile($flag) {
        $file = $this->files[$flag];
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $objPHPExcel->setActiveSheetIndex(0);
        return $objPHPExcel;
    }

    private function saveFile($file, $objPHPExcel) {
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
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
    
    private function getEmptyRow($aSheet) {
        $array = array();
        $result = 0;
        foreach($aSheet->getRowIterator() as $row){
          $cellIterator = $row->getCellIterator();
          $item = array();
          foreach($cellIterator as $cell){
            array_push($item, $cell->getCalculatedValue());
          }
          if($item[8]==NULL || trim($item[8])===''){
              ++$result;
              return $result;
          } else {
              ++$result;
          }
          array_push($array, $item);
        }
        return count($array)+1;
    }
    
    private function findProdRow($aSheet, $data, $flag) {
        $col = $flag=='prodList'?8:4;
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
        if(count($this->emptyRow==1) && $this->emptyRow[0]===0){array_shift($this->emptyRow);}
        $this->emptyRow[] = $result;
        return $data;
    }
/*---------------------------- methods ---------------------------------------*/
    
    public function updateItem($data, $flag, $sheet) {
        $letters = $flag=='drom'?$this->templeDrom:$this->letters;
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        if(isset($data['needlyrow'])){
            $row = $data['needlyrow'];
            if($data['quant']==='0'){
                switch ($flag) {
                    case 'drom':
                        $sheet = $this->deleteItem($row, $sheet);
                        return $sheet;
                    break;
                    case 'prodList':
                        $sheet = $this->saleItem($row, $data['quant'], $sheet);
                        return $sheet;
                    break;
                }
            }
        } else {
            $row = array_shift($this->emptyRow);
            if(empty($this->emptyRow)){
                $this->emptyRow[] = $row+1;
            }
        }
        foreach ($letters as $key => $letter) {
            if($key!=='photos'){$sheet->getColumnDimension($letter)->setAutoSize(true);}
            $sheet->setCellValueExplicit($letter.$row, isset($data[$key])?$data[$key]:$sheet->getCell($letter.$row), PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->getStyle($letter.$row.':'.$letter.$row)->applyFromArray($styleArray);
        }
        return $sheet;
    }
    
    public function saleItem($row, $endq, $sheet) {
        $sheet->setCellValueExplicit($this->letters['quant'].$row, $endq, PHPExcel_Cell_DataType::TYPE_STRING);
        foreach ($this->letters as $letter) {
            $sheet
                ->getStyle($letter.$row.':'.$letter.$row)
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('DD6666');
        }
        return $sheet;
    }   

    public function refundItem($row, $sheet, $endq) {
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
    
    public function deleteItem($row, $sheet) {
        foreach ($this->templeDrom as $letter) {
            $sheet->setCellValueExplicit($letter.$row, '', PHPExcel_Cell_DataType::TYPE_STRING);
        }
        return $sheet;
    }
    
    public function updateFile($flag) {
        $sup_date = $this->db->query("SELECT MAX(date) AS date FROM ".DB_PREFIX."downloads_history WHERE flag = '".$flag."'");
        $sup = $this->db->query("SELECT "
                . "ph.sku AS vin, "
                . "p.model AS model, "
                . "p.length AS modRow, "
                . "p.upc AS cond, "
                . "p.ean AS type, "
                . "p.isbn AS catN, "
                . "p.isbn AS catn, "
                . "p.jan AS note, "
                . "p.quantity AS quant, "
                . "b.name AS brand, "
                . "pd.name AS name, "
                . "p.price AS price, "
                . "p.weight AS stock, "
                . "p.location AS location, "
                . "p.width AS dop, "
                . "p.height AS donor, "
                . "p.avito AS avito, "
                . "p.drom AS drom, "
                . "p.comp AS complect, "
                . "p.comp_price AS cprice, "
                . "p.comp_whole AS whole, "
                . "p.category AS category, "
                . "p.podcateg AS podcat, "
                . "p.compability AS compability  "
                . "FROM ".DB_PREFIX."product_history ph "
                . "LEFT JOIN ".DB_PREFIX."product p ON ph.sku = p.sku "
                . "LEFT JOIN ".DB_PREFIX."product_description pd ON pd.product_id = p.product_id "
                . "LEFT JOIN ".DB_PREFIX."brand b ON b.id = p.manufacturer_id "
                . "WHERE "
                    . "ph.date_modify > '".$sup_date->row['date']."' "
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
                if($flag==='drom'){
                    $location = explode("/", $data['location']);
                    $data['stell'] = isset($location[0])?$location[0]:'';
                    $data['jar'] = isset($location[1])?$location[1]:'';
                    $data['shelf'] = isset($location[2])?$location[2]:'';
                    $data['box'] = isset($location[3])?$location[3]:'';
                    $sheet = $this->updateItem($data, $flag, $sheet);
                }
            }
            $this->saveFile($this->files[$flag], $xls);
        }
        //exit(var_dump($this->emptyRow));
        $this->db->query("INSERT INTO ".DB_PREFIX."downloads_history (flag, manager, date) VALUES ('".$flag."', '".$this->session->data['username']."', NOW())");
        return $this->files[$flag];
    }
}