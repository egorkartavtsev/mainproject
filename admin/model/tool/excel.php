<?php

class ModelToolExcel extends Model {
    
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

/*-------------------------universal tools-----------------------------------*/
    private function openFile($file) {
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
        return count($array);
    }
    
    private function findProdRow($aSheet, $vin) {
        $result = 0; 
        foreach($aSheet->getRowIterator() as $row){
          $cellIterator = $row->getCellIterator();
          $item = array();
          foreach($cellIterator as $cell){
            array_push($item, $cell->getCalculatedValue());
          }
          if($item[8]===$vin){
              ++$result;
              return $result;
          } else {
              ++$result;
          }
        }
        return FALSE;
    }
/*-----------------------------methods----------------------------------------*/
    
    public function addItem($data, $flag) {
        $file = $this->files[$flag];
        $xls = $this->openFile($file);
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $row = $this->getEmptyRow($sheet);
        $letters = $flag=='drom'?$this->templeDrom:$this->letters;
        foreach ($letters as $key => $letter) {
            if($key!=='photos'){$sheet->getColumnDimension($letter.$row)->setAutoSize(true);}
            $sheet->setCellValueExplicit($letter.$row, $data[$key], PHPExcel_Cell_DataType::TYPE_STRING);
        }
        $this->saveFile($file, $xls);
    }
    
    public function updateItem($data, $flag) {
        $file = $this->files[$flag];
        $xls = $this->openFile($file);
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $row = $this->findProdRow($sheet, $data['vin']);
        $letters = $flag=='drom'?$this->templeDrom:$this->letters;
        foreach ($letters as $key => $letter) {
            if($key!=='photos'){$sheet->getColumnDimension($letter.$row)->setAutoSize(true);}
            $sheet->setCellValueExplicit($letter.$row, $data[$key], PHPExcel_Cell_DataType::TYPE_STRING);
        }
        $this->saveFile($file, $xls);
    }
    
    public function saleItem($vin, $endq) {
        $file = $this->files['prodList'];
        $xls = $this->openFile($file);
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $row = $this->findProdRow($sheet, $vin);
        if($row){
            $sheet->setCellValueExplicit($this->letters['quant'].$row, $endq, PHPExcel_Cell_DataType::TYPE_STRING);
            if($endq==0){
                foreach ($this->letters as $letter) {
                    $sheet
                                    ->getStyle($letter.$row.':'.$letter.$row)
                                    ->getFill()
                                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                                    ->getStartColor()
                                    ->setRGB('DD6666');
                }
            }
            $this->saveFile($file, $xls);
        }
    }
    
    public function refundItem($vin) {
        $file = $this->files['prodList'];
        $xls = $this->openFile($file);
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $row = $this->findProdRow($sheet, $vin);
        if($row){
            $sheet->setCellValueExplicit($this->letters['quant'].$row, 1, PHPExcel_Cell_DataType::TYPE_STRING);
            if($endq==0){
                foreach ($this->letters as $letter) {
                    $sheet
                                    ->getStyle($letter.$row.':'.$letter.$row)
                                    ->getFill()
                                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                                    ->getStartColor()
                                    ->setRGB('DD6666');
                }
            }
            $this->saveFile($file, $xls);
        }
    }
    
    public function deleteItem($vin) {
        $file = $this->files['drom'];
        $xls = $this->openFile($file);
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $row = $this->findProdRow($sheet, $vin);
        foreach ($this->templeDrom as $letter) {
            $sheet->setCellValueExplicit($letter.$row, '', PHPExcel_Cell_DataType::TYPE_STRING);
        }
        $this->saveFile($file, $xls);
    }
}