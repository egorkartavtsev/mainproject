<?php
class ControllerCommonExcel extends Controller {
private $error = array();
	
   public function index() {
        
            $data = $this->getLayout();

        $data['results_ex'] = $this->getDBFiles();        
        $data['token_excel'] = $this->session->data['token'];
        $this->response->setOutput($this->load->view('common/excel', $data));
    }
    
/*********************************Р—Р°РіСЂСѓР·РєР° С„Р°Р№Р»Р° РЅР° СЃРµСЂРІРµСЂ******************************************************/
    
    public function upload(){
           $data = $this->getLayout();
/*****************Р—Р°РіСЂСѓР¶Р°РµРј, РїСЂРѕРІРµСЂСЏРµРј, РїРѕР»СѓС‡РµРЅРЅС‹Р№ С„Р°Р№Р»********************************/    
            if (!empty($_FILES)){
            
                $uploaddir = DIR_SITE . "/uploadeXcelfiles/";
                
                $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
                //РїСЂРѕРІРµСЂРєР° РЅР° СЃСѓС‰РµСЃС‚РІРѕРІР°РЅРёРµ С„Р°Р№Р»Р°
                    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                        $data['success_upload'] = "Файл ".$_FILES['userfile']['name']." успешно загружен на сервер, обработан. Товары занесены в базу данных.";
                        $this->db->query("INSERT INTO " . DB_PREFIX . "eXcel_files "
                            . "SET name = '" . $this->db->escape($_FILES['userfile']['name']) . "', "
                                . "going = '1', "
                                . "timedate = NOW()");
    /*******************************************************************************************************/                    
                    } else {
                        $errors['text'] = 'РћС€РёР±РєР° Р·Р°РіСЂСѓР·РєРё С„Р°Р№Р»Р°';
                    }
    /*******************************************************************************************************/
            }
            else {
                $errors['text'] = 'Р¤Р°Р№Р» РЅРµ РІС‹Р±СЂР°РЅ Р»РёР±Рѕ СѓР¶Рµ СЃСѓС‰РµСЃС‚РІСѓРµС‚';
            }
        $this->readFileXLS($uploadfile);
        if (isset($errors)) {$data['broken'] = $errors['text'];}
        $data['results_ex'] = $this->getDBFiles();
        $data['token_excel'] = $this->session->data['token'];
        $this->response->setOutput($this->load->view('common/excel', $data));
    }
    
    public function downloadFile(){
        
        $data = $this->getLayout();
                
            $this->createFile();
            
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
    
    public function createFile(){
        // РЎРѕР·РґР°РµРј РѕР±СЉРµРєС‚ РєР»Р°СЃСЃР° PHPExcel
        $xls = new PHPExcel();
        // РЈСЃС‚Р°РЅР°РІР»РёРІР°РµРј РёРЅРґРµРєСЃ Р°РєС‚РёРІРЅРѕРіРѕ Р»РёСЃС‚Р°
        $xls->setActiveSheetIndex(0);
        // РџРѕР»СѓС‡Р°РµРј Р°РєС‚РёРІРЅС‹Р№ Р»РёСЃС‚
        $sheet = $xls->getActiveSheet();
        // РџРѕРґРїРёСЃС‹РІР°РµРј Р»РёСЃС‚
        $sheet->setTitle('РўР°Р±Р»РёС†Р° СѓРјРЅРѕР¶РµРЅРёСЏ');
        // Р’СЃС‚Р°РІР»СЏРµРј С‚РµРєСЃС‚ РІ СЏС‡РµР№РєСѓ A1
        $sheet->setCellValue("A1", 'РўР°Р±Р»РёС†Р° СѓРјРЅРѕР¶РµРЅРёСЏ');
        $sheet->getStyle('A1')->getFill()->setFillType(
            PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('A1')->getFill()->getStartColor()->setRGB('EEEEEE');
        // РћР±СЉРµРґРёРЅСЏРµРј СЏС‡РµР№РєРё
        $sheet->mergeCells('A1:H1');
        // Р’С‹СЂР°РІРЅРёРІР°РЅРёРµ С‚РµРєСЃС‚Р°
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(
            PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        for ($i = 2; $i < 10; $i++) {
            for ($j = 2; $j < 10; $j++) {
                // Р’С‹РІРѕРґРёРј С‚Р°Р±Р»РёС†Сѓ СѓРјРЅРѕР¶РµРЅРёСЏ
                $sheet->setCellValueByColumnAndRow(
                                                  $i - 2,
                                                  $j,
                                                  $i . "x" .$j . "=" . ($i*$j));
                // РџСЂРёРјРµРЅСЏРµРј РІС‹СЂР°РІРЅРёРІР°РЅРёРµ
                $sheet->getStyleByColumnAndRow($i - 2, $j)->getAlignment()->
                        setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }
        }
        // Р’С‹РІРѕРґРёРј HTTP-Р·Р°РіРѕР»РѕРІРєРё
        header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
        header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
        header ( "Cache-Control: no-cache, must-revalidate" );
        header ( "Pragma: no-cache" );
        header ( "Content-type: application/vnd.ms-excel" );
        header ( "Content-Disposition: attachment; filename=matrix.xls" );
        $objWriter = new PHPExcel_Writer_Excel5($xls);
        $objWriter->save('php://output');
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
    
    public function getAllProducts() {
        
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "eXcel_files ");
        
        
        foreach ($query as $key){
                    $dirt_res[] = $key;
                }
        
    }
    
    /*
     * СЃР»РµРґСѓСЋС‰Р°СЏ С„СѓРЅРєС†РёСЏ С‡РёС‚Р°РµС‚ С„Р°Р№Р» excel
     * Рё РІРѕР·РІСЂР°С‰Р°РµС‚ РјР°СЃСЃРёРІ РѕС‚СЂР°Р¶Р°СЋС‰РёР№
     * СЃС‚СЂСѓРєС‚СѓСЂСѓ Р·Р°РіСЂСѓР¶Р°РµРјРѕРіРѕ С„Р°Р№Р»Р°.
     * РЎР°Рј С„Р°Р№Р» РїРµСЂРµРґР°С‘С‚СЃСЏ РІ С„СѓРЅРєС†РёСЋ, РєР°Рє Р°СЂРіСѓРјРµРЅС‚.
     */
    
    public function readFileXLS($file) {
        
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $objPHPExcel->setActiveSheetIndex(0);
        $aSheet = $objPHPExcel->getActiveSheet();
        
        $array = array();
        //РїРѕР»СѓС‡РёРј РёС‚РµСЂР°С‚РѕСЂ СЃС‚СЂРѕРєРё Рё РїСЂРѕР№РґРµРјСЃСЏ РїРѕ РЅРµРјСѓ С†РёРєР»РѕРј
        foreach($aSheet->getRowIterator() as $row){
          //РїРѕР»СѓС‡РёРј РёС‚РµСЂР°С‚РѕСЂ СЏС‡РµРµРє С‚РµРєСѓС‰РµР№ СЃС‚СЂРѕРєРё
          $cellIterator = $row->getCellIterator();
          //РїСЂРѕР№РґРµРјСЃСЏ С†РёРєР»РѕРј РїРѕ СЏС‡РµР№РєР°Рј СЃС‚СЂРѕРєРё
          //СЌС‚РѕС‚ РјР°СЃСЃРёРІ Р±СѓРґРµС‚ СЃРѕРґРµСЂР¶Р°С‚СЊ Р·РЅР°С‡РµРЅРёСЏ РєР°Р¶РґРѕР№ РѕС‚РґРµР»СЊРЅРѕР№ СЃС‚СЂРѕРєРё
          $item = array();
          foreach($cellIterator as $cell){
            //Р·Р°РЅРѕСЃРёРј Р·РЅР°С‡РµРЅРёСЏ СЏС‡РµРµРє РѕРґРЅРѕР№ СЃС‚СЂРѕРєРё РІ РѕС‚РґРµР»СЊРЅС‹Р№ РјР°СЃСЃРёРІ
            array_push($item, $cell->getCalculatedValue()/*iconv('utf-8', 'cp1251', $cell->getCalculatedValue())*/);
          }
          //Р·Р°РЅРѕСЃРёРј РјР°СЃСЃРёРІ СЃРѕ Р·РЅР°С‡РµРЅРёСЏРјРё СЏС‡РµРµРє РѕС‚РґРµР»СЊРЅРѕР№ СЃС‚СЂРѕРєРё РІ "РѕР±С‰РёР№ РјР°СЃСЃРІ СЃС‚СЂРѕРє"
          array_push($array, $item);
        }
        
        //РґР°Р»РµРµ РїСЂРёРєР»Р°РґС‹РІР°РµРј С€Р°Р±Р»РѕРЅ Р·Р°РіСЂСѓР·РєРё С‚Р°Р±Р»РёС†С‹ РІ С‚Р°Р±Р»РёС†Сѓ С‚РѕРІР°СЂРѕРІ Р±Р°Р·С‹ РґР°РЅРЅС‹С…
        
        //exit(var_dump($array));
        
        $countR = count($array);
        
        $all_prods_arr = $this->db->query("SELECT sku FROM ".DB_PREFIX."product WHERE 1");
        
        $all_p = $all_prods_arr->rows;
        
        //exit(var_dump($all_p));
        
        for ($i = 1; $i < $countR; $i++){
            
            if($array[$i][0]!=NULL){
                $skip = false;
                $vnutr = str_replace("/", "-", $array[$i][6]);
                foreach ($all_p as $vin){
                    
                    if ($vnutr == $vin['sku']) {
                        $skip = true;
                    }
                }
                
                if(!$skip) {
                    $this->ProdToDB($array[$i]);
                };
            } 
        }
        
        
        //return $array;
        
        
    }
    
    public function ProdToDB($data) {
        
        if($data[17]!=NULL){
            $image = 'catalog/demo/production/'.$data[17];
        } 
        $vin = str_replace("/", "-", $data[6]);
        
        $imgdir = DIR_IMAGE . 'catalog/demo/production/';
        $dirs = scandir($imgdir);
        $suc = false;
        
        foreach ($dirs as $dir){
            if ($dir == $vin) {
                $suc = true;
            } 
        }
        
        if ($suc){
            $dir = DIR_IMAGE . 'catalog/demo/production/'.$vin;
            $files = scandir($dir);
            $con = count($files);
            for($i = 2; $i < $con-1; $i++){
                $files1[] = $files[$i];
            }
            $image = 'catalog/demo/production/'.$vin."/".$files1[1];
        }
        else {
            $image = '';
        }
                $name = $data[4] . " ". $data[0] ." ". $data[2];
                $tag = $data[0].', '.$data[1].', '.$data[2].', '.$data[4].', '.$name;
                /*Авторазбор174.рф предлагает Вам приобрести 
                 * [Товар] для автомобиля 
                 * [Марка Модель Модельный ряд] со склада в г.Магнитогорске. 
                 *  Авторазбор автозапчасти б/у 
                 * для [Марка / Модель / Модельный ряд]
                 */                
                $description = "<h4>Авторазбор174.рф</h6> предлагает Вам приобрести "
                                .$name." для автомобиля "
                                .$data[0]." ".$data[2]." со склада в г.Магнитогорске. " 
                                ."Авторазбор автозапчасти б/у для ".$data[0]." ".$data[1]." ".$data[2];
                
                if($data[11]!=NULL) {$description.= "<h6><b>Применимость:</b></h6>".$data[11].";<br/>";}
                
                
                if($data[9]!=NULL) {$description.="<h6><b>Примечание:</b></h6>".$data[9]."<br/>";}
                
                $brand_arr = array();
                
                $query1 = $this->db->query("SELECT id AS manufacturer_id FROM ".DB_PREFIX."brand "
                         . "WHERE name = '".$data[0]."'");
                $brand_arr['man'] = $query1->row['manufacturer_id'];
                
                $query1 = $this->db->query("SELECT id AS model_id FROM ".DB_PREFIX."brand "
                        . "WHERE name = '".$data[1]."'");
                $brand_arr['mod'] = $query1->row['model_id'];
                
                $trimMR = trim($data[2]);
                $query1 = $this->db->query("SELECT id FROM ".DB_PREFIX."brand "
                        . "WHERE name = '".$trimMR."'");
                //exit(var_dump($query1));
                $brand_arr['mr'] = $query1->row['id'];
                
                $query = $this->db->query("SELECT id FROM ".DB_PREFIX."brand "
                        . "WHERE name = '".$data[0]."'");
                
                if ($data[18] != NULL) {
                    $price = $data[18];
                }
                else {
                    $price = 0;
                }
                //exit(var_dump($query));
                $this->db->query("INSERT INTO ".DB_PREFIX."product "
                        . "SET "
                        . "`manufacturer_id` = '". $query->row['id'] ."', "
                        . "`model` = '". $data[1] ."', "
                        . "`jan` = '". $data[9] ."', "
                        . "`sku` = '". $vin ."', "
                        . "`upc` = '". $data[7] ."', "
                        . "`ean` = '". $data[8] ."', "
                        . "`location` = '".$data[13].",".$data[14].",".$data[15].",".$data[16]."', "
                        . "`isbn` = '". $data[10] ."', "
                        . "`mpn` = '". $data[11] ."', "
                        . "`weight` = '". $data[12] ."', "
                        . "`price` = ". $price .", "
                        . "`image` = '". $image ."', "
                        . "`quantity` = ".$data[19].", "
                        . "`length` = '".$data[2]."', "
                        ."`status` = 1, "
                        ."`date_added` = NOW(), "
                        ."`date_available` = NOW(), "
                        ."`date_modified` = NOW(), "
                        . "`stock_status_id` = 7");
                
                $product_id = $this->db->getLastId();
                
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_description "
                                . "SET "
                                . "product_id = '" . (int)$product_id . "', "
                                . "language_id = 1, "
                                . "name = '" . $name ."', "
                                . "description = '".$description."', "
                                . "tag =  '".$tag."', "
                                . "meta_title = '" . $name . "', "
                                . "meta_h1 = '" . $name . "', "
                                . "meta_description = '" . $tag . "', "
                                . "meta_keyword = '" . $tag . "'");
                
                $this->db->query("INSERT INTO ". DB_PREFIX ."product_to_store "
                        . "SET "
                        . "product_id = '".(int)$product_id."',"
                        . "store_id = 0");
                
                $cat = $this->db->query("SELECT category_id FROM ".DB_PREFIX."category_description "
                        . "WHERE name = '".$data[4]."'");
                
                if(isset($cat->row['category_id'])){
                    $category_id = $cat->row['category_id'];
                }
                else {
                    $category_id = NULL;
                }
                
                if (isset($category_id)) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET "
                                        . "product_id = '" . (int)$product_id . "', "
                                        . "category_id = '" . (int)$category_id . "'");
			}
                
                $this->db->query("INSERT INTO ". DB_PREFIX ."url_alias "
                        . "SET "
                        . "query = 'product_id=".(int)$product_id."'");
                
                if(isset($files1)){
                    foreach ($files1 as $file){
                        $this->db->query("INSERT INTO ". DB_PREFIX ."product_image "
                                . "SET "
                                . "product_id = ". $product_id .", "
                                . "image = 'catalog/demo/production/".$vin."/".$file."' ");
                    }
                }
                foreach($brand_arr as $b_id){
                    
                    $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand "
                        . "SET "
                        . "product_id = ". $product_id .", "
                        . "brand_id = $b_id");
                }
    }
    
    
    /**************************************************************************/
}
?>