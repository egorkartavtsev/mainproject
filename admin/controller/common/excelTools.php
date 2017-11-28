<?php
    class ControllerCommonExcelTools extends Controller {
        /*
         *  $errors     - собирает в себя ошибки в процессе обработки
         *                загруженного файла XLS.
         *  $matches    - собирает количество совпадающих продуктов. 
         *  
         */
        public $errors = array();
        public $matches = 0;
        private $woi = 0;
        private $wod = 0;


        public function constructProdArr($XLSrows){
            $products = array();
            $this->load->model('common/excel');
            $template = $this->model_common_excel->getProductTemplate();
            $brands = $this->model_common_excel->getbrands();
            foreach ($XLSrows as $row){
                $product = array();
                if($row[8]!=NULL){
                    foreach ($template as $key => $cell) {
                        $product[$cell['name']] = str_replace("\\", "", $row[(int)$key]);
                    }
                    $product['quant'] = $product['quant']!=NULL?$product['quant']:0;
                    $product['vnutn'] = str_replace("/", "-", $product['vnutn']);
                    $product['price'] = $product['price']!=NULL?$product['price']:0;
                    $product['modr']  = str_replace(">", "-", $product['modr']);
                    $product['date']  = $product['date']!=NULL?DateTime::createFromFormat('Y-m-d', $product['date'])->format('d-‌​m-Y'):'';
                    $success = $this->emptyCells($product, $template);
                    if($success){
                        $success = $this->undefinedCells($product, $template);
                        if($success){
                            $success = $this->undefCompabil($product, $brands);
                            if($success){
                                $products[] = $product;
                            }
                        }
                    }
                }
            }
            return $products;           
        }
        
        public function emptyCells($product, $template) {
            foreach ($template as $cell) {
                if($cell['system']){
                    if((trim($product[$cell['name']])!=Null) || (trim($product[$cell['name']])!="")){
                        $result = TRUE;
                    } else {
                        $this->errors[] = 'У продукта <b>№'.$product['vnutn'].'</b> не заполнено обязательное поле <b>'.$cell['text'].'</b>. ';
                        return FALSE;
                    }
                }
            }
            return $result;
        }
        
        public function undefinedCells($product, $template) {
            foreach($template as $cell){
                if($cell['important']){
                    
                    $qbrand = $this->db->query("SELECT * FROM ".DB_PREFIX."brand WHERE name = '".$this->getTrueName($product[$cell['name']])."' ");
                    $qcat = $this->db->query("SELECT * FROM ".DB_PREFIX."category_description WHERE name = '".$product[$cell['name']]."' ");
                    
                    if(!empty($qbrand->row)){
                        $result = TRUE;
                    } elseif (!empty ($qcat->row)) {
                        $result = TRUE;
                    } else {
                        $this->errors[] = 'Для продукта <b>№'.$product['vnutn'].'</b> в поле <b>"'.$cell['text'].'"</b> указаны некорректные данные <b>('.trim($product[$cell['name']]).')</b>. ';
                        return FALSE;
                    }
                }
            }
            return $result;
        }
        
        public function undefCompabil($product, $brands) {
            $result = TRUE;
            if($product['comp']!=NULL){
                $comp_arr = explode(";", $product['comp']);
                foreach ($comp_arr as $modr){
                    if(($modr!=" ")&&(!in_array($this->getTrueName($modr), $brands))){
                        $this->errors[] = 'Для продукта <b>№ '.$product['vnutn'].'</b> в поле "Применимость" указаны некорректные данные <b>('.$modr.')</b>';
                        return FALSE;
                    }
                }
                return TRUE;
            } else{
                return TRUE;
            }
        }
        
        public function getTrueName($cell) {
                return mb_convert_case(trim($cell), MB_CASE_UPPER);
        }
        
        public function getCompability($comp){
            $result = array();
            $arr = explode("; ", $comp);
            foreach ($arr as $str) {
                $query = $this->db->query("SELECT * FROM ".DB_PREFIX."brand WHERE name = '".$str."' ");
                if(!empty($query->row)){
                    $result[] = array(
                        'id' => $query->row['id'],
                        'name' => $str
                    );
                }
            }
            return $result;
        }
        
        public function getImages($vin) {
            $this->errors['images'] = '';
            $images = array();
            $directory = DIR_IMAGE.'catalog/demo/production/'.$vin;
            $files = file_exists($directory)?scandir($directory):array();
            if(!empty($files)){
                $con = count($files);
                if($con != 0){
                    for($i = 2; $i < $con-1; $i++){
                        $images[] = $files[$i];
                    }
                } else {
                    $images[0] = ' ';
                    $this->woi += 1;
                }
            } else {
                $images[0] = ' ';
                $this->wod += 1;
            }
            $this->errors['woi'] = 'Предупреждение! У '.$this->woi.' товаров отсутствуют фотографии в папке с фотографиями.';
            $this->errors['wod'] = 'Предупреждение! У '.$this->wod.' товаров отсутствует папка с фотографиями.';
            return $images;
        }

        public function addProduct($products) {
            $this->load->model('common/excel');
            $listProds = $this->model_common_excel->getListProds();
            //exit(var_dump($listProds));
            $descripttemplate = $this->model_common_excel->getdescripttemplate();
            //exit(var_dump($products));
            foreach ($products as $product) {
                $vin = str_replace('/', '-', $product['vnutn']);
                if(!in_array($vin, $listProds)){
                    $comp = $this->getCompability($product['comp']);
                    $images = $this->getImages($vin);
                    $this->model_common_excel->setToDB($product, $vin, $descripttemplate, $images, $comp, $this->session->data['username']);
                } else {
                    $this->matches+=1;
                }
            }
        }
        
        public function synchProds($products) {
            $this->load->model('common/excel');
            $listProds = $this->model_common_excel->getListProds();
            foreach ($products as $product) {
                if (in_array(trim($product['vnutn']), $listProds)){
                    if($product['quant'] === 0){
						//echo $product['vnutn'].'<br>';
                        $this->model_common_excel->synchToDB($product);
                    } else {
                        $this->model_common_excel->updateToDB($product);
                    }
                }
            }
        }
        
        public function constructFile($products = 0) {
            $this->load->model("common/excel");
            $template = $this->model_common_excel->getProductTemplate();
            $xls = new PHPExcel();
            $xls->setActiveSheetIndex(0);
            $sheet = $xls->getActiveSheet();
            $sheet = $this->getHeading($sheet, $template);
            if($products!=0){
                ini_set('max_execution_time','3600');
                $sheet = $this->getTBody($products, $template, $sheet);
            }
            return $xls;
        }

        public function download_template($filter){
            
            $xls = $this->constructFile();
            header ( "Expires: " . gmdate("D,d M YH:i:s") . " GMT" );
            header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
            header ( "Cache-Control: no-cache, must-revalidate" );
            header ( "Pragma: no-cache" );
            header ( "Content-type: application/vnd.ms-excel" );
            header ( "Content-Disposition: attachment; filename=temp.xls" );
            
            $objWriter = new PHPExcel_Writer_Excel5($xls);
            $objWriter->save('php://output');
        }
        
        public function download_prods($filter_data) {
            $this->load->model('common/excel');

            $products = $this->model_common_excel->getInfoProducts($filter_data);
            $xls = $this->constructFile($products);
            
            header ( "Expires: " . gmdate("D,d M YH:i:s") . " GMT" );
            header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
            header ( "Cache-Control: no-cache, must-revalidate" );
            header ( "Pragma: no-cache" );
            header ( "Content-type: application/vnd.ms-excel" );
            header ( "Content-Disposition: attachment; filename=temp.xls" );
            
            $objWriter = new PHPExcel_Writer_Excel5($xls);
            $objWriter->save('php://output');
            
        }
        
        public function getHeading($sheet, $template) {
            $alphabet = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
            foreach ($template as $key => $field) {
                $letter = $alphabet[$key];
                $cell = $letter.'1';
                $sheet->setCellValue($cell, $field['text']);
                $sheet->getColumnDimension($letter)->setAutoSize(true);
                $sheet
                        ->getStyle($cell.':'.$cell)
                        ->getFill()
                        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB('DDDDDD'); 
                $sheet->getStyle($cell.':'.$cell)->applyFromArray($styleArray);        
                $sheet->getStyle($cell.':'.$cell)->getFont()->setBold(true);
            }
            return $sheet;
        }
        
        public function getTBody($products, $template, $sheet) {
            $alphabet = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
            $replace = array('+', '=', '\\');
            $i = 2;
            foreach ($products as $row) {
                foreach ($template as $key => $field) {
                    $letter = $alphabet[$key];
                    $sheet->getColumnDimension($letter)->setAutoSize(true);
                    $cell = $letter.$i;
                    $sheet->setCellValueExplicit($cell, (string)htmlspecialchars_decode(str_replace(">", "-", str_replace($replace, '***', (string)$row[$field['name']]))), PHPExcel_Cell_DataType::TYPE_STRING);
                    $sheet->getColumnDimension($letter)->setAutoSize(true);
                    if($row['quant']==0){
                        $sheet
                            ->getStyle($cell.':'.$cell)
                            ->getFill()
                            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB('DD6666');
                    }
                    
                    $sheet->getStyle($cell.':'.$cell)->applyFromArray($styleArray);
                }
                ++$i;
            }
            return $sheet;
        }
        
        public function getTrueDate($date_ext, $type) {
            
            list($day, $month, $year_time) = explode(".", $date_ext);
            list($year, $time) = explode(" ", $year_time);
            if($type=='start'){
                $date = $year."-".$month."-".$day." 00:00:00";
            } else {
                $date = $year."-".$month."-".$day." 23:59:59";
            }
            
            return $date;
        }
        
        public function constructFilter($filter_data) {
            $this->load->model('common/excel');
            
            $filter['brand'] = isset($filter_data['brand'])?$this->model_common_excel->getParam('brand',$filter_data['brand']):FALSE;
            $filter['model'] = isset($filter_data['model_id'])?$this->model_common_excel->getParam('brand', $filter_data['model_id']):FALSE;
            $filter['modr'] = isset($filter_data['modelRow_id'])?$this->model_common_excel->getParam('brand', $filter_data['modelRow_id']):FALSE;
            $filter['category'] = isset($filter_data['category_id'])?$this->model_common_excel->getParam('category', $filter_data['category_id']):FALSE;
            $filter['podcat'] = isset($filter_data['podcat_id'])?$this->model_common_excel->getParam('category', $filter_data['podcat_id']):FALSE;
            $filter['prod_on'] = isset($filter_data['prod_on'])?TRUE:FALSE;
            $filter['prod_off'] = isset($filter_data['prod_off'])?TRUE:FALSE;
            $filter['date_start'] = $filter_data['date']!=''?$this->getTrueDate($filter_data['date'], 'start'):FALSE;
            $filter['date_end'] = $filter_data['date1']!=''?$this->getTrueDate($filter_data['date1'], 'end'):FALSE;
            if($this->session->data['uType']!='adm'){
                $filter['manager']="AND manager = '".$this->session->data['username']."' ";
            } else {
                if($filter_data['manager']!='all'){
                    $filter['manager'] = "AND manager = '".$filter_data['manager']."' ";
                } else {
                    $filter['manager'] = "";
                }
            }
            //exit(var_dump($filter_data));
            if(isset($filter_data['stock'])){
                foreach ($filter_data['stock'] as $key => $stock) {
                    $filter['stock'][$key]['still'] = $filter_data['still'][$key]!=""?$filter_data['still'][$key]:FALSE; 
                    $filter['stock'][$key]['jar'] = $filter_data['jar'][$key]===""?FALSE:$filter_data['jar'][$key]; 
                    $filter['stock'][$key]['shelf'] = $filter_data['shelf'][$key]===""?FALSE:$filter_data['shelf'][$key]; 
                    $filter['stock'][$key]['box'] = $filter_data['box'][$key]===""?FALSE:$filter_data['box'][$key]; 
                }
            } else {
                $filter['stock'] = FALSE;
            }
            return $filter;
        }  
        
        public function constructSaleArray($info) {
            $prodinfo = array();
//            exit(var_dump($info));
            $query = $this->db->query("SELECT firstname, lastname FROM ".DB_PREFIX."user WHERE user_id = '".$this->session->data['user_id']."'");
            $manager = $query->row['firstname'].' '.$query->row['lastname'];
            
            foreach ($info['products'] as $vin => $prod) {
                $prodinfo[] = array(
                    'name'      =>  $prod['name'],
                    'vin'       =>  $vin,
                    'city'      =>  $info['city'],
                    'quan'      =>  $prod['quan'],
                    'quanfact'  =>  $prod['quanfact'],
                    'client'    =>  $info['client'],
                    'summ'      =>  $prod['pricefact']*$prod['quanfact'],
                    'location'  =>  $prod['locate'],
                    'saleprice' =>  $prod['pricefact'],
                    'price'     =>  $prod['price'],
                    'reason'    =>  $prod['reason'],
                    'date'      =>  $info['date'],
                    'manager'   =>  $manager
                );
            }
            return $prodinfo;
        }
        
        public function createInvoice($info, $id_invoice) {
            //exit(var_dump($info));
            $this->load->language('common/excelTools');
            $xls = new PHPExcel();
            $xls->setActiveSheetIndex(0);
            $sheet = $xls->getActiveSheet();
            
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
                )
            );
            
            $alignRight = array(
                'alignment' => array(
                    'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_RIGHT,
                    'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
                ),
                'font'  => array(
                    'bold'  => true
                )
            );
            $alignLeft = array(
                'alignment' => array(
                    'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_LEFT,
                    'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
                )
            );
            
            $sheet->getPageSetup()
                ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageSetup()
               ->SetPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
            
            $sheet->getColumnDimension('A')->setWidth(4.21);
            $sheet->getColumnDimension('B')->setWidth(4.21);
            $sheet->getColumnDimension('C')->setWidth(6.38);
            $sheet->getColumnDimension('D')->setWidth(6.38);
            $sheet->getColumnDimension('E')->setWidth(6.38);
            $sheet->getColumnDimension('F')->setWidth(5.83);
            $sheet->getColumnDimension('G')->setWidth(5.83);
            $sheet->getColumnDimension('H')->setWidth(5.83);
            $sheet->getColumnDimension('I')->setWidth(5.83);
            $sheet->getColumnDimension('I')->setWidth(5.83);
            $sheet->getColumnDimension('L')->setWidth(5.83);
            $sheet->getColumnDimension('M')->setWidth(5.83);
            $sheet->getColumnDimension('N')->setWidth(5.83);
            $sheet->getColumnDimension('O')->setWidth(5.83);
            $sheet->getColumnDimension('P')->setWidth(5.83);
            $sheet->getColumnDimension('Q')->setWidth(5.83);
            $sheet->getColumnDimension('R')->setWidth(5.83);
            $sheet->getColumnDimension('S')->setWidth(5.83);
            $sheet->getColumnDimension('T')->setWidth(5.83);
            $sheet->getColumnDimension('U')->setWidth(5.83);
            
            $row = 1;
            $sheet->mergeCells('A'.$row.':U'.$row);
            
            ++$row;
            $sheet->mergeCells('B'.$row.':E'.$row);
            $sheet->mergeCells('F'.$row.':I'.$row);
            $sheet->setCellValue('F'.$row, $this->language->get('number'));
            $sheet->getStyle('F'.$row)->applyFromArray($alignRight);
            $sheet->mergeCells('J'.$row.':L'.$row);
            $sheet->setCellValue('J'.$row, $id_invoice);
            $sheet->setCellValue('M'.$row, $this->language->get('ot'));
            $sheet->mergeCells('N'.$row.':P'.$row);
            $sheet->setCellValue('N'.$row, $info['date']);
            ++$row;
            
            $sheet->mergeCells('Q2:U5');
            
            $sheet->mergeCells('B'.$row.':P'.$row);
            ++$row;
            $sheet->mergeCells('C'.$row.':E'.$row);
            $sheet->setCellValue('C'.$row, $this->language->get('seller'));
            $sheet->getStyle('C'.$row)->applyFromArray($alignRight);
            $sheet->mergeCells('F'.$row.':P'.$row);
            //********************************************************/
            $sheet->getStyle('F'.$row)->applyFromArray($alignLeft);
            ++$row;
            $sheet->mergeCells('C'.$row.':E'.$row);
            $sheet->setCellValue('C'.$row, $this->language->get('buyer'));
            $sheet->getStyle('C'.$row)->applyFromArray($alignRight);
            $sheet->mergeCells('F'.$row.':P'.$row);
            $sheet->setCellValue('F'.$row, $info['client']);
            $sheet->getStyle('F'.$row)->applyFromArray($alignLeft);
            ++$row;
            $sheet->mergeCells('B'.$row.':U'.$row);
            ++$row;
            
            $sheet->setCellValue('B'.$row, $this->language->get('pnum'));
            $sheet->mergeCells('C'.$row.':K'.$row);
            $sheet->setCellValue('C'.$row, $this->language->get('pname'));
            $sheet->mergeCells('L'.$row.':M'.$row);
            $sheet->setCellValue('L'.$row, $this->language->get('pstock'));
            $sheet->mergeCells('N'.$row.':O'.$row);
            $sheet->setCellValue('N'.$row, $this->language->get('vnutn'));
            $sheet->mergeCells('P'.$row.':Q'.$row);
            $sheet->setCellValue('P'.$row, $this->language->get('quant'));
            $sheet->mergeCells('R'.$row.':S'.$row);
            $sheet->setCellValue('R'.$row, $this->language->get('price'));
            $sheet->mergeCells('T'.$row.':U'.$row);
            $sheet->setCellValue('T'.$row, $this->language->get('sum'));
            ++$row;
            
            $pnum = 1;
            $total = 0;
            $qtotal = 0;
            foreach ($info['products'] as $vin => $prod) {
                $summ = $prod['quanfact']*$prod['pricefact'];
                $qtotal += $prod['quanfact']; 
                $sheet->setCellValue('B'.$row, $pnum);
                $sheet->mergeCells('C'.$row.':K'.$row);
                $sheet->setCellValue('C'.$row, $prod['name']);
                $sheet->getStyle("C".$row)->getAlignment()->setWrapText(TRUE);
                $sheet->mergeCells('L'.$row.':M'.$row);
                $sheet->setCellValue('L'.$row, $prod['locate']);
                $sheet->mergeCells('N'.$row.':O'.$row);
                $sheet->setCellValue('N'.$row, $vin);
                $sheet->mergeCells('P'.$row.':Q'.$row);
                $sheet->setCellValue('P'.$row, $prod['quanfact']);
                $sheet->mergeCells('R'.$row.':S'.$row);
                $sheet->setCellValue('R'.$row, $prod['pricefact']);
                $sheet->mergeCells('T'.$row.':U'.$row);
                $sheet->setCellValue('T'.$row, $summ);
                $total+=$summ;
                ++$row;
                ++$pnum;
            }
            $sheet->getStyle('B7:U'.($row-1))->applyFromArray($styleArray);
            
            $sheet->mergeCells('B'.$row.':M'.$row);
            $sheet->mergeCells('N'.$row.':O'.$row);
            $sheet->setCellValue('N'.$row, $this->language->get('qtotal'));
            $sheet->getStyle('N'.$row)->applyFromArray($alignRight);
            $sheet->mergeCells('P'.$row.':Q'.$row);
            $sheet->setCellValue('P'.$row, $qtotal);
            $sheet->getStyle('P'.$row.':Q'.$row)->applyFromArray($styleArray);
            $sheet->mergeCells('T'.$row.':U'.$row);
            $sheet->setCellValue('T'.$row, $total);
            $sheet->getStyle('T'.$row.':U'.$row)->applyFromArray($styleArray);
            ++$row;
            
            $sheet->mergeCells('C'.$row.':E'.$row);
            $sheet->setCellValue('C'.$row, $this->language->get('countnames'));
            $sheet->getStyle('C'.$row)->applyFromArray($alignRight);
            $sheet->mergeCells('F'.$row.':U'.$row);
            $sheet->setCellValue('F'.$row, ($pnum-1));
            $sheet->getStyle('F'.$row)->applyFromArray($alignLeft);
            ++$row;
            $sheet->mergeCells('C'.$row.':E'.$row);
            $sheet->setCellValue('C'.$row, $this->language->get('totalq'));
            $sheet->getStyle('C'.$row)->applyFromArray($alignRight);
            $sheet->mergeCells('F'.$row.':U'.$row);
            $sheet->setCellValue('F'.$row, $qtotal);
            $sheet->getStyle('F'.$row)->applyFromArray($alignLeft);
            ++$row;
            $sheet->mergeCells('C'.$row.':E'.$row);
            $sheet->setCellValue('C'.$row, $this->language->get('totals'));
            $sheet->getStyle('C'.$row)->applyFromArray($alignRight);
            $sheet->mergeCells('F'.$row.':U'.$row);
            $sheet->setCellValue('F'.$row, $total);
            $sheet->getStyle('F'.$row)->applyFromArray($alignLeft);
            ++$row;
            
            $sheet->mergeCells('B'.$row.':U'.($row+1));
            $row+=2;
            
            $sheet->mergeCells('C'.$row.':E'.$row);
            $sheet->setCellValue('C'.$row, $this->language->get('stockman'));
            $sheet->getStyle('C'.$row)->applyFromArray($alignRight);
            $sheet->mergeCells('F'.$row.':J'.$row);
            $sheet->setCellValue('K'.$row, '/');
            $sheet->mergeCells('L'.$row.':N'.$row);
            $sheet->setCellValue('L'.$row, $this->language->get('takeman'));
            $sheet->getStyle('L'.$row)->applyFromArray($alignRight);
            $sheet->mergeCells('O'.$row.':S'.$row);
            $sheet->setCellValue('T'.$row, '/');
            
            /*******************************************************************/
            
            /*******************************************************************/
            header ( "Expires: " . gmdate("D,d M YH:i:s") . " GMT" );
            header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
            header ( "Cache-Control: no-cache, must-revalidate" );
            header ( "Pragma: no-cache" );
            header ( "Content-type: application/vnd.ms-excel" );
            header ( "Content-Disposition: attachment; filename=invoice_".$id_invoice.".xls" );
            
            $objWriter = new PHPExcel_Writer_Excel5($xls);
            $objWriter->save('php://output');
            $this->response->redirect($this->url->link('common/addprod', 'token=' . $this->session->data['token'], true));
        }
    }