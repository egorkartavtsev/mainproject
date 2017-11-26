<?php
class ControllerCommonExcelOld extends Controller {
private $error = array();
	
   public function index() {
        
        $data = $this->getLayout();
        
        /*****************************************************************/
        
        //берём категории
        $query = $this->db->query("SELECT "
                . "c.category_id AS id, "
                . "cd.name AS name "
                . "FROM ".DB_PREFIX."category c "
                . "LEFT JOIN ".DB_PREFIX."category_description cd "
                    . "ON (cd.language_id=1 AND cd.category_id = c.category_id) "
                . "WHERE c.parent_id = 0");
        
        $results = $query->rows;
        $data['category'] = array();
        foreach ($results as $res) {
                $data['category'][] = array(
                    'name' => $res['name'],
                    'val'  => $res['id']
                );
        }
        
        //берём марки
        $query = $this->db->query("SELECT id, name FROM ".DB_PREFIX."brand "
                                . "WHERE parent_id = 0");
                        
        $brands = $query->rows;
        $data['brands'] = array();
        foreach ($brands as $res) {
            $data['brands'][] = array(
            'name' => $res['name'],
            'val'  => $res['id']
            );
        }
        
        //Берём склады
        $query = $this->db->query("SELECT name FROM ".DB_PREFIX."stocks WHERE 1");
        $stocks = $query->rows;
        foreach ($stocks as $stock) {
            $data['stocks'][] = $stock['name'];
        }
        
            
        /*****************************************************************/
        
        $data['results_ex'] = $this->getDBFiles();        
        $data['token_excel'] = $this->session->data['token'];
        $this->response->setOutput($this->load->view('common/excel', $data));
    }
    
    public function applyFilter() {
        
        $this->load->model('common/excel');
        $filter_data = $this->request->post;
        $filter = array();
        //exit(var_dump($filter_data));
        
        if(empty($filter_data)){
            $this->downloadFile();
        } else {
            
            if(isset($filter_data['brand'])){
                $filter['brand'] = $filter_data['brand'];
            } else {
                $filter['brand'] = '';
            }
            if(isset($filter_data['model_id'])){
                $filter['mod'] = $filter_data['model_id'];
            } else {
                $filter['mod'] = '';
            }
            if(isset($filter_data['modelRow_id'])){
                $filter['mr'] = $filter_data['modelRow_id'];
            } else {
                $filter['mr'] = '';
            }
            if(isset($filter_data['category_id'])){
                $filter['cat'] = $filter_data['category_id'];
            } else {
                $filter['cat'] = '';
            }
            if(isset($filter_data['podcat_id'])){
                $filter['podcat'] = $filter_data['podcat_id'];
            } else {
                $filter['podcat'] = '';
            }
            if(isset($filter_data['stock'])){
                foreach($filter_data['stock'] as $stock){
                    $filter['stock_info'][$stock]['stock'] = $stock;
                    $filter['stock_info'][$stock]['still'] = $filter_data['still'][$stock];
                    $filter['stock_info'][$stock]['jar'] = $filter_data['jar'][$stock];
                    $filter['stock_info'][$stock]['shelf'] = $filter_data['shelf'][$stock];
                    $filter['stock_info'][$stock]['box'] = $filter_data['box'][$stock];
                }
            } else {
                $filter['stock_info'] = '';
            }
            if(isset($filter_data['prod_on'])){
                $filter['prodquan'] = 1;
                if(isset($filter_data['prod_off'])){
                    $filter['prodquan'] = -1;
                }
            }else{
                if(isset($filter_data['prod_off'])){
                    $filter['prodquan'] = 0;
                } else {
                    $filter['prodquan'] = -1;
                }
            }
            $products = $this->model_common_excel->getProds($filter);
        }
        //exit(var_dump($products));
        $this->downloadFile($products); 
    }
    
    
    public function upload(){
            $data = $this->getLayout();
            if (!empty($_FILES)){
            
                $uploaddir = DIR_SITE . "/uploadeXcelfiles/";
                
                $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
                    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                        $data['success_upload'] = "Файл ".$_FILES['userfile']['name']." успешно загружен на сервер, обработан. Товары занесены в базу данных.";
                        $this->db->query("INSERT INTO " . DB_PREFIX . "eXcel_files "
                            . "SET name = '" . $this->db->escape($_FILES['userfile']['name']) . "', "
                                . "going = '1', "
                                . "timedate = NOW()");                 
                    } else {
                        $errors['text'] = 'Ошибка загрузки файла. Попробуйте загррузить его снова или обратитесь к аддминистратору.';
                    }
            }
            else {
                $errors['text'] = 'Файл для зугрузки не выбран';
            }
            $upload_err = $this->readFileXLS($uploadfile);
            if (isset($errors)) {$data['broken'] = $errors['text'];}
            
            foreach ($upload_err as $error){
                $data['uper'][] = $error[0];
            }
            $data['uper']['match'] = $upload_err['match'];
            //exit(var_dump($data['uper']));
            $data['results_ex'] = $this->getDBFiles();
            $data['token_excel'] = $this->session->data['token'];
            $this->response->setOutput($this->load->view('common/excel', $data));
    }
    
    public function downloadFile($products = 0){
        
        $data = $this->getLayout();
                
            $this->createFile($products);
            
            $data['results_ex'] = $this->getDBFiles();
            $data['token_excel'] = $this->session->data['token'];
            $this->response->setOutput($this->load->view('common/excel', $data));    
    }
    
    public function getDBFiles() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "eXcel_files ");
                foreach ($query as $key){
                    $dirt_res[] = $key;
                }
                $data = $dirt_res['2'];
                return $data;
    }
    
    public function downloadTemplate() {
        $this->load->language('common/excel');
        
        $xls = new PHPExcel();
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $sheet->setTitle($this->language->get('sheet_prod'));
        //compose head of table
        $sheet->setCellValue("A1", $this->language->get('avito'));
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->setCellValue("B1", $this->language->get('drom'));
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->setCellValue("C1", $this->language->get('brand')."*");
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->setCellValue("D1", $this->language->get('model')."*");
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->setCellValue("E1", $this->language->get('mod_row')."*");
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->setCellValue("F1", $this->language->get('cat')."*");
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->setCellValue("G1", $this->language->get('podcat')."*");
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->setCellValue("H1", $this->language->get('name'));
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->setCellValue("I1", $this->language->get('vin')."*");
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->setCellValue("J1", $this->language->get('cond'));
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->setCellValue("K1", $this->language->get('type'));
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->setCellValue("L1", $this->language->get('note'));
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->setCellValue("M1", $this->language->get('cat_numb'));
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->setCellValue("N1", $this->language->get('comp'));
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->setCellValue("O1", $this->language->get('stock'));
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->setCellValue("P1", $this->language->get('still'));
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->setCellValue("Q1", $this->language->get('jar'));
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->setCellValue("R1", $this->language->get('shelf'));
        $sheet->getColumnDimension('R')->setAutoSize(true);
        $sheet->setCellValue("S1", $this->language->get('box'));
        $sheet->getColumnDimension('S')->setAutoSize(true);
        $sheet->setCellValue("T1", $this->language->get('price'));
        $sheet->getColumnDimension('T')->setAutoSize(true);
        $sheet->setCellValue("U1", $this->language->get('quan'));
        $sheet->getColumnDimension('U')->setAutoSize(true);
        $sheet->setCellValue("V1", $this->language->get('c_price'));
        $sheet->getColumnDimension('V')->setAutoSize(true);
        $sheet->setCellValue("W1", $this->language->get('c_heading'));
        $sheet->getColumnDimension('W')->setAutoSize(true);
        $sheet->setCellValue("X1", $this->language->get('c_whole'));
        $sheet->getColumnDimension('X')->setAutoSize(true);
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $sheet
                ->getStyle('A1:X1')
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('BBBBBB'); 
        $sheet->getStyle('A1:X1')->applyFromArray($styleArray);        
        $sheet->getStyle('A1:X1')->getFont()->setBold(true);
        // Р’С‹РІРѕРґРёРј HTTP-Р·Р°РіРѕР»РѕРІРєРё
        header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
        header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
        header ( "Cache-Control: no-cache, must-revalidate" );
        header ( "Pragma: no-cache" );
        header ( "Content-type: application/vnd.ms-excel" );
        header ( "Content-Disposition: attachment; filename=temp.xls" );
        $objWriter = new PHPExcel_Writer_Excel5($xls);
        $objWriter->save('php://output');
    }
    
    public function createFile($products = 0){
        
        $this->load->language('common/excel');
        $this->load->model('common/excel');
        if($products == 0){
            $prods = $this->model_common_excel->getallprods();
        } else {
            $prods = $products;
        }
        
        $xls = new PHPExcel();
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $sheet->setTitle($this->language->get('sheet_prod'));
        //compose head of table
        $sheet->setCellValue("A1", $this->language->get('avito'));
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->setCellValue("B1", $this->language->get('drom'));
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->setCellValue("C1", $this->language->get('brand'));
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->setCellValue("D1", $this->language->get('model'));
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->setCellValue("E1", $this->language->get('mod_row'));
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->setCellValue("F1", $this->language->get('cat'));
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->setCellValue("G1", $this->language->get('podcat'));
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->setCellValue("H1", $this->language->get('name'));
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->setCellValue("I1", $this->language->get('vin'));
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->setCellValue("J1", $this->language->get('cond'));
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->setCellValue("K1", $this->language->get('type'));
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->setCellValue("L1", $this->language->get('note'));
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->setCellValue("M1", $this->language->get('cat_numb'));
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->setCellValue("N1", $this->language->get('comp'));
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->setCellValue("O1", $this->language->get('stock'));
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->setCellValue("P1", $this->language->get('still'));
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->setCellValue("Q1", $this->language->get('jar'));
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->setCellValue("R1", $this->language->get('shelf'));
        $sheet->getColumnDimension('R')->setAutoSize(true);
        $sheet->setCellValue("S1", $this->language->get('box'));
        $sheet->getColumnDimension('S')->setAutoSize(true);
        $sheet->setCellValue("T1", $this->language->get('price'));
        $sheet->getColumnDimension('T')->setAutoSize(true);
        $sheet->setCellValue("U1", $this->language->get('quan'));
        $sheet->getColumnDimension('U')->setAutoSize(true);
        $sheet->setCellValue("V1", $this->language->get('c_price'));
        $sheet->getColumnDimension('V')->setAutoSize(true);
        $sheet->setCellValue("W1", $this->language->get('c_heading'));
        $sheet->getColumnDimension('W')->setAutoSize(true);
        $sheet->setCellValue("X1", $this->language->get('c_whole'));
        $sheet->getColumnDimension('X')->setAutoSize(true);
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $sheet
                ->getStyle('A1:X1')
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('BBBBBB'); 
        $sheet->getStyle('A1:X1')->applyFromArray($styleArray);        
        $sheet->getStyle('A1:X1')->getFont()->setBold(true);        
        //fill rows of table
        $i = 2;
        foreach ($prods as $row){
            
            $location = str_replace(",", "/", $row['locate']);
            //echo $row['pid'].' - '.$row['vin'].' - '.$location.'<br>';
            list($still, $jar, $shelf, $box,) = explode("/", $location);
            //$loc = "*".$still."-".$jar."-".$shelf."-".$box;
            //exit($loc);
            
            
            $sheet->setCellValue("A".$i, $row['avito']);
            $sheet->setCellValue("B".$i, $row['drom']);
            $sheet->setCellValue("C".$i, $row['brand']);
            $sheet->setCellValue("D".$i, $row['model']);
            $sheet->setCellValue("E".$i, $row['mod_row']);
            $sheet->setCellValue("F".$i, $row['cat']);
            $sheet->setCellValue("G".$i, $row['podcat']);
            $sheet->setCellValue("H".$i, $row['name']);
            $sheet->setCellValue("I".$i, $row['vin']);
            $sheet->setCellValue("J".$i, $row['cond']);
            $sheet->setCellValue("K".$i, $row['type']);
            $sheet->setCellValue("L".$i, $row['note']);
            $sheet->setCellValue("M".$i, $row['cat_numb']);
            $sheet->setCellValue("N".$i, $row['comp']);
            $sheet->setCellValue("O".$i, $row['stock']);
            $sheet->setCellValue("P".$i, $still);
            $sheet->setCellValue("Q".$i, $jar);
            $sheet->setCellValue("R".$i, $shelf);
            $sheet->setCellValue("S".$i, $box);
            $sheet->setCellValue("T".$i, $row['price']);
            $sheet->setCellValue("U".$i, $row['quan']);
            $sheet->setCellValue("V".$i, $row['c_price']);
            $sheet->setCellValue("W".$i, $row['comp']);
            $sheet->setCellValue("X".$i, $row['c_whole']);
            
            $color = 'FFFFFF';
            if($row['quan']==0){
                $color = 'FF9999';
            }
            $sheet->getStyle('A'.$i.':X'.$i)->applyFromArray($styleArray);
            $sheet
                ->getStyle('A'.$i.':X'.$i)
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB($color);
            ++$i;
        }
        //exit();
        // Р’С‹РІРѕРґРёРј HTTP-Р·Р°РіРѕР»РѕРІРєРё
        header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
        header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
        header ( "Cache-Control: no-cache, must-revalidate" );
        header ( "Pragma: no-cache" );
        header ( "Content-type: application/vnd.ms-excel" );
        header ( "Content-Disposition: attachment; filename=".$this->language->get('sheet_prod').".xls" );
        $objWriter = new PHPExcel_Writer_Excel5($xls);
        $objWriter->save('php://output');
        
        $this->index();
        //exit();
    }
    
    public function getLayout() {
        
        
                $this->load->language('common/excel');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/excel', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('common/excel', 'token=' . $this->session->data['token'], true)
		);
                $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                
                return $data;
        
    }
    
    public function readFileXLS($file) {
        $upload_err = array();
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $objPHPExcel->setActiveSheetIndex(0);
        $aSheet = $objPHPExcel->getActiveSheet();
        
        $array = array();
        foreach($aSheet->getRowIterator() as $row){
          $cellIterator = $row->getCellIterator();
          $item = array();
          foreach($cellIterator as $cell){
            array_push($item, $cell->getCalculatedValue());
          }
          array_push($array, $item);
        }
        
        $countR = count($array);
        
        $all_prods_arr = $this->db->query("SELECT sku FROM ".DB_PREFIX."product WHERE 1");
        foreach ($all_prods_arr->rows as $prod) {
            $all_p[] = $prod['sku'];
        }
        
        $match = 0;
        for ($i = 1; $i < $countR; $i++){
            if($array[$i][8]!=NULL){
                $skip = false;
                $vnutr = str_replace("/", "-", $array[$i][8]);
                if(in_array($vnutr, $all_p)){
                    $skip = true;
                    $match+= 1;
                }
                if(!$skip){
                    $upload_err[] = $this->ProdToDB($array[$i]);
                }
            } 
        }
        $upload_err['match'] = $match;
        return $upload_err;  
    }
    
    public function ProdToDB($data) {
        $this->load->language('common/excel');
        $this->load->model('common/excel');
        
        $upload_errs = array();
        $vin = str_replace("/", "-", $data[8]);
        $data['vin'] = $vin;
        
        $res = $this->model_common_excel->allowed($data);
        //exit(var_dump($res));
        
        $upload_errs[] = $res['errs'];
        $sup = $res['sup'];
        $comp = $res['comp'];
        $allow = $res['allow'];
        $direct = DIR_IMAGE.'catalog/demo/production/'.$vin;    
        $suc = file_exists($direct);
        $image = '';
        $files1 = array();
        if ($suc){
            $files = scandir($direct);
            $con = count($files);
            for($i = 2; $i < $con-1; $i++){
                $files1[] = $files[$i];
            }
            if($con != 0){
                $image = 'catalog/demo/production/'.$vin."/".$files1[0];
            }
            else{
                $image = ' ';
                $upload_errs[] = 'Предупреждение! Внутренний номер - <b>'.$vin.'</b> Отсутствуют фото в папке товара. Загрузите фотографии.';
            }
        }
        else {
            //$upload_errs[] = $this->language->get('err').'Внутренний номер - <b>'.$vin.'</b> Отсутствует папка с фото товара.';
            $image = ' ';
        }
        
        if ($allow) {
            $this->model_common_excel->settodb($data, $files1, $image, $sup, $comp);
        }    
        //exit(var_dump($upload_errs));    
        return $upload_errs;
    }
    
    
    /**************************************************************************/
    
    
    public function synch() {
        $data = $this->getLayout();
            if (!empty($_FILES)){
            
                $uploaddir = DIR_SITE . "uploadeXcelfiles/";
                
                $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
                    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                        $data['success_upload'] = "Файл ".$_FILES['userfile']['name']." успешно загружен на сервер, обработан. Товары синхронизированы.";
                        $this->db->query("INSERT INTO " . DB_PREFIX . "eXcel_files "
                            . "SET name = '" . $this->db->escape($_FILES['userfile']['name']) . "', "
                                . "going = 'synch', "
                                . "timedate = NOW()");                 
                    } else {
                        $errors['text'] = 'Загрузка не прошла';
                    }
            }
            else {
                $errors['text'] = 'Файл для загрузки не выбран';
            }
            
            $this->readFileSynch($uploadfile);
            
            if (isset($errors)) {$data['broken'] = $errors['text'];}
            $data['results_ex'] = $this->getDBFiles();
            $data['token_excel'] = $this->session->data['token'];
            $this->response->setOutput($this->load->view('common/excel', $data));
    }
    
    public function readFileSynch($file) {
        
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $objPHPExcel->setActiveSheetIndex(0);
        $aSheet = $objPHPExcel->getActiveSheet();
        
        $array = array();
        foreach($aSheet->getRowIterator() as $row){
          $cellIterator = $row->getCellIterator();
          $item = array();
          foreach($cellIterator as $cell){
            array_push($item, $cell->getCalculatedValue()/*iconv('utf-8', 'cp1251', $cell->getCalculatedValue())*/);
          }
          array_push($array, $item);
        }
        
       //exit(var_dump($array));
        
        $countR = count($array);
        
        $all_prods_arr = $this->db->query("SELECT sku FROM ".DB_PREFIX."product WHERE 1");
        
        $all_p = $all_prods_arr->rows;
        
        //exit(var_dump($all_p));
        
        for ($i = 1; $i < $countR; $i++){
            
            if($array[$i][3]!=NULL){
                $skip = false;
                $vnutr = str_replace("/", "-", $array[$i][9]);
                $this->synchProd($array[$i], $all_p);
            } 
        }
        //exit('end');
        //return $array;
    }
    
    public function synchProd($data, $prods) {
        
        //exit(var_dump($data));
        
        foreach($prods as $vin){
            if (($vin['sku'] == $data[9]) && ($data[22] == 0)){
                $dir = DIR_IMAGE."catalog/demo/production/".$vin['sku'];
                $this->removeDirectory($dir);
                $this->db->query("UPDATE ".DB_PREFIX."product "
                        . "SET quantity = ".$data[22].", "
                            . "status = 0 "
                        . "WHERE sku = '".$vin['sku']."';");
                
            $prod = $this->db->query("SELECT product_id FROM ".DB_PREFIX."product WHERE sku = '".$vin['sku']."'");
            $prod_id = $prod->row['product_id'];
            //echo(var_dump($prod_id));
            
            $this->db->query("DELETE FROM ".DB_PREFIX."product_image "
                    . "WHERE product_id = '".$prod_id."'");
            }
        }
    }
    
    public function clearPhotos(){
        $data = $this->getLayout();
        $data['token_excel'] = $this->session->data['token'];
        $pd_list = array();
        $dirs = DIR_IMAGE . 'catalog/demo/production/';
        $pd_arr = scandir($dirs);
		//exit(var_dump($pd_arr));
        //??????я так захотел
        $fuck = array_shift($pd_arr);
        $fuck = array_shift($pd_arr);
        //exit(var_dump($pd_list));
        
		foreach($pd_arr as $pd){
                    if(is_dir(DIR_IMAGE . 'catalog/demo/production/'.$pd)) {
                            $pd_list[] = $pd;
                    }
		}
		
        $all_prods_arr = $this->db->query("SELECT sku FROM ".DB_PREFIX."product WHERE 1");
        $all_p = $all_prods_arr->rows;
        
        foreach($pd_list as $dir){
            $res = 0;
            foreach ($all_p as $vin){
                if ($dir == $vin['sku']){
                    $res += 1;                    
                }
            }
            if ($res==0){
                $rd = DIR_IMAGE."catalog/demo/production/".$dir."/";
                $this->removeDirectory($rd);
            }
        }
        
        $data['success_upload'] = "Фотографии очищены успешно.";
        $this->response->setOutput($this->load->view('common/excel', $data));
    }
    
    function removeDirectory($dir) {
		
                $objs = scandir($dir);
		//??????я так захотел
                $fuck = array_shift($objs);
                $fuck = array_shift($objs);
        
		
		foreach($objs as $obj) {
                    $objct = $dir;
                    $objct.= $obj;
                    unlink($objct);
                }
		rmdir($dir);
    }
    
    public function PhotToProd() {
        
        $data = $this->getLayout();
        $dirs = DIR_IMAGE . 'catalog/demo/production/';
        $pd_list = scandir($dirs);
        
        //??????я так захотел!!!!!!
        $fuck = array_shift($pd_list);
        $fuck = array_shift($pd_list);
        //exit(var_dump($pd_list));
        
        $all_prods_arr = $this->db->query("SELECT sku, product_id AS id, image AS image FROM ".DB_PREFIX."product WHERE 1");
        $all_p = $all_prods_arr->rows;
        
        foreach ($all_p as $prod) {
            $res = '';
            $files1 = '';
            if(($prod['image']!='')&&(!file_exists(DIR_IMAGE."".$prod['image']))){
                $this->db->query("UPDATE ".DB_PREFIX."product SET image = '' WHERE product_id = '".$prod['id']."' ");
                $this->db->query("DELETE FROM ".DB_PREFIX."product_image WHERE product_id = '".$prod['id']."' ");
            }
            foreach ($pd_list as $dir) {
                
                if ($dir == $prod['sku']){
                    $res = $this->db->query("SELECT * FROM ".DB_PREFIX."product_image WHERE product_id = ".$prod['id']." ");
                    $res = $res->rows;
                    if(empty($res)){
			$files1 = array();
                        $dir1 = DIR_IMAGE . 'catalog/demo/production/'.$prod['sku'];
                        $dir2 = DIR_IMAGE . 'catalog/demo/production/'.$prod['sku'].'/';
                        $files = scandir($dir2);
                        $con = count($files);
                        if($con>1){
                            for($i = 1; $i < $con-1; $i++){
                                if ($files[$i] != '..'){
                                    $files1[] = $files[$i];
                                }
                            }
                            $image = '';
                            if(!empty($files1)){
                                $image = "catalog/demo/production/".$prod['sku']."/".$files1[0];
                            }
                            foreach ($files1 as $file) {
                                $this->db->query("INSERT INTO ".DB_PREFIX."product_image "
                                        . "(`product_id`, `image`, `sort_order`) "
                                        . "VALUES (".$prod['id'].", 'catalog/demo/production/".$prod['sku']."/".$file."', 0)");
                            }
                            $this->db->query("UPDATE ".DB_PREFIX."product "
                                    . "SET image = '".$image."' "
                                    . "WHERE product_id = '".$prod['id']."' ");
                        }
                    } 
                }
            }
            
        }
	$data['success_upload'] = "Фотографии успешно привязаны к товарам.";
        $data['token_excel'] = $this->session->data['token'];
        $this->response->setOutput($this->load->view('common/excel', $data));
    }
}
